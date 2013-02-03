<?php
/**
 * Add a new Debug Bar Panel.
 */
class DS_Debug_Bar_Transients extends Debug_Bar_Panel {

	/**
	 * Holds all of the transients.
	 *
	 * @var array
	 */
	private $_transients = array();

	/**
	 * Holds all of the site transients.
	 *
	 * @var array
	 */
	private $_site_transients = array();

	/**
	 * Holds only the core site transients.
	 *
	 * @var array
	 */
	private $_core_transients = array();

	/**
	 * Holds only the cron site transients..
	 *
	 * @var array
	 */
	private $_core_site_transients = array();

	/**
	 * Holds only the transients created by plugins or themes.
	 *
	 * @var array
	 */
	private $_user_transients = array();

	/**
	 * Holds only the site transients created by plugins or themes.
	 *
	 * @var array
	 */
	private $_user_site_transients = array();

	/**
	 * Total number of transients
	 *
	 * @var int
	 */
	private $_total_transients = 0;

	/**
	 * Total number of transients
	 *
	 * @var int
	 */
	private $_invalid_transients = 0;

	/**
	 * Give the panel a title and set the enqueues.
	 */
	public function init() {
		$this->title( __( 'Transients', 'ds-debug-bar-transients' ) );

		add_action( 'wp_print_styles', array( $this, 'print_styles' ) );
		add_action( 'admin_print_styles', array( $this, 'print_styles' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'print_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'print_scripts' ) );

		load_plugin_textdomain( 'ds-debug-bar-transients', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Enqueue styles.
	 */
	public function print_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';
		wp_enqueue_style(
			'ds-debug-bar-transients',
			plugins_url( "css/debug-bar-transients$suffix.css", __FILE__ ),
			array(),
			'10042012'
		);
	}

	/**
	 * Enqueue scripts.
	 */
	public function print_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';
		wp_enqueue_script(
			'ds-debug-bar-transients',
			plugins_url( "js/debug-bar-transients$suffix.js", __FILE__ ),
			array( 'jquery' ),
			'16042012'
		);
	}

	/**
	 * Show the menu item in Debug Bar.
	 */
	public function prerender() {
		$this->set_visible( true );
	}

	/**
	 * Show the contents of the page.
	 */
	public function render() {
		global $_wp_using_ext_object_cache;

		if ( $_wp_using_ext_object_cache ) {
			echo '<p class="invalid">' . __( 'You are using an unsupported external object cache.', 'ds-debug-bar-transients' ) . '</p>';
			return;
		}

		$this->get_total_transients();

		printf(
			'<h2><span>%s</span>%s</h2>',
			__( 'Total Transients:', 'ds-debug-bar-transients' ),
			number_format( $this->_total_transients )
		);

		printf(
			'<h2><span>%s</span>%s</h2>',
			__( 'Invalid Transients:', 'ds-debug-bar-transients' ),
			number_format( $this->_invalid_transients )
		);

		echo '<h3 id="custom-transients">' . __( 'Custom Transients', 'ds-debug-bar-transients' ) . '</h3>';
		if ( empty( $this->_user_transients ) )
			echo __( 'No transients found.', 'ds-debug-bar-transients' );
		else
			$this->display_transients( $this->_user_transients );

		echo '<h3 id="custom-site-transients">' . __( 'Custom Site Transients', 'ds-debug-bar-transients' ) . '</h3>';
		if ( empty( $this->_user_site_transients ) )
			echo __( 'No transients found.', 'ds-debug-bar-transients' );
		else
			$this->display_transients( $this->_user_site_transients, true );

		echo '<h3 id="core-transients">' . __( 'Core Transients', 'ds-debug-bar-transients' ) . '</h3>';
		if ( empty( $this->_core_transients ) )
			echo __( 'No transients found.', 'ds-debug-bar-transients' );
		else
			$this->display_transients( $this->_core_transients );

		echo '<h3 id="core-site-transients">' . __( 'Core Site Transients', 'ds-debug-bar-transients' ) . '</h3>';
		if ( empty( $this->_core_site_transients ) )
			echo __( 'No transients found.', 'ds-debug-bar-transients' );
		else
			$this->display_transients( $this->_core_site_transients, true );
	}

	/**
	 * Retrieve all the transients.
	 *
	 * @return int Total number of transients.
	 */
	private function get_total_transients() {
		$this->get_transients();

		$core_transients = array(
			'random_seed',
			'wporg_theme_feature_list',
			'settings_errors',
			'doing_cron',
			'plugin_slugs',
			'mailserver_last_checked',
			'dirsize_cache',
			'dash_',
			'rss_',
			'feed_',
			'feed_mod_',
			'plugins_delete_result_'
		);

		foreach ( $this->_transients as $transient => $data ) {
			$this->_total_transients++;

			if ( $this->_wildcard_search( $transient, $core_transients ) )
				$this->_core_transients[ $transient ] = $data;
			else
				$this->_user_transients[ $transient ] = $data;
		}

		$this->get_site_transients();

		$core_site_transients = array(
			'update_core',
			'update_plugins',
			'update_themes',
			'wporg_theme_feature_list',
			'browser_',
			'poptags_',
			'wordpress_credits_',
			'theme_roots'
		);

		foreach ( $this->_site_transients as $transient => $data ) {
			$this->_total_transients++;

			if ( $this->_wildcard_search( $transient, $core_site_transients )  )
				$this->_core_site_transients[ $transient ] = $data;
			else
				$this->_user_site_transients[ $transient ] = $data;
		}

		return $this->_total_transients;
	}

	/**
	 * Check if a string does exist in an array.
	 *
	 * @param  string  $needle   String to search.
	 * @param  array   $haystack Array in which string should be searched.
	 * @return boolean           True if founded, false if not.
	 */
	private function _wildcard_search( $needle, $haystack ) {
		foreach ( $haystack as $h )
			if ( 0 === strpos( $needle, $h ) )
				return true;

		return false;
	}

	/**
	 * Retrieve the transients (for a site).
	 *
	 * @return array The transients.
	 */
	private function get_transients() {
		if ( ! empty( $this->_transients ) )
			return $this->_transients;

		global $wpdb;

		$transients = $wpdb->get_results(
			"SELECT option_name AS name, option_value AS value FROM $wpdb->options WHERE option_name LIKE '_transient_%'"
		);

		array_walk( $transients, array( $this, '_format_transient' ) );
		unset( $transients );

		return $this->_transients;
	}

	/**
	 * Format the transients.
	 *
	 * @param  object $value One transient value from the database.
	 */
	private function _format_transient( $value ) {
		if ( false === strpos( $value->name, '_transient_timeout_' ) )
			$this->_transients[ str_replace( '_transient_', '', $value->name ) ]['value'] = $value->value;
		else {
			$this->_transients[ str_replace( '_transient_timeout_', '', $value->name ) ]['timeout'] = $value->value;

			if ( $value->value < time() )
				$this->_invalid_transients++;
		}
	}

	/**
	 * Retrieve the transients (for a network).
	 *
	 * @return array The site transients.
	 */
	private function get_site_transients() {
		if ( ! empty( $this->_site_transients ) )
			return $this->_site_transients;

		global $wpdb;

		if ( is_multisite() ) {
			$transients = $wpdb->get_results(
				"SELECT meta_key AS name, meta_value AS value FROM $wpdb->sitemeta WHERE meta_key LIKE '_site_transient_%' AND site_id = $wpdb->siteid"
			);
		} else {
			$transients = $wpdb->get_results(
				"SELECT option_name AS name, option_value AS value FROM $wpdb->options WHERE option_name LIKE '_site_transient_%'"
			);
		}

		array_walk( $transients, array( $this, '_format_site_transient' ) );
		unset( $transients );

		return $this->_site_transients;
	}

	/**
	 * Format the site transients.
	 *
	 * @param  object $value One transient value from the database.
	 */
	private function _format_site_transient( $value ) {
		if ( false === strpos( $value->name, '_site_transient_timeout_' ) )
			$this->_site_transients[ str_replace( '_site_transient_', '', $value->name ) ]['value'] = $value->value;
		else {
			$this->_site_transients[ str_replace( '_site_transient_timeout_', '', $value->name ) ]['timeout'] = $value->value;

			if ( $value->value < time() )
				$this->_invalid_transients++;
		}
	}

	/**
	 * Display the transients in a table.
	 *
	 * @param  array  $transients      The transients in an array.
	 * @param  boolean $site_transient If it's a site transient or not. Default: false.
	 */
	private function display_transients( $transients, $site_transient = false ) {
		if ( empty( $transients ) )
			return;

		wp_nonce_field( 'ds-delete-transient', '_ds-delete-transient-nonce' );

		echo '<table cellspacing="0">';
		echo '<thead>';
		echo '<th class="transient-name">' . __( 'Name', 'ds-debug-bar-transients' ) . '</th>';
		echo '<th class="transient-value">' . __( 'Value', 'ds-debug-bar-transients' ) . '</th>';
		echo '<th class="transient-timeout">' . __( 'Expiration', 'ds-debug-bar-transients' ) . '</th>';
		echo '</thead>';


		$action_links = sprintf(
			'<div class="row-actions"><span><a class="delete" data-transient-type="%s" data-transient-name="$" title="%s" href="#">%s</a> | <span class="switch-value"><a title="%s" href="#">%s</a></span></div></td>',
			( $site_transient ? 'site' : '' ),
			__( 'Delete this transient (No undo!)', 'ds-debug-bar-transients' ),
			__( 'Delete', 'ds-debug-bar-transients'),
			__( 'Switch between serialized and unserialized view', 'ds-debug-bar-transients' ),
			__( 'Switch value view', 'ds-debug-bar-transients' )
		);

		$class = ' class="alternate"';
		foreach( $transients as $transient => $data ) {
			echo '<tr' . $class . '>';
			echo '<td>' . $transient . str_replace( '$', $transient, $action_links ) . '</td>';
			echo '<td><pre class="serialized" title="' .  __( 'Click to expand' ) . '">' . esc_html( $data['value'] ) . '</pre><pre class="unserialized" title="' .  __( 'Click to expand' ) . '">' . esc_html( print_r( maybe_unserialize( $data['value'] ), true ) ) . '</pre></td>';
			echo '<td>' . $this->_print_timeout( $data )  . '</td>';
			echo '</tr>';

			$class = ( ' class="alternate"' == $class ) ? '' : ' class="alternate"';
		}

		echo '</table>';
	}

	/**
	 * Prepare the timeout value of a transient.
	 *
	 * @param  array   $data One transient value from the database.
	 * @return string        The prepared time.
	 */
	private function _print_timeout( $data ) {
		if ( empty( $data['timeout'] ) )
			return __( 'Unknown', 'ds-debug-bar-transients' );
		else
			$time = $data['timeout'];

		return sprintf(
			'%s<br />%s<br />%s',
			sprintf(
				__( 'Unix-Timestamp: %d', 'ds-debug-bar-transients' ),
				$time
			),
			date_i18n(
				__( 'M j, Y @ G:i' ),
				$time
			),
			( $time > time() ) ?
				sprintf(
					__( '(in %s)', 'ds-debug-bar-transients' ),
					human_time_diff( $time )
				)
				:
				sprintf(
					'<span class="invalid">' . __( '(invalid since %s)', 'ds-debug-bar-transients'  ) . '</span>',
					human_time_diff( $time )
				)
		);
	}
}

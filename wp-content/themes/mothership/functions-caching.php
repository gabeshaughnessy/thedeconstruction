<?php


function clear_all_transients(){
	global $wpdb;
	$wpdb->query( 
		"DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient_%')"
	);
}
add_action('wp_update_nav_menu', 'clear_all_transients' );
add_action('profile_update', 'clear_all_transients' );
add_action( 'save_post', 'clear_all_transients' );
?>
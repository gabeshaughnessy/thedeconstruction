<?php  /* Theme Functions */

/* DEFINE ENVIRONMENT GLOBAL */
$host = $_SERVER['HTTP_HOST'];
if (stristr($host, 'local' ) !== FALSE){ 
	define ('DECON_ENVIRONMENT', "development");
    }
    elseif ((stristr($host, 'staging') !== FALSE)){
        define('DECON_ENVIRONMENT', "staging");
        }
        else{
            define('DECON_ENVIRONMENT', "production");
            } 
/* Plugins Activiation */
/* ################################################################################# */

    if (DECON_ENVIRONMENT != 'development') {
       define('ACF_LITE', true);
    }

    /* Advanced Custome Fields */
    require_once('lib/plugins/advanced-custom-fields/acf.php');
    /* ACF Add-ons */
    //include_once( 'lib/plugins/advanced-custom-fields/add-ons/acf-repeater/acf-repeater.php' );
    //include_once( 'lib/plugins/advanced-custom-fields/add-ons/acf-flexible-content/acf-flexible-content.php' );
    //include_once( 'lib/plugins/advanced-custom-fields/add-ons/acf-options-page/acf-options-page.php' ); 
    //include_once( 'lib/plugins/advanced-custom-fields/add-ons/acf-field-date-time-picker/acf-date_time_picker.php' ); 

    if ( DECON_ENVIRONMENT != 'development' ) {
        // If this is staging or production
            // load ACF declarations
            require_once('lib/plugins/advanced-custom-fields/register_fields.php'); 
        }
        else{            
            add_action( 'admin_menu', 'decon_acf_menu', 9 );
            function decon_acf_menu(){
                add_submenu_page( 'edit.php?post_type=acf', __('Custom Fields','acf'), __('Custom Fields','acf'), 'manage_options', 'edit.php?post_type=acf');
                add_submenu_page( 'edit.php?post_type=acf', __('Import ACF','acf'), __('Import ACF','acf'), 'manage_options', 'admin.php?import=wordpress');

                }

    }


//Initialize Admin Pages 
require_once('lib/admin-page-class/admin-page-class.php');
require_once('lib/admin-page-class/theme-options.php');
require_once('functions-social.php');
require_once('functions-caching.php');

//REWRITES
require_once('lib/rewrites.php');
//enqueue foundation js
function decon_enqueue_scripts() {
	
	//jQuery
	wp_enqueue_script(
		'jquery'
	);
	//Foundation
	wp_enqueue_script(
		'foundation',
		get_template_directory_uri() . '/foundation/javascripts/foundation.min.js',
		array('jquery')
	);
	wp_enqueue_script(
		'accordion',
		get_template_directory_uri() . '/foundation/javascripts/jquery.foundation.accordion.js',
		array('jquery', 'foundation')
	);
	wp_enqueue_script(
		'foundation-reveal',
		get_template_directory_uri() . '/foundation/javascripts/jquery.foundation.reveal.js',
		array('foundation', 'jquery')
	);
	wp_enqueue_script(
		'foundation-top-bar',
		get_template_directory_uri() . '/foundation/javascripts/jquery.foundation.topbar.js',
		array('foundation', 'jquery')
	);
	//Global JS
	wp_enqueue_script(
		'global_scripts',
		get_template_directory_uri() . '/js/global.js',
		array('foundation', 'jquery', 'foundation-reveal')
	);
}
add_action('wp_enqueue_scripts', 'decon_enqueue_scripts');

/* USER PROFILES */
require_once('lib/user-fields.php');

/* ------ MENUS ----------*/
require_once('foundation/foundation-topbar-menu.php');
require_once('foundation/foundation-topbar-walker.php');
require_once('foundation/foundation-navbar-menu.php');
require_once('foundation/foundation-navbar-walker.php');
require_once('foundation/foundation-page-walker.php');


/* ___________ SIDEBARS _______________ */
 $main_sidebar = array(
	'name'          => __( 'Main Sidebar', 'mothership' ),
	'id'            => 'main-sidebar',
	'description'   => 'The default sidebar, on the right hand side of the page',
        'class'         => '',
	'before_widget' => '<li id="%1$s" class="widget %2$s">',
	'after_widget'  => '</li>',
	'before_title'  => '<h3 class="widgettitle">',
	'after_title'   => '</h3>' ); 
	
register_sidebar( $main_sidebar);

/* UTILITY FUNCTIONS */

//get the current page URL
function curPageURL() {
 $pageURL = 'http';

 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
// --- COLUMNS TO NUMBER STRINGS WRITTEN OUT
function writeOutNum($columns){
	switch($columns){
	case 1 :
	$columns = 'one';
	break;
	case 2 :
	$columns = 'two';
	break;
	case 3 :
	$columns = 'three';
	break;
	case 4 :
	$columns = 'four';
	break;
	case 5 :
	$columns = 'five';
	break;
	case 6 :
	$columns = 'six';
	break;
	case 7 :
	$columns = 'seven';
	break;
	case 8 :
	$columns = 'eight';
	break;
	case 9 :
	$columns = 'nine';
	break;
	case 10 :
	$columns = 'ten';
	break;
	case 11 :
	$columns = 'eleven';
	break;
	case 12:
	$columns = 'twelve';
	break;
	}
	return $columns;
}

//Print a Widget in a Shortcode
function widget($atts) {
    
    global $wp_widget_factory;
    
    extract(shortcode_atts(array(
        'widget_name' => FALSE,
        'widget_atts' => array()
    ), $atts));
    
    $widget_name = esc_html($widget_name);
    
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    ob_start();
    
    the_widget($widget_name, array('widget_id'=>$widget_name,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
}
add_shortcode('widget','widget'); 
function like_widget($atts) {
//settings here: https://developers.facebook.com/docs/reference/plugins/like/
    
    global $wp_widget_factory;
    
    extract(shortcode_atts(array(
    'send' => FALSE,
    'width' => '450',
    'show_faces' => FALSE,
    'font' => 'arial',
    'theme' => 'light',
    'ref' => FALSE,
    'layout' => 'standard',
    'action' => 'like'
    
    ), $atts));
    
     $widget_name="Facebook_Like_Button_Widget"; 
       
    if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    ob_start();
    
    the_widget($widget_name, array('widget_id'=>$widget_name,
	    'send' => $send,
	    'show_faces' => $show_faces,
	    'ref' => $ref,
	    'colorscheme' => $theme,
	    'font' => $font,
	    'action' => $action,
	    'layout' => $layout,
	    'width' => $width,
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
}
add_shortcode('like_widget','like_widget'); 

/* FORMATS */
add_theme_support( 'post-formats', array( 'video', 'image', 'aside', 'gallery', 'quote', 'link', 'audio' ) );
/* VIDEOS */
add_filter('embed_oembed_html', 'my_embed_oembed_html', 99, 4);
function my_embed_oembed_html($html, $url, $attr, $post_id) {
  return '<div class="flex-video">' . $html . '</div>';
}
/* IMAGES */

add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 120, 120, true );
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'one-column', 120, 120, true ); //(cropped)
	add_image_size( 'social', 700, 700, false ); //(not-cropped)
 }
 
 /* Dashboard - remove fields */
 // Create the function to use in the action hook
 
 function example_remove_dashboard_widgets() {
 	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
 	remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
 	remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
 	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
 	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
 	remove_meta_box ('dashboard_activity', 'dashboard', 'normal');
 } 
 
 // Hoook into the 'wp_dashboard_setup' action to register our function
 
 add_action('wp_dashboard_setup', 'example_remove_dashboard_widgets' );
?>
<?php 

add_action( 'save_post', 'clear_transients' );
add_action('edit_user_profile', 'clear_user_transient');
function clear_user_transient(){	
			delete_transient('team-list');	
}
function clear_transients(){	
			delete_transient('newsfeed_posts');	
}
?>
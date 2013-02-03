<?php  /* Theme Functions */

//Initialize Admin Pages 
require_once('admin-page-class/admin-page-class.php');
require_once('admin-page-class/theme-options.php');

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
	//Foundation App
	wp_enqueue_script(
		'foundation-app',
		get_template_directory_uri() . '/foundation/javascripts/app.js',
		array('foundation')
	);
	//Global JS
	wp_enqueue_script(
		'global_scripts',
		get_template_directory_uri() . '/js/global.js',
		array('foundation', 'jquery')
	);
}
add_action('wp_enqueue_scripts', 'decon_enqueue_scripts');

/* USER PROFILES */
require_once('user-fields.php');

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
set_post_thumbnail_size( 120, 9999, true );
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'one-column', 120, 9999, true ); //(cropped)
 }
?>
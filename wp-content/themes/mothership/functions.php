<?php  /* Theme Functions */

//Initialize Admin Pages 
require_once('admin-page-class/admin-page-class.php');
require_once('admin-page-class/theme-options.php');

//enqueue foundation js
function decon_enqueue_scripts() {
	
	wp_enqueue_script(
		'jquery'
	);
	wp_enqueue_script(
		'foundation',
		get_template_directory_uri() . '/foundation/javascripts/foundation.min.js',
		array('jquery')
	);
	wp_enqueue_script(
		'foundation-app',
		get_template_directory_uri() . '/foundation/javascripts/app.js',
		array('foundation')
	);
}
add_action('wp_enqueue_scripts', 'decon_enqueue_scripts');

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
?>
<?php
add_theme_support('menus'); 

/*
http://codex.wordpress.org/Function_Reference/register_nav_menus#Examples
*/
register_nav_menus( array(
	'main-menu' => 'Main Menu' // registers the menu in the WordPress admin menu editor
) );


/* 
http://codex.wordpress.org/Function_Reference/wp_nav_menu 
*/
function foundation_nav_bar() {
    wp_nav_menu(array( 
        'container' => false,             // remove menu container
        'container_class' => '',          // class of container
        'menu' => '',                     // menu name
        'menu_class' => 'nav-bar',        // adding custom nav class
        'theme_location' => 'main-menu',  // where it's located in the theme
        'before' => '',                   // before each link <a>
        'after' => '',                    // after each link </a>
        'link_before' => '<span class="icon"></span>',              // before each link text
        'link_after' => '',               // after each link text
        'depth' => 2,                     // limit the depth of the nav
    	'fallback_cb' => 'main_nav_fb',   // fallback function (see below)
        'walker' => new nav_bar_walker()      // walker to customize menu (see foundation-nav-walker)
	));
}

/*
http://codex.wordpress.org/Template_Tags/wp_list_pages
*/
function main_nav_fb() {
	echo '<ul class="nav-bar">';
	wp_list_pages(array(
		'depth'        => 2,
		'child_of'     => 0,
		'exclude'      => '',
		'include'      => '',
		'title_li'     => '',
		'echo'         => 1,
		'authors'      => '',
		'sort_column'  => 'menu_order, post_title',
		'link_before'  => '',
		'link_after'   => '',
		'walker'       => new page_walker(),
		'post_type'    => 'page',
		'post_status'  => 'publish' 
	));
	echo '</ul>';
}

?>
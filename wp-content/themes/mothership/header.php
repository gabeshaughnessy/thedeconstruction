<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage mothership
 * @since The Deconstruction Mothership 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--[endif] -->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_bloginfo( 'stylesheet_directory'); ?>/foundation/stylesheets/foundation.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_bloginfo( 'stylesheet_directory'); ?>/css/app.css" />

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link href='http://fonts.googleapis.com/css?family=Cinzel+Decorative' rel='stylesheet' type='text/css'>

<?php //get the theme options to an array
$theme_options = get_option('mothership_options');
//access each field by its id
//echo $theme_options['field_id'];
?>
<?php wp_head(); //goes right before the closing head tag for plugin support  ?>
</head>
<?php if(!is_page_template('page-modal.php')){ // no containers for the modals
?>
<body <?php body_class(); ?>>

<header class="row">
	<div id="top-nav">
		<?php 
		if(!is_author()){
			foundation_nav_bar(); //switch this with the template-part 'topbar' function below to change to a topbar
		}
		else {
			get_template_part('topbar', 'team'); 
		}
		?>
	</div>
	
	
	<?php 
	if(!is_author()){?>
	<!-- <div class="logo twelve columns last"><h1><?php bloginfo('name'); ?></h1></div> -->
	
	<div class="logo ten columns centered"><h1><img src="<?php bloginfo('stylesheet_directory') ?>/images/deconstruction_logo.jpg"/></h1></div>
	
	<div class="header_description ten columns centered"><p><?php bloginfo('description'); ?></p></div>
	<?php  } ?>
</header>
<div id="main-content-area" class="row">
<?php } ?>
	

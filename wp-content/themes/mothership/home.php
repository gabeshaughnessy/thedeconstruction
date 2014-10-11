<?php
/**
 * The default page for our theme.
 * 
 *
 * @package WordPress
 * @subpackage mothership
 * @since The Deconstruction Mothership 1.0
 */
?>
<?php get_header(); ?>
<div id="content" class="large-12 columns">
<?php get_template_part('loop', 'blogposts'); ?>
<div class="navigation"><p><?php posts_nav_link(); ?></p></div>
</div><!-- end of content -->
</div><!-- end of main content container -->

<?php get_footer(); ?>
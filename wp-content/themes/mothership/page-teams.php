<?php
/**
 * Template Name: Team Archive Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage mothership
 * @since The Deconstruction Mothership 1.0
 */
?>
<?php get_header(); ?>
<div id="content" class="large-11 columns">
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<article>
	<div class="post-title ">
		<h2><?php the_title(); ?></h2>
	</div>
	<div class="post-content">
		<?php the_content(); ?>
		<?php 
		get_template_part('loop', 'teams');
				?>
		
		
	</div>
	
</article>
<?php endwhile; ?>
<?php endif; ?>
</div><!-- end of content -->

	 <div class="large-1 columns offset-large-11">
	 <?php 
	 //get_template_part('sidebar'); 
	 get_template_part('accordion', 'social');
	 ?>
	 </div>
</div><!-- end of main content container -->

<?php get_footer(); ?>
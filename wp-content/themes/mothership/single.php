<?php
/**
 * The single post template
 * 
 *
 * @package WordPress
 * @subpackage mothership
 * @since The Deconstruction Mothership 1.0
 */
?>
<?php get_header(); ?>
<div id="content" class="nine columns">
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<article>
	<div class="post-title ">
		<h2><?php the_title(); ?></h2>
		<p class="author"><em>posted by: </em><?php the_author_posts_link(); ?> on <?php echo the_date('F j, Y', '<em>','</em>', true); ?></p>
		
	</div>
	<div class="post-content">
		<?php the_content(); ?>
		<?php comments_template(); ?>
	</div>
</article>
<?php endwhile; ?>
<?php endif; ?>
</div><!-- end of content -->

	 <div class="one columns offset-by-eleven">
	 <?php 
	 //get_template_part('sidebar'); 
	 get_template_part('accordion', 'social');
	 ?>
	 </div>
</div><!-- end of main content container -->

<?php get_footer(); ?>
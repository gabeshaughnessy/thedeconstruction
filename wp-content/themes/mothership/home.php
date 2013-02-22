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
<div id="content" class="nine columns">
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<article>
	<div class="post-title">
		<h2><a href="<?php the_permalink(); ?>" title="View Post"><?php the_title(); ?></a></h2>
			<div class="row"><div class="team-image one columns"><?php echo get_wp_user_avatar(get_the_author_meta('ID'), 'thumbnail');  ?></div><p class="author eleven columns end"><em>posted by: </em><?php the_author_posts_link(); ?> on <?php echo the_date('F j, Y', '<em>','</em>', true); ?></p></div>
	</div>
	<div class="post-content">
		<?php the_content(); ?>
	</div>
	<hr>
</article>
<?php endwhile; ?>
<?php endif; ?>
<div class="navigation"><p><?php posts_nav_link(); ?></p></div>
</div><!-- end of content -->

	 <div class="one columns offset-by-eleven">
	 <?php 
	 //get_template_part('sidebar'); 
	 get_template_part('accordion', 'social');
	 ?>
	 </div>
</div><!-- end of main content container -->

<?php get_footer(); ?>
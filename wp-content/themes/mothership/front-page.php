<?php
/**
 * The Home Page for our theme.
 * 
 *
 * @package WordPress
 * @subpackage mothership
 * @since The Deconstruction Mothership 1.0
 */
?>
<?php get_header(); ?>
<div id="content" class="eleven columns">
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<article>
	<div class="post-content">
		<?php the_content(); ?>
	</div>

</article>
<?php endwhile; ?>
<?php endif; ?>
</div>
<div class="one columns offset-by-eleven">
<?php 
get_template_part('accordion', 'social');
?>
</div>

<!-- end of content -->

	<div class="twelve columns">
		<div class="home_update_bar">
			<hr/>
		Updates
		</div>

	<?php 
	get_template_part('loop', 'news_feed');
	?>
	</div>

</div><!-- end of main content container -->

<?php get_footer(); ?>
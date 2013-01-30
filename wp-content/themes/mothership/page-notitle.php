<?php
/**
 * TEMPLATE NAME: No Title 
 *
 *
 * @package WordPress
 * @subpackage 1H1H
 */

get_header(); ?>
<div id="content" class="nine columns">
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<article>
	<div class="post-content">
		<?php the_content(); ?>
	</div>
</article>
<?php endwhile; ?>
<?php endif; ?>
</div><!-- end of content -->
	<div class="eleven columns">
	<?php 
	//get_template_part('loop', 'news_feed');
	?>
	</div>
	<div class="one columns offset-by-eleven">
	<?php 
	get_template_part('accordion', 'social');
	?>
	</div></div><!-- end of main content container -->

<?php get_footer(); ?>
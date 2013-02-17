<?php
/**
 * TEMPLATE NAME: 404 template
 *
 *
 * @package WordPress
 * @subpackage 1H1H
 */

get_header(); ?>
<div id="content" class="twelve columns">

<hr>
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<article>
<h3><?php the_title(); ?></h3>
	<div class="post-content">
		<?php the_content(); ?>
	</div>
</article>
<?php endwhile; ?>
<?php endif; ?>
</div><!-- end of content -->	
	<div class="twelve columns">
	<?php 
	//get_template_part('loop', 'news_feed');
	?>
	</div>
</div><!-- end of main content container -->

<?php get_footer(); ?>
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
<article>
<h3>404 Error! Page Not Found</h3>
	<div class="post-content">
		The page you are looking for does not exist.
		Maybe you'd like to <a title="Register a Team" href="/register" class="has-tip">build it?</a>
	</div>
</article>
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>

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
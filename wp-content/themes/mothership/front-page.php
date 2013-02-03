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

	</div><!-- end of main content container -->

<div class="post-footer row">
	<div class="six columns">
			<div class="title">
				
				<h5>Updates</h5>
			</div>
	
		<?php 
		get_template_part('loop', 'news_feed');
		?>
	</div>

	<div class="six columns end">
		<div class="title">
			<h5>Twitter</h5>
		</div>
		<div class="content">
		  <a class="twitter-timeline" href="https://twitter.com/Deconstruction" data-widget-id="291720147733979136">Tweets by @Deconstruction</a>
		  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
	
	</div>
</div>
<?php get_footer(); ?>
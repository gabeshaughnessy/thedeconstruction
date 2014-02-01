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
<div id="content" class="large-11 columns">
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<article>
	<div class="post-content">
		<?php the_content(); 
		//echo do_shortcode('[fbcomments]');
		
				?>
	</div>

</article>
<?php endwhile; ?>
<?php endif; ?>
</div>
<div class="large-1 columns offset-large-11">
<?php 
get_template_part('accordion', 'social');
?>
</div>

<!-- end of content -->

	</div><!-- end of main content container -->

<div class="post-footer row">
	<div class="large-6 columns">
			<div class="title">
				
				<h5>Deconstruct: News</h5>
				<hr>
			</div>
	
		<?php 
		get_template_part('loop', 'news_feed');
		?>
	</div>

	<div class="large-6 columns ">
		<div class="title">
			<h5>Deconstruct: Twitter</h5>
			<hr>
		</div>
		<div class="content">
		<a class="twitter-timeline" href="https://twitter.com/Deconstruction" data-widget-id="291720147733979136">Tweets by @Deconstruction</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
	
	</div>
</div>
<?php get_footer(); ?>
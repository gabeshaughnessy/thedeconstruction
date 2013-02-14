<?php
/**
 * The team profile template
 * 
 *
 * @package WordPress
 * @subpackage mothership
 * @since The Deconstruction Mothership 1.0
 */
?>
<?php get_header(); ?>
<?php //set up the current Author 
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
?>

<div id="content" class="nine columns">
<h2>This is the <?php echo $curauth->first_name.' '.$curauth->last_name; ?> page.</h2>
<div class="team-image four columns"><?php echo get_wp_user_avatar(get_the_author_meta('ID'), 'medium');  ?></div>
<p class="team bio eight columns"> <?php echo get_the_author_meta('bio'); ?></p>

<h4>The Team <?php echo $curauth->first_name.' '.$curauth->last_name;  ?> feed: </h4>
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>

<article>
	<div class="post-title ">
	<h3><?php the_title(); ?></h3>
	<p class="date"><em>Posted on: <?php the_date(); ?></em> 
			</div>
	<div class="post-content">
	
	<p><?php the_content(); ?></p>
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
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
$team_meta = get_user_meta($curauth->ID);
$team_name = $curauth->display_name;
$team_location = get_user_meta($curauth->ID, 'team-location', true);
$team_bio = $team_meta['bio'][0];
$team_twitter = $team_meta['twitter'][0];
$team_string = $team_meta['stream'][0];
$team_googleplus = $team_meta['googleplus'][0];
?>

<div id="content" class="nine columns">
<pre>
<?php  
//print_r($curauth);
//print_r($team_meta);
?>
</pre>
<div class="row">
	<div class="team-image four columns"><?php echo get_wp_user_avatar(get_the_author_meta('ID'), 'medium');  ?></div>
	<div class="eight columns">
	<h3><?php echo $curauth->first_name.' '.$curauth->last_name; ?></h3>
	<p class="team-location"> <?php echo $team_location; ?></p>
	</div>
</div>

<p class="team-bio eight columns"> <?php echo $team_bio; ?></p>

<h4>The <?php echo $curauth->first_name.' '.$curauth->last_name;  ?> feed: </h4>
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
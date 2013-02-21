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
$team_theme = get_user_meta($curauth->ID, 'team-theme', true);
$team_bio = $team_meta['bio'][0];
$team_twitter = $team_meta['twitter'][0];
$team_stream = $team_meta['stream'][0];
$team_googleplus = $team_meta['googleplus'][0];
$team_url = $curauth->user_url;
?>

<div id="content" class="eleven columns">
<pre>
<?php  
//print_r($curauth);
//print_r($team_meta);
?>
</pre>
<div class="row">
	<div class="team-image four columns"><?php echo get_wp_user_avatar(get_the_author_meta('ID'), 'medium');  ?></div>
	<div class="eight columns">
	<h3><?php echo $team_name; ?></h3>
	<?php if($team_theme != ''){ ?>
	<p><em>Deconstruction Theme: </em> <b><?php echo $team_theme; ?></b></p>
	<?php } ?>
	<?php if($team_bio != ''){ ?>
	<p class="team-bio"> <?php echo $team_bio; ?></p><?php }  ?>
	<ul class="inline-list">
	<?php if($team_location != ''){ ?>
	<li><em>Locations: </em></li>
	<li><a class="team-location has-tip tip-top" title="This team is located in <?php echo $team_location; ?>" href="#"><?php echo $team_location; ?></a></li>
	<?php } 
	if($team_url != ''){ ?>
	<li><a class="team-url has-tip tip-top" target="_blank" title="Visit the team's website" href="<?php
	 if(strpos('$team_url', 'http://') != false){
	 echo $team_url; 
	 }
	 else { echo $team_url; } ?>">Website</a></li>
	<?php }  
	if($team_twitter != ''){ ?>
	<li><a class="team-twitter has-tip tip-top" href="https://twitter.com/<?php echo $team_twitter; ?>" target="_blank" title="Find this team on twitter">Twitter</a></li>
	<?php } 
	if($team_googleplus != ''){ ?>
	<li><a class="team-google has-tip tip-top" href="<?php echo $team_googleplus; ?>" title="check out the Team Google+ page" target="_blank">Google+</a></li>
	<?php } ?>
	</ul>
	</div>
</div>


<h4 class="uppercase"><?php echo $team_name;  ?> live stream: </h4>
<div class="panel twelve columns fit-vid"><?php if($team_stream != ''){ ?><div class="six columns"><?php echo $team_stream; ?></div>
<?php } ?>
<div id="team-chat" class="six columns"><?php echo do_shortcode('[quick-chat  room="'.$team_name.'"]'); ?></div>


</div>
<h3 class="uppercase"><?php echo $team_name;  ?> updates: </h3>
<hr>
<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<article>
	<div class="post-title ">
	<h3 ><a href="<?php echo the_permalink(); ?>" title="View Post"><?php the_title(); ?></a></h3>
	<p class="date"><em>Posted on: <?php the_date(); ?></em> 
			</div>
	<div class="post-content">
	
	<p><?php the_content(); ?></p>
		<?php comments_template(); ?>
		
	</div>
</article><hr>
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
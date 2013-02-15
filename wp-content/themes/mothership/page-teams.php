<?php
/**
 * Template Name: Team Archive Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
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
	<div class="post-title ">
		<h2><?php the_title(); ?></h2>
	</div>
	<div class="post-content">
		<?php the_content(); ?>
		<?php //wp_list_authors('orderby=post_count&order=DESC&number=10&hide_empty=0&exclude_admin=1'); 
		
		$display_admins = false;
		$order_by = 'display_name'; // 'nicename', 'email', 'url', 'registered', 'display_name', or 'post_count'
		$role = 'team'; // 'subscriber', 'contributor', 'editor', 'author' - leave blank for 'all'
		$avatar_size = 260;
		$hide_empty = false; // hides authors with zero posts
		
		if(!empty($display_admins)) {
			$blogusers = get_users('orderby='.$order_by.'&role='.$role);
		} else {
			$admins = get_users('role=administrator');
			$exclude = array();
			foreach($admins as $ad) {
				$exclude[] = $ad->ID;
			}
			$exclude = implode(',', $exclude);
			$blogusers = get_users('exclude='.$exclude.'&orderby='.$order_by.'&role='.$role);
		}
		$authors = array();
		foreach ($blogusers as $bloguser) {
			$user = get_userdata($bloguser->ID);
			if(!empty($hide_empty)) {
				$numposts = count_user_posts($user->ID);
				if($numposts < 1) continue;
			}
			$authors[] = (array) $user;
		}
		
		echo '<ul class="team-list row">';
		$i = 1;
		$columns = 3;
		foreach($authors as $author) {
		if($i % $columns == 1){ 
			$row = true;
			echo '<li class="row"></li>';
		}
	
			$display_name = $author['data']->display_name;
			$avatar = get_avatar($author['ID'], $avatar_size);
			$author_profile_url = get_author_posts_url($author['ID']);
	
			$team_location = get_user_meta($author['ID'], 'team-location', true);
			echo '<li class="'.writeOutNum(12/$columns).' columns"><a class="th has-tip" title="View the Team Profile Page" href="', $author_profile_url, '">', $avatar , '</a><div class="team-details panel twelve columns"><a class="has-tip" title="View the Team Profile Page" href="', $author_profile_url, '" class="team-link"><h6>', $display_name, '</h6></a><p class="team-location ">'.$team_location.'</p></div></li>';
			$i++;
		}
		echo '</ul>';
		?>
		
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
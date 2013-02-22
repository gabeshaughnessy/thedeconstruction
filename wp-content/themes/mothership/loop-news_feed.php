<?php
//newsfeed section heading
?>
<div class="news-wrapper">
<!-- ><h3>Deconstruction News:</h3> -->
<?php
//check for newsfeed transient, if it exists, assign variable newfeed to it
$newsfeed_posts = get_transient('newsfeed_posts');

if($newsfeed_posts == false){
//if it doesn't exist:

//query the posts for
$admins = get_users('role=administrator');
$admin_ids = '';
foreach($admins as $admin){
$admin_ids .= $admin->ID.',';
}
$args = array(
'posts_per_page' => '3',
'category_name' => 'news',
'author' => $admin_ids
);

$newsfeed = new WP_Query( $args );
if($newsfeed->have_posts()) : while($newsfeed->have_posts()) : $newsfeed->the_post();
?>
<?php $newsfeed_posts .= '<div class="news-item">'; ?>

<?php $newsfeed_posts .= '<h4 class="post-title"><a href="'.get_permalink().'"title="View the Post">'.get_the_title().'</a></h4>';
 $newsfeed_posts .= '<div class="post-image three columns push left">';
 $newsfeed_posts .= '<a href="'.get_permalink().'" title="View the Post">';	
	if(has_post_format('video')){
	
	$newsfeed_posts .= '<img src="'.get_video_thumbnail().'" width="120" height="80" />' ;

	}
	else{
	$newsfeed_posts .= get_the_post_thumbnail();
	}
	
	$newsfeed_posts .= '</a>';
$newsfeed_posts .= '</div>';
$newsfeed_posts .= '<div class="post-excerpt">'.wpautop(get_the_excerpt()).'</div>';
$newsfeed_posts .= '<a class="twelve columns read more" href="'.get_permalink().'" title="Read More">Read More &rArr;</a>';
$newsfeed_posts .= '</div>';
endwhile;
endif;
//assign the query to variable newsfeed
//foreach loop through newsfeed posts
//display the post title, date and excerpt with a readmore link
//end the loop

//set newsfeed to the transient
set_transient('newsfeed_posts', $newsfeed_posts, 60*60*24); //set to 24 hours - 60*60*24
}
echo $newsfeed_posts;
//hook into save posts loop - clear transients for posts.
?>
<?php
//newsfeed section footer
?>
</div>
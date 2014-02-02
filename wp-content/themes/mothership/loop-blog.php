<div class="blog-wrapper">
<?php
//check for blogfeed transient, if it exists, assign variable newfeed to it
global $enable_transients;
if($enable_transients == true){
 $blogfeed_posts = get_transient('blog_posts');
}
else{
	$blogfeed_posts = false;

}

if($blogfeed_posts == false){
//if it doesn't exist:

//query the posts for just the admins.
$admins = get_users('role=administrator');
$admin_ids = '';
foreach($admins as $admin){
$admin_ids .= $admin->ID.',';
}
$args = array(
'posts_per_page' => '5',
'category_name' => 'news',
//'author' => $admin_ids
//admin query is disabled, querying for all users
);

$blogfeed = new WP_Query( $args );
if($blogfeed->have_posts()) : while($blogfeed->have_posts()) : $blogfeed->the_post();
global $more;
$more = 0;
$blogfeed_posts .= '<article>';
$blogfeed_posts .= '<div class="post-title"><h2><a href="'.get_permalink().'"title="View the Post">'.get_the_title().'</a></h2>';
 $blogfeed_posts .= '<div class="row"><div class="team-image large-1 columns">'.get_wp_user_avatar(get_the_author_meta("ID"), 'thumbnail').'</div><p class="author large-11 columns end"><em>posted by: </em><a href="'.get_author_posts_url(get_the_author_meta("ID")).'" title="view profile">'.get_the_author_meta('display_name').' </a> on '.the_date("F j, Y", "<em>","</em>", false).'</p></div></div>';	
$blogfeed_posts .= '<div class="post-content">'.apply_filters("the_content",get_the_content( "Read More &rArr;" )).'</div>';
$blogfeed_posts .= '<div class="share-section"><p class="share-text">Share:</p>'.socialAccordion($blogfeed).'</div>';
$blogfeed_posts .= '<hr></article>';
endwhile;
endif;
//assign the query to variable newsfeed
//foreach loop through newsfeed posts
//display the post title, date and excerpt with a readmore link
//end the loop

//set newsfeed to the transient
set_transient('blog_posts', $blogfeed_posts, 60*60*24); //set to 24 hours - 60*60*24
}
echo $blogfeed_posts;
//hook into save posts loop - clear transients for posts.
?>
</div>
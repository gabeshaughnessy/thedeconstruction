<?php
/**
 * TEMPLATE NAME: H.U.D. Page Template
 *
 *
 * @package WordPress
 * @subpackage 1H1H
 */
get_header(); 
global $post;

//wp_list_authors('orderby=post_count&order=DESC&number=10&hide_empty=0&exclude_admin=1'); 

//check for newsfeed transient, if it exists, assign variable newfeed to it
$team_list = false;//get_transient('team-huds');

if($team_list == false){
//if it doesn't exist:
$team_list = '';
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
	if(isset($_GET["offset"])){
	$offset = $_GET["offset"];
	}
	else{
	$offset = 0;
	}
	$number = 6;
	$args = array(
	'exclude' => $exclude,
	'orderby' => $order_by,
	'role'=> $role,
	'number' => $number,
	'offset' => $offset, 
	'meta_query' => array(
			array(
				'key' => 'stream',
				'value' => '',
				'compare' => '!='
			)
		)
	);
	$blogusers = get_users($args);
}
$teams = array();
foreach ($blogusers as $bloguser) {
	$user = get_userdata($bloguser->ID);
	if(!empty($hide_empty)) {
		$numposts = count_user_posts($user->ID);
		if($numposts < 1) continue;
	}
	$teams[] = (array) $user;
}

$team_list .=  '<ul class="team-list row">';
$i = 1;
$columns = 2;
foreach($teams as $team) {
if($i % $columns == 1){ 
	$row = true;
	$team_list .=  '<li class="row"></li>';
}
	$team_meta = get_user_meta($team['ID']);
	if(isset($team_meta['stream'])){
	$team_stream = $team_meta['stream'][0];
	}
	$display_name = $team['data']->display_name;
	$team_profile_url = home_url('/').'team/'.$team['data']->user_nicename;
	$team_location = get_user_meta($team['ID'], 'team-location', true);
	$team_list .= '<li class="'.writeOutNum(12/$columns).' columns panel"><div class="fit-vid twelve columns">'.$team_stream.'</div><div class="team-details twelve columns"><a class="" title="View the Team Profile Page" href="'. $team_profile_url.'" class="team-link"><h6>'. $display_name. '</h6></a><p class="team-location ">'.$team_location.'</p></div></li>';
	$i++;
}
$team_list .= '</ul>';
}
//set_transient('team-huds', $team_list, 60*60);//set to 1hr
echo $team_list;
$next_page = './?offset='.($number + $offset);
$prev_page = './?offset='.($offset - $number);
$page_link = '<div class="row">';
if(($offset - $number) >= 0){ 
$page_link .= '<a href="'.$prev_page.'" title="previous page">Previous Page</a> | ';
}
$page_link .= '<a href="'.$next_page.'" title="next page">Next Page</a></div>';
echo $page_link;
?>


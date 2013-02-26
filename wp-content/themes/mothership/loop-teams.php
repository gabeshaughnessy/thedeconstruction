<?php //check for newsfeed transient, if it exists, assign variable newfeed to it
$team_list = get_transient('team-list');

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
	$blogusers = get_users('exclude='.$exclude.'&orderby='.$order_by.'&role='.$role);
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
$columns = 3;
foreach($teams as $team) {
if($i % $columns == 1){ 
	$row = true;
	$team_list .=  '<li class="row"></li>';
}
	$display_name = $team['data']->display_name;
	$avatar = get_avatar($team['ID'], $avatar_size);
	$team_profile_url = home_url('/').'team/'.$team['data']->user_nicename;
	$team_location = get_user_meta($team['ID'], 'team-location', true);
	$team_list .= '<li class="'.writeOutNum(12/$columns).' columns"><a class="team-image th" title="View the Team Profile Page" href="'. $team_profile_url. '">'. $avatar . '</a><div class="team-details panel twelve columns"><a class="" title="View the Team Profile Page" href="'. $team_profile_url.'" class="team-link"><h6>'. $display_name. '</h6></a><p class="team-location ">'.$team_location.'</p></div></li>';
	$i++;
}
$team_list .= '</ul>';
}
set_transient('team-list', $team_list, 60*60);//set to 1hr
echo $team_list;
?>
<?php

function getSocialTags($post){

 $attached_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'social');
 if(!empty($attached_image) && isset($attached_image[0])){
  $social_meta['image'] = $attached_image[0];
 }  
 $has_video = false;

$socialTags = '';
  $socialTags .= '<meta property="og:site_name" content="'.get_bloginfo('name').'" >';
  $socialTags .= '<meta property="og:url" content="'.get_bloginfo('url').'" >';
  $socialTags .= '<meta property="og:type" content="article" >';
  $socialTags .= '<meta property="og:title" content="'.wp_title("",false).'" >';
  $socialTags .= '<meta property="og:description" content="'.strip_tags(get_the_excerpt($post->ID)).'"  >';
  //$socialTags .= '<meta property="og:author" content="" >';
  $socialTags .= '<meta property="og:image" content="'.(isset( $social_meta['image'])?  $social_meta['image'] : "").'" >';
  
  if($has_video == true){
    $socialTags .= '<meta property="og:video" content="" >';
    $socialTags .= '<meta property="og:video:width" content="" >';
    $socialTags .= '<meta property="og:video:height" content="" >';
   } 
  //TWITTER
  if($has_video == false){
    $socialTags .= '<meta name="twitter:card" content="summary" >';
  }
  else {
    $socialTags .= '<meta name="twitter:card" content="player" >';

  }
  
  $socialTags .= '<meta name="twitter:site" content="@thedeconstruction" >';
  $socialTags .= '<meta name="twitter:url" content="'.get_bloginfo('url').'" >';
  $socialTags .= '<meta name="twitter:creator" content="@thedeconstruction" >';
  $socialTags .= '<meta name="twitter:title" content="'.wp_title("",false).'" >';
  $socialTags .= '<meta name="twitter:description" content="'.strip_tags(get_the_excerpt($post->ID)).'" >';
  $socialTags .= '<meta name="twitter:image" content="'.(isset( $social_meta['image'])?  $social_meta['image'] : "").'" >';
  
  $video_id = get_field('decon_video_id', $post->ID);
  if($video_id != ''){
    $has_video = true;
    $socialTags .= '<meta name="twitter:card" content="player">';
    $socialTags .= '<meta name="twitter:player:stream" content="http://www.youtube.com/watch?v='.$video_id.'">';
    $socialTags .= '<meta name="twitter:image" content="http://i1.ytimg.com/vi/'.$video_id.'/maxresdefault.jpg">';
    $socialTags .= '<meta name="twitter:app:name:iphone" content="YouTube">';
    $socialTags .= '<meta name="twitter:app:id:iphone" content="544007664">';
    $socialTags .= '<meta name="twitter:app:name:ipad" content="YouTube">';
    $socialTags .= '<meta name="twitter:app:id:ipad" content="544007664">';
    $socialTags .= '<meta name="twitter:app:url:iphone" content="vnd.youtube://watch/'.$video_id.'">';
    $socialTags .= '<meta name="twitter:app:url:ipad" content="vnd.youtube://watch/'.$video_id.'">';
    $socialTags .= '<meta name="twitter:app:name:googleplay" content="YouTube">';
    $socialTags .= '<meta name="twitter:app:id:googleplay" content="com.google.android.youtube">';
    $socialTags .= '<meta name="twitter:app:url:googleplay" content="http://www.youtube.com/watch?v='.$video_id.'">';
    $socialTags .= '<meta name="twitter:player" content="https://www.youtube.com/embed/'.$video_id.'">';
    $socialTags .= '<meta name="twitter:player:width" content="1920">';
    $socialTags .= '<meta name="twitter:player:height" content="1080">';
  }

rewind_posts();
return $socialTags;
}

function socialAccordion($post){
$socialAccordion = '';
//this is to output the page title and content for a 'page for posts' page as set under  'settings' - 'reading'
    if( is_home() && !is_paged() && get_option('page_for_posts') ) {
      $posts_page_id = get_option('page_for_posts'); 
      $social_meta['page_title'] = get_the_title($posts_page_id);
      $posts_page = get_page( $posts_page_id );
       $social_meta['page_content'] =  apply_filters('the_content', $posts_page->post_content);
     }

$social_meta = array();

  
        //set up sharing url variables
        //twitter documentation: 
        // https://dev.twitter.com/docs/intents
        $social_meta['twitter_url'] = "https://twitter.com/intent/tweet?url=";
        $social_meta['linkedin_url'] = "http://www.linkedin.com/shareArticle?mini=true&url=";
        //we need to get our site validated to start using twitter cards: https://dev.twitter.com/docs/cards/validation/validator

        //linked in documentation:
        // https://developer.linkedin.com/documents/share-linkedin
        //linked in share link format: 
        // http://www.linkedin.com/shareArticle?mini=true&url={articleUrl}&title={articleTitle}&summary={articleSummary}&source={articleSource}

        $social_meta['googleplus_url'] = "https://plus.google.com/share?url=";
        //google+ documentation: https://developers.google.com/+/web/share/

        $social_meta['facebook_url'] = "https://www.facebook.com/sharer/sharer.php?u=";
        //facebook documentation: https://developers.facebook.com/docs/plugins/share-button/
        //facebook link format: https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fparse.com

$socialAccordion .= '<ul class="accordion social"> ';
$socialAccordion .=  '<li class="facebook">
    <div class="title">
      <a onclick=\'javascript:window.open(this.href,"", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600"); return false;\' class="icon jv_facebook" title="Share on Facebook" href="'.$social_meta["facebook_url"].urlencode(get_permalink($post->ID)).'"><h5>Facebook</h5></a>
    </div>
  </li>';
$socialAccordion .=  ' <li class="google">
    <div class="title">
      <a onclick=\'javascript:window.open(this.href,"","menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600"); return false;\' class="icon decon_googleplus" title="Share on Google+" href="'.$social_meta["googleplus_url"].urlencode(get_permalink($post->ID)).'"><h5>Google+</h5></a>
    </div>
  </li>';
$socialAccordion .=  ' <li class="twitter">
    <div class="title">
      <a onclick=\'javascript:window.open("'.$social_meta["twitter_url"].urlencode(get_permalink($post->ID)).'","","menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;\'class="icon decon_twitter" title="Tweet" href="#jv_twitter"><h5>Twitter</h5></a>
    </div>
  </li>';
$socialAccordion .=  '
  <li class="newsletter">
    <div class="title">
      <a onclick=\'javascript:window.open(this.href,"","menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600"); return false;\' class="icon" href="mailto:?subject="'.get_the_title().'&amp;body='. get_permalink($post->ID).'" title="Share by Email"><h5>Email</h5></a>
    </div>
   
  </li>';
$socialAccordion .=  '</ul>';
return $socialAccordion;  
}
?>
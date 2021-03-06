<?php

function getSocialTags($post){

 $attached_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'social');
 if(!empty($attached_image) && isset($attached_image[0])){
  
  $social_meta['image'] = $attached_image[0];
 }  
 $has_video = false;
  $video_id = get_field('decon_video_id', $post->ID);
  if($video_id != ''){
    $has_video = true;
  }

if(get_the_excerpt($post->ID) != ''){
  $description = get_the_excerpt($post->ID);
}
else {
  $description = wp_title("",false);
}

$socialTags = '';
  $socialTags .= '<meta property="og:site_name" content="'.get_bloginfo('name').'" >';
  $socialTags .= '<meta property="og:url" content="'.get_permalink($post->ID).'" >';
  $socialTags .= '<meta property="og:title" content="'.wp_title("",false).'" >';
  $socialTags .= '<meta property="og:description" content="'.$description.'"  >';
  //$socialTags .= '<meta property="og:author" content="" >';

  
  

   
  //TWITTER
  if($has_video == false){
    $socialTags .= '<meta property="og:type" content="article" >';
    $socialTags .= '<meta property="og:image" content="'.(isset( $social_meta['image'])?  $social_meta['image'] : "").'" >';
    $socialTags .= '<meta name="twitter:card" content="summary" >';
    $socialTags .= '<meta name="twitter:image" content="'.(isset( $social_meta['image'])?  $social_meta['image'] : "").'" >';
  } 
  $socialTags .= '<meta name="twitter:site" content="Deconstruction" >';
  $socialTags .= '<meta name="twitter:url" content="'.get_permalink($post->ID).'" >';
  $socialTags .= '<meta name="twitter:creator" content="Deconstruction" >';
  $socialTags .= '<meta name="twitter:title" content="'.wp_title("",false).'" >';
  

  $socialTags .= '<meta name="twitter:description" content="'.$description.'" >';

  if( $has_video == true){
    $json = file_get_contents('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v='.$video_id.'&format=json');
    $obj = json_decode($json);
    //error_log(print_r($obj, true));
    if(isset($obj->width)){
      $og_width = $obj->width;
    }
    if(isset($obj->height)){
      $og_height = $obj->height;
    }
    if(!isset($og_width)){
      $og_width = '1280';
    }
    if(!isset($og_height)){
      $og_width = '720';
    }
    if(function_exists('get_video_thumbnail') && get_video_thumbnail($post->ID) != ''){
    $video_thumbnail  = get_video_thumbnail($post->ID); 
    }
    elseif(isset($obj->thumbnail_url)){
      $video_thumbnail = $obj->thumbnail_url;
    }
    else{
      $video_thumbnail = 'http://i1.ytimg.com/vi/'.$video_id.'/maxresdefault.jpg';
    }

    $socialTags .= '<meta property="og:type" content="video" >';
    $socialTags .= '<meta property="og:image" content="'.$video_thumbnail.'" >';
    $socialTags .= '<meta property="og:video" content="http://www.youtube.com/v/'.$video_id.'?version=3&autohide=1" >';
    $socialTags .= '<meta property="og:video:width" content="'.$og_width.'" >';
    $socialTags .= '<meta property="og:video:height" content="'.$og_height.'" >';
    $socialTags .= '<meta name="twitter:card" content="player">';
    $socialTags .= '<meta name="twitter:image" content="'.$video_thumbnail.'">';
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
    $socialTags .= '<meta name="twitter:player:width" content="'.$og_width.'">';
    $socialTags .= '<meta name="twitter:player:height" content="'.$og_height.'">';
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


        $social_meta['facebook_url'] = "https://www.facebook.com/sharer/sharer.php?u=";
        //facebook documentation: https://developers.facebook.com/docs/plugins/share-button/
        //facebook link format: https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fparse.com

$socialAccordion .= '<ul class="accordion social"> ';
$socialAccordion .=  '<li class="facebook">
    <div class="title">
      <a onclick=\'javascript:window.open(this.href,"", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600"); return false;\' class="icon jv_facebook" title="Share on Facebook" href="'.$social_meta["facebook_url"].urlencode(get_permalink($post->ID)).'"><h5>Facebook</h5></a>
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
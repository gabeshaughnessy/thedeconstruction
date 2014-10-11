<?php 
global $post;

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
?>
<ul class="accordion social">
  <li class="facebook">
    <div class="title">
      <a onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false; " class="icon jv_facebook" title="Share on Facebook" href="<?php echo $social_meta['facebook_url'].urlencode(get_permalink($post->ID)); ?>"><h5>Facebook</h5></a>
    </div>
  </li>
  <li class="google">
    <div class="title">
      <a onclick="javascript:window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" class="icon decon_googleplus" title="Share on Google+" href="<?php echo $social_meta['googleplus_url'].urlencode(get_permalink($post->ID)); ?>"><h5>Google+</h5></a>
    </div>
  </li>
  <li class="twitter">
    <div class="title">
      <a onclick="javascript:window.open('<?php echo $social_meta['twitter_url'].urlencode(get_permalink($post->ID));
                //twitter widget.js conflicts with this unless we put the URL directly in the onclick function
             ?>','','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false; "class="icon decon_twitter" title="Tweet" href="#jv_twitter"><h5>Twitter</h5></a>
    </div>
  </li>
  <li class="newsletter">
    <div class="title">
      <a onclick="javascript:window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" class="icon" href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php echo get_permalink($post->ID); ?>" title="Share by Email"><h5>Email</h5></a>
    </div>
   
  </li>
  
</ul>
<ul class="accordion social">
  <li class="facebook">
    <div class="title">
      <h5>Facebook</h5>
    </div>
    <div class="content">
    <?php echo do_shortcode('[like_widget action="recommend" width="300" send="true" layout="button_count"] '); ?>
  </li>
  <li class="google">
    <div class="title">
      <h5>Google+</h5>
    </div>
    <div class="content">
      <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
      <g:plus href="https://plus.google.com/114145539134009316688"></g:plus>
    </div>
  </li>
  <li class="twitter">
    <div class="title">
      <h5>Twitter</h5>
    </div>
    <div class="content">
     <script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
        <a href="http://twitter.com/share" class="twitter-share-button"
           data-url="<?php the_permalink(); ?>"
           data-via="deconstruction"
           data-text="<?php the_title(); ?>"
           data-related="@deconstruction"
           data-count="horizontal">Tweet</a>
    </div>
  </li>
  <li class="newsletter">
    <div class="title">
      <h5>Newsletter</h5>
    </div>
    <div class="content">
       <?php echo do_shortcode('[gravityform id="2" name="Newsletter Signup" ajax="true"]'); ?>
    </div>
  </li>
  
</ul>

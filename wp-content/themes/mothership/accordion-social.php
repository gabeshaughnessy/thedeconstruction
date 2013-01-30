<div class="one columns offset-by-eleven">
<ul class="accordion social">
  <li class="facebook">
    <div class="title">
      <h5>Facebook</h5>
    </div>
    <div class="content">
    <?php echo do_shortcode('[like_widget action="recommend" width="300" send="true" layout="button_count"]'); ?>
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
      <a class="twitter-timeline" href="https://twitter.com/Deconstruction" data-widget-id="291720147733979136">Tweets by @Deconstruction</a>
      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
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
</div>
jQuery(document).ready(function() {
  var menuToggle = jQuery("#js-navigation-centered-mobile-menu").unbind();
  jQuery("#js-navigation-centered-menu").removeClass("show");
  
  menuToggle.on("click", function(e) {
    e.preventDefault();
    jQuery("#js-navigation-centered-menu").slideToggle(function(){
      if(jQuery("#js-navigation-centered-menu").is(":hidden")) {
        jQuery("#js-navigation-centered-menu").removeAttr("style");
      }
    });
  });
});

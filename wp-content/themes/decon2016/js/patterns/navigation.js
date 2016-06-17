jQuery(window).resize(function() {
  var more = document.getElementById("js-navigation-more");
  if (jQuery(more).length > 0) {
    var windowWidth = jQuery(window).width();
    var moreLeftSideToPageLeftSide = jQuery(more).offset().left;
    var moreLeftSideToPageRightSide = windowWidth - moreLeftSideToPageLeftSide;

    if (moreLeftSideToPageRightSide < 330) {
      jQuery("#js-navigation-more .submenu .submenu").removeClass("fly-out-right");
      jQuery("#js-navigation-more .submenu .submenu").addClass("fly-out-left");
    }

    if (moreLeftSideToPageRightSide > 330) {
      jQuery("#js-navigation-more .submenu .submenu").removeClass("fly-out-left");
      jQuery("#js-navigation-more .submenu .submenu").addClass("fly-out-right");
    }
  }
});

jQuery(document).ready(function() {
  var menuToggle = jQuery("#js-mobile-menu").unbind();
  jQuery("#js-navigation-menu").removeClass("show");

  menuToggle.on("click", function(e) {
    e.preventDefault();
    jQuery("#js-navigation-menu").slideToggle(function(){
      if(jQuery("#js-navigation-menu").is(":hidden")) {
        jQuery("#js-navigation-menu").removeAttr("style");
      }
    });
  });
}); 

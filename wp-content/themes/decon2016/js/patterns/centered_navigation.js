jQuery(window).on("load resize",function(e) {
  var more = document.getElementById("js-centered-more");

  if (jQuery(more).length > 0) {
    var windowWidth = jQuery(window).width();
    var moreLeftSideToPageLeftSide = jQuery(more).offset().left;
    var moreLeftSideToPageRightSide = windowWidth - moreLeftSideToPageLeftSide;

    if (moreLeftSideToPageRightSide < 330) {
      jQuery("#js-centered-more .submenu .submenu").removeClass("fly-out-right");
      jQuery("#js-centered-more .submenu .submenu").addClass("fly-out-left");
    }

    if (moreLeftSideToPageRightSide > 330) {
      jQuery("#js-centered-more .submenu .submenu").removeClass("fly-out-left");
      jQuery("#js-centered-more .submenu .submenu").addClass("fly-out-right");
    }
  }

  var menuToggle = jQuery("#js-centered-navigation-mobile-menu").unbind();
  jQuery("#js-centered-navigation-menu").removeClass("show");

  menuToggle.on("click", function(e) {
    e.preventDefault();
    jQuery("#js-centered-navigation-menu").slideToggle(function(){
      if(jQuery("#js-centered-navigation-menu").is(":hidden")) {
        jQuery("#js-centered-navigation-menu").removeAttr("style");
      }
    });
  });
}); 

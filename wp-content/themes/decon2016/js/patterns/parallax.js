jQuery(document).ready(function() {
  if (jQuery("#js-parallax-window").length) {
    parallax();
  }
});

jQuery(window).scroll(function(e) {
  if (jQuery("#js-parallax-window").length) {
    parallax();
  }
});

function parallax(){
  if( jQuery("#js-parallax-window").length > 0 ) {
    var plxBackground = jQuery("#js-parallax-background");
    var plxWindow = jQuery("#js-parallax-window");

    var plxWindowTopToPageTop = jQuery(plxWindow).offset().top;
    var windowTopToPageTop = jQuery(window).scrollTop();
    var plxWindowTopToWindowTop = plxWindowTopToPageTop - windowTopToPageTop;

    var plxBackgroundTopToPageTop = jQuery(plxBackground).offset().top;
    var windowInnerHeight = window.innerHeight;
    var plxBackgroundTopToWindowTop = plxBackgroundTopToPageTop - windowTopToPageTop;
    var plxBackgroundTopToWindowBottom = windowInnerHeight - plxBackgroundTopToWindowTop;
    var plxSpeed = 0.35;

    plxBackground.css('top', - (plxWindowTopToWindowTop * plxSpeed) + 'px');
  }
}

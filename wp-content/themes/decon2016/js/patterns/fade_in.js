jQuery(document).ready(function() {
  var element = document.getElementById("js-fadeInElement");
  jQuery(element).addClass('js-fade-element-hide');

  jQuery(window).scroll(function() {
    if( jQuery("#js-fadeInElement").length > 0 ) {
      var elementTopToPageTop = jQuery(element).offset().top;
      var windowTopToPageTop = jQuery(window).scrollTop();
      var windowInnerHeight = window.innerHeight;
      var elementTopToWindowTop = elementTopToPageTop - windowTopToPageTop;
      var elementTopToWindowBottom = windowInnerHeight - elementTopToWindowTop;
      var distanceFromBottomToAppear = 300;

      if(elementTopToWindowBottom > distanceFromBottomToAppear) {
        jQuery(element).addClass('js-fade-element-show');
      }
      else if(elementTopToWindowBottom < 0) {
        jQuery(element).removeClass('js-fade-element-show');
        jQuery(element).addClass('js-fade-element-hide');
      }
    }
  });
});

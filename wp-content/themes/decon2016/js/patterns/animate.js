jQuery(function() {
  var animationClasses = 'animated alternate iteration zoomOut';
  var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

  jQuery('.animate-trigger').on('click',function() {
    jQuery('.animate-target').addClass(animationClasses).one(animationEnd,function() {
      jQuery(this).removeClass(animationClasses);
    });
  });
});

jQuery(document).ready(function () {
  jQuery('.accordion-tabs').each(function(index) {
    jQuery(this).children('li').first().children('a').addClass('is-active').next().addClass('is-open').show();
  });
  jQuery('.accordion-tabs').on('click', 'li > a.tab-link', function(event) {
    if (!jQuery(this).hasClass('is-active')) {
      event.preventDefault();
      var accordionTabs = jQuery(this).closest('.accordion-tabs');
      accordionTabs.find('.is-open').removeClass('is-open').hide();

      jQuery(this).next().toggleClass('is-open').toggle();
      accordionTabs.find('.is-active').removeClass('is-active');
      jQuery(this).addClass('is-active');
    } else {
      event.preventDefault();
    }
  });
});

jQuery(document).ready(function() {
  jQuery(".js-vertical-tab-content").hide();
  jQuery(".js-vertical-tab-content:first").show();

  /* if in tab mode */
  jQuery(".js-vertical-tab").click(function(event) {


    event.preventDefault();
    jQuery(".js-vertical-tab-content").hide();
    var activeTab = jQuery(this).attr("rel");
    jQuery("#"+activeTab).show();

    jQuery(".js-vertical-tab").removeClass("is-active");
    jQuery(this).addClass("is-active");

    jQuery(".js-vertical-tab-accordion-heading").removeClass("is-active");
    jQuery(".js-vertical-tab-accordion-heading[rel^='"+activeTab+"']").addClass("is-active");
  });

  /* if in accordion mode */
  jQuery(".js-vertical-tab-accordion-heading").click(function(event) {
    event.preventDefault();

    jQuery(".js-vertical-tab-content").hide();
    var accordion_activeTab = jQuery(this).attr("rel");
    jQuery("#"+accordion_activeTab).show();

    jQuery(".js-vertical-tab-accordion-heading").removeClass("is-active");
    jQuery(this).addClass("is-active");

    jQuery(".js-vertical-tab").removeClass("is-active");
    jQuery(".js-vertical-tab[rel^='"+accordion_activeTab+"']").addClass("is-active");
  });
});
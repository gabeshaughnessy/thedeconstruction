jQuery(function() {
  jQuery("#modal-1").on("change", function() {
    if (jQuery(this).is(":checked")) {
      jQuery("body").addClass("modal-open");
    } else {
      jQuery("body").removeClass("modal-open");
    }
  });

  jQuery(".modal-fade-screen, .modal-close").on("click", function() {
    jQuery(".modal-state:checked").prop("checked", false).change();
  });

  jQuery(".modal-inner").on("click", function(e) {
    e.stopPropagation();
  });
});

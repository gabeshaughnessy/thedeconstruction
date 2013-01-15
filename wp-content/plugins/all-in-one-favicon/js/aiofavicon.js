/**
 * @package Techotronic
 * @subpackage All in one Favicon
 *
 * @since 3.2
 * @author Arne Franken
 *
 * AioFavicon Javascript
 */

/**
 * call favicon loader on page load.
 */
jQuery(document).ready(function() {
    loadFavicons();
    bindEventTriggers();
    bindChangeHandlers();
});

/**
 * load all uploaded favicons
 *
 * @since 3.2
 * @author Arne Franken
 */
(function(jQuery) {
    loadFavicons = function() {
        jQuery.each(Aiofavicon, function(key, value) {
            var $imgTag = "<img src=\"" + value  + "\" />";
            var selector = "#"+key+"-favicon";
            jQuery(selector).empty().html($imgTag).fadeIn();
        });
    }
})(jQuery);

// loadFavicons()

/**
 * Find all buttons, attach a click event.
 * Event triggers a click event on the hidden "file" input field
 * which displays the file selector dialog.
 *
 * @since 4.1
 * @author Arne Franken
 */
(function(jQuery) {
    bindEventTriggers = function() {

      var form = jQuery("form#aio-favicon-settings-update");

      var buttonInputs = form.find('input[type="button"]');

      buttonInputs.click(function () {
          jQuery(this)
              .siblings('input[type="file"]')
              .trigger('click');
      });

    }
})(jQuery);

// bindEventTriggers()


/**
 * Attach change event handler to all hidden "file" inputs.
 * Value will be copied to "text" input when user selects a file.
 * Only the filename will be displayed.
 *
 * @since 4.1
 * @author Arne Franken
 */
(function(jQuery) {
    bindChangeHandlers = function() {

        var form = jQuery("form#aio-favicon-settings-update");

        var fileInputs = form.find('input[type="file"]');

        fileInputs.change(function () {
            jQuery(this)
                .siblings('input[type="text"]')
                .val(jQuery(this)
                .val());
        });
    }
})(jQuery);

// bindChangeHandlers()

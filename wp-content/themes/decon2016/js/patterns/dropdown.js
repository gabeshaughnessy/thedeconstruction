jQuery(document).ready(function() {
  jQuery(".dropdown-button").click(function() {
    var jQuerybutton, jQuerymenu;
    jQuerybutton = jQuery(this);
    jQuerymenu = jQuerybutton.siblings(".dropdown-menu");
    jQuerymenu.toggleClass("show-menu");
    jQuerymenu.children("li").click(function() {
      jQuerymenu.removeClass("show-menu");
      jQuerybutton.html(jQuery(this).html());
    });
  });
});

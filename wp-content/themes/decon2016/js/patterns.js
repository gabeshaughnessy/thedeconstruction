jQuery(document).ready(function() {
	jQuery('.js-accordion-trigger').bind('click', function(e){
	  jQuery(this).parent().find('.submenu').slideToggle('fast');  // apply the toggle to the ul
	  jQuery(this).parent().toggleClass('is-expanded');
	  e.preventDefault();
	});
});jQuery(document).ready(function() {
	jQuery('.accordion-base-trigger').bind('click', function(e){
	  jQuery(this).parent().find('.submenu').slideToggle('fast');  // apply the toggle to the ul
	  jQuery(this).parent().toggleClass('is-expanded');
	  e.preventDefault();
	});
});jQuery(document).ready(function () {
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
jQuery(document).ready(function () {
  jQuery('.accordion-tabs-minimal').each(function(index) {
    jQuery(this).children('li').first().children('a').addClass('is-active').next().addClass('is-open').show();
  });
  jQuery('.accordion-tabs-minimal').on('click', 'li > a.tab-link', function(event) {
    if (!jQuery(this).hasClass('is-active')) {
      event.preventDefault();
      var accordionTabs = jQuery(this).closest('.accordion-tabs-minimal');
      accordionTabs.find('.is-open').removeClass('is-open').hide();

      jQuery(this).next().toggleClass('is-open').toggle();
      accordionTabs.find('.is-active').removeClass('is-active');
      jQuery(this).addClass('is-active');
    } else {
      event.preventDefault();
    }
  });
});
jQuery(function() {
  var animationClasses = 'animated alternate iteration zoomOut';
  var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

  jQuery('.animate-trigger').on('click',function() {
    jQuery('.animate-target').addClass(animationClasses).one(animationEnd,function() {
      jQuery(this).removeClass(animationClasses);
    });
  });
});
jQuery(document).ready(function() {
	jQuery('.base-accordion-trigger').bind('click', function(e){
  jQuery(this).parent().find('.submenu').slideToggle('fast');
  jQuery(this).parent().toggleClass('is-expanded');
  e.preventDefault();
});
});jQuery(window).on("load resize",function(e) {
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
jQuery(document).ready(function() {
  jQuery('.expander-trigger').click(function(){
    jQuery(this).toggleClass("expander-hidden");
  });
});
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
jQuery(document).ready(function() {
  var menuToggle = jQuery("#js-navigation-centered-mobile-menu").unbind();
  jQuery("#js-navigation-centered-menu").removeClass("show");
  
  menuToggle.on("click", function(e) {
    e.preventDefault();
    jQuery("#js-navigation-centered-menu").slideToggle(function(){
      if(jQuery("#js-navigation-centered-menu").is(":hidden")) {
        jQuery("#js-navigation-centered-menu").removeAttr("style");
      }
    });
  });
});
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
(function (jQuery) {
  jQuery.mark = {
    jump: function (options) {
      var defaults = {
        selector: 'a.scroll-on-page-link'
      };
      if (typeof options == 'string') {
        defaults.selector = options;
      }

      options = jQuery.extend(defaults, options);
      return jQuery(options.selector).click(function (e) {
        var jumpobj = jQuery(this);
        var target = jumpobj.attr('href');
        var thespeed = 1000;
        var offset = jQuery(target).offset().top;
        jQuery('html,body').animate({
          scrollTop: offset
        }, thespeed, 'swing');
        e.preventDefault();
      });
    }
  };
})(jQuery);


jQuery(function(){  
  jQuery.mark.jump();
});
var Filter = (function() {
  function Filter(element) {
    this._element = jQuery(element);
    this._optionsContainer = this._element.find(this.constructor.optionsContainerSelector);
  }

  Filter.selector = '.filter';
  Filter.optionsContainerSelector = '> div';
  Filter.hideOptionsClass = 'hide-options';

  Filter.enhance = function() {
    var klass = this;

    return jQuery(klass.selector).each(function() {
      return new klass(this).enhance();
    });
  };

  Filter.prototype.enhance = function() {
    this._buildUI();
    this._bindEvents();
  };

  Filter.prototype._buildUI = function() {
    this._summaryElement = jQuery('<label></label>').
      addClass('summary').
      attr('data-role', 'summary').
      prependTo(this._optionsContainer);

    this._clearSelectionButton = jQuery('<button class=clear></button>').
      text('Clear').
      attr('type', 'button').
      insertAfter(this._summaryElement);

    this._optionsContainer.addClass(this.constructor.hideOptionsClass);
    this._updateSummary();
  };

  Filter.prototype._bindEvents = function() {
    var self = this;

    this._summaryElement.click(function() {
      self._toggleOptions();
    });

    this._clearSelectionButton.click(function() {
      self._clearSelection();
    });

    this._checkboxes().change(function() {
      self._updateSummary();
    });

    jQuery('body').click(function(e) {
      var inFilter = jQuery(e.target).closest(self.constructor.selector).length > 0;

      if (!inFilter) {
        self._allOptionsContainers().addClass(self.constructor.hideOptionsClass);
      }
    });
  };

  Filter.prototype._toggleOptions = function() {
    this._allOptionsContainers().
      not(this._optionsContainer).
      addClass(this.constructor.hideOptionsClass);

    this._optionsContainer.toggleClass(this.constructor.hideOptionsClass);
  };

  Filter.prototype._updateSummary = function() {
    var summary = 'All';
    var checked = this._checkboxes().filter(':checked');

    if (checked.length > 0 && checked.length < this._checkboxes().length) {
      summary = this._labelsFor(checked).join(', ');
    }

    this._summaryElement.text(summary);
  };

  Filter.prototype._clearSelection = function() {
    this._checkboxes().each(function() {
      jQuery(this).prop('checked', false);
    });

    this._updateSummary();
  };

  Filter.prototype._checkboxes = function() {
    return this._element.find(':checkbox');
  };

  Filter.prototype._labelsFor = function(inputs) {
    return inputs.map(function() {
      var id = jQuery(this).attr('id');
      return jQuery("label[for='" + id + "']").text();
    }).get();
  };

  Filter.prototype._allOptionsContainers = function() {
    return jQuery(this.constructor.selector + " " + this.constructor.optionsContainerSelector);
  };

  return Filter;
})();

jQuery(function() {
  Filter.enhance();
});
jQuery(document).ready(function(){
  jQuery('.sliding-panel-button,.sliding-panel-fade-screen,.sliding-panel-close').on('click touchstart',function (e) {
    jQuery('.sliding-panel-content,.sliding-panel-fade-screen').toggleClass('is-visible');
    e.preventDefault();
  });
});
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
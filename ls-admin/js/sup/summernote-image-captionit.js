/* https://github.com/DiemenDesign/summernote-image-captionit */
(function (factory) {
  if (typeof define === 'function' && define.amd) {
    define(['jquery'],factory)
  } else if (typeof module === 'object' && module.exports) {
    module.exports = factory(require('jquery'));
  } else {
    factory(window.jQuery)
  }
}
(function ($) {
  $.extend(true,$.summernote.lang, {
    'en-US': {
      captionIt: {
        tooltip: 'Caption It'
      }
    }
  });
  $.extend($.summernote.options, {
    captionIt: {
      icon: '<i class="fas fa-closed-captioning" title="Image Caption" />',
      figureClass: '',
      figcaptionClass: '',
      captionText: 'Caption Goes Here.'
    }
  });
  $.extend($.summernote.plugins, {
    'captionIt': function(context) {
      var ui        = $.summernote.ui,
          $editable = context.layoutInfo.editable,
          options   = context.options,
          lang      = options.langInfo;
      context.memo('button.captionIt', function () {
        var button=ui.button({
          contents: options.captionIt.icon,
          tooltip:  lang.captionIt.tooltip,
          click: function () {
            var img = $($editable.data('target'));
            var $parentAnchorLink = img.parent();
            if ($parentAnchorLink.parent('figure').length) {
              $parentAnchorLink.next('figcaption').remove();
              $parentAnchorLink.unwrap('figure');
            } else {
              var titleText    = img.attr('title'),
                  altText      = img.attr('alt'),
                  classList    = img.attr('class'),
                  inlineStyles = img.attr('style'),
                  classList    = img.attr('class'),
                  inlineStyles = img.attr('style'),
                  imgWidth     = img.width(),
			            captionText  = '';
		          if (titleText) {
                captionText = titleText;
		          } else if (altText) {
                captionText = altText;
		          } else {
                captionText = options.captionIt.captionText;
		          }
              if ($parentAnchorLink.is('a')) {
                $newFigure = $parentAnchorLink.wrap('<figure class="' + options.captionIt.figureClass + '"></figure>').parent();
                $newFigure.append('<figcaption class="' + options.captionIt.figcaptionClass + '>' + captionText + '</figcaption>');
                $newFigure.width(imgWidth);
              } else {
                $newFigure = img.wrap('<figure class="' + options.captionIt.figureClass + '"></figure>').parent();
                img.after('<figcaption class="' + options.captionIt.figcaptionClass + '">' + captionText + '</figcaption>');
                $newFigure.width(imgWidth);
              }
            }
          }
        });
        return button.render();
      });
    }
  });
}));

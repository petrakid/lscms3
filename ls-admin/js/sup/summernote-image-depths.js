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
      imageDepths: {
        tooltip: 'Image Depths',
        tooltipDepthOptions: ['Depth 1', 'Depth 2', 'Depth 3', 'Depth 4', 'Depth 5', 'None']
      }
    }
  });
  $.extend($.summernote.options, {
    imageDepths: {
      icon: '<i class="fas fa-clone" title="Image Shadow" />',
      depths: ['z-depth-1', 'z-depth-2', 'z-depth-3', 'z-depth-4', 'z-depth-5', '']
    }
  });
  $.extend($.summernote.plugins, {
    'imageDepths': function(context) {
      var ui        = $.summernote.ui,
          $editable = context.layoutInfo.editable,
          options   = context.options,
          lang      = options.langInfo;
      context.memo('button.imageDepths', function() {
        var button = ui.buttonGroup([
          ui.button({
            className: 'dropdown-toggle',
            contents: options.imageDepths.icon + '&nbsp;&nbsp;<span class="caret"></span>',
            tooltip: lang.imageDepths.tooltipDepth,
            data: {
              toggle: 'dropdown'
            }
          }),
          ui.dropdown({
            className: 'dropdown-depth',
            items: lang.imageDepths.tooltipDepthOptions,
            click: function (e) {
              e.preventDefault();
              var $button = $(e.target);
              var $img    = $($editable.data('target'));
              var index   = $.inArray(
                $button.data('value'),
                lang.imageDepths.tooltipDepthOptions
              );
              $.each(options.imageDepths.depths, function (index,value) {
                $img.removeClass(value);
              });
              $img.addClass(options.imageDepths.depths[index]);
              context.invoke('editor.afterCommand');
            }
          })
        ]);
        return button.render();
      });
    }
  });
}));
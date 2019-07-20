/* https://github.com/DiemenDesign/summernote-image-attributes */
(function (factory) {
  if (typeof define === 'function' && define.amd) {
    define(['jquery'], factory);
  } else if (typeof module === 'object' && module.exports) {
    module.exports = factory(require('jquery'));
  } else {
    factory(window.jQuery);
  }
}(function ($) {
  var readFileAsDataURL = function (file) {
    return $.Deferred( function (deferred) {
      $.extend(new FileReader(),{
        onload: function (e) {
          var sDataURL = e.target.result;
          deferred.resolve(sDataURL);
        },
        onerror: function () {
          deferred.reject(this);
        }
      }).readAsDataURL(file);
    }).promise();
  };
  $.extend(true,$.summernote.lang, {
    'en-US': { /* US English(Default Language) */
      imageAttributes: {
        dialogTitle: 'Image Attributes',
        tooltip: 'Image Attributes',
        tabImage: 'Image',
          src: 'Source',
          browse: 'Browse',
          title: 'Title',
          alt: 'Alt Text',
          dimensions: 'Dimensions',
        tabAttributes: 'Attributes',
          class: 'Class',
          style: 'Style',
          role: 'Role',
        tabLink: 'Link',
          linkHref: 'URL',
          linkTarget: 'Target',
          linkTargetInfo: 'Options: _self, _blank, _top, _parent',
          linkClass: 'Class',
          linkStyle: 'Style',
          linkRel: 'Rel',
          linkRelInfo: 'Options: alternate, author, bookmark, help, license, next, nofollow, noreferrer, prefetch, prev, search, tag',
          linkRole: 'Role',
        tabUpload: 'Upload',
          upload: 'Upload',
        tabBrowse: 'Browse',
        editBtn: 'OK'
      }
    }
  });
  $.extend($.summernote.options, {
    imageAttributes: {
      icon: '<i class="note-icon-pencil"/>',
      removeEmpty: true,
      disableUpload: false,
      imageFolder: ''
    }
  });
  $.extend($.summernote.plugins, {
    'imageAttributes': function (context) {
      var self      = this,
          ui        = $.summernote.ui,
          $note     = context.layoutInfo.note,
          $editor   = context.layoutInfo.editor,
          $editable = context.layoutInfo.editable,
          options   = context.options,
          lang      = options.langInfo,
          imageAttributesLimitation = '';
      if (options.maximumImageFileSize) {
        var unit = Math.floor(Math.log(options.maximumImageFileSize) / Math.log(1024));
        var readableSize = (options.maximumImageFileSize/Math.pow(1024,unit)).toFixed(2) * 1 + ' ' + ' KMGTP'[unit] + 'B';
        imageAttributesLimitation = '<small class="help-block note-help-block">' + lang.image.maximumFileSize + ' : ' + readableSize+'</small>';
      }
      context.memo('button.imageAttributes', function() {
        var button = ui.button({
          contents: options.imageAttributes.icon,
          tooltip:  lang.imageAttributes.tooltip,
          click: function () {
            context.invoke('imageAttributes.show');
          }
        });
        return button.render();
      });
      this.initialize = function () {
        var $container = options.dialogsInBody ? $(document.body) : $editor;
        var timestamp = Date.now();
        var body = '<ul class="tabs">' +
                   '  <li class="tab col s3"><a class="active" href="#note-imageAttributes' + timestamp + '" data-toggle="tab">' + lang.imageAttributes.tabImage + '</a></li>' +
                   '  <li class="tab col s3"><a href="#note-imageAttributes-attributes' + timestamp + '" data-toggle="tab">' + lang.imageAttributes.tabAttributes + '</a></li>' +
                   '  <li class="tab col s3"><a href="#note-imageAttributes-link' + timestamp + '" data-toggle="tab">' + lang.imageAttributes.tabLink + '</a></li>';
        if (options.imageAttributes.disableUpload == false) {
           body += '  <li class="tab col s3"><a href="#note-imageAttributes-upload' + timestamp + '" data-toggle="tab">' + lang.imageAttributes.tabUpload + '</a></li>';
        }
        body +=    '</ul>' +
                   '<div class="tab-content">' +
                   // Tab 2
                   '  <div class="col s12" id="note-imageAttributes-attributes' + timestamp + '">' +
                   '    <div class="note-form-group note-group-imageAttributes-class">' +
                   '      <div class="input-field note-input-group">' +
                   '        <input class="note-imageAttributes-class note-form-control note-input" type="text" id="class">' +
                   '      <label class="control-label note-form-label" for="class">' + lang.imageAttributes.class + '</label>' +                   
                   '      </div>' +
                   '    </div>' +
                   '    <div class="note-form-group note-group-imageAttributes-style">' +
                   '      <div class="input-field note-input-group">' +
                   '        <input class="note-imageAttributes-style note-form-control note-input" type="text" id="style">' +
                   '      <label class="control-label note-form-label" for="style">' + lang.imageAttributes.style + '</label>' +
                   '      </div>' +
                   '    </div>' +
                   '    <div class="note-form-group note-group-imageAttributes-role">' +
                   '      <div class="input-field note-input-group">' +
                   '        <input class="note-imageAttributes-role note-form-control note-input" type="text" id="role">' +
                   '      <label class="control-label note-form-label" for="role">' + lang.imageAttributes.role + '</label>' +
                   '      </div>' +
                   '    </div>' +
                   '  </div>' +
                   // Tab 3
                   '  <div class="col s12" id="note-imageAttributes-link' + timestamp + '">' +
                   '    <div class="note-form-group note-group-imageAttributes-link-href">' +
                   '      <div class="input-field note-input-group">' +
                   '        <input class="note-imageAttributes-link-href note-form-control note-input" type="text" id="linkHref">' +
                   '      <label class="control-label note-form-label" for="linkHref">' + lang.imageAttributes.linkHref + '</label>' +
                   '      </div>' +
                   '    </div>' +
                   '    <div class="note-form-group note-group-imageAttributes-link-target">' +
                   '      <div class="input-field note-input-group">' +
                   '        <input class="note-imageAttributes-link-target note-form-control note-input" type="text" id="linkTarget">' +
                   '      <label class="control-label note-form-label" for="linkTarget">' + lang.imageAttributes.linkTarget + '</label>' +
                   '      </div>' +
                   '      <span class="form-text text-muted note-help-block text-right">' + lang.imageAttributes.linkTargetInfo + '</span>' +
                   '    </div>' +
                   '    <div class="note-form-group note-group-imageAttributes-link-class">' +
                   '      <div class="input-field note-input-group">' +
                   '        <input class="note-imageAttributes-link-class form-control note-form-control note-input" type="text" id="linkClass">' +
                   '      <label class="control-label note-form-label" for="linkClass">' + lang.imageAttributes.linkClass + '</label>' +
                   '      </div>' +
                   '    </div>' +
                   '    <div class="note-form-group note-group-imageAttributes-link-style">' +
                   '      <div class="input-field note-input-group">' +
                   '        <input class="note-imageAttributes-link-style form-control note-form-control note-input" type="text" id="linkStyle">' +
                   '      <label class="control-label note-form-label" for="linkStyle">' + lang.imageAttributes.linkStyle + '</label>' +
                   '      </div>' +
                   '    </div>' +
                   '    <div class="note-form-group note-group-imageAttributes-link-rel">' +
                   '      <div class="input-field note-input-group">' +
                   '        <input class="note-imageAttributes-link-rel form-control note-form-control note-input" type="text" id="linkRel">' +
                   '      <label class="control-label note-form-label" for="linkRel">' + lang.imageAttributes.linkRel + '</label>' +
                   '      </div>' +
                   '      <span class="form-text text-muted note-help-block text-right">' + lang.imageAttributes.linkRelInfo + '</span>' +
                   '    </div>' +
                   '    <div class="note-form-group note-group-imageAttributes-link-role">' +
                   '      <div class="input-field note-input-group">' +
                   '        <input class="note-imageAttributes-link-role note-form-control note-input" type="text" id="linkRole">' +
                   '      <label class="control-label note-form-label" for="linkRole">' + lang.imageAttributes.linkRole + '</label>' +
                   '      </div>' +
                   '    </div>' +
                   '  </div>';
      if (options.imageAttributes.disableUpload == false) {
                   // Tab 4
        body +=    '  <div class="col s12" id="note-imageAttributes-upload' + timestamp + '">' +
                   '   <div class="file-field input-field note-input-group">' +
                   '     <input class="note-imageAttributes-input note-form-control note-input" type="file" name="files" accept="image/*" multiple="multiple" id="upload" />' +
                         imageAttributesLimitation +
                   '   <label class="control-label note-form-label" for="upload">' + lang.imageAttributes.upload + '</label>' +
                   '    </div>' +
                   '  </div>';
        }
        // Tab 1
        body +=    '  <div class="col s12" id="note-imageAttributes' + timestamp + '">' +
                   '    <div class="note-form-group note-group-imageAttributes-url">' +
                   '      <div class="input-field note-input-group">' +
                   '        <input class="note-imageAttributes-src note-form-control note-input" type="text" id="imgSrc" />' +
                   '      <label class="control-label note-form-label active" for="imgSrc">' + lang.imageAttributes.src + '</label>' +
//                   '        <span class="input-group-btn">' +
//                   '          <button class="btn btn-default class="note-imageAttributes-browse">' + lang.imageAttributes.browse + '</button>' +
//                   '        </span>' +
                   '      </div>' +
                   '    </div>' +
                   '    <div class="note-form-group note-group-imageAttributes-title">' +
                   '      <div class="input-field note-input-group">' +
                   '        <input class="note-imageAttributes-title note-form-control note-input" type="text" id="imgTitle" />' +
                   '      <label class="control-label note-form-label" for="imgTitle">' + lang.imageAttributes.title + '</label>' +
                   '      </div>' +
                   '    </div>' +
                   '    <div class="note-form-group note-group-imageAttributes-alt">' +
                   '      <div class="input-field note-input-group">' +
                   '        <input class="note-imageAttributes-alt note-form-control note-input" type="text" id="imgAlt" />' +
                   '      <label class="control-label note-form-label active" for="imgAlt">' + lang.imageAttributes.alt + '</label>' +
                   '      </div>' +
                   '    </div>' +
                   '    <div class="row note-form-group note-group-imageAttributes-dimensions"><div class="col s12">' +
                   '      <div class="input-field note-input-group col s6">' +
                   '        <input class="note-imageAttributes-width note-form-control note-input" type="text" id="imgDimensions" />' +
                   '      <label class="control-label note-form-label active" for="imgDimensions">' + lang.imageAttributes.dimensions + '</label>' +
                   '     <span class="form-text form-text-custom">Width</span>' +
                   '     </div>' +
                   '     <div class="input-field note-input-group col s6">' +
                   '     <i class="summer-icons prefix">clear</i>' +
                   '        <input class="note-imageAttributes-height form-control note-form-control note-input" type="text" />' +
                   '     <span class="form-text form-text-custom">Height</span>' +
                   '      </div>' +
                   '    </div></div>' +
                   '  </div>' +
                   '</div>';
        this.$dialog=ui.dialog({
          title:  lang.imageAttributes.dialogTitle,
          body:   body,
          footer: '<button href="#" class="waves-effect waves-light green white-text btn note-imageAttributes-btn">' + lang.imageAttributes.editBtn + '</button>'
        }).render().appendTo($container);
      };
      this.destroy = function () {
        ui.hideDialog(this.$dialog);
        this.$dialog.remove();
      };
      this.bindEnterKey = function ($input,$btn) {
        $input.on('keypress', function (e) {
          if (e.keyCode === 13) $btn.trigger('click');
        });
      };
      this.bindLabels = function () {
        self.$dialog.find('.form-control:first').focus().select();
        self.$dialog.find('label').on('click', function () {
          $(this).parent().find('.form-control:first').focus();
        });
      };
      this.show = function () {
        var $img    = $($editable.data('target'));
        var imgInfo = {
          imgDom:  $img,
          title:   $img.attr('title'),
          src:     $img.attr('src'),
          alt:     $img.attr('alt'),
          width:   $img.attr('width'),
          height:  $img.attr('height'),
          role:    $img.attr('role'),
          class:   $img.attr('class'),
          style:   $img.attr('style'),
          imgLink: $($img).parent().is("a") ? $($img).parent() : null
        };
        this.showImageAttributesDialog(imgInfo).then( function (imgInfo) {
          ui.hideDialog(self.$dialog);
          var $img = imgInfo.imgDom;
          if (options.imageAttributes.removeEmpty) {
            if (imgInfo.alt)    $img.attr('alt',   imgInfo.alt);    else $img.removeAttr('alt');
            if (imgInfo.width)  $img.attr('width', imgInfo.width);  else $img.removeAttr('width');
            if (imgInfo.height) $img.attr('height',imgInfo.height); else $img.removeAttr('height');
            if (imgInfo.title)  $img.attr('title', imgInfo.title);  else $img.removeAttr('title');
            if (imgInfo.src)    $img.attr('src',   imgInfo.src);    else $img.attr('src', '#');
            if (imgInfo.class)  $img.attr('class', imgInfo.class);  else $img.removeAttr('class');
            if (imgInfo.style)  $img.attr('style', imgInfo.style);  else $img.removeAttr('style');
            if (imgInfo.role)   $img.attr('role',  imgInfo.role);   else $img.removeAttr('role');
          } else {
            if (imgInfo.src)    $img.attr('src',   imgInfo.src);    else $img.attr('src', '#');
            $img.attr('alt',    imgInfo.alt);
            $img.attr('width',  imgInfo.width);
            $img.attr('height', imgInfo.height);
            $img.attr('title',  imgInfo.title);
            $img.attr('class',  imgInfo.class);
            $img.attr('style',  imgInfo.style);
            $img.attr('role',   imgInfo.role);
          }
          if($img.parent().is("a")) $img.unwrap();
          if (imgInfo.linkHref) {
            var linkBody = '<a';
            if (imgInfo.linkClass) linkBody += ' class="' + imgInfo.linkClass + '"';
            if (imgInfo.linkStyle) linkBody += ' style="' + imgInfo.linkStyle + '"';
            linkBody += ' href="' + imgInfo.linkHref + '" target="' + imgInfo.linkTarget + '"';
            if (imgInfo.linkRel) linkBody += ' rel="' + imgInfo.linkRel + '"';
            if (imgInfo.linkRole) linkBody += ' role="' + imgInfo.linkRole + '"';
            linkBody += '></a>';
            $img.wrap(linkBody);
          }
          $note.val(context.invoke('code'));
          $note.change();
        });
      };
      this.showImageAttributesDialog = function (imgInfo) {
        return $.Deferred( function (deferred) {
          var $imageTitle  = self.$dialog.find('.note-imageAttributes-title'),
              $imageInput  = self.$dialog.find('.note-imageAttributes-input'),
              $imageSrc    = self.$dialog.find('.note-imageAttributes-src'),
              $imageAlt    = self.$dialog.find('.note-imageAttributes-alt'),
              $imageWidth  = self.$dialog.find('.note-imageAttributes-width'),
              $imageHeight = self.$dialog.find('.note-imageAttributes-height'),
              $imageClass  = self.$dialog.find('.note-imageAttributes-class'),
              $imageStyle  = self.$dialog.find('.note-imageAttributes-style'),
              $imageRole   = self.$dialog.find('.note-imageAttributes-role'),
              $linkHref    = self.$dialog.find('.note-imageAttributes-link-href'),
              $linkTarget  = self.$dialog.find('.note-imageAttributes-link-target'),
              $linkClass   = self.$dialog.find('.note-imageAttributes-link-class'),
              $linkStyle   = self.$dialog.find('.note-imageAttributes-link-style'),
              $linkRel     = self.$dialog.find('.note-imageAttributes-link-rel'),
              $linkRole    = self.$dialog.find('.note-imageAttributes-link-role'),
              $editBtn     = self.$dialog.find('.note-imageAttributes-btn');
          $linkHref.val();
          $linkClass.val();
          $linkStyle.val();
          $linkRole.val();
          $linkTarget.val();
          $linkRel.val();
          if (imgInfo.imgLink) {
            $linkHref.val(imgInfo.imgLink.attr('href'));
            $linkClass.val(imgInfo.imgLink.attr('class'));
            $linkStyle.val(imgInfo.imgLink.attr('style'));
            $linkRole.val(imgInfo.imgLink.attr('role'));
            $linkTarget.val(imgInfo.imgLink.attr('target'));
            $linkRel.val(imgInfo.imgLink.attr('rel'));
          }
          ui.onDialogShown(self.$dialog, function () {
            context.triggerEvent('dialog.shown');
            $imageInput.replaceWith(
              $imageInput.clone().on('change', function () {
                var callbacks = options.callbacks;
                if (callbacks.onImageUpload) {
                  context.triggerEvent('image.upload',this.files[0]);
                } else {
                  readFileAsDataURL(this.files[0]).then( function (dataURL) {
                    $imageSrc.val(dataURL);
                  }).fail( function () {
                    context.triggerEvent('image.upload.error');
                  });
                }
              }).val('')
            );
            $editBtn.click( function (e) {
              e.preventDefault();
              deferred.resolve({
                imgDom:     imgInfo.imgDom,
                title:      $imageTitle.val(),
                src:        $imageSrc.val(),
                alt:        $imageAlt.val(),
                width:      $imageWidth.val(),
                height:     $imageHeight.val(),
                class:      $imageClass.val(),
                style:      $imageStyle.val(),
                role:       $imageRole.val(),
                linkHref:   $linkHref.val(),
                linkTarget: $linkTarget.val(),
                linkClass:  $linkClass.val(),
                linkStyle:  $linkStyle.val(),
                linkRel:    $linkRel.val(),
                linkRole:   $linkRole.val()
              }).then(function (img) {
                context.triggerEvent('change', $editable.html());
              });
            });
            $imageTitle.val(imgInfo.title);
            $imageSrc.val(imgInfo.src);
            $imageAlt.val(imgInfo.alt);
            $imageWidth.val(imgInfo.width);
            $imageHeight.val(imgInfo.height);
            $imageClass.val(imgInfo.class);
            $imageStyle.val(imgInfo.style);
            $imageRole.val(imgInfo.role);
            self.bindEnterKey($editBtn);
            self.bindLabels();
          });
          ui.onDialogHidden(self.$dialog, function () {
            $editBtn.off('click');
            if (deferred.state() === 'pending') deferred.reject();
          });
          ui.showDialog(self.$dialog);
        });
      };
    }
  });
}));

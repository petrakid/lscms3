(function(factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof module === 'object' && module.exports) {
        module.exports = factory(require('jquery'));
    } else {
        factory(window.jQuery);
    }
}
(function($) {
    $.extend($.summernote.plugins, {
        'addRows': function (context) {
            var self = this;
            if(typeof context.options.addRows === 'undefined') {
               context.options.addRows = {};
            }
            if(typeof context.options.addRows.rowTags === 'undefined') {
                context.options.addRows.rowTags = ["col s1", "col s2","col s3", "col s4", "col s5", "col s6", "col s7", "col s8", "col s9", "col s10", "col s11", "col s12"];
            }
            var ui = $.summernote.ui;

            addStyleString(".scrollable-menu {height: auto; max-height: 200px; max-width:300px; overflow-x: hidden;}");

            context.memo('button.addRows', function () {
                return ui.buttonGroup([
                    ui.button({
                        className: 'dropdown-toggle',
                        contents: '<i class="fas fa-columns"\/>' + ' ' + ui.icon(context.options.icons.caret, 'span'),
                        tooltip: 'Add Columns',
                        data: {
                            toggle: 'dropdown'
                        }
                    }),
                    ui.dropdown({
                        className: 'dropdown-style scrollable-menu',
                        items: context.options.addRows.rowTags,
                        template: function (item) {

                            if (typeof item === 'string') {
                                item = {tag: "div", title: item, value: item};
                            }

                            var tag = item.tag;
                            var title = item.title;
                            var style = item.style ? ' style="" ' : '';
                            var cssclass = item.value ? ' class="" ' : '';
                   

                            return '<' + tag + ' ' + cssclass + '>' + title + '</' + tag + '>';
                        },
                        click: function (event, namespace, value) {

                            event.preventDefault();
                            value = value || $(event.target).closest('[data-value]').data('value');



                            var $node = $(context.invoke("restoreTarget"))
                            if ($node.length==0){
                                $node = $(document.getSelection().focusNode.parentElement, ".note-editable");
                            }
                            
                            if (typeof context.options.addRows !== 'undefined' && typeof context.options.addRows.debug !== 'undefined' && context.options.addRows.debug) {
                                console.debug(context.invoke("restoreTarget"), $node, "toggling class: " + value, window.getSelection());
                            }


                            $node.toggleClass(value)


                        }
                    })
                ]).render();
                return $optionList;
            });

            function addStyleString(str) {
                var node = document.createElement('style');
                node.innerHTML = str;
                document.body.appendChild(node);
            }

            // This events will be attached when editor is initialized.
            this.events = {
                // This will be called after modules are initialized.
                'summernote.init': function (we, e) {
                    //console.log('summernote initialized', we, e);
                },
                // This will be called when user releases a key on editable.
                'summernote.keyup': function (we, e) {
                    //  console.log('summernote keyup', we, e);
                }
            };

            // This method will be called when editor is initialized by $('..').summernote();
            // You can create elements for plugin
            this.initialize = function () {

            };

            // This methods will be called when editor is destroyed by $('..').summernote('destroy');
            // You should remove elements on `initialize`.
            this.destroy = function () {
                /*  this.$panel.remove();
                 this.$panel = null; */
            };
        }
    });
}));

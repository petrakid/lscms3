var url = new URL(window.location.href);
var href = url.protocol +'//'+ url.hostname;

$(window).on('load', function() {
     setTimeout(function() {
          $('body').addClass('loaded');
     }, 200);
});

$(function() {
     $('.modal').modal();
});

$(function() {
     $('.modal-static').modal({
          dismissible: true,
     })
})

$(function() {
     $('#sort_area').sortable({
          placeholder: "ui-state-highlight",
          axis: 'y',
          update: function(event, ui) {
               var data = $(this).sortable('serialize');
               $.ajax({
                    url: href + '/admin/includes/includes.php',
                    type: 'POST',
                    data: data
               })
          }
     });
     $('#sort_area').disableSelection();
})

$(function() {
	$(".phone_number").mask("(999)999-9999");
     $(".phone_number").on("blur", function() {
          var last = $(this).val().substr($(this).val().indexOf("-") + 1);
          if(last.length == 3) {
               var move = $(this).val().substr( $(this).val().indexOf("-") - 1, 1);
               var lastfour = move + last;
               var first = $(this).val().substr(0, 9);
               $(this).val(first + '-' + lastfour);
          }
     })
});

function checkUsername()
{
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'validate_email': 1,
               'email_address': $('#username').val()
          },
          success: function(data) {
               if(data == 'true') {
                    $('#user_val').attr('data-success', 'Username Found');
               } else {
                    $('#user_val').attr('data-error', 'Username not found or incorrect format');
               }
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          }          
     })
}

function userLogin()
{
     $('#loginbutton').hide();
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'user_login': 1,
               'username': $('#username').val(),
               'password': $('#password').val(),
               'rememberme': $('#remember_me').prop('checked')
          },
          success: function(data) {
               $('#loginres').show();
               $('#loginres').html(data);
               setTimeout(function() {
                    window.location.href = href;
               }, 1300);
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          } 
     })
}

function exitQuickEdit(s)
{
     myurl = s.split("&")[0];
     window.location.href = 'https://' + myurl;
}

function newMenuForm()
{
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'new_menu_form': 1
          },
          success: function(data) {
               $('#newmenuform').html(data);
               $('#nmenu_parent_id').material_select();
               $('#nmenu_order').material_select();               
          }
     })
}

function makeFriendly(mname)
{
     var mlink;
     mlink = mname.toString();
     mlink = mlink.normalize('NFD');
     mlink = mlink.replace(/[\u0300-\u036f]/g,'');
     mlink = mlink.replace(/\s+/g,'-');
     mlink = mlink.toLowerCase();
     mlink = mlink.replace(/&/g,'-and-');
     mlink = mlink.replace(/[^a-z0-9\-]/g,'');
     mlink = mlink.replace(/-+/g,'-');
     mlink = mlink.replace(/^-*/,'');
     mlink = mlink.replace(/-*$/,'');
     $('#nmenu_link').val(mlink);      
}

function changeFullLink(link)
{
     var nlink;
     nlink = link.toString();
     nlink = nlink.normalize('NFD');
     nlink = nlink.replace(/[\u0300-\u036f]/g,'');
     nlink = nlink.replace(/\s+/g,'-');
     nlink = nlink.toLowerCase();
     nlink = nlink.replace(/&/g,'-and-');
     nlink = nlink.replace(/[^a-z0-9\-]/g,'');
     nlink = nlink.replace(/-+/g,'-');
     nlink = nlink.replace(/^-*/,'');
     nlink = nlink.replace(/-*$/,'');
     $('#emenu_link').val(nlink);        
}

function getChildren(sel)
{
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'get_children': 1,
               'parent': sel
          },
          success: function(data) {
               $('#nmenu_order').html(data);
               $('#nmenu_order').material_select();
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          }           
     })
}

function saveNewPage()
{
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'save_new_page': 1,
               'menu_name': $('#nmenu_name').val(),
               'menu_link': $('#nmenu_link').val(),
               'menu_status': $('input[name=nmenu_status]:checked').val(),
               'menu_order': $('#nmenu_order').val(),
               'menu_parent_id': $('#nmenu_parent_id').val()
          },
          success: function(data) {
               $('#pages_table').html(data);
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          }           
     })     
}

function editPageContent(mid)
{
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'edit_content': 1,
               'menu_id': mid
          },
          success: function(data) {
               $('#modal-editor').html(data);
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          }           
     })      
}

function deleteConfirm(mid)
{
     $('#page-delete').html('Are you SURE?<br /><br /><a href="#!" class="modal-close waves-effect waves-green btn-flat red" onclick="deletePage('+ mid +')">Delete!</a>&nbsp;<a href="#!" class="modal-close waves-effect waves-green btn-flat blue lighten-1">Cancel</a>');
}

function deletePage(mid)
{
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'delete_page': 1,
               'm_id': mid
          },
          success: function(data) {
               $('#pages_table').html(data);               
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          }             
     })     
}

function deleteImage(image, pid)
{
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'delete_image': 1,
               'image_type': image,
               'p_id': pid
          },
          success: function(data) {
               $('#'+ image).hide();               
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          }             
     })
}

function saveChanges(mid, pid)
{
     $('.savebutton').html('<i class="fas fa-circle-notch fa-spin left"></i> Please wait...');
     $('.savebutton').removeAttr('href');
     $('.savebutton').removeAttr('onclick');
     if($('#show_carousel').is(":checked")) {
          var carousel = 1
     } else {
          var carousel = 0;
     }
     if($('#show_sharing').is(":checked")) {
          var sharing = 1;
     } else {
          var sharing = 0;
     }
     fData = new FormData();
     fData.append('update_page', 1);
     fData.append('menu_id', mid);
     fData.append('page_id', pid);
     fData.append('page_title', $('#page_title').val());
     fData.append('section_content', $('#summernotea').summernote('code'));
     fData.append('menu_link', $('#menu_link').val());
     fData.append('keywords', $('#keywords').val());
     fData.append('description', $('#description').val());
     fData.append('menu_status', $('input[name=menu_status]:checked').val());
     fData.append('show_carousel', carousel);
     fData.append('show_sharing', sharing);
     fData.append('seo_image', $('input[name=seo_image]')[0].files[0]);
     fData.append('landing_image', $('input[name=landing_image')[0].files[0]);
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: fData,
          processData: false,
          contentType: false,
          success: function(data) {
               if(data > '') {
                    $('.updateres').show();
                    $('.updateres').html(data);
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500)
               } else {
                    $('.savebutton').html('<i class="material-icons left">check</i> Page Saved!');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500)                    
               }               
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          }
     })
}

function displayImage(input, location)
{
     if(input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
               $('#'+ location)
                    .attr('src', e.target.result)
                    .width('100%')
          }
          reader.readAsDataURL(input.files[0]);
     }     
}

function editUser(userid)
{
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'edit_user': 1,
               'user_id': userid
          },
          success: function(data) {
               $('#usermodalc').html(data);
               $('#security_level').material_select(); 
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          }
     })
}

function resetPass(userid)
{
     if(confirm('Do you really want to reset this user password?')) {
          $.ajax({
               url: href +'/admin/includes/includes.php',
               type: 'POST',
               data: {
                    'reset_pass': 1,
                    'user_id': userid
               },
               success: function(data) {
                    $('#usermodalc').html(data);
               },
               error: function(jqXHR, exception) {
                    console.log(jqXHR.status);
               }
          })
     }
}

function checkScore(passone)
{
     var score = 0;
     var letters = new Object();
     for(var i=0; i<passone.length; i++) {
          letters[passone[i]] = (letters[passone[i]] || 0) + 1;
          score += 5.0 / letters[passone[i]];
     }
     
     var variations = {
          digits: /\d/.test(passone),
          lower: /[a-z]/.test(passone),
          upper: /[A-Z]/.test(passone),
          nonWords: /\W/.test(passone,)
     }
     
     variationCount = 0;
     for(var check in variations) {
          variationCount += (variations[check] == true) ? 1: 0;
     }
     score += (variationCount - 1) * 10;
     
     return parseInt(score);
}

function checkStrength()
{
     passone = $('#new_pass_1').val();
     var score = checkScore(passone);
     if(score > 80) {
          $('#new_pass_1_check').html('Strong Password');
          $('#new_pass_1_check').removeAttr('data-error');
          $('#new_pass_1_check').removeData('error');
          $('#new_pass_1_check').attr('data-success', 'Strong Password');
          return 1;
     }
     if(score > 60) {
          $('#new_pass_1_check').html('Good Password');
          $('#new_pass_1_check').removeAttr('data-error');
          $('#new_pass_1_check').removeData('error');
          $('#new_pass_1_check').attr('data-success', 'Good Password');
          return 1;          
     }
     if(score >=30) {
          $('#new_pass_1_check').html('Password too weak!');
          $('#new_pass_1_check').removeAttr('data-success');
          $('#new_pass_1_check').removeData('success');          
          $('#new_pass_1_check').attr('data-error', 'Password too weak!');
          return 0;          
     }
     if(score < 29) {
          $('#new_pass_1_check').html('Password too weak!');
          $('#new_pass_1_check').removeAttr('data-success');
          $('#new_pass_1_check').removeData('success');          
          $('#new_pass_1_check').attr('data-error', 'Password too weak!');
          return 0;          
     }     
     return 0;
}

function checkPasswords()
{
     passone = $('#new_pass_1').val();
     passtwo = $('#new_pass_2').val();
     if(passone == passtwo) {
          $('#new_pass_2_check').html('Passwords match!');          
          $('#new_pass_2_check').removeAttr('data-error');
          $('#new_pass_2_check').removeData('error');          
          $('#new_pass_2_check').attr('data-success', 'Passwords match!');           
          $('#reset_button').show();
          return 1;
     } else if(passone != passtwo) {
          $('#new_pass_2_check').html('Passwords do not match!');
          $('#new_pass_2_check').removeAttr('data-success');
          $('#new_pass_2_check').removeData('success');          
          $('#new_pass_2_check').attr('data-error', 'Passwords do not match!');          
          $('#reset_button').hide();
          return 0;
     }
}

function autoPass(userid)
{
     var len = 10;
     var chars = 'abcdefghjklmnpqrstuvwxyz123456789#$%^*'.split('');
     var i = chars.length;
     var randomstring = [];
     while(len--) {
          randomstring[len] = chars[Math.random() * i | 0];
     }
     $('#new_pass_1').val(randomstring.join(''));
     $('#new_pass_2').val(randomstring.join(''));
     $('#reset_button').show();     
}

function doResetPass(userid)
{
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'do_reset_pass': 1,
               'user_id': userid,
               'new_password': $('#new_pass_1').val()
          },
          success: function(data) {
               $('#usermodalc').html(data);
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          }
     })
}

function banUser(userid)
{
     if(confirm('Do you really want to ban this user?')) {
          $.ajax({
               url: href +'/admin/includes/includes.php',
               type: 'POST',
               data: {
                    'ban_user': 1,
                    'user_id': userid
               },
               success: function(data) {
                    if(data == 1) {
                         alert('User Banned!');
                    } else if(data == 2) {
                         alert('You cannot ban your own account, silly!  Ban prevented.');
                    } else {
                         alert('An error occurred.  User still active.');
                    }
               },
               error: function(jqXHR, exception) {
                    console.log(jqXHR.status);
               }
          })
     }
}

function addUser()
{
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'add_user': 1,
          },
          success: function(data) {
               $('#usermodalc').html(data);
               $('#security_level').material_select();           
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          }
     })     
}

function saveAccount()
{
     $('#usersave_btn').hide();
     $('#savewait').show();
     var fdata = new FormData();
     fdata.append('save_account', '1');
     fdata.append('user_id', $('#user_id').val());
     fdata.append('first_name', $('#first_name').val());
     fdata.append('last_name', $('#last_name').val());
     fdata.append('security_level', $('#security_level').val());
     fdata.append('user_avatar', $('input[name=user_avatar')[0].files[0]);
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          processData: false,
          contentType: false,
          data: fdata,
          success: function(data) {
               $('#usermodalc').html(data);
               setTimeout(function() {
                    window.location.reload()
               }, 2500);               
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          }
     })
}

function updateAccount()
{
     $('#userupdate_btn').hide();
     $('#updatewait').show();
     var fdata = new FormData();
     fdata.append('update_account', '1');
     fdata.append('cur_user', $('#cur_user').val());
     fdata.append('user_id', $('#user_id').val());
     fdata.append('first_name', $('#first_name').val());
     fdata.append('last_name', $('#last_name').val());
     fdata.append('security_level', $('#security_level').val());
     fdata.append('user_avatar', $('input[name=user_avatar')[0].files[0]);
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          processData: false,
          contentType: false,
          data: fdata,
          success: function(data) {
               $('#usermodalc').html(data);
               setTimeout(function() {
                    window.location.reload()
               }, 2500);               
          },
          error: function(jqXHR, exception) {
               $('#usermodalc').html(jqXHR.status);
          }
     })     
}

function enableEdit()
{
     $('#first_name').prop('disabled', false);
     $('#last_name').prop('disabled', false);
     $('#user_id').prop('disabled', false);
}

function changeprofileValue(field, value)
{
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'change_profile_value': 1,
               'field': field,
               'value': value
          },
          success: function(data) {
               $('#'+ field).prop('disabled', true);
               $('#profupdateres').show();
               setTimeout(function() {
                    $('#profupdateres').hide()
               }, 2000)
          },
          error: function(jqXHR, exception) {
               console.log(jqXHR.status);
          }
     })      
}

$(function() {
     "use strict";

     var window_width = $(window).width();

     $('.header-search-input').focus(
     function() {
          $(this).parent('div').addClass('header-search-wrapper-focus');
     }).blur(
     function() {
          $(this).parent('div').removeClass('header-search-wrapper-focus');
     });

     $('#task-card input:checkbox').each(function() {
          checkbox_check(this);
     });

     $('#task-card input:checkbox').change(function() {
          checkbox_check(this);
     });

     function checkbox_check(el) {
          if (!$(el).is(':checked')) {
               $(el).next().css('text-decoration', 'none'); // or addClass
          } else {
               $(el).next().css('text-decoration', 'line-through'); //or addClass
          }
     }

     $('select').material_select();

     var indeterminateCheckbox = document.getElementById('indeterminate-checkbox');
     if (indeterminateCheckbox !== null)
     indeterminateCheckbox.indeterminate = true;


     $('.dropdown-button, .translation-button, .dropdown-menu').dropdown({
          inDuration: 300,
          outDuration: 225,
          constrainWidth: false,
          hover: true,
          gutter: 0,
          belowOrigin: true,
          alignment: 'left',
          stopPropagation: false
     });

     $('.notification-button, .profile-button, .dropdown-settings').dropdown({
          inDuration: 300,
          outDuration: 225,
          constrainWidth: false,
          hover: true,
          gutter: 0,
          belowOrigin: true,
          alignment: 'right',
          stopPropagation: false
     });

     $('.scrollspy').scrollSpy();

     $('.tooltipped').tooltip({
          delay: 50
     });

     $('.sidebar-collapse').sideNav({
          edge: 'left',
     });

     $('.menu-sidebar-collapse').sideNav({
          menuWidth: 240,
          edge: 'left',
          menuOut: false
     });

     $('.chat-collapse').sideNav({
          menuWidth: 300,
          edge: 'right',
     });

     $('.datepicker').pickadate({
          selectMonths: true,
          selectYears: 15
     });

     $('select').not('.disabled').material_select();
     var leftnav = $(".page-topbar").height();
     var leftnavHeight = window.innerHeight - leftnav;
     if (!$('#slide-out.leftside-navigation').hasClass('native-scroll')) {
          $('.leftside-navigation').perfectScrollbar({
               suppressScrollX: true
          });
     }
     var righttnav = $("#chat-out").height();
     $('.rightside-navigation').perfectScrollbar({
          suppressScrollX: true
     });

     function toggleFullScreen() {
          if ((document.fullScreenElement && document.fullScreenElement !== null) ||
          (!document.mozFullScreen && !document.webkitIsFullScreen)) {
               if (document.documentElement.requestFullScreen) {
                    document.documentElement.requestFullScreen();
               } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
               } else if (document.documentElement.webkitRequestFullScreen) {
                    document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
               }
          } else {
               if (document.cancelFullScreen) {
                    document.cancelFullScreen();
               } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
               } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
               }
          }
     }

     $('.toggle-fullscreen').click(function() {
          toggleFullScreen();
     });

     var toggleFlowTextButton = $('#flow-toggle')
     toggleFlowTextButton.click(function() {
          $('#flow-text-demo').children('p').each(function() {
               $(this).toggleClass('flow-text');
          })
     });

     function is_touch_device() {
          try {
               document.createEvent("TouchEvent");
               return true;
          } catch (e) {
               return false;
          }
     }
     if (is_touch_device()) {
          $('#nav-mobile').css({
               overflow: 'auto'
          })
     }
});

(function (factory) {
     if (typeof define === 'function' && define.amd) {
          define(['jquery'], factory);
     } else if (typeof module === 'object' && module.exports) {
          module.exports = factory(require('jquery'));
     } else {
          factory(window.jQuery);
     }
}
(function($) {
     function ensureWidget(version) {
          if (typeof uploadcare == 'undefined') $.getScript([
               'https://ucarecdn.com/widget/', version, '/uploadcare/uploadcare.min.js'
          ].join(''))
     }

function createButton(context, opts) {
     return function() {
          var icon = opts.buttonIcon ? '<i class="fa fa-' + opts.buttonIcon + '" /> ' : '';

          return $.summernote.ui.button({
               contents: icon + opts.buttonLabel,
               tooltip: opts.tooltipText,
               click: function() {
                    var dialog = uploadcare.openDialog({}, opts);

                    context.invoke('editor.saveRange');
                    dialog.done(done(context, opts));
               }
          }).render();
     };
}

function init(context) {
     var opts = $.extend({
          crop: '',
          version: '2.9.0',
          buttonLabel: 'Uploadcare',
          tooltipText: 'Upload files via Uploadcare'
     }, context.options.uploadcare);

     ensureWidget(opts.version);

     context.memo('button.uploadcare', createButton(context, opts));
}

function standardCallback(context, blob) {
     context.invoke('editor.insertNode', $(
     (blob.isImage ? ['<img src="', blob.cdnUrl + (blob.cdnUrlModifiers ? '' : '-/preview/'), '" alt="', blob.name, '" />' ] : ['<a href="', blob.cdnUrl, '">', blob.name, '</a>']
     ).join('')).get(0));
}

function done(context, opts) {
     return function(data) {
          var isMultiple = opts.multiple;
          var uploads = isMultiple ? data.files() : [data];

          $.when.apply(null, uploads).done(function() {
               var blobs = [].slice.apply(arguments);
               var cb = opts.uploadCompleteCallback;

               context.invoke('editor.restoreRange');

               $.each(blobs, function(i, blob) {
                    if ($.isFunction(cb)) {
                         cb.call(context, blob);
                    } else {
                         standardCallback(context, blob);
                    }
               });
          });
     }
}

$.extend($.summernote.plugins, {uploadcare: init});
}));

$(function() {
     $('#summernote').summernote({
          minHeight: 500,
          focus: true,
          toolbar: [
               ['savebutton', ['save']],
               ['style', ['style']],
               ['font', ['bold', 'italic', 'underline', 'clear']],
               ['fontname', ['fontname']],
               ['color', ['color']],
               ['para', ['ul', 'ol', 'paragraph']],
               ['height', ['height']],
               ['table', ['table']],
               ['insert', ['media', 'link', 'hr', 'video']],
               ['uploadcare', ['uploadcare']],
               ['view', ['codeview']],
               ['help', ['help']]
          ],
          buttons: {
               save: SaveButton
          },
          uploadcare: {
               buttonLabel: 'Media',
               buttonIcon: 'image',
               tooltipText: 'Upload files or video or something',
     
               publicKey: uckey,
               crop: 'free',
               tabs: 'all',
               multiple: true
          }
     });
});

$(function() {
     $('#summerblock').summernote({
          minHeight: 300,
          focus: true,
          toolbar: [
               ['savebutton', ['save']],
               ['style', ['style']],
               ['font', ['bold', 'italic', 'underline', 'clear']],
               ['fontname', ['fontname']],
               ['color', ['color']],
               ['para', ['ul', 'ol', 'paragraph']],
               ['height', ['height']],
               ['table', ['table']],
               ['insert', ['media', 'link', 'hr', 'video']],
               ['uploadcare', ['uploadcare']],
               ['view', ['codeview']],
               ['help', ['help']]
          ],
          buttons: {
               save: SaveButtonB
          },
          uploadcare: {
               buttonLabel: 'Media',
               buttonIcon: 'image',
               tooltipText: 'Upload files or video or something',
     
               publicKey: uckey,
               crop: 'free',
               tabs: 'all',
               multiple: true
          }
     });
});

$(function() {
     $('#summernotea').summernote({
          minHeight: 500,
          focus: true,
          toolbar: [
               ['style', ['style']],
               ['font', ['bold', 'italic', 'underline', 'clear']],
               ['fontname', ['fontname']],
               ['color', ['color']],
               ['para', ['ul', 'ol', 'paragraph']],
               ['height', ['height']],
               ['table', ['table']],
               ['insert', ['media', 'link', 'hr', 'video']],
               ['uploadcare', ['uploadcare']],
               ['view', ['codeview']],
               ['help', ['help']]
          ],
          uploadcare: {
               buttonLabel: 'Media',
               buttonIcon: 'image',
               tooltipText: 'Upload files or video or something',
     
               publicKey: uckey,
               crop: 'free',
               tabs: 'all',
               multiple: true
          }
     });
});

var SaveButton = function(context) {
     var ui = $.summernote.ui;
     var button = ui.button({
          contents: '<i class="fas fa-save" style="color: red" /> Save',
          tooltip: 'Save changes',
          click: function () {
               var savepage = document.location.href.split("//");
               var savepage = savepage[1].split("/");
               var savepage = savepage[1].split("&");
               var savepage = savepage[0];
               $.ajax({
                    url: href +'/admin/includes/includes.php',
                    type: 'POST',
                    data: {
                         'save_quick_edit': 1,
                         'menu_link': savepage,
                         'content': $('#summernote').summernote('code')
                    },
                    success: function(data) {
					$(function() {
						Materialize.toast('Update Successful!', 2500, 'rounded');
					});
                         setTimeout(function() {
                              window.history.back();
                         }, 1000);
                    },
                    error: function(jqXHR, exception) {
                         console.log(jqXHR.status);
                    }                    
               })
          }
     });
     return button.render();
}

var SaveButtonB = function(context) {
     var ui = $.summernote.ui;
     var button = ui.button({
          contents: '<i class="fas fa-save" style="color: red" /> Save',
          tooltip: 'Save changes',
          click: function () {
               $.ajax({
                    url: href +'/admin/includes/includes.php',
                    type: 'POST',
                    data: {
                         'save_block_content': 1,
                         'block_area': $('#blockarea').val(),
                         'block_content': $('#summerblock').summernote('code')
                    },
                    success: function(data) {
					$(function() {
						Materialize.toast('Update Successful!', 2500, 'rounded');
					});
                         setTimeout(function() {
                              window.location.reload();
                         }, 1000);
                    },
                    error: function(jqXHR, exception) {
                         console.log(jqXHR.status);
                    }                    
               })
          }
     });
     return button.render();
}

function updateValue(field, value)
{
	$.ajax({
		url: href +'/admin/includes/includes.php',
		type: 'POST',
		data: {
			'update_value': 1,
			'field': field,
			'value': value
		},
		success: function(data) {
			$(function() {
				Materialize.toast('Update Successful!', 2500, 'rounded');
			})
		},
		error: function(jqXHR, exception) {
			console.log(jqXHR.status);
		}
	})
}

function updateBlock(area, value)
{
     if(area == 'nav') {
    
     }
     if(area == 'navc') {
          myClasses = $('select[name="nav_shade"] :selected').prop("class").toString().split(' ');
          var myClass = myClasses[1];
          $('#nav_shade > option').each(function() {
               $(this).removeClass(myClass);
               $(this).addClass(value);                    
          })
     }
     if(area == 'navcc') {
     }
     if(area == 'navt') {
          
     }
     
     $.ajax({
          url: href +'/admin/includes/includes.php',
          type: 'POST',
          data: {
               'update_block': 1,
               'field': area,
               'value': value
          }, success: function(data) {

          },
		error: function(jqXHR, exception) {
			console.log(jqXHR.status);
		}
     })
}

function editBlock(block)
{
	$.ajax({
		url: href +'/admin/includes/includes.php',
		type: 'POST',
		data: {
			'edit_block': 1,
			'block': block
		},
		success: function(data) {
			switch(block) {
                    case 'fl':
                    case 'fm':
                    case 'fr':
                         $('#blockres').html(data);
                         $(function() {
          				$('#summerblock').summernote({
          					minHeight: 300,
          					focus: true,
          					toolbar: [
          						['savebutton', ['save']],
          						['style', ['style']],
          						['font', ['bold', 'italic', 'underline', 'clear']],
          						['fontname', ['fontname']],
          						['color', ['color']],
          						['para', ['ul', 'ol', 'paragraph']],
          						['height', ['height']],
          						['table', ['table']],
          						['insert', ['media', 'link', 'hr', 'video']],
          						['uploadcare', ['uploadcare']],
          						['view', ['codeview']],
          						['help', ['help']]
          					],
          					buttons: {
          						save: SaveButtonB
          					},
          					uploadcare: {
          						buttonLabel: 'Media',
          						buttonIcon: 'image',
          						tooltipText: 'Upload files or video or something',
          
          						publicKey: uckey,
          						crop: 'free',
          						tabs: 'all',
          						multiple: true
          					}
          				});
           			});
                         break;
                    case 'nav':
                         $('#blockres').html(data);
                         break;
                    case 'cnt':
                         $('#blockres').html(data);
                         break;
                    default:
                         break;
  			}
		},
		error: function(jqXHR, exception) {
			console.log(jqXHR.status);
		}		
	})
}

function showCompany(box)
{
	if($(box).is(':checked')) {
		$.ajax({
			url: href +'/admin/includes/includes.php',
			type: 'POST',
			data: {
				'save_block_company': 1,
				'block_area': $('#blockarea').val(),
			},
			success: function(data) {
				Materialize.toast('Block Updated', 1500, 'rounded');
				setTimeout(function() {
					window.location.reload()
				}, 1500);
			},
			error: function(jqXHR, exception) {
				console.log(jqXHR.status);
			}		
		})
	} else {
		$(function() {
			$('#summerblock').summernote('code', '');
		});
	}
}

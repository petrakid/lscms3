// Copyright (c) 2015, Fujana Solutions - Moritz Maleck. All rights reserved.
// For licensing, see LICENSE.md
var url = new URL(document.URL);
var href = url.protocol +'//'+ url.hostname;
CKEDITOR.plugins.add( 'imageuploader', {
    init: function( editor ) {
        editor.config.filebrowserBrowseUrl = href +'/admin/js/ckeditor/plugins/imageuploader/imgbrowser.php';
    }
});

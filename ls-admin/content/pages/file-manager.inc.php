<link rel="stylesheet" type="text/css" href="<?php echo $g['site_url'] ?>/ls-admin/js/elfinder/css/elfinder.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $g['site_url'] ?>/ls-admin/js/elfinder/css/theme.css" />
<script type="text/javascript" charset="utf-8">
// Documentation for client options:
// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
$(document).ready(function() {
	$('#elfinder').elfinder(
		// 1st Arg - options
		{
			cssAutoLoad : false,               // Disable CSS auto loading
			baseUrl : '<?php echo $g['site_url'] ?>/ls-admin/js/elfinder/',                    // Base URL to css/*, js/*
			url : '<?php echo $g['site_url'] ?>/ls-admin/js/elfinder/php/connector.php'  // connector URL (REQUIRED)
			// , lang: 'ru'                    // language (OPTIONAL)
		},
		// 2nd Arg - before boot up function
		function(fm, extraObj) {
			// `init` event callback function
			fm.bind('init', function() {
				// Optional for Japanese decoder "encoding-japanese.js"
				if (fm.lang === 'ja') {
					fm.loadScript(
						[ '//cdn.rawgit.com/polygonplanet/encoding.js/1.0.26/encoding.min.js' ],
						function() {
							if (window.Encoding && Encoding.convert) {
								fm.registRawStringDecoder(function(s) {
									return Encoding.convert(s, {to:'UNICODE',type:'string'});
								});
							}
						},
						{ loadType: 'tag' }
					);
				}
			});
			// Optional for set document.title dynamically.
			var title = document.title;
			fm.bind('open', function() {
				var path = '',
					cwd  = fm.cwd();
				if (cwd) {
					path = fm.path(cwd.hash) || null;
				}
				document.title = path? path + ':' + title : title;
			}).bind('destroy', function() {
				document.title = title;
			});
		}
	);
});
</script>
<div class="row">
<div class="col s12">
<h5 class="card-title">File Manager</h5>
<p>Using the File Manager is easy!  You can drag and drop files into the folders below.  You can click the Upload button to upload multiple files. You can add folders for better
organization.  Several types of files types are open to you.  Right-click on a file to copy its URL and use in your page content.  You can even edit most image formats on the fly!
If the File Manager doesn't accept a particular file, it will reject it.  Simple and easy!  Now get to work!</p>
</div>
</div>
<div class="row">
<div class="col s12">
<div id="elfinder"></div>
</div>
</div>
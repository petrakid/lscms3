<div class="row">
<h2>Page Blocks</h2>
<div class="col s12 m12">
<div class="card horizontal">
<div class="card-image">
<img src="<?php echo $g['site_url'] ?>/admin/content/assets/img/menu_edit.png" />
</div>
<div class="card-stacked">
<div class="card-content">
<p>You can edit some features and functions of the Main Navigation bar at the top of your screen (public side only, not the administrative bar or menu).</p>
</div>
<div class="card-action">
<a href="#blockeditmodal" class="modal-trigger" onclick="editBlock('nav')">Edit Menu Navigation Block</a>
</div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col s8 m8 offset-s4 offset-l2">
<div class="card horizontal">
<div class="card-image">
<img src="<?php echo $g['site_url'] ?>/admin/content/assets/img/content_edit.png" />
</div>
<div class="card-stacked">
<div class="card-content">
<p>Modify a few settings for the main content block.</p>
</div>
<div class="card-action">
<a href="#blockeditmodal" class="modal-trigger" onclick="editBlock('cnt')">Edit Main Content Block</a>
</div>
</div>
</div>
</div>
</div>
<div class="row">  

<div class="col s12 m4">
<div class="card horizontal">
<div class="card-image">
<img src="<?php echo $g['site_url'] ?>/admin/content/assets/img/lfooter_edit.png" />
</div>
<div class="card-stacked">
<div class="card-content">
<p>The first of three footer blocks.  This is the LEFT footer block and it allows for several options.</p>
</div>
<div class="card-action">
<a href="#blockeditmodal" class="modal-trigger" onclick="editBlock('fl')">Edit Left Footer Block</a>
</div>
</div>
</div>
</div>

<div class="col s12 m4">
<div class="card horizontal">
<div class="card-image">
<img src="<?php echo $g['site_url'] ?>/admin/content/assets/img/mfooter_edit.png" />
</div>
<div class="card-stacked">
<div class="card-content">
<p>The second of three footer blocks.  This is the CENTER footer block and it allows for several options.</p>
</div>
<div class="card-action">
<a href="#blockeditmodal" class="modal-trigger" onclick="editBlock('fm')">Edit Center Footer Block</a>
</div>
</div>
</div>
</div>

<div class="col s12 m4">
<div class="card horizontal">
<div class="card-image">
<img src="<?php echo $g['site_url'] ?>/admin/content/assets/img/rfooter_edit.png" />
</div>
<div class="card-stacked">
<div class="card-content">
<p>The last of three footer blocks.  This is the RIGHT footer block and it allows for several options.</p>
</div>
<div class="card-action">
<a href="#blockeditmodal" class="modal-trigger" onclick="editBlock('fr')">Edit Right Footer Block</a>
</div>
</div>
</div>
</div>
</div>

<div id="blockeditmodal" class="modal">
<div class="modal-content">
<div id="blockres">
	
</div>
</div>
<div class="modal-footer">
<a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
</div>
</div>

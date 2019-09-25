
<?php
$x = $a->editContent($_GET['d']);
$z = $x->fetch(PDO::FETCH_ASSOC);
?>
<section class="section">
<div class="row">
<div class="col s12">
<a class="waves-effect waves-light btn blue" href="<?php echo $g['site_url'] ?>/admin/pages/"><i class="material-icons left">arrow_back</i> Return</a>
<a class="waves-effect waves-light btn green savebutton" href="#!" onclick="saveChanges(<?php echo $z['m_id'] ?>, <?php echo $z['p_id'] ?>)"><i class="material-icons left">save</i> Save Changes</a>
</div>
<div class="col s12">
<div class="card-panel red accent-1 updateres" style="display: none;"></div>
</div>
</div>
</section>

<section class="section">
<div class="row">
<div class="input-field col s12">
<input placeholder="Page Title" id="page_title" type="text" id="page_title" value="<?php echo $z['page_title'] ?>" />
<label for="page_title">Page Title</label>
<span class="helper-text">This is the Page Title.  You may leave this blank if you do not want a title shown at the top of the page or in the browser status/tab.</span>
</div>
</div>
<div class="row">
<div class="input-field col s12">
<div id="summernotea"><?php echo $z['section_content'] ?></div>

</div>
</div>
</div>

</section>

<section class="section">
<div class="row">
<div class="input-field col s3">
<input placeholder="Friendly URL" id="menu_link" name="menu_link" maxlength="55" type="url" value="<?php echo $z['menu_link'] ?>" onblur="changeFullLink(this.value)" />
<label for="menu_link">Friendly Link</label>
<span id="full_link" class="helper-text">Full Link: <a href="<?php echo $g['site_url'] ?>/<?php echo $m->getParent($z['menu_parent_id']) ?><?php echo $z['menu_link'] ?>"><?php echo $g['site_url'] ?>/<?php echo $m->getParent($z['menu_parent_id']) ?><?php echo $z['menu_link'] ?></a></span>
</div>
<div class="col s3">
<span class="helper-text">Page/Menu Status</span>
<p>
<label>
<input type="radio" id="menu_status1" name="menu_status" value="1" <?php if($z['menu_status'] == 1) { echo 'checked="checked"'; } ?> class="with-gap" />
<span>Published</span>
</label>
</p>
<p>
<label>
<input type="radio" id="menu_status2" name="menu_status" value="2" <?php if($z['menu_status'] == 2) { echo 'checked="checked"'; } ?> class="with-gap" />
<span>Hidden</span>
</label>
</p>
<p>
<label>
<input type="radio" id="menu_status0" name="menu_status" value="0" <?php if($z['menu_status'] == 0) { echo 'checked="checked"'; } ?> class="with-gap" />
<span>Draft</span>
</label>
</p>
<p> 
<label>
<input type="checkbox" id="show_carousel" name="show_carousel" value="1" <?php if($z['show_carousel'] == 1) { echo 'checked="checked"';} ?> />
<span>Show Carousel?</span>
</label>
<span class="helper-text">*Will override the Landing Image if enabled</span>
</p>
<p>
<label>
<input type="checkbox" id="show_sharing" name="show_sharing" value="1" <?php if($z['show_sharing'] == 1) { echo 'checked="checked"';} ?> />
<span>Show Sharing Features?</span>
</label>
</p>
</div>
<div class="input-field col s6">
<input placeholder="Search Keywords" id="keywords" name="keywords" maxlength="150" type="text" value="<?php echo $z['keywords'] ?>" />
<label for="keywords">Search Keywords</label>
<span class="helper-text">Useful for search engines and searches.</span>
</div>
</div>

<div class="row">
<div class="input-field file-field col s3">
<div class="btn blue lighten-2"><span>Sharing Image</span><input type="file" accept=".jpg, .jpeg, .png" name="seo_image" id="eseo_image" onchange="displayImage(this, 'si')" /></div>
<div class="file-path-wrapper"><input class="file-path validate" type="text" /></div>
<span class="helper-text">A nice image that will be used when this page is shared on Social Media.  Current image below:</span>
<div style="clear: both;"></div>
</div>
<div class="input-field file-field col s3">
<div class="btn orange"><span>Landing Image</span><input type="file" accept=".jpg, .jpeg, .png" name="landing_image" id="elanding_image" onchange="displayImage(this, 'li')" /></div>
<div class="file-path-wrapper"><input class="file-path validate" type="text" /></div>
<span class="helper-text">A widescreen image which will show at the top of the page.  Current image below:</span>
<div style="clear: both;"></div>
</div>
<div class="input-field col s6">
<textarea id="description" name="description" rows="5" class="materialize-textarea" maxlength="150"><?php echo stripslashes($z['description']) ?></textarea>
<label for="description">Page Description</label>
<span class="helper-text">As with Search Keywords, helps search engines find this page.</span>
</div>
</div>
<div class="row">
<div class="col s3">
<div class="image-container">
<img class="image responsive-img z-depth-3" id="si" src="<?php echo $g['site_url'] ?>/content/assets/seo_images/<?php echo $z['seo_image'] ?>" style="width: 100%;" alt="No Image Added" />
<div class="overlay">
<a href="#!" class="icon" title="Delete Image" onclick="deleteImage('si', <?php echo $z['p_id'] ?>)"><i class="fas fa-trash"></i></a>
</div>
</div>
</div>
<div class="col s3">
<div class="image-container">
<img class="image responsive-img z-depth-3" id="li" src="<?php echo $g['site_url'] ?>/content/assets/landing_images/<?php echo $z['landing_image'] ?>" style="width: 100%;" alt="No Image Added" />
<div class="overlay">
<a href="#!" class="icon" title="Delete Image" onclick="deleteImage('li', <?php echo $z['p_id'] ?>)"><i class="fas fa-trash"></i></a>
</div>
</div>
</div>
<div class="input-field col s6">
<select name="my_plugin" id="my_plugin">
<option value="0" selected>None</option>
<?php
$plug = $plg->getPlugins();
while($pl = $plug->fetch(PDO::FETCH_ASSOC)) {
     if($pl['pl_id'] == $z['plugin_id']) {
          echo '<option value="'. $pl['pl_id'] .'" selected="selected">'. $pl['plugin_name'] .'</option>';
     } else {
          echo '<option value="'. $pl['pl_id'] .'">'. $pl['plugin_name'] .'</option>';          
     }
     ?>
     <?php
}
?>

</select>
<label>Page Plugin</label>
</div>
</div>
</section>

<section class="section">
<div class="row">
<div class="col s12">
<a class="waves-effect waves-light btn blue" href="<?php echo $g['site_url'] ?>/admin/pages/"><i class="material-icons left">arrow_back</i> Return</a>
<a class="waves-effect waves-light btn green savebutton" href="#!" onclick="saveChanges(<?php echo $z['m_id'] ?>, <?php echo $z['p_id'] ?>)"><i class="material-icons left">save</i> Save Changes</a>
</div>
<div class="col s12">
<div class="card-panel red accent-1 updateres" style="display: none;"></div>
</div>
</div>
</section>

<script>
$(function() {
     $('#main').css("padding-left", "0");
     $('.cke_dialog_ui_input_text').addClass('browser-default');
})
</script>

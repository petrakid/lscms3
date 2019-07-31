<div class="row">
<h2 class="header">Style Options</h2>

<?php $st = $a->getStyleSettings() ?>

<div class="col s12 m4">
<div class="card-panel">
<h5 class="card-title">Logo Options</h5>
<span class="helper-text">Page Title or Logo</span>
<p>
<label>
<input class="with-gap" name="page_title" id="page_titlet" type="radio" value="t" onclick="updateStyle('page_title', this.value)" <?php if($st['page_title'] == 't') { echo 'checked="checked"';} ?> />
<span>Text</span>
</label>
</p>
<p>
<label>
<input class="with-gap" name="page_title" id="page_titlel" type="radio" value="l" onclick="updateStyle('page_title', this.value)" <?php if($st['page_title'] == 'l') { echo 'checked="checked"';} ?> />
<span>Logo</span>
</label>
</p>
<span class="helper-text">Site Logo (shows if you select "Logo")</span>
<div class="file-field input-field">
<div class="btn">
<span>Logo Image</span>
<input type="file" name="site_logo" id="site_logo" onchange="displayLogo(this, 'current_logo')" accept=".jpg, .jpeg, .png" />
</div>
<div class="file-path-wrapper">
<input class="file-path validate" type="text" />
</div>
<span class="helper-text">(png or jpg only)</span>
</div>
<div class="row">
<div class="col s12">
<div class="image-container">
<img class="image responsive-img z-depth-3" id="current_logo" src="<?php echo $g['site_url'] ?>/content/assets/logos/<?php echo $st['site_logo'] ?>" style="width: 100%;" alt="No Image Added" />
<div class="overlay">
<a href="#!" class="icon" title="Delete Logo" onclick="deleteLogo('current_logo')"><i class="fas fa-trash"></i></a>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col s12">
<span class="helper-text">Title Font</span>
<div class="input-field">
<input id="select_title_font" type="text" value="<?php echo $a->getWebFonts('title_font') ?>" />
</div>
<span class="helper-text">Title Font Color</span>
<div class="input-field">
<input id="title_font_color" type="color" value="<?php echo $st['title_font_color'] ?>" />
</div>
<nav class="<?php echo $b->getBlockValue('navc') ?> <?php echo $b->getBlockValue('navcc') ?>">
<a class="brand-logo" href="#!" id="title_sample" style="<?php echo $st['title_font'] ?> color: <?php echo $st['title_font_color'] ?>"><?php echo $g['site_name'] ?></a>
</nav>
</div>
</div>
</div>
</div>

<div class="col s12 m4">
<div class="card-panel">
<h5 class="card-title">Navbar Font Settings</h5>
<span class="helper-text">Parent Menu Font</span>
<div class="input-field">
<input id="select_parent_font" type="text" value="<?php echo $a->getWebFonts('parent_menu_font') ?>" />
</div>
<span class="helper-text">Parent Font Color</span>
<div class="input-field">
<input id="parent_font_color" type="color" value="<?php echo $st['parent_font_color'] ?>" />
</div>
<p id="parent_sample" style="<?php echo $st['parent_menu_font'] ?>  color: <?php echo $st['parent_font_color'] ?>">Lorem ipsum dolor sit amet consectetur adipiscing elit, hac accumsan imperdiet varius ultricies ligula leo integer, porta torquent netus eleifend hendrerit ex. Conubia eu ligula nullam lobortis diam tincidunt sagittis massa potenti euismod curabitur convallis dis, magna cubilia lorem ac penatibus pulvinar varius nibh gravida nisi litora.</p>

<span class="helper-text">Child Menu Font</span>
<div class="input-field">
<input id="select_child_font" type="text" value="<?php echo $a->getWebFonts('child_menu_font') ?>" />
</div>
<span class="helper-text">Child Font Color</span>
<div class="input-field">
<input id="child_font_color" type="color" value="<?php echo $st['child_font_color'] ?>" />
</div>
<p id="child_sample" style="<?php echo $st['child_menu_font'] ?> color: <?php echo $st['child_font_color'] ?>">Lorem ipsum dolor sit amet consectetur adipiscing elit, hac accumsan imperdiet varius ultricies ligula leo integer, porta torquent netus eleifend hendrerit ex. Conubia eu ligula nullam lobortis diam tincidunt sagittis massa potenti euismod curabitur convallis dis, magna cubilia lorem ac penatibus pulvinar varius nibh gravida nisi litora.</p>
</div>
</div>

<div class="col s12 m4">
<div class="card-panel">
<h5 class="card-title">Landing Images</h5>
<span class="helper-text">Image Width</span>
<p>
<label>
<input class="with-gap" name="landing-width" id="landing-widthf" type="radio" value="f" onclick="updateStyle('landing_width', this.value)" <?php if($st['landing_width'] == 'f') { echo 'checked="checked"';} ?> />
<span>Full Width</span>
</label>
</p>
<p>
<label>
<input class="with-gap" name="landing-width" id="landing-widthc" type="radio" value="c" onclick="updateStyle('landing_width', this.value)" <?php if($st['landing_width'] == 'c') { echo 'checked="checked"';} ?> />
<span>Centered</span>
</label>
</p>
<span class="helper-text">Landing Image Shadow Depth (Centered only)</span>
<div class="row">
<div class="input-field col s12">
<select name="landing_shadow_depth" id="landing_shadow_depth" onchange="updateStyle(this.id, this.value)">
<option value="" selected disabled>Select</option>
<?php
for($d=0; $d<=5;$d++) {
     ?>
     <option value="z-depth-<?php echo $d ?>" <?php if('z-depth-'. $d == $st['landing_shadow_depth']) { echo 'selected=selected"';} ?>><?php echo $d ?></option>
     
     <?php
}
?>
</select>
</div>
</div>


</div>
</div>

</div>
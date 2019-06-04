<?php
$car = new Carousel($db);
?>

<div class="row">
<h2 class="header">Site Carousel</h2>

<div class="col s12 m4">
<h4 class="header">Carousel Settings</h4>
<?php
$cars = $car->carouselSettings();
$st = $cars->fetch(PDO::FETCH_ASSOC);
?>
<div class="row">
<div class="input-field col s6">
<input id="c_duration" name="c_duration" type="number" value="<?php echo $st['c_duration'] ?>" onblur="changeCSValue(this.id, this.value)" />
<label for="c_duration" class="active">Slide View Duration (in milliseconds)</label>
<span class="helper-text helper-text-custom">(1000 milliseconds = 1 second)</span>
</div>
</div>
<div class="space"></div>

<div class="row">
<div class="col s6">
<span class="helper-text">Slider Display Style</span>
<div class="switch">
<label>Full Width
<input type="checkbox" name="c_fullWidth" id="c_fullWidth" onchange="changeCSValue(this.id, this)" <?php if($st['c_fullWidth'] == 0) { echo 'checked="checked"'; } ?> />
<span class="lever"></span>
Cascade
</label>
</div>
</div>
</div>
<div class="space"></div>
<div class="row show_cascade">
<div class="input-field col s6">
<input id="c_dist" name="c_dist" type="number" value="<?php echo $st['c_dist'] ?>" onblur="changeCSValue(this.id, this.value)" />
<label for="c_dist" class="active">Zoom Level</label>
<span class="helper-text helper-text-custom">(0 = All Items same zoom, negative numbers ok)</span>
</div>
</div>
<div class="space"></div>
<div class="row show_cascade">
<div class="input-field col s6">
<input id="c_shift" name="c_shift" type="number" value="<?php echo $st['c_shift'] ?>" onblur="changeCSValue(this.id, this.value)" />
<label for="c_shift" class="active">Center Item Shift</label>
<span class="helper-text helper-text-custom">(in pixels)</span>
</div>
<div class="input-field col s6 show_cascade">
<input id="c_padding" name="c_padding" type="number" value="<?php echo $st['c_padding'] ?>" onblur="changeCSValue(this.id, this.value)" />
<label for="c_shift" class="active">Slide Padding</label>
<span class="helper-text helper-text-custom">(in pixels)</span>
</div>
</div>
<div class="space"></div>
<div class="row show_cascade">
<div class="input-field col s6">
<input id="c_numVisible" name="c_numVisible" type="number" value="<?php echo $st['c_numVisible'] ?>" onblur="changeCSValue(this.id, this.value)" />
<label for="c_numVisible" class="active">Number of Visible slides</label>
<span class="helper-text helper-text-custom">Must be at least 1</span>
</div>
</div>
<div class="space"></div>
<div class="row show_fullWidth">
<div class="col s6">
<p>
<label>
<input type="radio" name="c_indicators" id="c_indicators0" value="0" <?php if($st['c_indicators'] == 0) { echo 'checked="checked"'; } ?> onchange="changeCSValue(c_indicators, this.value)" />
<span>Show Slide Indicators</span>
</label>
</p>
<p>
<label>
<input type="radio" name="c_indicators" id="c_indicators1" value="1" <?php if($st['c_indicators'] == 1) { echo 'checked="checked"'; } ?> onchange="changeCSValue(c_indicators, this.value)" />
<span>Hide Slide Indicators</span>
</label>
</p>
</div>
</div>
</div>

<div class="col s12 m4">
<h4 class="header">Add a Slide</h4>

</div>
<div class="col s12 m4">
<h4 class="header">Current Slides</h4>
<p>To reorder the slides, simply drag and drop to the order you'd like.</p>
<div id="sort_area">
<?php
$slides = $car->carouselSlides(1);
while($sl = $slides->fetch(PDO::FETCH_ASSOC)) {
     ?>
     <div class="slide-container ui-state-default" draggable="true" id="item-<?php echo $sl['cs_id'] ?>">
     <img class="responsive-img" style="width: 100%" src="<?php echo $g['site_url'] ?>/content/assets/carousel/<?php echo $sl['cs_image'] ?>" />
     <div class="slide-options">
     <a href="#!" class="tooltipped" data-position="bottom" data-tooltip="Change or Add a URL" onclick="changeSlideLink(<?php echo $sl['cs_id'] ?>)"><i class="material-icons blue-text" title="Change Link">link</i></a>
     <a href="#!" class="tooltipped" data-position="bottom" data-tooltip="Change Slide Type" onclick="changeSlideType(<?php echo $sl['cs_id'] ?>)" title="Change Slide Type"><i class="material-icons purple-text">aspect_ratio</i></a>
     <?php
     if($sl['cs_status'] == 0) {
          ?>
          <a href="#!" class="tooltipped" data-position="bottom" data-tooltip="Show Slide" onclick="showSlide(<?php echo $sl['cs_id'] ?>)" title="Slide is Hidden"><i class="material-icons blue-gray-text darken-3">check_box_outline_blank</i></a>
          
          <?php
     } else {
          ?>
          <a href="#!" class="tooltipped" data-position="bottom" data-tooltip="Hide Slide" onclick="hideSlide(<?php echo $sl['cs_id'] ?>)" title="Slide is Visible"><i class="material-icons green-text">check_box</i></a>
                    
          <?php
     }
     ?>
     <a href="#!" class="tooltipped" data-position="bottom" data-tooltip="Remove Slide" onclick="removeSlide(<?php echo $sl['cs_id'] ?>)" title="Remove Slide"><i class="material-icons red-text">delete</i></a>
     </div>
     </div>
     
     <?php
}

?>
</div>
</div>

</div>

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
<span class="helper-text">(1000 milliseconds = 1 second)</span>
</div>
<div class="input-field col s6">
<input id="c_interval" name="c_interval" type="number" value="<?php echo $st['c_interval'] ?>" onblur="changeCSValue(this.id, this.value)" />
<label for="c_interval" class="active">Transition Duration (in milliseconds)</label>
<span class="helper-text">(1000 milliseconds = 1 second)</span>
</div>
</div>
<div class="space"></div>

<div class="row">
<div class="col s6">
<span class="helper-text">Slider Display Style</span>
<div class="switch">
<label>Set Width
<input type="checkbox" name="c_fullWidth" id="c_fullWidth" onchange="showOptions()" <?php if($st['c_fullWidth'] == 1) { echo 'checked="checked"'; } ?> />
<span class="lever"></span>
Full Width
</label>
</div>
</div>
<div class="row">
<div class="input-field col s6">
<input type="text" name="c_height" id="c_height" onchange="changeCSValue(this.id, this.value)" value="<?php echo $st['c_height'] ?>" />
<label for="c_height">Slide Height</label>
</div>
</div>
</div>
<div class="space"></div>
<div class="col s12">
<span class="helper-text">Slide Indicators</span>
<p>
<label>
<input type="radio" name="c_indicators" id="c_indicators1" value="1" <?php if($st['c_indicators'] == 1) { echo 'checked="checked"'; } ?> onchange="changeCSValue('c_indicators', this.value)" />
<span>Show</span>
</label>
</p>
<p>
<label>
<input type="radio" name="c_indicators" id="c_indicators0" value="0" <?php if($st['c_indicators'] == 0) { echo 'checked="checked"'; } ?> onchange="changeCSValue('c_indicators', this.value)" />
<span>Hide</span>
</label>
</p>
</div>
</div>

<div class="col s12 m4">
<h4 class="header">Add a Slide</h4>
<span class="helper-text">If you are using the Cascade feature, the image size is not important.  But for the Full Width feature, you MUST use an image that is 16:9 or wide-screen formatted.  Otherwise your carousel will look quite distasteful.<br /><br />
A GREAT place to go to make very nice images is <a href="https://spark.adobe.com" target="_blank">Adobe Spark</a>.  It's free, easy to use, and there are thousands of images available to you.
Plus, you can add text and other formats to the image.  Your final image should not be more than a megabyte in size, however, so be sure to keep this in mind.<br /><br />
NOTE: Do NOT go to Google Images or do a web search for an image and simply use what you find.  You are messing with copyright issues when you do this, and you can easily be fined or sued for 
such things!!
</span>
<div class="row">
<div class="col s12">
<div class="file-field input-field">
<div class="btn">
<span>Add Slide Image</span>
<input type="file" name="cs_image" id="cs_image" accept="image/*" onchange="displaySlide(this)" />
</div>
<div class="file-path-wrapper">
<input class="file-path validate" type="text" />
</div>
</div>
<img id="slide_image_preview" />
</div>
</div>
<div class="row">
<div class="col s12" style="text-align: right;">
<a href="#!" id="add_slide_button" class="waves-effect waves-light btn green white-text" onclick="addSlide()" style="display: none;"><i class="material-icons left">add</i> Accept</a>
</div>
</div>
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
     <a href="#carouselModal" class="tooltipped modal-trigger" data-position="bottom" data-tooltip="Change or Add a URL" onclick="changeSlideLink(<?php echo $sl['cs_id'] ?>)"><i class="material-icons blue-text" title="Change Link">link</i></a>
     <a href="#carouselModal" class="tooltipped modal-trigger" data-position="bottom" data-tooltip="Change Slide Type" onclick="changeSlideType(<?php echo $sl['cs_id'] ?>)" title="Change Slide Type"><i class="material-icons purple-text">aspect_ratio</i></a>
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

<div id="carouselModal" class="modal">
<div class="modal-content" id="slideRes">

</div>
<div class="modal-footer">
<a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancel</a>
</div>
</div>

<script>
function showOptions()
{
     if($('#c_fullWidth').prop('checked') == false) {
          $('.show_cascade').show();
          $('.show_fullWidth').hide();          
     }
     if($('#c_fullWidth').prop('checked') == true) {
          $('.show_cascade').hide();          
          $('.show_fullWidth').show();
     }
     changeCSValue('c_fullWidth', $('#c_fullWidth').prop('checked'));     
}
</script>

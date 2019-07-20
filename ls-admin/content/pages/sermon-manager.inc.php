<?php
$ser = new SermonManager($db);
$settings = $ser->getSermonSettings();
$s = $settings->fetch(PDO::FETCH_ASSOC);
?>

<section class="section">
<h3 class="header">Sermon Manager</h3>
<ul class="collapsible">
<li>
<div class="collapsible-header"><i class="material-icons">settings_applications</i>General Settings</div>
<div class="collapsible-body">
<div class="row">
<div class="col s12 m3">
<div class="card-panel">
<h5 class="header">Scripture Settings</h5>
<div class="input-field col s12">
<input type="text" name="scripture_api" id="scripture_api" value="<?php echo $s['scripture_api'] ?>" onblur="updateSermonConfig(this.id, this.value)" />
<label for="scripture_api">API Key for displaying Scripture</label>
</div>
<blockquote>You only need the API Key for the ESV translation.  You can obtain this API Key by going to <a href="https://my.crossway.org/account/register/" target="_blank">Crossways</a> and
registering.  Once registered (and verified), go to <a href="https://api.esv.org/account/create-application/" target="_blank">Crossways ESV</a> and apply for your API Key.  You
will see the "Authorization: Token" followed by a string of text.  Copy ONLY the string of text and paste it here.  Now you can use the ESV Bible.  Otherwise select any
other version below without using the API Key.</blockquote>
<div class="space"></div>
<div class="input-field col s12">
<select name="scripture_version" id="scripture_version" onchange="updateSermonConfig(this.id, this.value)">
<?php echo $ser->getScriptureVersions($s['scripture_version']) ?>

</select>
<label>Scripture Version</label>
</div>
<blockquote>You may also change this per sermon.</blockquote>
</div>

<div class="card-panel">
<h5 class="header">Featured Sermons</h5>
<span class="helper-text">Control how featured sermons are displayed on the page</span>
<p>Number of Featured sermons to display</p>
<div class="input-field col s12">
<input type="number" min="1" max="4" name="max_featured" id="max_featured" value="<?php echo $s['max_featured'] ?>" onblur="updateSermonConfig(this.id, this.value)" />
<label for="max_featured">Maximum Featured</label>
</div>
<p>&nbsp;</p>
</div>
</div>

<div class="col s12 m3">
<div class="card-panel">
<h5 class="header">Preachers</h5>
<div class="input-field col s12">
<select name="preacher_list" id="preacher_list" onchange="viewPreacher(this.value)">
<option value="" selected>Select</option>
<?php echo $ser->getPreachersSelect() ?>

</select>
<label>Preachers</label>
</div>
<blockquote>Select a preacher from the drop-down menu to edit his information.  You may also add a new preacher to the site by filling in the fields and clicking "Add".</blockquote>
<div class="space"></div>
<div class="row">
<div class="col s12" id="prRes">

<div class="input-field col s6">     
<select name="title" id="ntitle">
<option value="" selected disabled>Select</option>
<option value="Mr.">Mr.</option>
<option value="Rev.">Rev.</option>
<option value="Dr.">Dr.</option>
<option value="Rev. Dr.">Rev. Dr.</option>
<option value="Fr.">Fr.</option>
</select>
<label>Title</label>     
</div>
<div class="input-field col s12">
<input type="text" name="first_name" id="nfirst_name" />
<label class="active" for="nfirst_name">First Name</label>
</div>
<div class="input-field col s12">
<input type="text" name="last_name" id="nlast_name" />
<label class="active" for="nlast_name">Last Name</label>
</div>
<div class="input-field col s12">
<input type="text" name="preacher_location" id="npreacher_location" />
<label class="active" for="npreacher_location">Serving Location</label>
</div>
<div class="input-field col s12">
<input type="text" name="preacher_postion" id="npreacher_position" />
<label class="active" for="npreacher_position">Current Position at Location</label>
</div>
<div class="input-field col s12">
<input type="email" name="preacher_email" id="npreacher_email" />
<label class="active" for="npreacher_email">Email Address</label>
</div>
<div class="input-field col s12">
<input type="text" name="preacher_phone" id="npreacher_phone" />
<label class="active" for="npreacher_phone">Phone Number (numbers only)</label>
</div>
<div class="col s12">
<a href="#!" class="waves-effect waves-light btn teal" onclick="addPreacher()"><i class="material-icons left">save</i>Add</a>
</div>
</div>

</div>
</div>
</div>

<div class="col s12 m3">
<div class="card-panel" style="min-height: 1025px;">
<h5 class="header">Seasons</h5>
<blockquote>You can modify your church year seasons below.  Drag and drop to reorder.  Click on the season name it change options.  Add a new season at the end.</blockquote>

<div class="collection col s12" id="season-sortable">
<?php
$seasons = $ser->getSeasons();
while($se = $seasons->fetch(PDO::FETCH_ASSOC)) {
     ?>
     <div class="ui-state-default collection-item <?php echo $se['season_color'] ?>" id="season-<?php echo $se['se_id'] ?>"><span class="handle secondary-content" style="cursor: pointer;"><i class="material-icons">drag_handle</i></span><a class="<?php if($se['season_color'] == 'black') { echo 'white-text'; } else { echo 'black-text'; } ?>" href="#!" onclick="editSeason(<?php echo $se['se_id'] ?>)"><?php echo $se['season_name'] ?></a></div>
     
     <?php
}
?>

</div>

<div class="col s12" id="seaRes">
<div class="input-field col s12">
<input type="text" name="nseason_name" id="nseason_name" />
<label for="nseason_name" class="active">Season Name</label>
</div>
<div class="input-field col s12">
<select name="nseason_color" id="nseason_color">
<option value="" selected disabled>Select</option>
<option class="black" value="black">Black</option>
<option class="blue" value="blue">Blue</option>
<option class="yellow accent-4" value="yellow accent-4">Gold</option>
<option class="green" value="green">Green</option>
<option class="pink" value="pink">Pink</option>
<option class="purple" value="purple">Purple</option>
<option class="red" value="red">Red</option>
<option class="pink accent-1" value="pink accent-1">Rose</option>
<option class="grey lighten-5" value="grey lighten-5">White</option>
</select>
<label>Season Color</label>
</div>
<a href="#!" class="waves-effect waves-light btn teal" onclick="addSeason()"><i class="material-icons">save</i></a>
</div>
</div>
</div>

<div class="col s12 m3">
<div class="card-panel" style="min-height: 1025px;">
<h5 class="header">Series'</h5>
<blockquote>You can create series to further categorize and feature your sermons.  Enter your new series name, or click on an existing series to edit.</blockquote>
<div class="collection col s12">
<?php
$series = $ser->getSeries();
while($sr = $series->fetch(PDO::FETCH_ASSOC)) {
     ?>
     <a href="#!" class="collection-item" onclick="editSeries(<?php echo $sr['se_id'] ?>)"><?php echo $sr['series_name'] ?></a>
     
     <?php
}
?>
</div>
<div class="col s12" id="ssRes">
<div class="input-field col s12">
<input type="text" name="nseries_name" id="nseries_name" />
<label for="nseries_name" class="active">New Series Name</label>
</div>
<a href="#!" class="waves-effect waves-light btn teal" onclick="addSeries()"><i class="material-icons">save</i></a>
</div>
</div>
</div>
</div>

</div>
</li>

<li>
<div class="collapsible-header active"><i class="material-icons">cloud</i> Sermons</div>
<div class="collapsible-body">
<div class="row">
<div class="col s12 m12" style="margin-bottom: 15px;">
<a href="#addModal" class="waves-effect waves-light btn blue tooltipped modal-trigger" data-position="top" data-tooltip="Add a Sermon" onclick="newSermon()"><i class="material-icons left">add</i>Add Sermon</a>
</div>
<div class="col s12 m12" id="slistRes">
<ul class="collection">
<?php
$sermons = $ser->getSermons();
if($sermons->rowCount() > 0) {
     while($smn = $sermons->fetch(PDO::FETCH_ASSOC)) {
          ?>
          <li class="collection-item avatar <?php echo $ser->getSeason($smn['sermon_season_id']) ?>">
          <img src="<?php echo $g['site_url'] ?>/content/assets/sermons/images/<?php echo $smn['sermon_image_file'] ?>" class="circle" />
          <span class="title"><?php echo $smn['sermon_title'] ?></span>
          <p><?php echo date('M j Y', strtotime($smn['sermon_date'])) ?><br />
          <?php echo $ser->getPreacher($smn['sermon_preacher_id']) ?></p>
          <div class="options">
          <a href="#editModal" class="waves-effect waves-light btn-floating btn-large teal tooltipped modal-trigger" onclick="editSermon(<?php echo $smn['se_id'] ?>)" data-position="top" data-tooltip="Edit"><i class="material-icons">edit</i></a>
          <a href="#!" class="waves-effect waves-light btn-floating btn-large orange tooltipped" data-position="top" data-tooltip="Feature" onclick="changeFeatured(<?php echo $smn['se_id'] ?>)"><i class="material-icons">star</i></a>
          <?php
          if($smn['sermon_status'] == 1) {
               ?>
               <a href="#!" class="waves-effect waves-light btn-floating btn-large green tooltipped" id="sermonstatuslink" onclick="hideSermon(<?php echo $smn['se_id'] ?>)" data-position="top" data-tooltip="Shown; click to hide"><i class="material-icons" id="showhidsermonbtn">visibility</i></a>
               
               <?php
          }
          if($smn['sermon_status'] == 0) {
               ?>
               <a href="#!" class="waves-effect waves-light btn-floating btn-large grey tooltipped" id="sermonstatuslink" onclick="showSermon(<?php echo $smn['se_id'] ?>)" data-position="top" data-tooltip="Hidden; click to show"><i class="material-icons" id="showhidsermonbtn">visibility_off</i></a>
               
               <?php
          }
          ?>
          <a href="#!" class="waves-effect waves-light btn-floating btn-large red tooltipped" onclick="deleteSermon(<?php echo $smn['se_id'] ?>)" data-position="top" data-tooltip="Delete"><i class="material-icons">delete_forever</i></a>
          
          </div>
          <?php
          if($smn['sermon_featured'] == 1) {
               ?>
               <a href="#!" class="secondary-content" title="Featured" id="featuredstar"><i class="material-icons">grade</i></a>
               
               <?php
          } else {
               ?>
               <a href="#!" class="secondary-content" title="Featured" style="display: none;" id="featuredstar"><i class="material-icons">grade</i></a>
               
               <?php               
          }
          ?>
          </li>
          
          <?php
     }
} else {
     echo 'There are no sermons in the database, sorry.';
}
?>

</ul>
</div>
</div>
</div>
</li>
</ul>
</section>

<div id="editModal" class="modal">
<div class="modal-content">
<h4>Edit Sermon</h4>
<div id="editSermonRes">

</div>
</div>
<div class="modal-footer">

</div>
</div>

<div id="addModal" class="modal bottom-sheet">
<div class="modal-content">
<h4>Add Sermon</h4>
<div id="addSermonRes">

</div>
</div>
<div class="modal-footer">

</div>
</div>
   
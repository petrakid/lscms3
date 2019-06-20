<?php
$ser = new SermonManager($db);
$settings = $ser->getSermonSettings();
$s = $settings->fetch(PDO::FETCH_ASSOC);
?>

<section class="section">
<h3 class="header">Sermon Manager</h3>
<div class="row">
<div class="col s12 m3">
<h5 class="header">Configuration and Settings</h5>
<div class="input-field col s12">
<input type="text" name="scripture_api" id="scripture_api" value="<?php echo $s['scripture_api'] ?>" onblur="updateSermonConfig(this.id, this.value)" />
<label for="scripture_api">API Key for displaying Scripture</label>
<span class="helper-text">You only need the API Key for the ESV translation.  You can obtain this API Key by going to <a href="https://my.crossway.org/account/register/" target="_blank">Crossways</a> and
registering.  Once registered (and verified), go to <a href="https://api.esv.org/account/create-application/" target="_blank">Crossways ESV</a> and apply for your API Key.  You
will see the "Authorization: Token" followed by a string of text.  Copy ONLY the string of text and paste it here.  Now you can use the ESV Bible.  Otherwise select any
other version below without using the API Key.</span>
<div class="space"></div>
</div>
<div class="input-field col s12">
<select name="scripture_version" id="scripture_version" onchange="updateSermonConfig(this.id, this.value)">
<?php echo $ser->getScriptureVersions($s['scripture_version']) ?>

</select>
<label for="scripture_version">Scripture Version</label>
<span class="helper-text helper-text-custom">You may also change this per sermon.</span>
</div>
</div>
<div class="col s12 m3">
<h5 class="header">Preachers</h5>

</div>
<div class="col s12 m3">
<h5 class="header">Seasons</h5>

</div>
<div class="col s12 m3">
<h5 class="header">Series</h5>


</div>
</div>
<div class="divider"></div>
<div class="row">
<div class="col s12 m12">
<h5 class="header">Sermons</h5>

</div>
</div>
</section>
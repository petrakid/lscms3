<div class="row">
<div class="col s12">
<h2 class="header">Social Media Features</h2>
</div>
</div>

<div class="row">
<div class="col s4">
<div class="card teal lighten-3">
<div class="card-content">
<div class="card-title">Social Media Integration</div>
<p>The website offers a robust and very modern social media platform called "Addthis".  Addthis has been offering sharing and other services for about as long as the internet
has been a thing.  With Addthis, visitors can click a button and share your page or content to one of hundreds of social media platforms (Facebook, Instagram, Twitter, etc.).</p>
<p>To get started, you will need to setup your AddThis account and create your API Key.  Once created you must copy and paste the API key in the field to the right.  After you
do this you will be able to configure your Sharing features.</p>
</div>
</div>
</div>
<?php
if($sm->socialApiStatus() === true) {
     ?>
     <div class="col s8">
     <div class="card grey lighten-4">
     <div class="card-content">
     <div class="card-title">Sharing Options</div>
     <p>You need to do one more thing at the Addthis website.  You need to get your Profile ID and paste it in the box below.  You can get this by going to your Profile Settings
     page on <a href="https://www.addthis.com" target="_blank">Addthis</a>, and then copying your "ID" which is just under your profile name.  Paste it in the following
     box.</p>
     <div class="input-field col s4">
     <input type="text" name="sm_profile_id" id="sm_profile_id" value="<?php echo $sm->getProfileId() ?>" onblur="saveSmField(this.id, this.value)" />
     <label for="sm_profile_id">Addthis Profile ID</label>
     </div>
     <hr />
     <p>The following settings will customize the layout and other options for the Addthis sharing experience.  </p>
     
     </div>
     </div>
     </div>
     
     <?php
} else {
     ?>
     <div class="col s4">
     <div class="card blue lighten-4">
     <div class="card-content">
     <div class="card-title">Addthis API Registration</div>
     <p>Step 1) Go to <a href="https://www.addthis.com" target="_blank">Addthis</a> and register your account.  You can do this by clicking the "Get started, it's free" button
     or the Dashboard button on the Addthis website.</p>
     <p>Step 2) Once you finish creating your account, you will be taken to the "Select a Tool" page.  This page is of no help to you.  Click the X at the top right of the page.
     On the Dashboard, at the top left, you'll see "My Site".  Click this and then click "Add Profile".  Enter your site's name or url or whatever you'd like.</p>
     <p>Step 3) The Profile Settings for [your profile name] page will now appear.  On the left menu, click API Keys.</p>
     <p>Step 4) On the API Keys page, click Register a New Application.  Give the application a name (doesn't matter what) and save.</p>
     <p>Step 5) You will now see the API Key for the application you just made.  Highlight the API Key and copy it.</p>
     <p>Step 6) Paste the API Key in the following box and click "Save Key".  Your site is now registered with the Addthis API and you can make changes to the Sharing settings.
     Way to go!</p>
     <hr />
     <div class="input-field col s12">
     <input type="text" name="sm_api_key" id="sm_api_key" />
     <label for="sm_api_key">Addthis API Key</label>
     </div>
     <a class="waves-effect waves-light btn green" onclick="saveSmKey()" href="#!"><i class="material-icons left">save</i>Save Key</a>
     </div>
     </div>
     </div>

     <?php
}
?>

</div>
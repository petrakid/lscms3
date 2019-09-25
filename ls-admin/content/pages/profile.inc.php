
<?php
$u = $a->getProfile($_SESSION['user']['user_id'])->fetch(PDO::FETCH_ASSOC) ?>
<section class="section">
<div class="row">
<div class="col s12 m3" style="text-align: center;">
<div class="card blue lighten-3">
<div class="card-content">
<img src="<?php echo $g['site_url'] ?>/content/assets/img/avatar/<?php echo $u['user_avatar'] ?>" style="max-height: 225px;" class="circle responsive-img profile-image valign cyan" />
</div>
</div>
</div>

<div class="col s12 m5">
<h4>Account Information for <?php echo $u['first_name'] .' '. $u['last_name'] ?></h4>
<div class="divider"></div>
<div class="card-panel">

<div class="col s12" style="text-align: right;">
<i class="fas fa-edit" onclick="enableEdit()" style="cursor: pointer;" title="Edit Profile"></i>
</div>

<div class="row">
<div class="input-field col s6">
<input type="text" name="first_name" id="first_name" value="<?php echo $u['first_name'] ?>" disabled="true" onblur="changeprofileValue('first_name', this.value)" />
<label for="first_name" class="active">First Name</label>
</div>

<div class="input-field col s6">
<input type="text" name="last_name" id="last_name" value="<?php echo $u['last_name'] ?>" disabled="true" onblur="changeprofileValue('last_name', this.value)" />
<label for="last_name" class="active">Last Name</label>
</div>
</div>

<div class="row">
<div class="input-field col s12">
<input type="email" name="user_id" id="user_id" value="<?php echo $u['user_id'] ?>" disabled="true" onblur="changeprofileValue('user_id', this.value)" />
<label for="user_id" class="active">Email Address (also your Username)</label>
</div>
</div>
<div class="row">
<div class="col s6">
<b>Your Security Level:</b> <?php echo $sec->getSecLevel($u['security_level']) ?>
</div>
<div class="col s6">
<b>Your Last Login:</b> <?php echo date('M j Y, h:i a', strtotime($_SESSION['user']['last_login'])) ?>
</div>
</div>
<div class="row">
<div class="col s12" style="padding: 5px;">
<div class="green lighten-1" id="profupdateres" style="display: none; padding: 5px; color: white;">Change Successful!</div>
</div>
</div>

</div>
</div>

<div class="col s12 m4">
<div class="card blue-grey lighten-4">
<div class="card-content">
<span class="card-title">Account Options</span>
<div class="section">
<a href="#profileModal" class="btn-large btn-large-min waves-effect waves-light yellow darken-1 modal-trigger" onclick="changeMyPass()"><i class="material-icons left">vpn_key</i> Change Password</a>
</div>
<div class="divider"></div>
<div class="section">
<a href="#profileModal" class="btn-large btn-large-min waves-effect waves-light blue darken-1 modal-trigger" onclick="viewMyMessages()"><i class="material-icons left">message</i> View Messages</a>
</div>
<div class="divider"></div>
<div class="section">
<a href="#profileModal" class="btn-large btn-large-min waves-effect waves-light teal darken-1 modal-trigger" onclick="sendMessage()"><i class="material-icons left">email</i> Send Message</a>
</div>
<div class="divider"></div>
<div class="section">
<a href="#profileModal" class="btn-large btn-large-min waves-effect waves-light cyan darken-1 modal-trigger" onclick="changeMyAvatar()"><i class="material-icons left">camera</i> Change Profile Avatar</a>
</div>
<div class="divider"></div>
<div class="section">
<a href="#!" class="btn-large btn-large-min waves-effect waves-light red darken-1" onclick="closeMyAccount()"><i class="material-icons left">time_to_leave</i> Close Account</a>
</div>
</div>
</div>
</div>
</div>

</section>

<div class="modal" id="profileModal">
<div class="modal-content" id="profileRes">

</div>
<div class="modal-footer">
<a href="#!" class="modal-close waves-effect waves-green btn-flat">Cancel</a>
</div>
</div>



<div class="section">
<div class="row">
<div class="col s12 m4">
	<div class="card-panel beige">
	<h4 class="card-title">Site Identity</h4>
	<div class="row">
		<div class="input-field col s12">
		<input type="text" name="site_name" id="site_name" value="<?php echo $g['site_name'] ?>" onChange="updateValue(this.id, this.value)" />
		<label for="site_name">Site/Company Name</label>
		</div>
		<div class="input-field col s12">
		<span class="helper-text red-text">Changing this incorrectly could break your site!</span>			
		<input type="url" name="site_url" id="site_url" value="<?php echo $g['site_url'] ?>" onChange="updateValue(this.id, this.value)" />
		<label for="site_url">Site URL</label>
		</div>
		<div class="input-field col s12">
		<select name="homepage" id="homepage" onChange="updateValue(this.id, this.value)">
		<option value="<?php echo $g['homepage'] ?>"	selected>Select to Change</option>
		<?php echo $a->selectHomepage($g['homepage']) ?>
			
		</select>
		<label>Change the Homepage</label>
		</div>
		<div class="input-field col s12">
		<input type="text" name="address_1" id="address_1" value="<?php echo $g['address_1'] ?>" onChange="updateValue(this.id, this.value)" />
		<label for="address_1">Address</label>
		</div>
		<div class="input-field col s6">
		<input type="text" name="address_2" id="address_2" value="<?php echo $g['address_2'] ?>" onChange="updateValue(this.id, this.value)" />
		<label for="address_2">Address (line 2)</label>
		</div>
		<div class="input-field col s6">
		<input type="text" name="city" id="city" value="<?php echo $g['city'] ?>" onChange="updateValue(this.id, this.value)" />
		<label for="city">City</label>
		</div>
		<div class="input-field col s6">
		<select name="state" id="state" onChange="updateValue(this.id, this.value)">
		<?php echo $a->getStates($g['state']) ?>
			
		</select>
		</div>
		<div class="input-field col s6">
		<input type="text" name="zip_code" id="zip_code" value="<?php echo $g['zip_code'] ?>" onChange="updateValue(this.id, this.value)" />
		<label for="zip_code">ZipCode</label>
		</div>
		<div class="input-field col s6">
		<input type="text" name="phone_1" id="phone_1" class="phone_number" value="<?php echo $g['phone_1'] ?>" onChange="updateValue(this.id, this.value)" />
		<label for="phone_1">Primary Phone</label>
		</div>
		<div class="input-field col s6">
		<input type="text" name="phone_2" id="phone_2" class="phone_number" value="<?php echo $g['phone_2'] ?>" onChange="updateValue(this.id, this.value)" />
		<label for="phone_2">Secondary Phone</label>
		</div>
		<div class="input-field col s12">
		<input type="email" name="email_address" id="email_address" value="<?php echo $g['email_address'] ?>" onChange="updateValue(this.id, this.value)" />
		<label for="email_address">Email Address</label>
		</div>
		<div class="input-field col s6">
		<input type="text" name="fax_1" id="fax_1" class="phone_number" value="<?php echo $g['fax_1'] ?>" onChange="updateValue(this.id, this.value)" />
		<label for="fax_1">Fax Number</label>
		</div>		
		</div>
	</div>
</div>
<div class="col s12 m4">
	<div class="card-panel blue lighten-4">
	<h4 class="card-title">Security & Data Settings</h4>
	<span class="helper-text white-text">Site Maintenance Mode</span>
	<p>
     <label>
	<input class="with-gap green-text" name="maintenance" id="m_0" type="radio" onClick="updateValue('maintenance', 0)" <?php if($g['maintenance'] == 0) { echo 'checked="checked"';} ?> />
	<span class="green-text">Site is ON <i class="far fa-smile green-text"></i></span>
     </label>
	</p>
	<p>
     <label>
	<input class="with-gap" name="maintenance" id="m_1" type="radio" onClick="updateValue('maintenance', 1)" <?php if($g['maintenance'] == 1) { echo 'checked="checked"';} ?> />
	<span><span class="red-text">Site is OFF <i class="far fa-frown red-text"></i></span>
     </label>	
	</p>
	<div class="divider"></div>
	<span class="helper-text white-text">User Registrations</span>
	<p>
     <label>
	<input class="with-gap green" name="allow_reg" id="r_0" type="radio" onClick="updateValue('allow_reg', 1)" <?php if($g['allow_reg'] == 1) { echo 'checked="checked"';} ?> />
	<span class="green-text">Registrations Allowed</span>
     </label>
	</p>
	<p>
     <label>
	<input class="with-gap red" name="allow_reg" id="r_1" type="radio" onClick="updateValue('allow_reg', 0)" <?php if($g['allow_reg'] == 0) { echo 'checked="checked"';} ?> />
	<span class="red-text">Registrations Restricted</span>
     </label>	
	</p>
	<div class="divider"></div>
	<span class="helper-text white-text">Password Reset</span>
	<p>
     <label>
	<input class="with-gap green" name="allow_reset_pass" id="p_0" type="radio" onClick="updateValue('allow_reset_pass', 1)" <?php if($g['allow_reset_pass'] == 1) { echo 'checked="checked"';} ?> />
	<span class="green-text">Reset Password Allowed</span>
     </label>
	</p>
	<p>
     <label>
	<input class="with-gap red" name="allow_reset_pass" id="r_1" type="radio" onClick="updateValue('allow_reset_pass', 0)" <?php if($g['allow_reset_pass'] == 0) { echo 'checked="checked"';} ?> />
	<span class="red-text">Reset Password Restricted</span>
     </label>	
	</p>
	<div class="divider"></div>
	<span class="helper-text white-text">Remember Login Cookie</span>
	<p>
     <label>
	<input class="with-gap green" name="allow_remember_me" id="c_0" type="radio" onClick="updateValue('allow_remember_me', 1)" <?php if($g['allow_remember_me'] == 1) { echo 'checked="checked"';} ?> />
	<span class="green-text">Remember Login Allowed</span>
     </label>
	</p>
	<p>
     <label>
	<input class="with-gap red" name="allow_remember_me" id="c_1" type="radio" onClick="updateValue('allow_remember_me', 0)" <?php if($g['allow_remember_me'] == 0) { echo 'checked="checked"';} ?> />
	<span class="red-text">Remember Login Restricted</span>
     </label>	
	</p>
	<div class="row">
	<div class="input-field col s12">
	<input type="email" name="no_reply_email" id="no_reply_email" value="<?php echo $g['no_reply_email'] ?>" onChange="updateValue(this.id, this.value)" />
	<label for="no_reply_email" class="white-text">No Reply Email Address</label>
	</div>
	<div class="input-field col s12">
	<span class="helper-text">(Acquire from Luthersites; allows visitor tracking and Dashboard analytics data.)</span>
	<input type="email" name="mamoto_id" id="mamoto_id" value="<?php echo $g['mamoto_id'] ?>" onChange="updateValue(this.id, this.value)" />
	<label for="mamoto_id" class="white-text">Mamoto Analytics ID</label>
	</div>
	<div class="input-field col s12">
	<input type="email" name="copyright" id="copyright" value="<?php echo $g['copyright'] ?>" onChange="updateValue(this.id, this.value)" />
	<label for="copyright" class="white-text">Copyright Label</label>
	</div>
	<div class="input-field col s12">
	<input type="email" name="uploacare_api" id="uploadcare_api" value="<?php echo $g['uploadcare_api'] ?>" onChange="updateValue(this.id, this.value)" />
	<label for="copyright" class="white-text">Uploadcare API Key</label>
	<span class="helper-text">The Uploadcare API Key is necessary for adding files/images to your pages.  You can acquire a key by going to <a href="https://uploadcare.com" target="_blank">Uploadcare</a> and registering for an account.</span>		
	</div>		
	</div>
	</div>
</div>
<div class="col s12 m4">
	<div class="card-panel red lighten-4">
	<h4 class="card-title">Server Information</h4>
	<span class="helper-text white-text">This information is read-only.</span>
	<div class="row">
	<div class="input-field col s12">
	<input type="text" id="site_ver" readonly="true" value="<?php echo SITE_VERSION ?>" />
	<label for="site_ver">CMS Version</label>
	</div>
	<div class="input-field col s12">
	<input type="text" id="doc_root" readonly="true" value="<?php echo $g['doc_root'] ?>" />
	<label for="doc_root">Site Root Folder</label>
	</div>
	<div class="input-field col s12">
	<input type="text" id="server_type" readonly="true" value="<?php echo $_SERVER['SERVER_SOFTWARE'] ?>" />
	<label for="server_type">Server Software</label>
	</div>
	<div class="input-field col s12">
	<input type="text" id="php_type" readonly="true" value="<?php echo phpversion() ?>" />
	<label for="php_type">Php Revision</label>
	</div>
	<div class="input-field col s12">
	<input type="text" id="mysql_type" readonly="true" value="<?php echo $db->getAttribute(constant("PDO::ATTR_SERVER_VERSION")) ?>" />
	<label for="mysql_type">Database Engine</label>
	</div>
	<div class="input-field col s12">
	<input type="text" id="mysql_db" readonly="true" value="<?php echo DB_NAME ?>" />
	<label for="mysql_db">Database Name</label>
	</div>
	<div class="input-field col s12">
	<input type="text" id="mysql_user" readonly="true" value="<?php echo DB_USER ?>" />
	<label for="mysql_user">Database User</label>
	</div>		
	<div class="input-field col s12">
	<input type="text" id="mysql_pass" disabled="true" readonly="true" value="<?php echo DB_PASSWORD ?>" />
	<label for="mysql_pass">Database Password</label>
	</div>
	<div class="input-field col s12">
	<input type="text" id="server_ip" readonly="true" value="<?php echo $_SERVER['SERVER_ADDR'] ?>" />
	<label for="server_ip">Server IP Address</label>
	</div>		
		
	</div>	
	</div>
</div>
</div>
</div>

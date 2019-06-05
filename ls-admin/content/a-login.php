<style>
html,
body {
    height: 100%;
}
html {
    display: table;
    margin: auto;
}
body {
    display: table-cell;
    vertical-align: middle;
}
#login-page {
     width: 400px;
}
.login-form {
     width: 100%;
}
.login-form-text {
     text-transform: uppercase;
     letter-spacing: 2px;
     font-size: 0.8rem;
}
</style>

<div id="login-page" class="row">
<div class="col s12 z-depth-4 card-panel">
<form class="login-form" autocomplete="false">
<div class="row">
<div class="input-field col s12 center">
<i class="blue-text medium material-icons">vpn_key</i>
<p class="center login-form-text">Login</p>
</div>
</div>
<div class="row margin">
<div class="input-field col s12">
<i class="material-icons prefix">person</i>
<input id="username" type="email" name="username" class="validate" onblur="checkUsername()" value="" autocomplete="off" />
<label for="username" class="center-align active">Username</label>
<span class="helper-text helper-text-custom" id="user_val" data-error="Username not found or incorrect format" data-success=""></span>
</div>
</div>
<div class="row margin">
<div class="input-field col s12">
<i class="material-icons prefix">lock</i>
<input id="password" type="password" name="password" value="" autocomplete="false" />
<label for="password" class="active">Password</label>
</div>
</div>

<?php
if($sec->allowRememberme() !== false) {
     echo '<div class="row"><input type="checkbox" id="remember_me" name="remember_me" /><label for="remember_me">Remember Me for 30 days?</label></div>'."\n";
}
?>

<div class="row">
<div class="input-field col s12" id="loginbutton">
<a href="!#" class="btn waves-effect waves-light col s12" onclick="userLogin(); return false;">Login</a>
</div>
<div id="loginres" class="col s12" style="display: none"></div>
</div>
<div class="row">

<?php
if($sec->allowRegistration() !== false) {
     echo '<div class="input-field col s6 m6 l6"><p class="margin medium-small"><a href="">Register Now!</a></p></div>'."\r";
}
if($sec->allowResetPass() !== false) {
     echo '<div class="input-field col s6 m6 l6"><p class="margin right-align medium-small"><a href="">Forgot Password?</a></p>'."\r";
}
?>

</div>          
</div>

</form>
</div>

<script src="<?php echo $g['site_url'] ?>/ls-admin/js/materialize.js"></script>
<script src="<?php echo $g['site_url'] ?>/ls-admin/js/perfect-scrollbar.min.js"></script>
<script src="<?php echo $g['site_url'] ?>/ls-admin/js/a-app.js"></script>
</body>
</html>



<section class="section">
<div class="row">
<div class="col s12 m10 l10">
<div class="card">
<div class="card-content">
<span class="card-title">Current Users</span>
<table class="responsive-table highlight">
<thead>
<tr>
<th></th>
<th>Username</th>
<th>Name</th>
<th>Security Level</th>
<th>Account Status</th>
<th>Last Login</th>
<th>Options</th>
</tr>
</thead>

<tfoot>
<tr>
<th></th>
<th>Username</th>
<th>Name</th>
<th>Security Level</th>
<th>Account Status</th>
<th>Last Login</th>
<th>Options</th>
</tr>
</tfoot>

<tbody>
<?php
$x = $sec->userList();
while($y = $x->fetch(PDO::FETCH_ASSOC)) {
     ?>
     
     <tr>
     <td><img class="circle responsive-img valign profile-image cyan" src="<?php echo $g['site_url'] ?>/content/assets/img/avatar/<?php echo $y['user_avatar'] ?>" width="45" /></td>
     <td><?php echo $y['user_id'] ?></td>
     <td><?php echo $y['first_name'] .' '. $y['last_name'] ?></td>
     <td><?php echo $sec->getSecLevel($y['security_level']) ?></td>
     <td><?php echo $sec->getAcctStatus($y['account_status']) ?></td>
     <td><?php echo date('M j Y, h:i a', strtotime($y['last_login'])) ?></td>
     <td>
     <?php
     if($y['user_id'] != $_SESSION['user']['user_id']) {
          ?>
          
          <a class="waves-effect waves-light teal darken-3 btn modal-trigger" href="#usermodal" onclick="editUser('<?php echo $y['user_id'] ?>')"><i class="material-icons left">create</i> Edit</a>
          <a class="waves-effect waves-light yellow darken-1 btn modal-trigger" href="#usermodal" onclick="resetPass('<?php echo $y['user_id'] ?>')"><i class="material-icons left">vpn_key</i> Reset Pass</a>
          <a class="waves-effect waves-light red darken-1 btn modal-trigger" href="#!" onclick="banUser('<?php echo $y['user_id'] ?>')"><i class="material-icons left">block</i> Ban</a>
     
          <?php
     } else {
          ?>
          <a class="waves-effect waves-light blue darken-1 btn" href="<?php echo $g['site_url'] ?>/admin/profile/"><i class="material-icons left">account_circle</i> View/Edit Your Account</a>          
          
          <?php
     }
     ?>
     
     </td>
     </tr>
     
     <?php
}
?>

</tbody>
</table>
</div>
</div>
</div>

<div class="col s12 m2 l2">
<div class="card">
<div class="card-content">
<span class="card-title">Add User</span>
<a class="waves-effect waves-light green darken-1 btn modal-trigger" href="#usermodal" onclick="addUser()"><i class="material-icons left">add</i> Add User</a>
</div>
</div>
</div>
</div>
</section>

<div id="usermodal" class="modal">
<div class="modal-content">
<div id="usermodalc">

</div>
</div>
<div class="modal-footer">
<a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancel</a>
</div>
</div>

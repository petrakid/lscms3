  <footer class="page-footer <?php echo $b->getBlockValue('navc') ?> <?php echo $b->getBlockValue('navcc') ?>" <?php if($fullwidth == 1) { echo 'style="position: relative; top: 100vh;"';} ?>>
    <div class="container">
      <div class="row">
      <div class="col s12 m4 l4">
	<?php
	$footl = $b->getBlockContent('fl');
	$fl = $footl->fetch(PDO::FETCH_ASSOC);
	if($fl['block_content'] == 'company') {
		?>
		<h5><?php echo $g['site_name'] ?></h5>
		<?php echo ($g['address_1'] > '' ? $g['address_1'] .'<br />' : ''); ?>
		<?php echo ($g['address_2'] > '' ? $g['address_2'] .'<br />' : ''); ?>
		<?php echo ($g['city'] > '' ? $g['city'] .', ' : ''); ?>
		<?php echo ($g['state'] > '' ? $g['state'] .'  ' : ''); ?>
		<?php echo ($g['zip_code'] > '' ? $g['zip_code'] .'<br />' : ''); ?>
		<?php echo ($g['phone_1'] > '' ? 'Ph: <a class="white-text" href="tel:'. $g['phone_1'] .'">'. $b->formatPhone($g['phone_1']) .'</a> |' : ''); ?>
		<?php echo ($g['fax_1'] > '' ? 'Fx: '. $b->formatPhone($g['fax_1']) .'<br />' : '<br />'); ?>
		<?php echo ($g['email_address'] > '' ? 'Em: '. $g['email_address'] : '');
	} else {
		echo $fl['block_content'];
	}
	?>
	</div>
	<div class="col s12 m4 l4" style="text-align: center">
	<?php 	
	$footm = $b->getBlockContent('fm');
	$fm = $footm->fetch(PDO::FETCH_ASSOC);
	if($fm['block_content'] == 'company') {
		?>
		<h5><?php echo $g['site_name'] ?></h5>
		<?php echo ($g['address_1'] > '' ? $g['address_1'] .'<br />' : ''); ?>
		<?php echo ($g['address_2'] > '' ? $g['address_2'] .'<br />' : ''); ?>
		<?php echo ($g['city'] > '' ? $g['city'] .', ' : ''); ?>
		<?php echo ($g['state'] > '' ? $g['state'] .'  ' : ''); ?>
		<?php echo ($g['zip_code'] > '' ? $g['zip_code'] .'<br />' : ''); ?>
		<?php echo ($g['phone_1'] > '' ? 'Ph: <a class="white-text" href="tel:'. $g['phone_1'] .'">'. $b->formatPhone($g['phone_1']) .'</a> |' : ''); ?>
		<?php echo ($g['fax_1'] > '' ? 'Fx: '. $b->formatPhone($g['fax_1']) .'<br />' : '<br />'); ?>
		<?php echo ($g['email_address'] > '' ? 'Em: '. $g['email_address'] : '');
	} else {
		echo $fm['block_content'];
	}
	?>		 
	</div>
	<div class="col s12 m4 l4" style="text-align: right">
	<?php 
	$footr = $b->getBlockContent('fr');
	$fr = $footr->fetch(PDO::FETCH_ASSOC);
	if($fr['block_content'] == 'company') {
		?>
		<h5><?php echo $g['site_name'] ?></h5>
		<?php echo ($g['address_1'] > '' ? $g['address_1'] .'<br />' : ''); ?>
		<?php echo ($g['address_2'] > '' ? $g['address_2'] .'<br />' : ''); ?>
		<?php echo ($g['city'] > '' ? $g['city'] .', ' : ''); ?>
		<?php echo ($g['state'] > '' ? $g['state'] .'  ' : ''); ?>
		<?php echo ($g['zip_code'] > '' ? $g['zip_code'] .'<br />' : ''); ?>
		<?php echo ($g['phone_1'] > '' ? 'Ph: <a class="white-text" href="tel:'. $g['phone_1'] .'">'. $b->formatPhone($g['phone_1']) .'</a> |' : ''); ?>
		<?php echo ($g['fax_1'] > '' ? 'Fx: '. $b->formatPhone($g['fax_1']) .'<br />' : '<br />'); ?>
		<?php echo ($g['email_address'] > '' ? 'Em: '. $g['email_address'] : '');
	} else {
		echo $fr['block_content'];
	}	
	?>		 
	</div>
      </div>
    </div>
    <div class="footer-copyright">
      <div class="container">
      <?php echo $g['copyright'] ?> | 
      Made by <a class="brown-text text-lighten-3" href="https://www.luthersites.net">Luthersites</a>
      </div>
    </div>
  </footer>
  
<script src="//cdn.lutherhost.net/js/materialize/materialize.min.js"></script>
<script src="<?php echo $g['site_url'] ?>/js/app.js"></script>

<?php
if(isset($_SESSION['isLoggedIn'])) {
     ?>
     <script src="<?php echo $g['site_url'] ?>/ls-admin/js/a-app.js"></script>
          
     <?php
}
if($_GET['p'] != 'admin') {
     $soc = new Social($db);
     if($soc->isEnabled() === true) {
          ?>
          <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $soc->getID() ?>"></script> 

          <?php
     }
}
?>
</body>
</html>

<?php $ec = new Calendar($db) ?>

<?php
if(isset($_SESSION['isLoggedIn'])) {
     echo '<section>';
     echo $ec->showAdminRow($pg['p_id']);
     echo '</section>';
}
?>

<section>
<?php $cals = $ec->getCalendar($pg['p_id']) ?>

</section>

<div id="eventDetails" class="modal">
<div class="modal-content" id="eventResModal">

</div>
<div class="modal-footer">
<a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
</div>
</div>


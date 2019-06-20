<?php
$z = new MailingLists($db);
?>

<section class="section">
<div class="row">
<div class="col s4">
<h4 class="header">Mailing Lists</h4>
<ul class="collection">
<?php
$list = $z->getMailingLists();
while($t = $list->fetch(PDO::FETCH_ASSOC)) {
     ?>
     <li class="collection-item"><div><?php echo $t['list_name'] ?><?php if($t['list_visible'] == 0) { echo '<i class="material-icons grey-text lighten-2 tooltipped" data-position="top" data-tooltip="Not visible to the public">visibility_off</i>'; } ?>
     <a href="#listModal" class="secondary-content modal-trigger tooltipped" data-postion="top" data-tooltip="Edit List Setting" onclick="editList(<?php echo $t['l_id'] ?>)"><i class="material-icons blue-text">edit</i></a>
     <a href="#listModal" class="secondary-content modal-trigger tooltipped" data-postion="top" data-tooltip="Send a Mailing" onclick="sendMailing(<?php echo $t['l_id'] ?>)"><i class="material-icons yellow-text">mail</i></a>
     <a href="#listModal" class="secondary-content modal-trigger tooltipped" data-postion="top" data-tooltip="Edit Subscribers" onclick="editSubscribers(<?php echo $t['l_id'] ?>)"><i class="material-icons red-text">subscriptions</i></a>
     </div>
     </li>
       
     <?php   
}
?>

<li class="collection-item teal lighten-2"><a href="#listModal" onclick="newList()" class="collection-item active modal-trigger tooltipped teal lighten-2" data-position="top" data-tooltip="New List">New List</a></li>
</ul>
</div>
<div class="col s4">
<h4 class="header">Subscribers</h4>
<ul class="collapsible">
<?php
$lists = $z->getMailingLists();
while($v = $lists->fetch(PDO::FETCH_ASSOC)) {
     ?>
     <li>
     <div class="collapsible-header">
     <h5 class="header"><?php echo $v['list_name'] ?></h5>
     </div>
     <div class="collapsible-body">
     <ul class="collection">
     <?php
     $subs = $z->getSubscribers(0);
     while($u = $subs->fetch(PDO::FETCH_ASSOC)) {
          ?>
          <li class="collection-item"><div><span class="tooltipped" data-position="top" data-tooltip="<?php echo $u['email_address'] ?>"><?php echo $u['first_name'] .' '. $u['last_name'] ?></span><a href="#listModal" onclick="editSubscriber(<?php echo $u['s_id'] ?>)" class="secondary-content modal-trigger tooltipped" data-position="top" data-tooltip="Edit Subscriber"><i class="material-icons blue-text">edit</i></a></div></li>
          
          <?php
     }
     ?>
     <li class="collection-item red lighten-2"><a href="#listModal" onclick="addSubscriber(<?php echo $v['l_id'] ?>)" class="collection-item active modal-trigger tooltipped red lighten-2 white-text" data-postion="top" data-tooltip="Add Subscriber">Add Subscriber</a></li>
     </ul>
     </div>
     </li>
     <?php
}
?>
</ul>
</div>
<div class="col s4">
<h4 class="header">Mailings</h4>
<h6>Scheduled</h6>
<ul class="collection">
<?php
$sched = $z->getScheduledMailings();
while($w = $sched->fetch(PDO::FETCH_ASSOC)) {
     ?>
     <li class="collection-item"><div><span class="tooltipped" data-position="top" data-tooltip="<?php echo $w['mailing_subject'] ?>"> <?php echo mb_strimwidth($w['mailing_subject'], 0, 20, '...') ?></span> (<?php echo date('M j Y h:i a', strtotime($w['mailing_date'])) ?>)<a href="#listModal" onclick="editMailing(<?php echo $w['m_id'] ?>)" class="secondary-content modal-trigger tooltipped" data-position="top" data-tooltip="Edit Mailing"><i class="material-icons blue-text">edit</i></a>
     </div>
     </li>
     
     <?php
}
?>
<li class="collection-item blue lighten-2"><a href="#listModal" onclick="sendMailing(0)" class="collection-item active modal-trigger tooltipped blue lighten-2 white-text" data-postion="top" data-tooltip="New Mailing">Create New Mailing</a></li>

</ul>
<div class="divider"></div>
<h6 class="">Archive</h6>
<ul class="collection">
<?php
$arch = $z->getArchivedMailings();
while($y = $arch->fetch(PDO::FETCH_ASSOC)) {
     ?>
     <li class="collection-item"><div><span class="tooltipped" data-position="top" data-tooltip="<?php echo $y['mailing_subject'] ?>"> <?php echo mb_strimwidth($y['mailing_subject'], 0, 20, '...') ?></span> (Sent: <?php echo date('M j Y h:i a', strtotime($y['mailing_date'])) ?><a href="#listModal" onclick="editMailing(<?php echo $y['m_id'] ?>)" class="secondary-content modal-trigger tooltipped" data-position="top" data-tooltip="Edit Mailing"><i class="material-icons blue-text">edit</i></a>
     </div>
     </li>
     
     <?php     
}
?>
</ul>
</div>
</div>
</section>

<div class="modal" id="listModal">
<div class="modal-content" id="listRes">

</div>
<div class="modal-footer">
<a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
</div>
</div>

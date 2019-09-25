<div class="row mt-1">
<div class="col s12">
<a class="waves-effect waves-light btn btn-large modal-trigger" href="#newpagemodal" onclick="newMenuForm()"><i class="material-icons left">add</i> New Page</a>
</div>
</div>

<div class="row mt-1">
<div class="col s12">
<table class="striped" id="pages_table">
<thead>
<tr>
<th>Page Name</th>
<th>Parent</th>
<th>Friendly Link</th>
<th>Options</th>
</tr>
</thead>
<tbody>
<?php
$list = $a->getPageListP();
while($ls = $list->fetch(PDO::FETCH_ASSOC)) {
     ?>
     
     <tr>
     <td><?php echo stripslashes($ls['menu_name']) ?></td>
     <td><?php echo $a->getParent($ls['menu_parent_id']) ?></td>
     <td><?php echo $ls['menu_link'] ?></td>
     <td><?php echo $a->getMenuOptions($ls['m_id']) ?></td>
     </tr>
     
     <?php
     $clist = $a->getPageListC($ls['m_id']);
     if($clist->rowCount() > 0) {
          while($cs = $clist->fetch(PDO::FETCH_ASSOC)) {
               ?>
               <tr>
               <td>-- <?php echo stripslashes($cs['menu_name']) ?></td>
               <td><?php echo $a->getParent($cs['menu_parent_id']) ?></td>
               <td><?php echo $cs['menu_link'] ?></td>
               <td><?php echo $a->getMenuOptions($cs['m_id']) ?></td>
               </tr>               
               <?php
          }
     }
}
?>
</tbody>
<tfoot>
<th>Page Name</th>
<th>Parent</th>
<th>Last Update</th>
<th>Options</th>
</tfoot>
</table>
</div>
</div>

<div id="newpagemodal" class="modal">
<div class="modal-content" id="newmenuform">

</div>
<div class="modal-footer">
<a href="#!" class="modal-close waves-effect waves-green btn-flat" onclick="saveNewPage()">Save</a>
<a href="#!" class="modal-close waves-effect waves-red btn-flat">Cancel</a>
</div>
</div>

<div id="deletepagemodal" class="modal">
<div class="modal-content">
<h4>Delete this page?</h4>
<div id="page-delete">

</div>
</div>

</div>

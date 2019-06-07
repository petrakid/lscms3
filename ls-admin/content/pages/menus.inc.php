<style>
.menu_ul { list-style-type: none; margin: 0; padding: 0; }
.menu_li { text-align: left; min-height: 70px; width: 90%; z-index: 1000; border: 1px solid black; margin-bottom: 5px; padding: 8px; border-radius: 8px; }
.cmenu_ul { list-style-type: none; margin-bottom: 8px; padding: 0; }
.cmenu_li { text-align: left; height: 70px; width: 80%; z-index: 1000; border: 1px solid black; margin-bottom: 5px; padding: 8px; border-radius: 8px; }
.new_menu_li { text-align: center; height: 70px; width: 100%; z-index: 1000; }
</style>
<div class="row">
<div class="col s12 m4 l4">
<div class="card">
<div class="card-content">
<span class="card-title">Add Menu</span>
<ul class="menu_ul">
<li id="menu_drag" class="new_menu_li ui-state-highlight"><i class="material-icons" style="font-size: 50px;">add</i></li>
</ul>
</div>
<div class="card-action">
Drag the bar into the Site Menu Structure and drop it where you'd like it
</div>
</div>

<div class="card">
<div class="card-content">
<span class="card-title">Available Content</span>
<ul class="menu_ul">
<li id="menu_drag2" class="new_menu_li ui-state-highlight"><i class="material-icons" style="font-size: 50px;">link</i> External Link</li>
</ul>
</div>
<div class="card-action">

</div>
</div>
</div>
<div class="col s12 m8 l8">
<div class="card">
<div class="card-content">
<span class="card-title">Site Menu Structure</span>
<ul class="menu_ul menu_sortable" id="pmenu_sort">
<?php
$parent = $a->getParentMenu();
while($par = $parent->fetch(PDO::FETCH_ASSOC)) {
     echo '<li class="menu_li ui-state-default" id="list-p'. $par['m_id'] .'">'. $par['menu_name'];
     $child = $a->getChildMenu($par['m_id']);
     if($child->rowCount() > 0) {
          echo '<ul class="cmenu_ul menu_sortable" id="cmenu_sort">';
          while($chi = $child->fetch(PDO::FETCH_ASSOC)) {
               echo '<li class="cmenu_li ui-state-default" id="list-c'. $chi['m_id'] .'">'. $chi['menu_name'] .'</li>';
          }
          echo '</ul>';
     }
     echo '</li>';
}
?>
</ul>
</div>
<div class="card-action"></div>
</div>
</div>
</div>

<script>
$(function() {
     $("#pmenu_sort").sortable({
          revert: true
     });
     $('#cmenu_sort').sortable({
          revert: true
     });
     $("#menu_drag").draggable({
          connectToSortable: ".menu_sortable",
          helper: "clone",
          revert: "invalid"
    });
     $("#menu_drag2").draggable({
          connectToSortable: ".menu_sortable",
          helper: "clone",
          revert: "invalid"
    });    
    $("#menu_drag").disableSelection();
});
</script>
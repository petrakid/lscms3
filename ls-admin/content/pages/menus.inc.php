<style>
.menu_ul { list-style-type: none; margin: 0; padding: 0; }
.menu_li { text-align: left; min-height: 70px; width: 100%; z-index: 1000; border: 1px solid black; margin-bottom: 5px; padding: 8px; border-radius: 8px; }
.cmenu_ul { list-style-type: none; margin-bottom: 8px; padding: 0; min-height: 70px; }
.cmenu_li { text-align: left; min-height: 70px; width: 90%; z-index: 1000; border: 1px solid black; margin-bottom: 5px; margin-left: 18px; padding: 8px; border-radius: 8px; }
.new_menu_li { text-align: center; height: 70px; width: 100%; z-index: 1000; }
</style>
<div class="row">
<div class="col s12 m4 l4">
<div class="card">
<div class="card-content">
<span class="card-title">Add Menu</span>
<div class="row">
<div class="input-field col s12">
<input type="text" name="n_menu_name" id="n_menu_name" onkeyup="makeFriendly(this.value)" />
<label for="n_menu_name">New Menu Name</label>
</div>
</div>
<div class="row">
<div class="input-field col s12">
<input type="text" name="n_menu_link" id="nmenu_link" class="validate" />
<label class="active" for="nmenu_link">Menu Link</label>
</div>
</div>
<div class="row">
<div class="input-field col s12">
<select id="n_menu_parent_id" name="n_menu_parent_id">
<option value="0" disabled selected>None</option>
<?php echo $a->getMenuForAdd() ?>

</select>
<label>Parent</label>
</div>
</div>

<div class="row">
<div class="col s3">
<span class="helper-text">Menu Status</span>
<p>
<label>
<input type="radio" id="n_menu_status1" name="n_menu_status" value="1" class="with-gap" />
<span>Published</span>
</label>
</p>
<p>
<label>
<input type="radio" id="n_menu_status2" name="n_menu_status" value="2" class="with-gap" />
<span>Hidden</span>
</label>
</p>
<p>
<label>
<input type="radio" id="n_menu_status0" name="n_menu_status" checked="checked" value="0" class="with-gap" />
<span>Draft</span>
</label>
</p>
</div>
</div>
<div class="row">
<div class="col s6 offset-s6" style="text-align: right;">
<a class="waves-effect waves-light btn" onclick="addnMenu()">Add</a>
</div>
</div>
</div>
<div class="card-action">
Enter, minimally, the menu name and select its parent and click "Add".
</div>
</div>
</div>

<div class="col s12 m8 l8">
<div class="card">
<div class="card-content">
<span class="card-title">Site Menu Structure</span>
<p>The Home menu is excluded since you cannot add child menus to Home.</p>
<ul class="menu_ul menu_sortable sort-main" id="pmenu_sort">
<?php
$parent = $a->getParentMenu(1);
while($par = $parent->fetch(PDO::FETCH_ASSOC)) {
     echo '<li class="menu_li ui-state-default" id="mlist-'. $par['m_id'] .'">'. $par['menu_name'];
     $child = $a->getChildMenu($par['m_id']);
     if($child->rowCount() > 0) {
          echo '<ul class="cmenu_ul menu_sortablec sort-child" id="cmenu_sort-'. $par['m_id'] .'">';
          while($chi = $child->fetch(PDO::FETCH_ASSOC)) {
               echo '<li class="cmenu_li ui-state-default" id="clist-'. $chi['m_id'] .'">'. $chi['menu_name'] .'</li>';
          }
          echo '</ul>';
     } else {
         echo '<ul class="cmenu_ul menu_sortablec sort-child" id="cmenu_sort-'. $par['m_id'] .'"></ul>'; 
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


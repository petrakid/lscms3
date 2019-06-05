
<div class="row">
<div class="col s12 m4 l4">
<div class="card">
<div class="card-content">
<span class="card-title">Add Menu</span>
<div class="new_menu_dragger z-depth-3" style="cursor: move;" draggable="true" id="new_drag"><i class="medium material-icons">add</i></div>
</div>
<div class="card-action">
Drag the bar into the Site Menu Structure and drop it where you'd like it
</div>
</div>

<div class="card">
<div class="card-content">
<span class="card-title">Available Content</span>

</div>
<div class="card-action">

</div>
</div>
</div>
<div class="col s12 m8 l8">
<div class="card">
<div class="card-content">
<span class="card-title">Site Menu Structure</span>

</div>
<div class="card-action"></div>
</div>
</div>
</div>

<script>
function drag_start(event) {
    var style = window.getComputedStyle(event.target, null);
    event.dataTransfer.setData("text/plain",
    (parseInt(style.getPropertyValue("left"),10) - event.clientX) + ',' + (parseInt(style.getPropertyValue("top"),10) - event.clientY));
} 
function drag_over(event) { 
    event.preventDefault(); 
    return false; 
} 
function drop(event) { 
    var offset = event.dataTransfer.getData("text/plain").split(',');
    var dm = document.getElementById('new_drag');
    dm.style.left = (event.clientX + parseInt(offset[0],10)) + 'px';
    dm.style.top = (event.clientY + parseInt(offset[1],10)) + 'px';
    event.preventDefault();
    return false;
} 
var dm = document.getElementById('new_drag'); 
dm.addEventListener('dragstart',drag_start,false); 
document.body.addEventListener('dragover',drag_over,false); 
document.body.addEventListener('drop',drop,false); 
</script>
<?php
$dl = new Downloads($db);
?>

<section class="section">
<h3 class="header">Downloads Manager</h3>
<table class="highlight responsive striped mtable" id="datatable">
<thead>
<tr>
<th colspan="4" class="teal lighten-4 center-align" style="padding: 3px 3px !important; border-radius: 0">
<div class="input-field inline" style="margin-top: 0 !important; margin-bottom: 0 !important; display: block"><i class="material-icons white-text prefix">search</i><input title="Search" style="margin-left: 3rem; margin-bottom: 0;" type="text" name="search_downloads" id="search_downloads" onchange="searchDownloads(this.value)" /></div>
</th>
<th colspan="2" class="teal lighten-4 right-align" style="padding: 3px 3px !important; border-radius: 0"> 
<a class="btn-small teal lighten-4 modal-trigger" href="#newdownloadmodal" onclick="addResource()"><i class="material-icons left">add</i> Add</button>
</th>
</tr>
</thead>
<tbody>
<tr>
<th></th>
<th>Title</th>
<th>File Type</th>
<th>Date</th>
<th>Downloaded</th>
<th>Page</th>
</tr>
<?php echo $dl->getDownloads(); ?>

</tbody>
<tfoot>
<tr>
<th colspan="4" class="teal lighten-4 center-align" style="padding: 3px 3px !important; border-radius: 0">
</th>
<th colspan="2" class="teal lighten-4 right-align" style="padding: 3px 3px !important; border-radius: 0">
<a class="btn-small teal lighten-4 modal-trigger" href="#newdownloadmodal" onclick="addResource()"><i class="material-icons left">add</i> Add</button>
</th>
</tr>
</tfoot>
</table>
</section>

<div id="newdownloadmodal" class="modal">
<div class="modal-content">
<h4>New Download Resource</h4>
<div id="addRes"></div>
</div>
<div class="modal-footer">
<a href="#!" class="waves-effect waves-red btn-flat teal lighten-1" id="saveResBtn" onclick="saveResource()">Save Resource</a>
<a href="#!" class="modal-close waves-effect waves-blue btn-flat grey lighten-2" onclick="window.location.reload()">Close</a>
</div>
</div>
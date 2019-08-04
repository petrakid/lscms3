

<section>
<div class="row">
<div class="col s12">
<table class="responsive highlight">
<thead>
<tr>
<th></th>
<th>Title</th>
<th>File Type</th>
<th>Downloads</th>
</tr>
</thead>
<tbody>
<?php echo $rs->getResources($pg['p_id']) ?>

</tbody>
<tfoot>
<th></th>
<th>Title</th>
<th>File Type</th>
<th>Downloads</th>
</tfoot>
</table>
</div>
</div>

</section>
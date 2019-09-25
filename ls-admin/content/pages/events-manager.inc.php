<?php $evt = new Events($db); ?>

<section class="section">
<h3 class="header">Events Manager</h3>
<div class="row">
<div class="col s12 m6">
<div class="card">
<div class="card-content">
<div class="row">
<div class="col m6">
<span class="card-title">General Settings</span>
</div>
<div class="col m6 right-align">
<a href="#newCalModal" class="waves-effect waves-light modal-trigger btn teal" onclick="addCalendar()"><i class="material-icons left">add</i>Add Calendar</a>
</div>
</div>
<select name="calendars" id="cal_id" class="" onchange="calSettings(this.value)">
<option value="" disabled selected>Select a Calendar</option>
<?php echo $evt->getCalendarList(); ?>

</select>

<div id="calSettingsRes">

</div>
</div>
</div>
</div>
<div class="col s12 m6">
<div class="card">
<div class="card-content">
<span class="card-title">Categories &amp; Locations</span>

<?php echo $evt->getCategories(); ?>

<hr />
<?php echo $evt->getLocations(); ?>

</div>
</div>
</div>
</div>
<div class="row">
<div class="col s12">
<div class="card">
<div class="card-content">
<div class="row">
<div class="col s4"><span class="card-title">Current Events</span></div>
<div class="col s8 right-align"><a class="btn waves-effect waves-light modal-trigger" href="#newEventModal" onclick="newEvent()"><i class="material-icons left">add</i>New Event</a></div>
</div>
<div class="row">
<table class="highlight responsive centered">
<thead>
<tr>
<th>Options</th>
<th>Image</th>
<th>Title</th>
<th>Schedule</th>
<th>Calendar</th>
<th>Bookable</th>
<th>Payments</th>
</tr>
</thead>
<tbody>
<?php echo $evt->getEventsList() ?>

</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</section>

<div id="newCalModal" class="modal modal-fixed-footer">
<div class="modal-content">
<h4>New Calendar</h4>
<div id="newCalRes">

</div>
</div>
<div class="modal-footer">
<a href="#!" class="waves-effect waves-green btn" onclick="saveNewCalendar()">Save</a>
<a href="#!" class="modal-close waves-effect waves-grey btn-flat">Cancel</a>
</div>
</div>

<div id="newEventModal" class="modal modal-fixed-footer">
<div class="modal-content">
<h4>New Event</h4>
<div id="newEventRes">

</div>
</div>
<div class="modal-footer">
<a href="#!" class="waves-effect waves-green btn" id="newEventSave" onclick="saveNewEvent()">Save</a>
<a href="#!" class="modal-close waves-effect waves-grey btn-flat">Cancel</a>
</div>
</div>

<div id="editEventModal" class="modal modal-fixed-footer">
<div class="modal-content">
<h4>Edit Event</h4>
<div id="editEventRes">

</div>
</div>
<div class="modal-footer">
<a href="#!" class="waves-effect waves-red btn red" id="eventDel" onclick="deleteEvent()">Delete</a>
<a href="#!" class="waves-effect waves-green btn" id="eventSave" onclick="saveEvent()">Save</a>
<a href="#!" class="modal-close waves-effect waves-grey btn-flat">Cancel</a>
</div>
</div>

<div id="editLocation" class="modal modal-fixed-footer">
<div class="modal-content">
<h4>Edit Location</h4>
<div id="editLocRes">

</div>
</div>
<div class="modal-footer">
<a href="#!" class="waves-effect waves-green btn" id="saveLocButton" onclick="saveLocation()">Save</a>
<a href="#!" class="modal-close waves-effect waves-grey btn-flat">Cancel</a>
</div>
</div>

<div id="editCategory" class="modal modal-fixed-footer">
<div class="modal-content">
<h4>Edit Category</h4>
<div id="editCatRes">

</div>
</div>
<div class="modal-footer">
<a href="#!" class="waves-effect waves-green btn" onclick="saveCategory()">Save</a>
<a href="#!" class="modal-close waves-effect waves-grey btn-flat">Cancel</a>
</div>
</div>

<div id="newLocation" class="modal modal-fixed-footer">
<div class="modal-content">
<h4>New Location</h4>
<div id="newLocRes">

</div>
</div>
<div class="modal-footer">
<a href="#!" class="waves-effect waves-green btn" id="savenLocBtn" onclick="saveNewLocation()">Save</a>
<a href="#!" class="modal-close waves-effect waves-grey btn-flat">Cancel</a>
</div>
</div>

<div id="newCategory" class="modal modal-fixed-footer">
<div class="modal-content">
<h4>New Category</h4>
<div id="newCatRes">

</div>
</div>
<div class="modal-footer">
<a href="#!" class="waves-effect waves-green btn" onclick="saveNewCategory()">Save</a>
<a href="#!" class="modal-close waves-effect waves-grey btn-flat">Cancel</a>
</div>
</div>
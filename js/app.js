var url = new URL(window.location.href);
var href = url.protocol +'//'+ url.hostname;

(function($){
     $(function(){
          $(".dropdown-trigger").dropdown({
               hover: false,
               inDuration: 300,
               outDuration: 225,
               belowOrigin: true,
               coverTrigger: false
          });
          $('.parallax').parallax({
               
          });
          $('.sidenav').sidenav({
               
          });
    
     }); // end of document ready
})(jQuery); // end of jQuery name space

function showToast(data)
{
     M.toast({html: data, displayLength: 1800, classes: 'teal rounded'});
}

function addResourceCount(did)
{
     $.ajax({
          url: href + '/includes/ls-includes.php',
          type: 'POST',
          data: {
               'add_resource_count': 1,
               'd_id': did
          },
          success: function(data) {
               
          }
     })
}

function selectCalendar(es_id, page)
{
     $.ajax({
          url: href + '/includes/ls-includes.php',
          type: 'POST',
          data: {
               'set_calendar': 1,
               'es_id': es_id,
               'page': page
          },
          success: function(data) {
               showToast(data);
               setTimeout(function(){
                    window.location.reload()
               }, 1000)
          }
     })
}

document.addEventListener('DOMContentLoaded', function() {
     var calendarEl = document.getElementById('calendar');

     var calendar = new FullCalendar.Calendar(calendarEl, {
          header: { center: 'dayGridMonth,timeGridWeek' },
          views: {
               dayGridMonth: {
                    titleFormat: { year: 'numeric', month: '2-digit', day: '2-digit' }
               }
          },
          plugins: ['dayGrid']
     });
     calendar.render();
});

function readMoreEvent(evid)
{
     $.ajax({
          url: href + '/includes/ls-includes.php',
          type: 'POST',
          data: {
               'view_event_details': 1,
               'ev_id': evid
          },
          success: function(data) {
               $('#eventResModal').html(data);
          }
     })     
}

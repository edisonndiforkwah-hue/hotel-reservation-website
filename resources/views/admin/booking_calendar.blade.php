<!DOCTYPE html>
<html>
  <head> 
    @include('admin.css')
    <!-- FullCalendar CSS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <style>
      #calendar {
        max-width: 1000px;
        margin: 40px auto;
        background: #fff;
        padding: 20px;
        color: #000;
      }
      .fc-event {
        cursor: pointer;
      }
    </style>
  </head>
  <body>
    @include('admin.header')
    @include('admin.sidebar')

     <div class="page-content"> 
        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">Booking Calendar</h2>
          </div>
        </div>
        <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <div class="title"><strong>Reservations Overview</strong></div>
                  <div class="block-body">
                    <div id='calendar'></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

    @include('admin.footer')

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var events = @json($events);

        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
          },
          events: events,
          eventClick: function(info) {
            if (info.event.url) {
              window.location.href = info.event.url;
              info.jsEvent.preventDefault(); 
            }
          }
        });
        calendar.render();
      });
    </script>
  </body>
</html>

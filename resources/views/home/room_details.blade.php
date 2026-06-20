<!DOCTYPE html>
<html lang="en">
   <head>

   <base href="/public">

     @include('home.css')
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
     <style>
        .room-detail-row {
           margin-bottom: 2rem;
        }

        .room-detail-main {
           padding-right: clamp(12px, 2vw, 24px);
        }

        @media (max-width: 991px) {
           .room-detail-main {
              padding-right: 12px;
              margin-bottom: 1.5rem;
           }
        }

        .room-detail-gallery {
           overflow: hidden;
           border-radius: 14px;
           border: 1px solid #e6ebf1;
           background: #f4f7fb;
           margin-bottom: 1rem;
        }

        .room-detail-gallery figure {
           margin: 0;
        }

        .room-detail-gallery img {
           display: block;
           width: 100%;
           max-width: 100%;
           height: auto;
           min-height: 280px;
           object-fit: cover;
           padding: 0 !important;
        }

        .room-detail-info {
           padding: 0.75rem 0 0;
        }

        .room-detail-info h3 {
           color: #102542;
           font-size: 1.75rem;
           font-weight: 700;
           margin-bottom: 0.5rem;
        }

        .room-detail-meta {
           display: flex;
           flex-wrap: wrap;
           gap: 0.75rem 1.5rem;
           margin-bottom: 1rem;
        }

        .room-detail-meta span {
           font-size: 1rem;
           color: #2d3e50;
        }

        .room-detail-meta .label {
           font-weight: 600;
           color: #102542;
        }

        .room-detail-desc {
           color: #4a5568;
           font-size: 0.975rem;
           line-height: 1.6;
           margin: 0;
        }

        .booking-sidebar {
           position: sticky;
           top: 1rem;
           padding-left: clamp(12px, 2vw, 8px);
        }

        @media (max-width: 991px) {
           .booking-sidebar {
              position: static;
              padding-left: 12px;
           }
        }

        .booking-card {
           background: #ffffff;
           border: 1px solid #e6ebf1;
           border-radius: 14px;
           padding: 1.75rem 1.5rem;
           box-shadow: 0 12px 36px rgba(12, 35, 64, 0.08);
        }

        .booking-card-header {
           margin-bottom: 1.25rem;
           padding-bottom: 1rem;
           border-bottom: 1px solid #eef2f6;
        }

        .booking-card h4 {
           margin: 0 0 0.35rem;
           color: #102542;
           font-size: 1.35rem;
           font-weight: 700;
        }

        .booking-card-lead {
           margin: 0;
           font-size: 0.875rem;
           color: #64748b;
           line-height: 1.45;
        }

        .booking-dates-row {
           display: grid;
           grid-template-columns: 1fr 1fr;
           gap: 0.75rem 1rem;
        }

        @media (max-width: 479px) {
           .booking-dates-row {
              grid-template-columns: 1fr;
           }
        }

        .booking-group {
           margin-bottom: 0.95rem;
        }

        .booking-group label {
           display: block;
           margin-bottom: 6px;
           color: #2d3e50;
           font-size: 0.8125rem;
           font-weight: 600;
           letter-spacing: 0.01em;
        }

        .booking-group input {
           width: 100%;
           border: 1px solid #ccd6e0;
           border-radius: 8px;
           padding: 10px 12px;
           font-size: 14px;
           color: #1f2d3d;
           background-color: #fdfefe;
           transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .booking-group input:focus {
           border-color: #0d6efd;
           outline: none;
           box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .booking-btn {
           width: 100%;
           border-radius: 10px;
           padding: 12px 16px;
           font-weight: 600;
           margin-top: 0.5rem;
        }
     </style>
   </head>
   <!-- body -->
   <body class="main-layout">
      <!-- loader  -->
      <div class="loader_bg">
         <div class="loader"><img src="images/loading.gif" alt="#"/></div>
      </div>
      <!-- end loader -->
      <!-- header -->
      <header>
         @include('home.header')
      </header>
      <!-- end header inner -->
      <!-- end header -->
      <!-- banner -->
  



      <div  class="our_room">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <h2>Our Room</h2>
                     <p>Lorem Ipsum available, but the majority have suffered </p>
                  </div>
               </div>
            </div>
            <div class="row room-detail-row align-items-start">
               <div class="col-lg-8 col-md-12 room-detail-main">
                  <div id="serv_hover" class="room">
                     <div class="room-detail-gallery">
                        <div class="room_img">
                           <figure><img src="/room_img/{{ $room->image }}" alt="Room {{ $room->room_number }}"></figure>
                        </div>
                     </div>
                     <div class="bed_room room-detail-info">
                        <h3>Room {{ $room->room_number }}</h3>
                        <div class="room-detail-meta">
                           <span><span class="label">Price:</span> {{ $room->price }}</span>
                           <span><span class="label">Status:</span> {{ $room->status }}</span>
                        </div>
                        <p class="room-detail-desc">{{ $room->description }}</p>
                     </div>
                  </div>
               </div>

               <div class="col-lg-4 col-md-12 booking-sidebar">
                  <form action="{{ route('book_room', ['id' => $room->id]) }}" method="POST">
                     @csrf
                     <div class="booking-card">
                        <div class="booking-card-header">
                           <h4>Book this room</h4>
                           <p class="booking-card-lead">Fill in your details and stay dates to request a reservation.</p>
                        </div>

                        @if (session('success'))
                           <div class="alert alert-success" role="alert" style="font-size: 0.875rem;">{{ session('success') }}</div>
                        @endif
                        @if (session('message'))
                           <div class="alert alert-success" role="alert" style="font-size: 0.875rem;">{{ session('message') }}</div>
                        @endif

                        @if ($errors->any())
                           <div class="alert alert-danger" role="alert" style="font-size: 0.875rem;">
                              <ul class="mb-0 ps-3">
                                 @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                 @endforeach
                              </ul>
                           </div>
                        @endif

                        <div class="booking-group">
                           <label for="booking-name">Full name</label>
                           <input id="booking-name" type="text" name="name" value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}" placeholder="Jane Doe" required>
                        </div>

                        <div class="booking-group">
                           <label for="booking-email">Email</label>
                           <input id="booking-email" type="email" name="email" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}" placeholder="you@example.com" required>
                        </div>

                        <div class="booking-group">
                           <label for="booking-phone">Phone</label>
                           <input id="booking-phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="+1 555 0123" required>
                        </div>

                        <div class="booking-dates-row">
                           <div class="booking-group">
                              <label for="start_date">Check-in</label>
                              <input type="date" name="start_date" id="start_date" value="{{ old('start_date', request('start_date', date('Y-m-d'))) }}" min="{{ date('Y-m-d') }}">
                           </div>
                           <div class="booking-group">
                              <label for="end_date">Check-out</label>
                              <input type="date" name="end_date" id="end_date" value="{{ old('end_date', request('end_date')) }}" min="{{ date('Y-m-d') }}">
                           </div>
                        </div>

                        <div class="booking-group">
                           <label for="guests">Guests</label>
                           <select name="guests" id="guests" required style="width: 100%; border: 1px solid #ccd6e0; border-radius: 8px; padding: 10px 12px; font-size: 14px;">
                              <option value="1" {{ request('guests') == 1 ? 'selected' : '' }}>1 Person</option>
                              <option value="2" {{ request('guests') == 2 ? 'selected' : '' }}>2 Persons</option>
                              <option value="3" {{ request('guests') == 3 ? 'selected' : '' }}>3 Persons</option>
                              <option value="4" {{ request('guests') == 4 ? 'selected' : '' }}>4+ Persons</option>
                           </select>
                        </div>

                        <div>
                           <input type="submit" class="btn btn-primary booking-btn" value="Book room">
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>





      <!-- end contact -->
      <!--  footer -->
     @include('home.footer')

     <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
     <script>
        (function () {
           var start = document.getElementById('start_date');
           var end = document.getElementById('end_date');
           if (!start || !end) return;

           var bookedDates = @json($bookings);
           var disabledDateRanges = bookedDates.map(function(booking) {
               return {
                   from: booking.start_date,
                   to: booking.end_date
               };
           });

           var startPicker = flatpickr(start, {
               minDate: "today",
               disable: disabledDateRanges,
               onChange: function(selectedDates, dateStr, instance) {
                   if (dateStr) {
                       endPicker.set('minDate', dateStr);
                   }
               }
           });

           var endPicker = flatpickr(end, {
               minDate: "today",
               disable: disabledDateRanges
           });

           var form = start.closest('form');
           if (form) {
               form.addEventListener('submit', function(e) {
                   var startVal = start.value;
                   var endVal = end.value;
                   if (startVal && endVal) {
                       var hasOverlap = bookedDates.some(function(booking) {
                           return startVal <= booking.end_date && endVal >= booking.start_date;
                       });
                       if (hasOverlap) {
                           e.preventDefault();
                           alert('The selected date range overlaps with an existing booking. Please choose different dates.');
                       }
                   }
               });
           }
        })();
     </script>
     
   </body>
</html>
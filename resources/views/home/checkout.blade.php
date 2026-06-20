<!DOCTYPE html>
<html lang="en">
   <head>
   <base href="/public">
     @include('home.css')
     <style>
        .checkout-row {
           margin-bottom: 2rem;
        }

        .checkout-main {
           padding-right: clamp(12px, 2vw, 24px);
        }

        @media (max-width: 991px) {
           .checkout-main {
              padding-right: 12px;
              margin-bottom: 1.5rem;
           }
        }

        .checkout-card {
           background: #ffffff;
           border: 1px solid #e6ebf1;
           border-radius: 14px;
           padding: 1.75rem 1.5rem;
           box-shadow: 0 12px 36px rgba(12, 35, 64, 0.08);
           margin-bottom: 1.5rem;
        }

        .checkout-card:last-child {
           margin-bottom: 0;
        }

        .checkout-card-header {
           margin-bottom: 1.25rem;
           padding-bottom: 1rem;
           border-bottom: 1px solid #eef2f6;
        }

        .checkout-card h3,
        .checkout-card h4 {
           margin: 0 0 0.35rem;
           color: #102542;
           font-size: 1.35rem;
           font-weight: 700;
        }

        .checkout-card-lead {
           margin: 0;
           font-size: 0.875rem;
           color: #64748b;
           line-height: 1.45;
        }

        .checkout-room-preview {
           display: flex;
           align-items: center;
           gap: 1rem;
           padding: 1rem;
           margin-bottom: 1.25rem;
           background: #f4f7fb;
           border-radius: 10px;
           border: 1px solid #e6ebf1;
        }

        .checkout-room-preview img {
           width: 96px;
           height: 72px;
           object-fit: cover;
           border-radius: 8px;
           flex-shrink: 0;
        }

        .checkout-room-preview h4 {
           font-size: 1.1rem;
           margin-bottom: 0.25rem;
        }

        .checkout-room-preview p {
           margin: 0;
           font-size: 0.875rem;
           color: #64748b;
        }

        .checkout-room-preview .room-price {
           color: #fe0000;
           font-weight: 700;
           font-size: 1rem;
        }

        .checkout-detail-list {
           margin: 0;
           padding: 0;
           list-style: none;
        }

        .checkout-detail-list li {
           display: flex;
           justify-content: space-between;
           align-items: flex-start;
           gap: 1rem;
           padding: 0.65rem 0;
           border-bottom: 1px solid #eef2f6;
           font-size: 0.9375rem;
        }

        .checkout-detail-list li:last-child {
           border-bottom: none;
        }

        .checkout-detail-list .label {
           color: #64748b;
           font-weight: 600;
           flex-shrink: 0;
        }

        .checkout-detail-list .value {
           color: #102542;
           text-align: right;
           font-weight: 500;
        }

        .checkout-sidebar {
           position: sticky;
           top: 1rem;
           padding-left: clamp(12px, 2vw, 8px);
        }

        @media (max-width: 991px) {
           .checkout-sidebar {
              position: static;
              padding-left: 12px;
           }
        }

        .price-summary-table {
           width: 100%;
           margin-bottom: 1rem;
        }

        .price-summary-table td {
           padding: 0.5rem 0;
           font-size: 0.9375rem;
           color: #2d3e50;
           border: none;
        }

        .price-summary-table td:last-child {
           text-align: right;
           font-weight: 500;
           color: #102542;
        }

        .price-summary-table tr.total-row td {
           padding-top: 1rem;
           font-size: 1.15rem;
           font-weight: 700;
           color: #102542;
           border-top: 2px solid #eef2f6;
        }

        .price-summary-table tr.total-row td:last-child {
           color: #fe0000;
        }

        .checkout-deposit-note {
           font-size: 0.875rem;
           color: #64748b;
           text-align: center;
           margin: 0 0 1.25rem;
           line-height: 1.5;
        }

        .checkout-deposit-note strong {
           color: #102542;
        }

        .checkout-group {
           margin-bottom: 0.95rem;
        }

        .checkout-group label {
           display: block;
           margin-bottom: 6px;
           color: #2d3e50;
           font-size: 0.8125rem;
           font-weight: 600;
           letter-spacing: 0.01em;
        }

        .checkout-group select {
           width: 100%;
           border: 1px solid #ccd6e0;
           border-radius: 8px;
           padding: 10px 12px;
           font-size: 14px;
           color: #1f2d3d;
           background-color: #fdfefe;
           transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .checkout-group select:focus {
           border-color: #0d6efd;
           outline: none;
           box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .checkout-btn {
           width: 100%;
           border-radius: 10px;
           padding: 14px 16px;
           font-weight: 600;
           font-size: 1rem;
           margin-top: 0.5rem;
           background-color: #121212;
           border-color: #121212;
           transition: background-color 0.3s ease;
        }

        .checkout-btn:hover {
           background-color: #fe0000;
           border-color: #fe0000;
        }

        .checkout-steps {
           display: flex;
           justify-content: center;
           gap: 0.5rem;
           margin-bottom: 2rem;
           flex-wrap: wrap;
        }

        .checkout-step {
           display: flex;
           align-items: center;
           gap: 0.5rem;
           font-size: 0.8125rem;
           color: #94a3b8;
           font-weight: 600;
        }

        .checkout-step.active {
           color: #102542;
        }

        .checkout-step-num {
           width: 28px;
           height: 28px;
           border-radius: 50%;
           display: flex;
           align-items: center;
           justify-content: center;
           background: #e6ebf1;
           font-size: 0.75rem;
        }

        .checkout-step.active .checkout-step-num {
           background: #fe0000;
           color: #fff;
        }

        .checkout-step.done .checkout-step-num {
           background: #102542;
           color: #fff;
        }
     </style>
   </head>
   <body class="main-layout">
      <div class="loader_bg">
         <div class="loader"><img src="images/loading.gif" alt="#"/></div>
      </div>

      <header>
         @include('home.header')
      </header>

      <div class="our_room">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <h2>Checkout</h2>
                     <p>Review your booking details and confirm your reservation</p>
                  </div>
               </div>
            </div>

            <div class="checkout-steps">
               <div class="checkout-step done">
                  <span class="checkout-step-num">1</span>
                  <span>Room &amp; dates</span>
               </div>
               <div class="checkout-step active">
                  <span class="checkout-step-num">2</span>
                  <span>Review &amp; pay</span>
               </div>
               <div class="checkout-step">
                  <span class="checkout-step-num">3</span>
                  <span>Confirmation</span>
               </div>
            </div>

            <div class="row checkout-row align-items-start">
               <div class="col-lg-8 col-md-12 checkout-main">
                  <div class="checkout-card">
                     <div class="checkout-room-preview">
                        @if($room->image)
                        <img src="/room_img/{{ $room->image }}" alt="Room {{ $room->room_number }}">
                        @endif
                        <div>
                           <h4>Room {{ $room->room_number }}</h4>
                           <p>{{ $room->room_type ?? 'Standard' }} &middot; {{ $bookingData['guests'] }} guest(s)</p>
                           <p class="room-price">${{ number_format($room->price, 2) }} / night</p>
                        </div>
                     </div>

                     <div class="checkout-card-header">
                        <h3>Your details</h3>
                        <p class="checkout-card-lead">Guest information for this reservation</p>
                     </div>
                     <ul class="checkout-detail-list">
                        <li>
                           <span class="label">Name</span>
                           <span class="value">{{ $bookingData['name'] }}</span>
                        </li>
                        <li>
                           <span class="label">Email</span>
                           <span class="value">{{ $bookingData['email'] }}</span>
                        </li>
                        <li>
                           <span class="label">Phone</span>
                           <span class="value">{{ $bookingData['phone'] }}</span>
                        </li>
                     </ul>
                  </div>

                  <div class="checkout-card">
                     <div class="checkout-card-header">
                        <h3>Stay information</h3>
                        <p class="checkout-card-lead">Dates and duration of your stay</p>
                     </div>
                     <ul class="checkout-detail-list">
                        <li>
                           <span class="label">Check-in</span>
                           <span class="value">{{ $bookingData['start_date'] }}</span>
                        </li>
                        <li>
                           <span class="label">Check-out</span>
                           <span class="value">{{ $bookingData['end_date'] }}</span>
                        </li>
                        <li>
                           <span class="label">Guests</span>
                           <span class="value">{{ $bookingData['guests'] }} person(s)</span>
                        </li>
                        <li>
                           <span class="label">Duration</span>
                           <span class="value">{{ $days }} night(s)</span>
                        </li>
                     </ul>
                  </div>
               </div>

               <div class="col-lg-4 col-md-12 checkout-sidebar">
                  <div class="checkout-card">
                     <div class="checkout-card-header">
                        <h4>Price summary</h4>
                        <p class="checkout-card-lead">Review charges before confirming</p>
                     </div>

                     <table class="price-summary-table">
                        <tr>
                           <td>Rate per night</td>
                           <td>${{ number_format($room->price, 2) }}</td>
                        </tr>
                        <tr>
                           <td>Subtotal ({{ $days }} night(s))</td>
                           <td>${{ number_format($subtotal, 2) }}</td>
                        </tr>
                        <tr>
                           <td>Taxes ({{ $room->tax_rate }}%)</td>
                           <td>${{ number_format($taxes, 2) }}</td>
                        </tr>
                        <tr class="total-row">
                           <td>Total</td>
                           <td>${{ number_format($totalPrice, 2) }}</td>
                        </tr>
                     </table>

                     <p class="checkout-deposit-note">A 30% deposit of <strong>${{ number_format($deposit, 2) }}</strong> may be required at check-in.</p>

                     <form action="{{ route('process_checkout', $room->id) }}" method="POST">
                        @csrf
                        <div class="checkout-group">
                           <label for="payment_method">Payment method</label>
                           <select name="payment_method" id="payment_method" required>
                              <option value="on_site">Pay at hotel</option>
                              <option value="card">Credit / debit card (simulated)</option>
                              <option value="momo">Mobile money (simulated)</option>
                           </select>
                        </div>
                        <button type="submit" class="btn btn-primary checkout-btn">Confirm &amp; book</button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>

      @include('home.footer')
   </body>
</html>

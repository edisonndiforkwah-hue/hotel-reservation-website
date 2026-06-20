<!DOCTYPE html>
<html>
  <head> 
    @include('admin.css')

    <style>
        .deg {
            width: 80%;
            margin: auto;
            border-collapse: collapse;
            text-align: center;
            margin-top: 20px; 
        }
        .deg th {
            background-color: #000;
            color: #fff;
            padding: 10px;
        }
        .deg td {
            background-color: #fff;
            color: #000;
            padding: 10px;
        }
        .room-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
      
    </style>
  </head>
  <body>

    @include('admin.header')

    @include('admin.sidebar')
    <!-- Sidebar Navigation end-->


    
    <div class="page-content"> 
        <div class="page-header">
          <div class="container-fluid">

          <div class="mb-3">
            <a href="{{ url('create_booking') }}" class="btn btn-primary">Create Booking</a>
            <a href="{{ url('booking_calendar') }}" class="btn btn-info">View Calendar</a>
          </div>
          <table class="table deg"> 
            <tr>
              <th>Booking No.</th>
              <th>Customer Name</th>
              <th>Room</th>
              <th>Arrival Date</th>
              <th>Leaving Date</th>
              <th>Status</th>
              <th>Total Price</th>
              <th>Payment</th>
              <th>Edit</th>
              <th>Delete</th>
            </tr>
            @foreach($data as $booking)
            <tr>
              <td>{{ $booking->booking_number ?? 'N/A' }}</td>
              <td>{{ $booking->name }}<br><small>{{ $booking->email }}</small></td>
              <td>Room {{ $booking->room?->room_number ?? 'N/A' }}</td>
              <td>{{ $booking->start_date }}</td>
              <td>{{ $booking->end_date }}</td>
              <td>
                @php
                  $statusColors = [
                    'Pending' => 'warning',
                    'Confirmed' => 'success',
                    'Checked-in' => 'info',
                    'Checked-out' => 'secondary',
                    'Cancelled' => 'danger'
                  ];
                  $color = $statusColors[$booking->status] ?? 'dark';
                @endphp
                <span class="badge badge-{{ $color }}">{{ $booking->status }}</span>
              </td>
              <td>${{ number_format($booking->total_price, 2) }}</td>
              <td>
                <span class="badge badge-{{ $booking->payment_status == 'paid' ? 'success' : ($booking->payment_status == 'pending' ? 'warning' : 'danger') }}">
                  {{ ucfirst($booking->payment_status) }}
                </span>
              </td>
              <td>
                <a href="{{ url('edit_booking', $booking->id) }}" class="btn btn-sm btn-secondary">Edit</a>
              </td>
              <td>
                <form action="{{ route('delete_booking', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
              </td>
            </tr>
            @endforeach
          </table>
        
            </div>
            </div>
            </div>
 

    @include('admin.footer')

  </body>
</html>
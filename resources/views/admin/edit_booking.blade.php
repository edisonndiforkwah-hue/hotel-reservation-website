<!DOCTYPE html>
<html>
  <head> 
    <base href="/public">
    @include('admin.css')
  </head>
  <body>
    @include('admin.header')
    @include('admin.sidebar')

     <div class="page-content"> 
        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">Edit Booking: {{ $booking->booking_number }}</h2>
          </div>
        </div>
        <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-8">
                <div class="block">
                  <div class="title"><strong>Booking Details</strong></div>
                  <div class="block-body">
                    @if(session('success'))
                      <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                      <div class="alert alert-danger">
                        <ul class="mb-0">
                          @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        </ul>
                      </div>
                    @endif

                    <form action="{{url('update_booking', $booking->id)}}" method="post">
                      @csrf
                      
                      <div class="form-group">       
                        <label class="form-control-label">Room</label>
                        <select name="room_id" class="form-control mb-3">
                          @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}>
                              Room {{ $room->room_number }} ({{ $room->room_type }} - ${{ $room->price }}/night)
                            </option>
                          @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Guest Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $booking->name) }}" required>
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $booking->email) }}" required>
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $booking->phone) }}" required>
                      </div>
                      
                      <div class="form-group">
                        <label class="form-control-label">Guests Count</label>
                        <input type="number" name="guests" class="form-control" value="{{ old('guests', $booking->guests) }}" min="1" required>
                      </div>

                      <div class="row">
                        <div class="col-md-6 form-group">
                          <label class="form-control-label">Start Date</label>
                          <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $booking->start_date) }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                          <label class="form-control-label">End Date</label>
                          <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $booking->end_date) }}" required>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6 form-group">       
                          <label class="form-control-label">Booking Status</label>
                          <select name="status" class="form-control mb-3">
                            <option value="Pending" {{ old('status', $booking->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Confirmed" {{ old('status', $booking->status) == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="Checked-in" {{ old('status', $booking->status) == 'Checked-in' ? 'selected' : '' }}>Checked-in</option>
                            <option value="Checked-out" {{ old('status', $booking->status) == 'Checked-out' ? 'selected' : '' }}>Checked-out</option>
                            <option value="Cancelled" {{ old('status', $booking->status) == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                          </select>
                        </div>
                        <div class="col-md-6 form-group">       
                          <label class="form-control-label">Payment Status</label>
                          <select name="payment_status" class="form-control mb-3">
                            <option value="pending" {{ old('payment_status', $booking->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ old('payment_status', $booking->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ old('payment_status', $booking->payment_status) == 'failed' ? 'selected' : '' }}>Failed</option>
                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Admin Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $booking->notes) }}</textarea>
                      </div>

                      <div class="form-group mt-4">
                        <input type="submit" value="Update Booking" class="btn btn-primary">
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

    @include('admin.footer')
  </body>
</html>

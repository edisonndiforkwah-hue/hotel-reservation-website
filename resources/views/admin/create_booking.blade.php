<!DOCTYPE html>
<html>
  <head> 
    @include('admin.css')
  </head>
  <body>
    @include('admin.header')
    @include('admin.sidebar')

     <div class="page-content"> 
        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">Create Manual Booking</h2>
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

                    <form action="{{url('/store_booking')}}" method="post">
                      @csrf
                      
                      <div class="form-group">       
                        <label class="form-control-label">Room</label>
                        <select name="room_id" class="form-control mb-3">
                          @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                              Room {{ $room->room_number }} ({{ $room->room_type }} - ${{ $room->price }}/night)
                            </option>
                          @endforeach
                        </select>
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Guest Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                      </div>
                      
                      <div class="form-group">
                        <label class="form-control-label">Guests Count</label>
                        <input type="number" name="guests" class="form-control" value="{{ old('guests', 1) }}" min="1" required>
                      </div>

                      <div class="row">
                        <div class="col-md-6 form-group">
                          <label class="form-control-label">Start Date</label>
                          <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                          <label class="form-control-label">End Date</label>
                          <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                        </div>
                      </div>

                      <div class="form-group">       
                        <label class="form-control-label">Status</label>
                        <select name="status" class="form-control mb-3">
                          <option value="Confirmed" {{ old('status') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                          <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Admin Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                      </div>

                      <div class="form-group mt-4">
                        <input type="submit" value="Create Booking" class="btn btn-primary">
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

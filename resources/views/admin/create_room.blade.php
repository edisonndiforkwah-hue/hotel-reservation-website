<!DOCTYPE html>
<html>
  <head> 
    @include('admin.css')
  </head>
  <body>
    @include('admin.header')

    @include('admin.sidebar')
    <!-- Sidebar Navigation end-->


     <div class="page-content"> 
        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">Add New Room</h2>
          </div>
        </div>
        <section class="no-padding-top">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-8">
                <div class="block">
                  <div class="title"><strong>Room Details</strong></div>
                  <div class="block-body">
                    @if(session('success'))
                      <div class="alert alert-success">
                        {{ session('success') }}
                      </div>
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

                    <form action="{{url('/add_room')}}" method="post" enctype="multipart/form-data">
                      @csrf

                      <div class="form-group">
                        <label class="form-control-label">Room Number</label>
                        <input type="number" name="room_number" placeholder="Enter room number" class="form-control" value="{{ old('room_number') }}">
                      </div>
                      
                      <div class="form-group">       
                        <label class="form-control-label">Room Category</label>
                        <select name="room_type" class="form-control mb-3">
                          <option value="Standard" {{ old('room_type') == 'Standard' ? 'selected' : '' }}>Standard</option>
                          <option value="Deluxe" {{ old('room_type') == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                          <option value="Suite" {{ old('room_type') == 'Suite' ? 'selected' : '' }}>Suite</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Price</label>
                        <input type="number" step="0.01" name="price" placeholder="Enter price" class="form-control" value="{{ old('price') }}">
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Capacity (Guests)</label>
                        <input type="number" name="capacity" placeholder="Enter room capacity" class="form-control" value="{{ old('capacity', 2) }}">
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Amenities</label><br>
                        <div class="d-flex flex-wrap">
                          <div class="mr-3"><input type="checkbox" name="amenities[]" value="WiFi"> WiFi</div>
                          <div class="mr-3"><input type="checkbox" name="amenities[]" value="TV"> TV</div>
                          <div class="mr-3"><input type="checkbox" name="amenities[]" value="AC"> AC</div>
                          <div class="mr-3"><input type="checkbox" name="amenities[]" value="Mini Bar"> Mini Bar</div>
                          <div class="mr-3"><input type="checkbox" name="amenities[]" value="Pool View"> Pool View</div>
                          <div class="mr-3"><input type="checkbox" name="amenities[]" value="Balcony"> Balcony</div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Image</label>
                        <br>
                        <input type="file" name="image" class="form-control-file">
                      </div>

                      <div class="form-group">       
                        <label class="form-control-label">Status</label>
                        <select name="status" class="form-control mb-3">
                          <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                          <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                          <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                          <option value="cleaning" {{ old('status') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                          <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Enter room description">{{ old('description') }}</textarea>
                      </div>

                      <div class="form-group mt-4">
                        <input type="submit" value="Add Room" class="btn btn-primary">
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
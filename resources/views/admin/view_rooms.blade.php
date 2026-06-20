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
      
    </style>
  </head>
  <body>
    @include('admin.header')

    @include('admin.sidebar')
    <!-- Sidebar Navigation end-->

    <div class="page-content"> 
        <div class="page-header">
          <div class="container-fluid">

            <table class="table deg"> 
            <tr>
              <th>Room Number</th>
              <th>Room Category</th>
              <th>Capacity</th>
              <th>Price</th>
              <th>Status</th>
              <th>Description</th>
              <th>Image</th>
              <th>Delete</th>
              <th>Update</th>
            </tr>
            @foreach($data as $room)
            <tr>
              <td>{{ $room->room_number }}</td>
              <td>{{ $room->room_type }}</td>
              <td>{{ $room->capacity }}</td>
              <td>${{ $room->price }}</td>
              <td>
                <span class="badge badge-{{ $room->status == 'available' ? 'success' : ($room->status == 'occupied' ? 'primary' : ($room->status == 'reserved' ? 'warning' : 'secondary')) }}">
                  {{ ucfirst($room->status) }}
                </span>
              </td>
              <td>{!! Str::limit($room->description, 50) !!}</td>
              <td><img src="{{ asset('room_img/' . $room->image) }}" alt="Room {{ $room->room_number }}" width="100"></td>
              <td>
                <form action="{{ route('room_delete', $room->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this room?');">
                  @csrf
                  <button type="submit" class="btn btn-danger">Delete</button>
                </form>
              </td>
              <td>
                <a class="btn btn-secondary" href="{{ url('update_room', $room->id) }}">Update</a>
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
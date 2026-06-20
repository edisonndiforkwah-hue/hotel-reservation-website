 <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      <nav id="sidebar">
        <!-- Sidebar Header-->
        <div class="sidebar-header d-flex align-items-center">
          <div class="avatar"><img src="images/logoa.jpg" alt="..." class="img-fluid rounded-circle"></div>
          <div class="title">
            <h1 class="h5">Eddy</h1>
            <p>Supper Admin</p>
          </div>
        </div>
        <!-- Sidebar Navidation Menus--><span class="heading">Main</span>
        <ul class="list-unstyled">
                <li class="active"><a href="{{ url('home')}}"> <i class="icon-home"></i>Hotel Rooms </a></li>
                
                <li><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Options </a>
                  <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                    <li><a href="{{url('create_room')}}">Add Rooms</a></li>
                    <li><a href="{{url('view_rooms')}}">view_Rooms</a></li>
                  </ul>
                </li>
                <li><a href="#bookingsDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-windows"></i>Bookings </a>
                  <ul id="bookingsDropdown" class="collapse list-unstyled ">
                    <li><a href="{{url('bookings')}}">All Bookings</a></li>
                    <li><a href="{{url('create_booking')}}">Add Booking</a></li>
                    <li><a href="{{url('booking_calendar')}}">Calendar</a></li>
                  </ul>
                </li>
                 <li><a href="{{ url('view_gallery') }}"> <i class="icon-home"></i>Gallery </a></li>

                 <li><a href="{{ url('messages') }}"> <i class="icon-home"></i>Messages </a></li>


              
        </ul>
      </nav>
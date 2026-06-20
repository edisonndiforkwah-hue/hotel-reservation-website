 <div class="page-content"> 
        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">Dashboard Overview</h2>
          </div>
        </div>
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <!-- Total Bookings -->
              <div class="col-md-3 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-contract"></i></div><strong>Total Bookings</strong>
                    </div>
                    <div class="number dashtext-1">{{ $total_bookings }}</div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                  </div>
                </div>
              </div>
              
              <!-- Occupancy Rate -->
              <div class="col-md-3 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-user-1"></i></div><strong>Occupancy Rate</strong>
                    </div>
                    <div class="number dashtext-2">{{ $occupancy_rate }}%</div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: {{ $occupancy_rate }}%" aria-valuenow="{{ $occupancy_rate }}" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                  </div>
                </div>
              </div>

              <!-- Available Rooms -->
              <div class="col-md-3 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-home"></i></div><strong>Available Rooms</strong>
                    </div>
                    <div class="number dashtext-3">{{ $available_rooms }}</div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-3"></div>
                  </div>
                </div>
              </div>

              <!-- Maintenance Alerts -->
              <div class="col-md-3 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-info"></i></div><strong>Maintenance</strong>
                    </div>
                    <div class="number dashtext-4">{{ $maintenance_rooms }}</div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-4"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section class="margin-bottom-sm">
          <div class="container-fluid">
            <div class="row d-flex align-items-stretch">
              <!-- Revenue Summary -->
              <div class="col-lg-4">
                <div class="stats-with-chart-1 block">
                  <div class="title"> <strong class="d-block">Revenue Summary</strong><span class="d-block">Confirmed Payments</span></div>
                  <div class="row d-flex align-items-end justify-content-between">
                    <div class="col-12">
                      <div class="text"><strong class="d-block dashtext-3">${{ number_format($revenue, 2) }}</strong><span class="d-block">Total Revenue</span></div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Pending Payments -->
              <div class="col-lg-4">   
                <div class="stats-with-chart-1 block">
                  <div class="title"> <strong class="d-block">Pending Payments</strong><span class="d-block">Unpaid Bookings</span></div>
                  <div class="row d-flex align-items-end justify-content-between">
                    <div class="col-12">
                      <div class="text"><strong class="d-block dashtext-1">${{ number_format($pending_payments, 2) }}</strong><span class="d-block">Total Pending</span></div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Today's Activity -->
              <div class="col-lg-4">
                <div class="stats-with-chart-1 block">
                  <div class="title"> <strong class="d-block">Today's Activity</strong><span class="d-block">Check-ins & Check-outs</span></div>
                  <div class="row d-flex align-items-end justify-content-between">
                    <div class="col-6">
                      <div class="text"><strong class="d-block dashtext-2">{{ $checkins_today }}</strong><span class="d-block">Check-ins</span></div>
                    </div>
                    <div class="col-6 text-right">
                      <div class="text"><strong class="d-block dashtext-4">{{ $checkouts_today }}</strong><span class="d-block">Check-outs</span></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section class="no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-6">
                <div class="messages-block block">
                  <div class="title"><strong>Recent Bookings</strong></div>
                  <div class="messages">
                    @forelse($recent_bookings as $booking)
                    <a href="{{ url('bookings') }}" class="message d-flex align-items-center">
                      <div class="profile"><i class="icon-contract fa-2x"></i></div>
                      <div class="content">   
                        <strong class="d-block">{{ $booking->name }}</strong>
                        <span class="d-block">Room: {{ $booking->room->room_number ?? 'N/A' }} | Status: {{ $booking->status }}</span>
                        <small class="date d-block">{{ $booking->created_at->diffForHumans() }}</small>
                      </div>
                    </a>
                    @empty
                    <p class="text-center text-muted">No recent bookings found.</p>
                    @endforelse
                  </div>
                </div>
              </div>
              
              <div class="col-lg-6">                                           
                <div class="messages-block block">
                  <div class="title"><strong>Recent Messages</strong></div>
                  <div class="messages">
                    @forelse($recent_messages as $msg)
                    <a href="{{ url('messages') }}" class="message d-flex align-items-center">
                      <div class="profile"><i class="icon-mail fa-2x"></i></div>
                      <div class="content">   
                        <strong class="d-block">{{ $msg->name }}</strong>
                        <span class="d-block">{{ Str::limit($msg->message, 50) }}</span>
                        <small class="date d-block">{{ $msg->created_at->diffForHumans() }}</small>
                      </div>
                    </a>
                    @empty
                    <p class="text-center text-muted">No recent messages found.</p>
                    @endforelse
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        
        <section class="margin-bottom-sm">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="stats-with-chart-2 block">
                  <div class="title"><strong class="d-block">Guest Statistics</strong><span class="d-block">Total Registered Users</span></div>
                  <div class="text"><strong class="d-block">{{ $guest_count }}</strong><span class="d-block">Guests</span></div>
                </div>
              </div>
            </div>
          </div>
        </section>
</div>
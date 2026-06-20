               @forelse($room as $rooms)
               <div class="col-md-4 col-sm-6 mb-4" style="margin-bottom: 30px;">
                  <div class="room-card-vertical" style="border: 1px solid #e0e0e0; border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.05); display: flex; flex-direction: column; height: 100%; position: relative;">
                     
                     <!-- Top: Image -->
                     <div style="height: 220px; position: relative;">
                        <img src="{{ asset('room_img/' . $rooms->image) }}" alt="Room {{ $rooms->room_number }}" style="width: 100%; height: 100%; object-fit: cover;">
                        <!-- Bouton Favori -->
                        <div onclick="toggleFavorite({{ $rooms->id }}, this)" class="fav-btn" data-id="{{ $rooms->id }}" style="position: absolute; top: 15px; right: 15px; background: rgba(0,0,0,0.5); border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; color: #ffffff; cursor: pointer; transition: all 0.2s; font-size: 1.2rem; text-shadow: 0 0 5px rgba(0,0,0,0.8);">
                           &#10084;
                        </div>
                     </div>
                     
                     <!-- Middle: Details -->
                     <div style="padding: 20px; flex-grow: 1; display: flex; flex-direction: column;">
                        <h3 style="margin-top:0; font-size: 1.25rem; font-weight: bold; color: #000; margin-bottom: 5px;">Room {{ $rooms->room_number }}</h3>
                        <p style="color: #444; font-size: 0.9rem; margin-bottom: 12px;">{{ $rooms->room_type ?? 'Standard' }} Room</p>
                        
                        <div style="margin-bottom: 15px; display: flex; align-items: center;">
                           <span style="background: #008009; color: white; padding: 3px 8px; border-radius: 6px; font-weight: bold; font-size: 0.9rem; margin-right: 8px;">9.1</span>
                           <span style="font-weight: bold; font-size: 0.9rem; margin-right: 5px; color: #000;">Excellent</span>
                           <span style="color: #666; font-size: 0.9rem;">(124)</span>
                        </div>

                        <div style="background: #c82354; color: white; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; display: inline-block; margin-bottom: 15px; align-self: flex-start;">
                           15% lower than other sites
                        </div>

                        <div style="margin-top: auto;">
                           <div style="color: #666; font-size: 0.85rem; margin-bottom: 2px;">Hotel Site</div>
                           <div style="font-size: 1.6rem; font-weight: bold; color: #000;">${{ $rooms->price }} <span style="font-size: 0.9rem; font-weight: normal; color: #666;">per night</span></div>
                           <div style="font-size: 0.85rem; color: #666; margin-top: 10px;">Pay at the property</div>
                        </div>
                     </div>

                     <!-- Arrow Button -->
                     <a href="{{ url('room_details', $rooms->id) }}{{ request('start_date') ? '?start_date='.request('start_date').'&end_date='.request('end_date') : '' }}{{ request('guests') ? (request('start_date') ? '&' : '?').'guests='.request('guests') : '' }}" style="position: absolute; bottom: 20px; right: 20px; width: 40px; height: 40px; border: 1px solid #ccc; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #000; text-decoration: none; transition: background 0.2s;">
                        <i class="fa fa-chevron-right"></i>
                     </a>
                  </div>
               </div>
               @empty
               <div class="col-md-12">
                  <div class="alert alert-warning">No rooms match your search criteria.</div>
               </div>
               @endforelse

 <div id="gallery" class="gallery">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <h2>gallery</h2>
                  </div>
               </div>
            </div>
            <div class="row">
               @if($gallery->isNotEmpty())
                  @foreach($gallery as $galleryItem)
                  <div class="col-md-3 col-sm-6">
                     <div class="gallery_img">
                        <figure>
                           <img src="{{ asset('gallery/' . $galleryItem->image) }}" alt="Gallery image" />
                        </figure>
                     </div>
                  </div>
                  @endforeach
               @elseif($room->isNotEmpty())
                  @foreach($room as $roomItem)
                  <div class="col-md-3 col-sm-6">
                     <div class="gallery_img">
                        <figure>
                           <img src="{{ asset('room_img/' . $roomItem->image) }}" alt="Room {{ $roomItem->room_number ?? 'image' }}" />
                        </figure>
                     </div>
                  </div>
                  @endforeach
               @else
               <div class="col-md-12">
                  <div class="alert alert-info">No images available yet.</div>
               </div>
               @endif
            </div>
         </div>
      </div>
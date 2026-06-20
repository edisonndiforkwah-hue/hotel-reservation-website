    <div id="our_room" class="our_room" style="padding: 40px 0;">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="titlepage">
                     <h2 style="font-weight: bold; text-align: left; font-size: 2rem; margin-bottom: 30px;">Hot hotel deals right now</h2>
                  </div>
               </div>
            </div>

            <!-- Grille des chambres (Verticales) -->
            <div class="row" id="rooms-grid-container">
               @include('home.ajax_rooms_grid')
            </div>
         </div>
      </div>
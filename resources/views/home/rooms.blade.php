<!DOCTYPE html>
<html lang="en">
   <head>
     @include('home.css')
   </head>
   <!-- body -->
   <body class="main-layout">
      <!-- loader  -->
      <div class="loader_bg">
         <div class="loader"><img src="images/loading.gif" alt="#"/></div>
      </div>
      <!-- end loader -->
      <!-- header -->
      <header>
         @include('home.header')
      </header>

      <!-- our_room -->
      @include('home.our_room')
      <!-- end our_room -->

      <!--  footer -->
      @include('home.footer')
     
   </body>
</html>

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
      <!-- end header inner -->
      <!-- end header -->
      <!-- banner -->
    @include('home.banner')
      <!-- end banner -->
      <!-- ajax search bar -->
      @include('home.ajax_search')
      <!-- end ajax search bar -->
      <!-- about -->
      @include('home.about')
      <!-- end about -->
      <!-- our_room -->
      @include('home.our_room')
      <!-- end our_room -->
      <!-- gallery -->
     @include('home.gallery')
      <!-- end gallery -->
      <!-- blog -->
    @include('home.blog')
      <!-- end blog -->
      <!-- services -->
    @include('home.services')
      <!-- end services -->
      <!-- reviews -->
    @include('home.reviews')
      <!-- end reviews -->
      <!--  contact -->
     @include('home.contact')
      <!-- end contact -->
      <!--  footer -->
     @include('home.footer')
     
   </body>
</html>
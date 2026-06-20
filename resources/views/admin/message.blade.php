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
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Message</th>
              <th>Send Email</th>
            </tr>
              
       
            @foreach($data as $message)
            <tr>
              <td>{{$message->name}}</td>
              <td>{{$message->email}}</td>
              <td>{{$message->phone}}</td>
              <td>{{$message->message}}</td>
              <td><a class="btn btn-success" href="{{url('send_mail', $message->id)}}">Send Email</a></td>
            </tr>
           @endforeach 
          </table>
        



</div>
</div>
</div>

    @include('admin.footer')

  </body>
</html>
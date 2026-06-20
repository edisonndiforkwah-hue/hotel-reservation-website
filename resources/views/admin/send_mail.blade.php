<!DOCTYPE html>
<html>
  <base href="/public">
 
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

          <center>
            <h1 style="font-size: 30px; font-weight: bold;">Send Email To{{$data->name}}</h1>

          <form action="{{url('send_mail/$data->id')}}" method="post" enctype="multipart/form-data">
                      @csrf

                      <div class="form-group">
                        <label class="form-control-label">Greeting</label>
                        <input type="text" name="greeting" placeholder="Enter greeting" class="form-control" value="{{ old('greeting') }}">
                      </div>
                      
                      <div class="form-group">       
                      <div class="form-group">
                        <label class="form-control-label">Mail Body</label>
                        <textarea type="text" name="mailbody" placeholder="Enter mailbody" class="form-control" value="{{ old('mailbody') }}"></textarea>
                      </div>   

                      <div class="form-group">
                        <label class="form-control-label">Action Name</label>
                        <textarea name="action" class="form-control" rows="4" placeholder="Enter action">{{ old('action') }}</textarea>
                      </div>

                       <div class="form-group">
                        <label class="form-control-label">Action url</label>
                        <textarea name="url" class="form-control" rows="4" placeholder="Enter url">{{ old('url') }}</textarea>
                      </div>

                      <div class="form-group">
                        <label class="form-control-label">End line</label>
                        <textarea name="endline" class="form-control" rows="4" placeholder="Enter endline">{{ old('endline') }}</textarea>
                      </div>


                      <div class="form-group mt-4">
                        <input type="submit" value="Send Mail" class="btn btn-primary">
                      </div>
                    </form>
            
          </center>

            

          </div>
        </div>
      </div>

    @include('admin.footer')
    
  </body>
</html>
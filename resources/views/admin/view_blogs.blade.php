<!DOCTYPE html>
<html>
  <head> 
    @include('admin.css')
    <style type="text/css">
        .title_deg
        {
            font-size: 30px;
            font-weight: bold;
            color: white;
            padding-bottom: 40px;
            text-align: center;
        }
        .table_deg
        {
            border: 2px solid white;
            width: 100%;
            text-align: center;
            margin: auto;
        }
        .th_deg
        {
            background-color: skyblue;
            padding: 15px;
        }
        tr
        {
            border: 3px solid white;
        }
        td
        {
            padding: 10px;
        }
    </style>
  </head>
  <body>
    @include('admin.header')
    @include('admin.sidebar')
      
    <div class="page-content">
      <div class="page-header">
        <div class="container-fluid">
            <h1 class="title_deg">All Blogs</h1>

            <table class="table_deg">
                <tr>
                    <th class="th_deg">Title</th>
                    <th class="th_deg">Category</th>
                    <th class="th_deg">Image</th>
                    <th class="th_deg">Action</th>
                </tr>
                @foreach($blogs as $blog)
                <tr>
                    <td>{{ $blog->title }}</td>
                    <td>{{ $blog->category->name }}</td>
                    <td>
                        @if($blog->image)
                        <img src="{{ asset('blog_img/' . $blog->image) }}" width="100">
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('edit_blog', $blog->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('delete_blog', $blog->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
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

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
            width: 80%;
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
            <h1 class="title_deg">Blog Categories</h1>

            <div style="text-align: center; margin-bottom: 20px;">
                <form action="{{ url('add_blog_category') }}" method="POST">
                    @csrf
                    <input type="text" name="name" placeholder="Category Name" required style="padding: 5px;">
                    <input type="submit" class="btn btn-primary" value="Add Category">
                </form>
            </div>

            <table class="table_deg">
                <tr>
                    <th class="th_deg">Category Name</th>
                    <th class="th_deg">Action</th>
                </tr>
                @foreach($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>
                        <form action="{{ route('delete_blog_category', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
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

<!DOCTYPE html>
<html>
  <head> 
    @include('admin.css')
    <style type="text/css">
        label
        {
            display: inline-block;
            width: 200px;
        }
        .div_deg
        {
            padding-top: 30px;
        }
        .div_center
        {
            text-align: center;
            padding-top: 40px;
        }
    </style>
  </head>
  <body>
    @include('admin.header')
    @include('admin.sidebar')
      
    <div class="page-content">
      <div class="page-header">
        <div class="container-fluid">
            <h1 style="font-size: 30px; font-weight: bold;">Create Blog</h1>

            <div class="div_center">
                <form action="{{ route('store_blog') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="div_deg">
                        <label>Blog Title</label>
                        <input type="text" name="title" required>
                    </div>

                    <div class="div_deg">
                        <label>Category</label>
                        <select name="blog_category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="div_deg">
                        <label>Description</label>
                        <textarea name="description" required style="width: 400px; height: 150px;"></textarea>
                    </div>

                    <div class="div_deg">
                        <label>Image</label>
                        <input type="file" name="image">
                    </div>

                    <div class="div_deg">
                        <input type="submit" class="btn btn-primary" value="Create Blog">
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
    
    @include('admin.footer')
  </body>
</html>

<!DOCTYPE html>
<html>
  <head> 
    <base href="/public">
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
            <h1 style="font-size: 30px; font-weight: bold;">Update Blog</h1>

            <div class="div_center">
                <form action="{{ route('update_blog', $blog->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="div_deg">
                        <label>Blog Title</label>
                        <input type="text" name="title" value="{{ $blog->title }}" required>
                    </div>

                    <div class="div_deg">
                        <label>Category</label>
                        <select name="blog_category_id" required>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $blog->blog_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="div_deg">
                        <label>Description</label>
                        <textarea name="description" required style="width: 400px; height: 150px;">{{ $blog->description }}</textarea>
                    </div>

                    <div class="div_deg">
                        <label>Current Image</label>
                        @if($blog->image)
                        <img style="margin:auto;" width="100" src="/blog_img/{{ $blog->image }}">
                        @endif
                    </div>

                    <div class="div_deg">
                        <label>Change Image</label>
                        <input type="file" name="image">
                    </div>

                    <div class="div_deg">
                        <input type="submit" class="btn btn-primary" value="Update Blog">
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
    
    @include('admin.footer')
  </body>
</html>

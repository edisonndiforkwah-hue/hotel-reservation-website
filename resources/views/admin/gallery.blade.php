<!DOCTYPE html>
<html>
  <head>
    @include('admin.css')
    <style>
      .gallery-admin-page .gallery-admin-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
      }
      .gallery-admin-page .gallery-admin-thumb {
        margin: 0;
        border-radius: 10px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
      }
      .gallery-admin-page .gallery-admin-thumb:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 36px rgba(0, 0, 0, 0.35);
        border-color: rgba(121, 106, 238, 0.45);
      }
      .gallery-admin-page .gallery-admin-thumb img {
        display: block;
        width: 100%;
        height: 170px;
        object-fit: cover;
      }
      .gallery-admin-page .gallery-upload-card .gallery-upload-zone {
        position: relative;
        border: 2px dashed rgba(255, 255, 255, 0.22);
        border-radius: 12px;
        padding: 2rem 1.5rem;
        text-align: center;
        background: linear-gradient(
          145deg,
          rgba(255, 255, 255, 0.06) 0%,
          rgba(255, 255, 255, 0.02) 100%
        );
        transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
      }
      .gallery-admin-page .gallery-upload-card .gallery-upload-zone:focus-within {
        border-color: rgba(121, 106, 238, 0.75);
        box-shadow: 0 0 0 3px rgba(121, 106, 238, 0.2);
        background: rgba(121, 106, 238, 0.06);
      }
      .gallery-admin-page .gallery-upload-card .gallery-upload-zone .upload-icon {
        font-size: 2.25rem;
        color: rgba(121, 106, 238, 0.9);
        margin-bottom: 0.75rem;
        display: block;
      }
      .gallery-admin-page .gallery-upload-card .gallery-upload-zone .upload-hint {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.65);
        margin-bottom: 0.35rem;
      }
      .gallery-admin-page .gallery-upload-card .gallery-upload-zone .upload-formats {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.4);
      }
      .gallery-admin-page .gallery-upload-card input[type="file"] {
        width: 100%;
        max-width: 320px;
        margin: 1rem auto 0;
        padding: 0.5rem;
        font-size: 0.875rem;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        background: rgba(0, 0, 0, 0.25);
        color: rgba(255, 255, 255, 0.85);
      }
      .gallery-admin-page .gallery-upload-card input[type="file"]::file-selector-button {
        margin-right: 0.75rem;
        padding: 0.45rem 1rem;
        border-radius: 6px;
        border: none;
        background: rgba(121, 106, 238, 0.85);
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s ease;
      }
      .gallery-admin-page .gallery-upload-card input[type="file"]::file-selector-button:hover {
        background: rgba(121, 106, 238, 1);
      }
      .gallery-admin-page .gallery-upload-actions {
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.75rem;
      }
      .gallery-admin-page .gallery-upload-actions .btn-primary {
        padding: 0.55rem 1.35rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        border-radius: 8px;
        box-shadow: 0 4px 14px rgba(121, 106, 238, 0.35);
      }
      .gallery-admin-page .gallery-empty {
        padding: 2.5rem 1.5rem;
        text-align: center;
        border-radius: 10px;
        border: 1px dashed rgba(255, 255, 255, 0.15);
        color: rgba(255, 255, 255, 0.45);
        margin-bottom: 2rem;
      }
    </style>
  </head>
  <body class="gallery-admin-page">
    @include('admin.header')

    @include('admin.sidebar')

    <div class="page-content">
      <div class="page-header">
        <div class="container-fluid">
          <h2 class="h5 no-margin-bottom">Gallery</h2>
          <p class="text-muted small mb-0 mt-1">Manage hotel gallery images</p>
        </div>
      </div>

      <section class="no-padding-top">
        <div class="container-fluid">
          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row">
            <div class="col-lg-12 mb-4">
              <div class="block">
                <div class="title"><strong>Current images</strong></div>
                <div class="block-body">
                  @if ($gallery->isEmpty())
                    <div class="gallery-empty">No images yet. Upload your first photo below.</div>
                  @else
                    <div class="gallery-admin-grid">
                      @foreach ($gallery as $item)
                        <figure class="gallery-admin-thumb">
                          <img src="{{ asset('gallery/' . $item->image) }}" alt="Gallery image">
                          <form action="{{url('delete_gallery',$item->id)}}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this image?');">Delete</button>
                          </form>
                        </figure>
                      @endforeach
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <div class="col-lg-8">
              <div class="block gallery-upload-card">
                <div class="title"><strong>Add image</strong></div>
                <div class="block-body">
                  <form action="{{ url('/upload_gallery') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="gallery-upload-zone">
                      <span class="upload-icon"><i class="fa fa-cloud-upload"></i></span>
                      <div class="upload-hint">Choose an image to add to the gallery</div>
                      <div class="upload-formats">JPEG, PNG, GIF or WebP · max 2&nbsp;MB recommended</div>
                      <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" required>
                    </div>

                    <div class="gallery-upload-actions">
                      <button type="submit" class="btn btn-primary">
                        <i class="fa fa-plus-circle mr-2"></i>Add image
                      </button>
                      <span class="text-muted small">Images are stored in <code>public/gallery</code></span>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    @include('admin.footer')
  </body>
</html>

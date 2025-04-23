<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bootstrap Image Gallery Preview</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .gallery-img {
      height: 200px;
      object-fit: cover;
      cursor: pointer;
      border-radius: 8px;
      width: 100%;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="row g-3">
    <!-- Thumbnails -->
    <div class="col-md-3">
      <img src="https://picsum.photos/id/1011/600/400" class="gallery-img" data-bs-toggle="modal" data-bs-target="#galleryModal" data-bs-slide-to="0">
    </div>
    <div class="col-md-3">
      <img src="https://picsum.photos/id/1012/600/400" class="gallery-img" data-bs-toggle="modal" data-bs-target="#galleryModal" data-bs-slide-to="1">
    </div>
    <div class="col-md-3">
      <img src="https://picsum.photos/id/1013/600/400" class="gallery-img" data-bs-toggle="modal" data-bs-target="#galleryModal" data-bs-slide-to="2">
    </div>
    <div class="col-md-3">
      <img src="https://picsum.photos/id/1014/600/400" class="gallery-img" data-bs-toggle="modal" data-bs-target="#galleryModal" data-bs-slide-to="3">
    </div>
  </div>
</div>

<!-- Modal with Carousel (Manual Only) -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0">
        <div id="carouselGallery" class="carousel slide" data-bs-ride="false" data-bs-interval="false">
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="https://picsum.photos/id/1011/1200/800" class="d-block w-100">
            </div>
            <div class="carousel-item">
              <img src="https://picsum.photos/id/1012/1200/800" class="d-block w-100">
            </div>
            <div class="carousel-item">
              <img src="https://picsum.photos/id/1013/1200/800" class="d-block w-100">
            </div>
            <div class="carousel-item">
              <img src="https://picsum.photos/id/1014/1200/800" class="d-block w-100">
            </div>
          </div>

          <!-- Prev/Next Controls -->
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselGallery" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselGallery" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      </div>

      <div class="modal-footer bg-dark border-0 justify-content-center">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

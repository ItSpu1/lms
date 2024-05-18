@extends('frontend.master')
@section('title')
| Knowledge Hub
@endsection
@section('home')
<!-- ================================
    START BREADCRUMB AREA
================================= -->
<section class="breadcrumb-area section-padding img-bg-2">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
            <div class="section-heading">
                <h2 class="section__title text-white"> </h2>
            </div>
            <ul class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                <li><a href="index.html">Home</a></li>
                <li> </li>
            </ul>
        </div><!-- end breadcrumb-content -->
    </div><!-- end container -->
</section><!-- end breadcrumb-area -->
<!-- ================================
    END BREADCRUMB AREA
================================= -->



<section class="course-area pb-90px">
    <div class="course-wrapper">
        <div class="container">
            <div class="section-heading text-center">
                <h5 class="ribbon ribbon-lg mb-2">Learn on your schedule</h5>
                <h2 class="section__title">Students are viewing</h2>
                <span class="section-divider"></span>
            </div><!-- end section-heading -->
            <div class="course-carousel owl-action-styled owl--action-styled mt-30px">

                @foreach ($categories as $category)
                <div class="card" style="width: 18rem;">
                    <img src={{ asset($category->image) }} class="card-img-top" alt="...">
                    <div class="card-body">
                      <h5 class="card-title">{{$category->category_name}}</h5>
                      <p class="card-text">{{$category->category_slug}}</p>
                      <a href="{{ url('category/'.$category->id.'/'.$category->category_slug) }}" class="btn btn-primary">View</a>
                    </div>
                  </div>
                        @endforeach
            </div><!-- end tab-content -->
        </div><!-- end container -->
    </div><!-- end course-wrapper -->
</section><!-- end courses-area -->
@endsection
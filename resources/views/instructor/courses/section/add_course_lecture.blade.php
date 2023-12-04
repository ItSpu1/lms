@extends('instructor.instructor_dashboard')
@section('instructor')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js">
</script><!--library for show image whn you upload it -->

<div class="page-content">

    <div class="row">
        <div class="col-12">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset($course->course_image) }}" 
                        class="rounded-circle p-1 border" width="90" height="90" alt="...">
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mt-0">{{ $course->course_name }}</h5>
                            <p class="mb-0">{{ $course->course_title }}</p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Section</button>
                    </div>
                </div>
            </div>
          {{-- //add section and lecture --}}
          @foreach( $section as $item )

<div class="container">
    <div class="main-body">
       <div class="row" >
      
        <div class="col-lg-12">
        <div class ="card">
            <div class="card-body p-4 d-flex justify-content-between">
                <h6>{{ $item->section_title }}</h6>
            
            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-danger px-2 ms-auto">Delete Section</button> &nbsp;
                <a class="btn btn-info px-3 ms-auto">Add Lecture </a>
            </div>
            </div>
        </div>
        </div>


        </div>
    </div>
</div>
@endforeach




           
            
        {{-- //add section and lecture --}} 
    </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('add.course.section') }}" method="POST">
                    @csrf

                        <input type="hidden" name="id" value="{{ $course->id }}">

                    <div class="form-group mb-3">
                        <label for="input1" class="form-label">Course Section</label>
                        <input type="text" name="section_title" class="form-control" id="input1" >
                    </div>
                

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>

        </div>
    </div>
</div>

@endsection
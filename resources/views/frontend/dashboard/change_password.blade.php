@extends('frontend.dashboard.user_dashboard')
@section('userdashboard')

        <div class="container-fluid">

            <div class="section-block mb-5"></div>
            <div class="dashboard-heading mb-5">
                <h3 class="fs-22 font-weight-semi-bold">Settings</h3>
            </div>
            <ul class="nav nav-tabs generic-tab pb-30px" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="edit-profile-tab" data-toggle="tab" href="#edit-profile" role="tab" aria-controls="edit-profile" aria-selected="false">
                        Profile
                    </a>
                </li>




            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                    <div class="setting-body">
                        <h3 class="fs-17 font-weight-semi-bold pb-4">Change Password</h3>
                         <form method ="POST" action="{{ route('user.profile.store')}}" enctype="multipart/form-data" class="row pt-40px">
                                        @csrf


                            <div class="input-box col-lg-12">
                                <label class="label-text">Old password</label>
                                <div class="form-group">
                                    <input class="form-control form--control @error('old_password') is-invalid @enderror" type="password" name="old_password" id="old_password" >
                                    <span class="la la-user input-icon"></span>
                                    @error('old_password')
                                                <span class="text-danger">{{$message}}</span>

                                                @enderror
                                </div>
                            </div><!-- end input-box -->
                           <div class="input-box col-lg-12">
                                <label class="label-text">New password</label>
                                <div class="form-group">
                                    <input class="form-control form--control @error('new_password') is-invalid @enderror" type="password" name="new_password" id="new_password" >
                                    <span class="la la-user input-icon"></span>
                                    @error('new_password')
                                                <span class="text-danger">{{$message}}</span>

                                                @enderror
                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-12">
                                <label class="label-text">Confirm New password</label>
                                <div class="form-group">
                                    <input class="form-control form--control " type="password" name="new_password_confirmation" id="new_password_confirmation" >
                                    <span class="la la-user input-icon"></span>

                                </div>
                            </div><!-- end input-box -->
                            <div class="input-box col-lg-12 py-2">
                               	<input type="submit" class="btn btn-primary px-4" value="Save Changes" />
                            </div><!-- end input-box -->
                        </form>
                    </div><!-- end setting-body -->
                </div><!-- end tab-pane -->





            </div><!-- end tab-content -->

        </div><!-- end container-fluid -->

@endsection

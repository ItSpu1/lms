<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\CourseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/',[UserController::class,'Public'])->name('public');

Route::get('/dashboard', function () {
    return view('frontend.dashboard.index');})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
Route::get('/user/profile',[UserController::class,'UserProfile'])->name('user.profile');
Route::post('/user/profile/store',[UserController::class,'UserProfileStore'])->name('user.profile.store');
Route::get('/user/logout',[UserController::class,'UserLogout'])->name('user.logout');
Route::get('/user/change/password',[UserController::class,'UserChangePassword'])->name('user.change.password');
Route::post('/user/password/update',[UserController::class,'UserPasswordUpdate'])->name('user.password.update');

});

require __DIR__.'/auth.php';

//Admin Group Middleware

Route::middleware(['auth','role:admin'])->group(function(){
Route::get('/admin/dashboard',[AdminController::class,'AdminDashboard'])->name('admin.dashboard');
Route::get('/admin/logout',[AdminController::class,'AdminLogout'])->name('admin.logout');
Route::get('/admin/profile',[AdminController::class,'AdminProfile'])->name('admin.profile');
Route::post('/admin/profile/store',[AdminController::class,'AdminProfileStore'])->name('admin.profile.store');
Route::get('/admin/change/password',[AdminController::class,'AdminChangePassword'])->name('admin.change.password');
Route::post('/admin/password/update',[AdminController::class,'AdminPasswordUpdate'])->name('admin.password.update');
});




Route::get('/admin/login',[AdminController::class,'AdminLogin'])->name('admin.login');

//owies
Route::get('/become/instructor',[AdminController::class,'BecomeInstructor'])->name('become.instructor');

Route::post('/instructor/register',[AdminController::class,'InstructorRegister'])->name('instructor.register');
//owies


//Instructor Group Middleware
Route::middleware(['auth','role:instructor'])->group(function(){
Route::get('/instructor/dashboard',[InstructorController::class,'InstructorDashboard'])->name('instructor.dashboard');
Route::get('/instructor/logout',[InstructorController::class,'InstructorLogout'])->name('instructor.logout');
Route::get('/instructor/profile',[InstructorController::class,'InstructorProfile'])->name('instructor.profile');
Route::post('/instructor/profile/store',[InstructorController::class,'InstructorProfileStore'])->name('instructor.profile.store');
Route::get('/instructor/change/password',[InstructorController::class,'InstructorChangePassword'])->name('instructor.change.password');
Route::post('/instructor/password/update',[InstructorController::class,'InstructorPasswordUpdate'])->name('instructor.password.update');
//AGHA
Route::controller(CourseController::class)->group(function(){
    Route::get('/all/course','AllCourse')->name('all.course');
    Route::get('/add/course','AddCourse')->name('add.course');
    Route::get('/subcategory/ajax/{category_id}','GetSubCategory');
    Route::post('/store/course','StoreCourse')->name('store.course');
//AGHA video 61
});

//course section and lecture by owies
Route::controller(CourseController::class)->group(function(){
    Route::get('/add/course/lecture/{id}','AddCourseLecture')->name('add.course_lecture');
    Route::post('/add/course/section/','AddCourseSection')->name('add.course.section');
  
//owies video 78
});



});
Route::get('/instructor/login',[InstructorController::class,'InstructorLogin'])->name('instructor.login');

//category group controller added bu eenas
Route::controller(CategoryController::class)->group(function(){
    Route::get('/all/category','AllCategory')->name('all.category');
    Route::get('/add/category','AddCategory')->name('add.category');
    Route::post('/store/category','StoreCategory')->name('store.category');
    Route::get('/edit/category/{id}','EditCategory')->name('edit.category');
    Route::post('/update/category','UpdateCategory')->name('update.category');
    Route::get('/delete/category/{id}','DeleteCategory')->name('delete.category');
});
//SubCategory Added By enas section 8
Route::controller(CategoryController::class)->group(function(){
    Route::get('/all/subcategory','AllSubCategory')->name('all.subcategory');
    Route::get('/add/subcategory','AddSubCategory')->name('add.subcategory');
    Route::post('/store/subcategory','StoreSubCategory')->name('store.subcategory');
    Route::get('/edit/subcategory/{id}','EditSubCategory')->name('edit.subcategory');
    Route::post('/update/subcategory','UpdateSubCategory')->name('update.subcategory');
    Route::get('/delete/subcategory/{id}','DeleteSubCategory')->name('delete.subcategory');
});


//Instructor all route Added By owies section 10
Route::controller(AdminController::class)->group(function(){
    Route::get('/all/instructor','AllInstructor')->name('all.instructor');
    Route::post('/update/user/stauts','UpdateUserStatus')->name('update.user.stauts');
});

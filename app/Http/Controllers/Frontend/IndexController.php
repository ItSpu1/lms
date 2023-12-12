<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Course;
use App\Models\Course_goals;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use Intervention\Image\Facades\Image;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function CourseDetails($id,$slug){
        $course=Course::find($id);
        $goals=Course_goals::where('course_id',$id)->orderBy('id','DESC')->get();
        $ins_id=$course->instructor_id;
        $instructorCourses=Course::where('instructor_id',$ins_id)->orderBy('id','DESC')->get();
        $categories=Category::latest()->get();
        $cat_id=$course->category_id;
        $relatedCourses= Course::where('category_id',$cat_id)->where('id','!=',$id)->orderBy('id','DESC')->limit(3)->get();
        return view('frontend.course.course_details',compact('course','goals','instructorCourses','categories','relatedCourses'));
    }

}

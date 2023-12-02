<?php
//AGHA
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use app\Models\SubCategory;
use App\Models\Course;
use App\Models\Course_goal;
use Intervention\Image\Facades\Image;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CourseCotroller extends Controller
{
    public function AllCourse(){
        $id = Auth::user()->id;
        $courses=Course::where('instructor_id',$id)->orderby('id','desc')->get();
        return view('instructor.courses.all_courses',compact('courses'));
    }//end method


    public function AddCourse(){
        $categories = Category::latest()->get();
        return view('instructor.courses.add_course',compact('categories'));
    }//end method


    public function StoreCourse(Request $request){
        $request->validate([
            'video' => 'required|mimes:mp4|max:10000',
        ]);

        $image=$request->file('course_image');
        $name_gen=hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,246)->save('upload/course/thumbnail/'.$name_gen);
        $save_url ='upload/course/thumbnail/'.$name_gen;

        $video=$request->file('video');
        $videoName= time().'.'.$video->getClientOriginalExtension();
        $video->move(public_path('upload/course/video/'),$videoName);
        $save_url ='upload/course/video/'.$videoName;


    }//end method




}
//AGHA

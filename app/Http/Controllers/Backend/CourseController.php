<?php
//AGHA
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use app\Models\SubCategory;
use App\Models\Course;
use App\Models\Course_goal;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use Intervention\Image\Facades\Image;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;

class CourseController extends Controller
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

    public function GetSubCategory($category_id){

        $subcat = SubCategory::where('category_id',$category_id)->orderBy('subcategory_name','ASC')->get();
        return json_encode($subcat);

    }

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
        $save_video='upload/course/video/'.$videoName;

        $course_id= Course::insertGetId([
            'category_id'=>$request->category_id,
            'subcategory_id'=>1,
            'instructor_id'=>Auth::user()->id,
            'course_title'=>$request->course_title,
            'course_name'=>$request->course_name,
            'course_name_slug'=>strtolower(str_replace(' ','-',$request->course_name)),
            'description'=>$request->description,
            'video'=>$save_video,
            
            'label'=>$request->label,
            'duration'=>$request->duration,
            'resources'=>$request->resources,
            'certificate'=>$request->certificate,
            'selling_price'=>$request->selling_price,
            'discount_price'=>$request->discount_price,
            'prerequisites'=>$request->prerequisites,
            'bestseller'=>$request->bestseller,
            'featured'=>$request->featured,
            'highestrated'=>$request->highestrated,
            'status'=>1,
            'course_image'=>$save_url,
            'created_at'=>Carbon::now(),
            
        ]) ;     

            ///Course Goal Add Form

            $goals = Count($request->course_goals);
            if ($goals !=NULL){
                 for ($i=0; $i < $goals; $i++) { 
                    $goal_count = new Course_goal();
                    $goal_count ->course_id = $course_id;
                    $goal_count ->goal_name = $request->course_goals[$i];
                    $goal_count->save();
                }
            }
            /// End Course Goal Add Form

            $notification = array(
                'message'=>'Course Insterted Successfully',
                'alert-Type'=>'Success'
            );
            return redirect()->route('all.course')->with($notification);


    }  //end method   //AGHA

            //owies


    public function AddCourseLecture($id){


        $course =Course::find($id);

        return view('instructor.courses.section.add_course_lecture' ,compact
        ('course'));
    }

    public function AddCourseSection(Request $request){

        $cid = $request->id;
        
        CourseSection::insert([
            'course_id => $cid',
            'section_title' => $request->section_title,
        ]);

        $notification = array(
            'message'=>'Course Section Add Successfully',
            'alert-Type'=>'Success'
        );
        return redirect()->route('all.course')->with($notification);

    }
        
    
           
              // owies video 78
}
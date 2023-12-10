<?php
//AGHA
namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
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
            'subcategory_id'=>$request->subcategory_id,
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




     //end method
    //Enas section 12
        public function EditCourse($id){
            $course =Course ::find($id);
            $goals= Course_goal::Where('course_id',$id)->get();
            $categories = Category::latest()->get();
            $subcategories = SubCategory::latest()->get();
            return view ('instructor.courses.edit_course',compact('course','categories','subcategories','goals'));
        }

            public function UpdateCourse(Request $request){

                $cid = $request->course_id;

                Course::find($cid)->update([
                    'category_id' => $request->category_id,
                    'subcategory_id' => $request->subcategory_id,
                    'instructor_id' => Auth::user()->id,
                    'course_title' => $request->course_title,
                    'course_name' => $request->course_name,
                    'course_name_slug' => strtolower(str_replace(' ', '-', $request->course_name)),
                    'description' => $request->description,

                    'label' => $request->label,
                    'duration' => $request->duration,
                    'resources' => $request->resources,
                    'certificate' => $request->certificate,
                    'selling_price' => $request->selling_price,
                    'discount_price' => $request->discount_price,
                    'prerequisites' => $request->prerequisites,

                    'bestseller' => $request->bestseller,
                    'featured' => $request->featured,
                    'highestrated' => $request->highestrated,

                ]);

                $notification = array(
                    'message' => 'Course Updated Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->route('all.course')->with($notification);

            }// End Method
            public function UpdateCourseImage(Request $request){

                $course_id = $request->id;
                $oldImage = $request->old_img;

                $image = $request->file('course_image');
                $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
                Image::make($image)->resize(370,246)->save('upload/course/thumbnail/'.$name_gen);
                $save_url = 'upload/course/thumbnail/'.$name_gen;

                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }

                Course::find($course_id)->update([
                    'course_image' => $save_url,
                    'updated_at' => Carbon::now(),
                ]);

                $notification = array(
                    'message' => 'Course Image Updated Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->back()->with($notification);

            }// End Method
            public function UpdateCourseVideo(Request $request){

                $course_id = $request->vid;
                $oldVideo = $request->old_vid;

                $video = $request->file('video');
                $videoName = time().'.'.$video->getClientOriginalExtension();
                $video->move(public_path('upload/course/video/'),$videoName);
                $save_video = 'upload/course/video/'.$videoName;

                if (file_exists($oldVideo)) {
                    unlink($oldVideo);
                }

                Course::find($course_id)->update([
                    'video' => $save_video,
                    'updated_at' => Carbon::now(),
                ]);

                $notification = array(
                    'message' => 'Course Video Updated Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->back()->with($notification);

            }// End Method
            public function UpdateCourseGoal(Request $request){

                $cid = $request->id;

                if ($request->course_goals == NULL) {
                    return redirect()->back();
                }
                else{

                    Course_goal::where('course_id',$cid)->delete();

                    $goles = Count($request->course_goals);

                    for ($i=0; $i < $goles; $i++) {
                        $gcount = new Course_goal();
                        $gcount->course_id = $cid;
                        $gcount->goal_name = $request->course_goals[$i];
                        $gcount->save();
                    }  // end for
                } // end else

                $notification = array(
                    'message' => 'Course Goals Updated Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->back()->with($notification);
            }//end method
            public function DeleteCourse($id){
                $course = Course::find($id);
                unlink($course->course_image);
                unlink($course->video);

                Course::find($id)->delete();

                $goalsData = Course_goal::where('course_id',$id)->get();
                foreach ($goalsData as $item) {
                    $item->goal_name;
                    Course_goal::where('course_id',$id)->delete();
                }

                $notification = array(
                    'message' => 'Course Deleted Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->back()->with($notification);

            }// End Method






/// owies
public function AddCourseLecture($id){

    $course = Course::find($id);

    $section = CourseSection::where('course_id',$id)->latest()->get();

    return view('instructor.courses.section.add_course_lecture',compact('course','section'));

}// End Method

    public function AddCourseSection(Request $request){

        $cid = $request->id;

        CourseSection::insert([
            'course_id' => $cid,
            'section_title' => $request->section_title,
        ]);

        $notification = array(
            'message'=>'Course Section Add Successfully',
            'alert-Type'=>'Success'
        );
        return redirect()->back()->with($notification);

    }
    public function SaveLecture(Request $request){

        $lecture = new CourseLecture();
        $lecture->course_id = $request->course_id;
        $lecture->section_id = $request->section_id;
        $lecture->lecture_title = $request->lecture_title;
        $lecture->url = $request->lecture_url;
        $lecture->content = $request->content;
        $lecture->save();

        return response()->json(['success' => 'Lecture Saved Successfully']);

    }// End Method
    public function EditLecture($id){
        $clecture = CourseLecture::find($id);
        return view ('instructor.courses.lecture.edit_course_lecture',compact('clecture'));



    }//end method
    public function UpdateCourseLecture(Request $request)
    {
    $lid=$request->id;//hidden id
        CourseLecture::find($lid)->update([
            'lecture_title'=>$request->lecture_title,
            'url'=>$request->url,
            'content'=>$request->content,

        ]);

        $notification = array(
            'message'=>'Course lecture Updated Successfully',
            'alert-Type'=>'Success'
        );
        return redirect()->back()->with($notification);



    }//end method
    public function DeleteLecture($id)
    {
        CourseLecture::find($id)->delete();
        $notification = array(
            'message'=>'Course lecture Delete Successfully',
            'alert-Type'=>'Success'
        );
        return redirect()->back()->with($notification);

    }//end method
    public function DeleteSection($id)
    {   $section=CourseSection::find($id);
        //Deleted realeted lectures in coursesection relationship
        $section->lectures()->delete();
//Delete section
$section->delete();

        $notification = array(
            'message'=>'Course Section Delete Successfully',
            'alert-Type'=>'Success'
        );
        return redirect()->back()->with($notification);

    }//end method
}



              // owies video 78






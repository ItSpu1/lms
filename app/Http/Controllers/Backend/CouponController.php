<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\SubCategory;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class CouponController extends Controller
{
    
    //owies section 26
    public function AdminAllCoupon(){

        $coupon = Coupon::latest()->get();
        return view('admin.backend.coupon.coupon_all',compact('coupon'));
    }

    public function AdminAddCoupon(){

        return view('admin.backend.coupon.coupon_add');

    }//end method


    public function AdminStoreCoupon(Request $request){

        Coupon::insert([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validity' => $request->coupon_validity,
            'created_at' => Carbon::now(),
        ]);


        $notification = array(
            'message'=>'Coupon Insterted Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('admin.all.coupon')->with($notification);

    }
    public function AdminEditCoupon($id) {
        $coupon = Coupon::find($id);
        return view('admin.backend.coupon.coupon_edit', compact('coupon'));
    }//end method



    public function AdminUpdateCoupon(Request $request){

        $coupon_id = $request->id;

        Coupon::find($coupon_id)->update([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validity' => $request->coupon_validity,
            'created_at' => Carbon::now(),
        ]);


        $notification = array(
            'message'=>'Coupon Updated Successfully',
            'alert-type'=>'success'
        );
        return redirect()->route('admin.all.coupon')->with($notification);



    }//end method
    public function AdminDeleteCoupon($id){

        Coupon::find($id)->delete();

        $notification = array(
            'message'=>'Coupon Delete Successfully',
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);


    }//end method

    /////////////////////Instructor All Coupon Method

    public function InstructorAllCoupon(){
        $id = Auth::user()->id;
        $coupon = Coupon::where('instructor_id',$id)->latest()->get();
        return view('instructor.coupon.coupon_all',compact('coupon'));

    }/// End Method 



    public function InstructorAddCoupon(){
        $id = Auth::user()->id;
        $courses = Course::where('instructor_id',$id)->get();
        return view('instructor.coupon.coupon_add',compact('courses'));

    }// End Method 


    public function InstructorStoreCoupon(Request $request){

        Coupon::insert([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validity' => $request->coupon_validity,
            'instructor_id' => Auth::user()->id,
            'course_id' => $request->course_id,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Coupon Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('instructor.all.coupon')->with($notification);

    }// End Method 


    public function InstructorEditCoupon($id){

        $coupon = Coupon::find($id);
        $insid = Auth::user()->id;
        $courses = Course::where('instructor_id',$insid)->get();
        return view('instructor.coupon.coupon_edit',compact('coupon','courses'));

    }//end method


    public function InstructorUpdateCoupon(Request $request){
        $coupon_id = $request->coupon_id;

        Coupon::find($coupon_id)->Update([
            'coupon_name' => strtoupper($request->coupon_name),
            'coupon_discount' => $request->coupon_discount,
            'coupon_validity' => $request->coupon_validity,
            'instructor_id' => Auth::user()->id,
            'course_id' => $request->course_id,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Coupon Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('instructor.all.coupon')->with($notification);

    }// End Method 


    public function InstructorDeleteCoupon($id){

        Coupon::find($id)->delete();
        $notification = array(
            'message' => 'Coupon Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);



    }//end Method

     




       
    //owies 







}

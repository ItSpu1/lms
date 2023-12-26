<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\SubCategory;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

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
            'alert-Type'=>'Success'
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
            'alert-Type'=>'Success'
        );
        return redirect()->route('admin.all.coupon')->with($notification);



    }//end method
    public function AdminDeleteCoupon($id){

        Coupon::find($id)->delete();

        $notification = array(
            'message'=>'Coupon Delete Successfully',
            'alert-Type'=>'Success'
        );
        return redirect()->back()->with($notification);


    }//end method

     




       
    //owies section 26







}

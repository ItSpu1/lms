<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Course;
use App\Models\Wishlist;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use Intervention\Image\Facades\Image;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class WishListController extends Controller
{
    public function AddToWishList(Request $request, $course_id){
        if (Auth::check()) {
            $exists = Wishlist::where('user_id',Auth::id())->where('course_id',$course_id)->first();
        
            if (!$exists){
                Wishlist::insert([
                    'user_id' => Auth::id(),
                    'course_id' => $course_id,
                    'created_at' => Carbon::now(),
                ]);
                return response()->json(['success' => 'Successfully Added on your Wishlist']);
            }
            else{
                return response()->json(['error' => 'This Product Has Already on your Wishlist']);                
            }
        }
            else{
            return response()->json(['error' => 'At First Login Your Account']);
        }
    }//End Method


    public function AllWishlist(){
        return view('frontend.wishlist.all_wishlist');

    }//End Method


    public function GetWishlistCourse(){

        $wishlist = Wishlist::with('course')->where('user_id',Auth::id())->latest()->get();

        $wishQty = Wishlist::count();

        return response()->json(['wishlist'=>$wishlist, 'wishQty' => $wishQty]);

    }//End Method



    public function RemoveWishlist($id){

        Wishlist::where('user_id',Auth::id())->where('id',$id)->delete();
        return response()->json(['success' => 'Successfully Course Remove']);

    }//End Method



}

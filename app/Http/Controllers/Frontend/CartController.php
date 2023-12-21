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
use  Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function AddToCart(Request $request, $id){

        $course = Course::find($id);



        // Check if the course is already in the cart
        $cartItem = Cart::search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id === $id;
        });

        if ($cartItem->isNotEmpty()) {
            return response()->json(['error' => 'Course is already in your cart']);
        }

        if ($course->discount_price == NULL) {

            Cart::add([
                'id' => $id,'name' => $request->course_name,'qty' => 1,'price' => $course->selling_price,'weight' => 1,'options' => ['image' => $course->course_image,'slug' => $request->course_name_slug,'instructor' => $request->instructor, ], ]);

        }else{

            Cart::add([
                'id' => $id,
                'name' => $request->course_name,
                'qty' => 1,
                'price' => $course->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $course->course_image,
                    'slug' => $request->course_name_slug,
                    'instructor' => $request->instructor,
                ],
            ]);
        }

        return response()->json(['success' => 'Successfully Added on Your Cart']);

    }// End Method
    public function CartData(){
        //get all data from cart
        $carts=Cart::content();
        $cartTotal=Cart::total();
        $cartQty=Cart::count();

        return response()->json(array(
            'carts'=>$carts,
            'cartTotal'=>$cartTotal,
            'cartQty'=>$cartQty,
        ));


    }
    public function AddMiniCart(){

        $carts = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json(array(
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));

    }// End Method
    public function RemoveMiniCart($rowId){

        Cart::remove($rowId);
        return response()->json(['success' => 'Course Remove From Cart']);

    }// End Method
    //new section enas
    public function MyCart(){
        return view ('frontend.mycart.view_mycart');

    }
    public function GetCartCourse(){

        $carts = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json(array(
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));

    }// End Method
    public function CartRemove($rowId){

        Cart::remove($rowId);
        return response()->json(['success' => 'Course Remove From Cart']);

    }// End Method
}

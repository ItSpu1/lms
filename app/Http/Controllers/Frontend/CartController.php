<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course_goals;
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
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\Orderconfirm;
class CartController extends Controller
{
    public function AddToCart(Request $request, $id){

        $course = Course::find($id);

        if (Session::has('coupon')) {
            Session::forget('coupon');
        }



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

        if (Session::has('coupon')) {
            $coupon_name = Session::get('coupon')['coupon_name'];
            $coupon = Coupon::where('coupon_name')->first();

            Session::put('coupon',[
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100),
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
            ]);

        }
        return response()->json(['success' => 'Course Remove From Cart']);

    }// End Method


    //section 27 start by owies
    public function CouponApply(Request $request){

        $coupon = Coupon::where('coupon_name',$request->coupon_name)->where('coupon_validity','>=',Carbon::now()->format('Y-m-d'))->first(); 

        if ($coupon) {
            Session::put('coupon',[
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100),
                'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
            ]);

            return response()->json(array(
                'validity' => true,
                'success' => 'Coupon Applied Successfully'
            ));

        }else {
            return response()->json(['error' => 'Invaild Coupon']);
        }

    }// End Method 
    public function CouponCalculation(){

        if (Session::has('coupon')) {
           return response()->json(array(
            'subtotal' => Cart::total(),
            'coupon_name' => session()->get('coupon')['coupon_name'],
            'coupon_discount' => session()->get('coupon')['coupon_discount'],
            'discount_amount' => session()->get('coupon')['discount_amount'],
            'total_amount' => session()->get('coupon')['total_amount'],
           ));
        } else{
            return response()->json(array(
                'total' => Cart::total(),
            ));
        }

    }// End Method 


    public function CouponRemove(){

        Session::forget('coupon');
        return response()->json(['success' => 'Coupon Remove Successfully']);


    }// End Method 
    //section 27 end by owies
    
    //Agha 
    public function CheckoutCreate(){
        if (Auth::check()) {
            if (Cart::total() > 0) {
                $carts=Cart::content();
                $cartTotal=Cart::total();
                $cartQty=Cart::count();
                return view('frontend.checkout.checkout_view',compact('carts','cartTotal','cartQty'));
            }else{
                $notification = array(
                    'message'=>'Add at Least One Course',
                    'alert-Type'=>'error'
                );
                return redirect()->to('/')->with($notification);
            }
        }else{
            $notification = array(
                'message' => 'You Need To Login First',
                'alert-Type'=>'error'
            );
            return redirect()->route('login')->with($notification);
        }
    }// End Method



    public function Payment(Request $request){
        if (Session::has('coupon')) {
            $total_amount = Session::get('coupon')['total_amount'];
        }else{
            $total_amount = round(Cart::total());
        }

        //Create a New Payment Record
        $data = new Payment();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->cash_delivery = $request->cash_delivery;
        $data->total_amount = $request->total_amount;
        $data->payment_type = 'Direct Payment';
        $data->invoice_no = 'EOS' . mt_rand(100000000, 999999999);//Easy Onlone System
        $data->order_date = Carbon::now()->format('D M Y');
        $data->order_month = Carbon::now()->format('M');
        $data->order_year = Carbon::now()->format('Y');
        $data->status = 'pending';
        $data->created_at =Carbon::now();
        $data->save();

        foreach ($request->course_title as $key => $course_title) {
            $existingOrder = Order::where('user_id',Auth::user()->id)->where('course_id',$request->course_id[$key])->first();

            if ($existingOrder) {
                $notification = array(
                    'message' => 'You Have already enrolled in this course',
                    'alert-Type'=>'error');
                    return redirect()->back()->with($notification);
                }//End if
                $order = new Order();
                $order -> payment_id = $data->id; 
                $order -> user_id = Auth::user()->id; 
                $order -> course_id = $request->course_id[$key];
                $order -> instructor_id = $request->instructor_id[$key];
                $order -> course_title = $course_title; 
                $order -> price = $request->price[$key];
                $order -> save();
        }//End foreach
        $request->session()->forget('cart');
        
        $paymentId = $data->id;
        // start Send email to student //
        $sendmail = Payment::find($paymentId);
        $data = [
            'invoice_no' => $sendmail->invoce_no,
            'amount' => $total_amount,
            'name' => $sendmail->name,
            'email' => $sendmail->email,
        ];
        Mail::to($request->email)->send(new Orderconfirm($data));
        //end Send email to student //



        if ($request->cash_delivery == 'stripe') {
                    echo "stripe";
                }else{
                    $notification = array(
                    'message' => 'Cash Payment Submit Successfully',
                    'alert-Type'=>'success');
                    return redirect()->route('public')->with($notification);

                }


    }//End Method
//Agha
}

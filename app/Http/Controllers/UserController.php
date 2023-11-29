<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
public function Index(){
    return view('frontend.index');
}
public function UserProfile(){
    $id =Auth::user()->id;
    $profileData= User::find($id);
    return view('frontend.dashboard.edit_profile',compact('profileData'));

}
public function UserProfileStore(Request $request){
    $id=Auth::user()->id;
    $data =User::find($id);
    $data->name=$request->name;
    $data->username= $request->username;
    $data->email=$request->email;
    $data->phone=$request->phone;
    $data->address=$request->address;
    if($request->file('photo')){
            $file=$request->file('photo');
            @unlink(public_path('upload/user_images'.$data->photo));
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$filename);
            $data['photo']=$filename;
        }
        $data->save();
        $notification = array(
            'message'=>'user Profile Updated Successfully',
            'alert-Type'=>'success'
        );

        return redirect()->back()->with($notification);

}
public function UserLogout(Request $request)
{
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/login');
}
public function UserChangePassword(){
    $id =Auth::user()->id;
    $profileData= User::find($id);
    return view('frontend.dashboard.change_password',compact('profileData'));

}
}

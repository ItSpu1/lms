<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class InstructorController extends Controller
{
    public function InstructorDashboard(){
        return view('instructor.index');
    }//End methods
    public function InstructorLogout(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message'=>'Logout Successfully',
            'alert-type'=>'success'
        );

        return redirect('/instructor/login')->with($notification);

    }//end methods

    public function InstructorLogin(){
        return view('instructor.instructor_login');
    }//end method

    public function InstructorProfile(){
        $id =Auth::user()->id;
        $profileData= User::find($id);
        return view('instructor.instructor_profile_view',compact('profileData'));
    }//end method
    public function InstructorProfileStore(Request $request){
        $id=Auth::user()->id;
        $data =User::find($id);
        $data->name=$request->name;
        $data->username= $request->username;
        $data->email=$request->email;
        $data->phone=$request->phone;
        $data->address=$request->address;
        if($request->file('photo')){
                $file=$request->file('photo');
                @unlink(public_path('upload/instructor_images'.$data->photo));
                $filename= date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('upload/instructor_images'),$filename);
                $data['photo']=$filename;
            }
            $data->save();
            $notification = array(
                'message'=>'Instructor Profile Updated Successfully',
                'alert-type'=>'success'
            );

            return redirect()->back()->with($notification);
    }//end methods


    public function InstructorChangePassword(){
        $id =Auth::user()->id;
        $profileData= User::find($id);
        return view('instructor.instructor_change_password',compact('profileData'));

    }
    public function instructorPasswordUpdate(Request $request){
        //validation
        $request->validate([
            'old_password'=>'required',
            'new_password'=>'required|confirmed',
        ]);
    if(!Hash::check($request->old_password, auth::user()->password)){
        $notification = array(
            'message'=>'Old Password Does not Match!',
            'alert-type'=>'error'
        );
        return redirect()->back()->with($notification);
    }
    //update the new password
    User::whereId(auth::user()->id)->update([
        'password'=>Hash::make($request->new_password)

    ]);
    $notification = array(
        'message'=>'Password Change Successfully!',
        'alert-type'=>'success'
    );
    return redirect()->back()->with($notification);

    }
}

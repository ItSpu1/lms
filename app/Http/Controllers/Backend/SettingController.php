<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmtpSetting;

class SettingController extends Controller
{
    public function SmtpSetting(){
        $smtp=SmtpSetting::find(1);
        return view('admin.backend.setting.Smtp_update',compact('smtp'));

    }
    public function SmtpUpdate(Request $request){
        $smtp_id=$request->id;
        SmtpSetting::find($smtp_id)->update([
            'mailer'=>$request->mailer,
            'host'=>$request->host,
            'port'=>$request->port,
            'username'=>$request->usernam,
            'password'=>$request->paswword,
            'encryption'=>$request->encryption,
            'from_address'=>$request->from_address,


        ]);
        $notification = array(
            'message'=>'Smtp Setting Updated Successfully',
            'alert-Type'=>'Success'
        );
        return redirect()->back()->with($notification);
    }
}

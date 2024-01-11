<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmtpSetting;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\SiteSetting;


class SettingController extends Controller
{
    public function SmtpSetting(){
        $smtp=SmtpSetting::find(1);
        return view('admin.backend.setting.smtp_update',compact('smtp'));

    }//End Method
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
            'alert-type'=>'success'
        );
        return redirect()->back()->with($notification);
    }//End Method


    public function SiteSetting(){
        $site=SiteSetting::find(1);
        return view('admin.backend.site.site_update',compact('site'));

    }//End Method

    public function UpdateSite(Request $request){
        
        $site_id = $request->id;

        if ($request->file('logo')) {

    
            $manager = new ImageManager(new Driver());
            $name_gen=hexdec(uniqid()).'.'.$request->file('logo')->getClientOriginalExtension();
            $image = $manager->read($request->file('logo'));
            $image->resize(160, 70);  
            $image->save(base_path('public/upload/logo/'.$name_gen));
            $save_url ='upload/logo/'.$name_gen;



            SiteSetting::find($site_id)->update([
                'phone' => $request->phone, 
                'email' => $request->email, 
                'address' => $request->address, 
                'facebook' => $request->facebook, 
                'instagram' => $request->instagram, 
                'copyright' => $request->copyright,  
                'logo' => $save_url,        
    
            ]);
    
            $notification = array(
                'message' => 'Site Setting Updated with image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);  
        
        } else {

            SiteSetting::find($site_id)->update([
                'phone' => $request->phone, 
                'email' => $request->email, 
                'address' => $request->address, 
                'facebook' => $request->facebook, 
                'instagram' => $request->instagram, 
                'copyright' => $request->copyright,  
    
            ]);
    
            $notification = array(
                'message' => 'Site Setting Updated without image Successfully',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);  

        } // end else 

    }// End Method 


}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Intervention\Image\Facades\Image;
class CategoryController extends Controller
{
// added by enas
    public function AllCategory(){
        $category= Category::latest()->get();
        return view ('admin.backend.category.all_category',compact('category'));
    }//End Method
    public function AddCategory(){
            return view('admin.backend.category.add_category');

           }
    public function StoreCategory(Request $request){
        $image=$request->file('image');
        $name_gen=hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,246)->save('upload/category/'.$name_gen);
        $save_url ='upload/category/'.$name_gen;
        Category::insert([
            'category_name'=>$request->category_name,
            'category_slug'=>strtolower(str_replace('','-',$request->category_name)),// Web Development =>web-development
                'image'=>$save_url,
        ]);
        $notification = array(
            'message'=>'Category Insterted Successfully',
            'alert-Type'=>'Success'
        );
        return redirect()->route('all.category')->with($notification);
    }//end method
    public function EditCategory($id){
        $category = Category::find($id);
        return view('admin.backend.category.edit_category',compact('category'));
    }
    public function UpdateCategory(Request $request){
        $cat_id=$request->id;
        if($request->file('image')){
            $image=$request->file('image');
            $name_gen=hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(370,246)->save('upload/category/'.$name_gen);
            $save_url ='upload/category/'.$name_gen;
            Category::find($cat_id)->update([
                'category_name'=>$request->category_name,
                'category_slug'=>strtolower(str_replace('','-',$request->category_name)),// Web Development =>web-development
                    'image'=>$save_url,
            ]);
            $notification = array(
                'message'=>'Category updated with Image  Successfully',
                'alert-Type'=>'Success'
            );
            return redirect()->route('all.category')->with($notification);
        }
        else {
            Category::find($cat_id)->update([
                'category_name'=>$request->category_name,
                'category_slug'=>strtolower(str_replace('','-',$request->category_name)),// Web Development =>web-development

            ]);
            $notification = array(
                'message'=>'Category updated without Image  Successfully',
                'alert-Type'=>'Success'
            );

            return redirect()->route('all.category')->with($notification);
        }
    }//end method
    public function DeleteCategory($id){
        $item=Category::find($id);
        $img=$item->image;
        unlink($img);
        Category ::find($id)->delete();
        $notification = array(
            'message'=>'Category Deleted Successfully',
            'alert-Type'=>'Success'
        );

        return redirect()->back()->with($notification);


    }
//////////////////////////////All SuB Category Added By Enas section 8/////////////////


    public function AllSubCategory(){
        $subcategory= SubCategory::latest()->get();
        return view ('admin.backend.subcategory.all_subcategory',compact('subcategory'));
    }//End Method
    public function AddSubCategory(){
        $category=Category::latest()->get();
        return view('admin.backend.subcategory.add_subcategory',compact ('category'));

   }
   public function StoreSubCategory(Request $request){

    SubCategory::insert([
        'category_id'=>$request->category_id,
        'subcategory_name'=>$request->subcategory_name,
       'subcategory_slug' =>strtolower(str_replace('','-',$request->subcategory_name)),// Web Development =>web-development

    ]);
    $notification = array(
        'message'=>'SubCategory Insterted Successfully',
        'alert-Type'=>'Success'
    );
    return redirect()->route('all.subcategory')->with($notification);
}//end method
    public function EditSubCategory($id){
        $category=Category::latest()->get();
        $subcategory=SubCategory::find($id);
        return view('admin.backend.subcategory.edit_subcategory',compact('category','subcategory'));
    }
    public function UpdateSubCategory(Request $request){
        $subcat_id=$request->id;

            SubCategory::find($subcat_id)->update([
                'category_id'=>$request->category_id,
                'subcategory_name'=>$request->subcategory_name,
                'subcategory_slug'=>strtolower(str_replace('','-',$request->subcategory_name)),// Web Development =>web-development

            ]);
            $notification = array(
                'message'=>'SubCategory updated with Image  Successfully',
                'alert-Type'=>'Success'
            );
            return redirect()->route('all.subcategory')->with($notification);


        }//end method

    public function DeleteSubCategory($id){

        SubCategory ::find($id)->delete();
        $notification = array(
            'message'=>'SubCategory Deleted Successfully',
            'alert-Type'=>'Success'
        );

        return redirect()->back()->with($notification);


    }
}



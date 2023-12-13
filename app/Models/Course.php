<?php
//AGHA

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded=[];
//AGHA
    public function Category(){
        return $this->belongsto(Category::class, 'category_id' , 'id');
    }
    //enas setion 16
    public function subCategory(){
        return $this->belongsto(SubCategory::class, 'subcategory_id' , 'id');
    }


    public function user(){
        return $this->belongsto(User::class, 'instructor_id' , 'id');
    }


}
//AGHA

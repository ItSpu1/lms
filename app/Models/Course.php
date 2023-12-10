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
    public function category(){
        return $this->belongsto(Category::class, 'category_id' , 'id');
    }

    
    public function user(){
        return $this->belongsto(User::class, 'instructor_id' , 'id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    
    public function user(){
        return $this->belongsto(User::class, 'instructor_id' , 'id');
    }

}
//AGHA

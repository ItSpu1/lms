<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
class Wishlist extends Model
{
    use HasFactory;
    Protected $guarded=[];
    public function course(){
        return $this->belongsto(Course::class, 'course_id' , 'id');
}
}
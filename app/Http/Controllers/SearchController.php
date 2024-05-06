<?php

namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\Http\Request;

class SearchController extends Controller
{
 
    
    public function CourseSearch(Request $request)
    {
        $searchTerm = $request->input('term');
        if(empty($searchTerm)) {
            return response()->json([]);
        }
        $products =Course::where('course_name', 'like', $searchTerm . '%')->get();

        return response()->json($products);
    }
}
?>

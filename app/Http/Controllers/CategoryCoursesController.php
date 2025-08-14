<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCourseCollection;
use App\Http\Resources\CategoryCourseResource;
use App\Models\CategoryCourses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryCoursesController extends Controller
{
  use ApiResponse;
    public function __construct()
    {
        $this->middleware('role:Admin')->only(['store','update','destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index(Request $request)
    {
        $categoryCourses = CategoryCourses::with('courses');
        if($request->pagination!=null){
            $categoryCourses = $categoryCourses->paginate($request->pagination);
        }
        else{
            $categoryCourses = $categoryCourses->paginate(10);
        }
        return response(new CategoryCourseCollection($categoryCourses));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|string',
            'level'=>'string|nullable',
        ]);
        if($validator->fails()){
          return $this->apiresponse(null,'validation error',$validator->errors(),400);
        }
        $categoryCourses = CategoryCourses::create($request->all());
        return $this->apiresponse($categoryCourses,'category created successfully',201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CategoryCourses  $categoryCourses
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categoryCourse = CategoryCourses::with('courses')->find($id);
        if($categoryCourse){
          return $this->apiresponse(new CategoryCourseResource($categoryCourse),'category found',201);
        }
        return $this->apiresponse(null,'category not found',404);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CategoryCourses  $categoryCourses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|string',
            'level'=>'string|nullable',
        ]);
        if($validator->fails()){
          return $this->apiresponse(null,'validation error',$validator->errors(),400);
        }
        $categoryCourse = CategoryCourses::find($id);
        if($categoryCourse){
          $categoryCourse->update($request->all());
          return $this->apiresponse($categoryCourse,'category created successfully',201);
        }
        return $this->apiresponse(null,'category not found',404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CategoryCourses  $categoryCourses
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $categoryCourse = CategoryCourses::find($id);
        if($categoryCourse){
          $categoryCourse->delete();
          return $this->apiresponse(null,'category deleted successfully',201);
        }
        return $this->apiresponse(null,'category not found',404);
    }
}

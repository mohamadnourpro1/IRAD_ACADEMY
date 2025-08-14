<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseCollection;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
  use ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Courses = Course::with(['category','groups']);
        if($request->pagination!=null){
            $Courses = $Courses->paginate($request->pagination);
        }
        else{
            $Courses = $Courses->paginate(10);
        }
        return response(new CourseCollection($Courses));
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
            'category_id'=>'nullable|exists:category_courses,id',
        ]);
        if($validator->fails()){
          return $this->apiresponse(null,'validation error',$validator->errors(),400);
        }
        $course = Course::create($request->all());
        return $this->apiresponse($course,'course created successfully',201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Course = Course::with(['category','groups'])->find($id);
        if($Course){
          return $this->apiresponse(new CourseResource($Course),'course found',201);
        }
        return $this->apiresponse(null,'course not found',404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
          $validator = Validator::make($request->all(), [
            'name'=>'required|string',
            'category_id'=>'nullable|exists:category_courses,id',
        ]);
        if($validator->fails()){
          return $this->apiresponse(null,'validation error',$validator->errors(),400);
        }
        $course = Course::find($id);
        if($course){
          $course->update($request->all());
          return $this->apiresponse($course,'course updated successfully',201);
        }
        return $this->apiresponse(null,'course not found',404);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::find($id);
        if($course){
          $course->delete();
          return $this->apiresponse(null,'course deleted successfully',201);
        }
        return $this->apiresponse(null,'course not found',404);
    }
}

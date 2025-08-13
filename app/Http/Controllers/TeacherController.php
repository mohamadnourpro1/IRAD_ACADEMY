<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
  use ApiResponse;
  public function __construct()
    {
        $this->middleware('role:Admin')->only(['store','update','destroy','index','show']);;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teacher::all();
        return $this->apiresponse($teachers,'all teachers',200);
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
            'email'=>'email|unique:users,email',
            'password' => 'required|string|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'address' => 'nullable|string',
            'phone' => 'nullable|string'
        ]);
        if($validator->fails()){
          return $this->apiresponse(null,'validation error',$validator->errors(),400);
        }
        $profilePicturePath = null;
        if ($request->hasFile('profile_image')) {
            $profilePicturePath = $request->file('profile_image')->store('teachers/profile_images', 'public');
        }
        $teacher = Teacher::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => \Illuminate\Support\Facades\Hash::make($request->password),
          'address' => $request->address,
          'phone' => $request->phone,
          'profile_image' => $profilePicturePath,
          // سيتم ربط user_id تلقائياً في الموديل
      ]);
      return $this->apiresponse($teacher, 'teacher created successfully',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        if($teacher){
          return $this->apiresponse($teacher,'teacher details',200);
        }
        return $this->apiresponse(null,'teacher not found',404);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
        public function update(Request $request, $id)
{
    $teacher = Teacher::findOrFail($id); // البحث بالـ id

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|string',
        'email' => 'sometimes|required|email|unique:users,email,' . $teacher->user_id,
        'password' => 'nullable|string|min:8',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'address' => 'nullable|string',
        'phone' => 'nullable|string'
    ]);

    if ($validator->fails()) {
        return $this->apiresponse(null, 'validation error', $validator->errors(), 400);
    }

    $profilePicturePath = $teacher->profile_image;
    if ($request->hasFile('profile_image')) {
        $profilePicturePath = $request->file('profile_image')->store('teachers/profile_images', 'public');
    }

    $teacher->update([
        'name' => $request->name ?? $teacher->name,
        'email' => $request->email ?? $teacher->email,
        'password' => $request->password ? \Illuminate\Support\Facades\Hash::make($request->password) : $teacher->password,
        'address' => $request->address ?? $teacher->address,
        'phone' => $request->phone ?? $teacher->phone,
        'profile_image' => $profilePicturePath,
    ]);

    return $this->apiresponse($teacher, 'Teacher updated successfully', 200);
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    $teacher = Teacher::findOrFail($id); // البحث بالـ id
    $teacher->delete(); // يحذف معه الـ User تلقائياً من الـ boot()
    return $this->apiresponse(null, 'Teacher deleted successfully', 200);
}
}

<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
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
        $students = Student::all();
        return $this->apiresponse($students,'all students',200);
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
            $profilePicturePath = $request->file('profile_image')->store('students/profile_images', 'public');
        }
        $student = Student::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => \Illuminate\Support\Facades\Hash::make($request->password),
          'address' => $request->address,
          'phone' => $request->phone,
          'profile_image' => $profilePicturePath,
          // سيتم ربط user_id تلقائياً في الموديل
      ]);
      return $this->apiresponse($student, 'Student created successfully',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::findOrFail($id);
        if($student){
          return $this->apiresponse($student,'student details',200);
        }
        return $this->apiresponse(null,'student not found',404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
{
    $student = Student::findOrFail($id); // البحث بالـ id

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|string',
        'email' => 'sometimes|required|email|unique:users,email,' . $student->user_id,
        'password' => 'nullable|string|min:8',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'address' => 'nullable|string',
        'phone' => 'nullable|string'
    ]);

    if ($validator->fails()) {
        return $this->apiresponse(null, 'validation error', $validator->errors(), 400);
    }

    $profilePicturePath = $student->profile_image;
    if ($request->hasFile('profile_image')) {
        $profilePicturePath = $request->file('profile_image')->store('students/profile_images', 'public');
    }

    $student->update([
        'name' => $request->name ?? $student->name,
        'email' => $request->email ?? $student->email,
        'password' => $request->password ? \Illuminate\Support\Facades\Hash::make($request->password) : $student->password,
        'address' => $request->address ?? $student->address,
        'phone' => $request->phone ?? $student->phone,
        'profile_image' => $profilePicturePath,
    ]);

    return $this->apiresponse($student, 'Student updated successfully', 200);
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    $student = Student::findOrFail($id); // البحث بالـ id
    $student->delete(); // يحذف معه الـ User تلقائياً من الـ boot()
    return $this->apiresponse(null, 'Student deleted successfully', 200);
}

}

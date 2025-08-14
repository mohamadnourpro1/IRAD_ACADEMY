<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
  use ApiResponse;
  public function __construct()
    {
        $this->middleware('role:Admin')->only(['store','update','destroy','index','show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = Employee::all();
        return $this->apiresponse($employee,'all employees',200);
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
            'password' => 'required|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'address' => 'nullable|string',
            'phone' => 'nullable|string'
        ]);
        if($validator->fails()){
          return $this->apiresponse(null,'validation error',$validator->errors(),400);
        }
        $profilePicturePath = null;
        if ($request->hasFile('profile_image')) {
            $profilePicturePath = $request->file('profile_image')->store('employees/profile_images', 'public');
        }
        $employee = Employee::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => \Illuminate\Support\Facades\Hash::make($request->password),
          'address' => $request->address,
          'phone' => $request->phone,
          'profile_image' => $profilePicturePath,
          // سيتم ربط user_id تلقائياً في الموديل
      ]);
      return $this->apiresponse($employee, 'Employee created successfully',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);
        if($employee){
          return $this->apiresponse($employee,'employee details',200);
        }
        return $this->apiresponse(null,'employee not found',404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
        public function update(Request $request, $id)
{
    $employee = Employee::find($id); // البحث بالـ id

    $validator = Validator::make($request->all(), [
        'name' => 'sometimes|required|string',
        'email' => 'sometimes|required|email|unique:users,email,' . $employee->user_id,
        'password' => 'nullable|string|min:8',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'address' => 'nullable|string',
        'phone' => 'nullable|string'
    ]);

    if ($validator->fails()) {
        return $this->apiresponse(null, 'validation error', $validator->errors(), 400);
    }

    $profilePicturePath = $employee->profile_image;
    if ($request->hasFile('profile_image')) {
        $profilePicturePath = $request->file('profile_image')->store('employees/profile_images', 'public');
    }

    $employee->update([
        'name' => $request->name ?? $employee->name,
        'email' => $request->email ?? $employee->email,
        'password' => $request->password ? \Illuminate\Support\Facades\Hash::make($request->password) : $employee->password,
        'address' => $request->address ?? $employee->address,
        'phone' => $request->phone ?? $employee->phone,
        'profile_image' => $profilePicturePath,
    ]);

    return $this->apiresponse($employee, 'Employee updated successfully', 200);
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    $employee = Employee::find($id); // البحث بالـ id
    $employee->delete(); // يحذف معه الـ User تلقائياً من الـ boot()
    // $user = $employee->user;
    //         if ($user) {
    //             $user->delete();
    //         }
    return $this->apiresponse(null, 'Employee deleted successfully', 200);
}
}

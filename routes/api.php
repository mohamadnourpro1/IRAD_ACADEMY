<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post("login",[AuthController::class,"login"]);
// Route::post("register",[AuthController::class,"register"]);
Route::post("logout",[AuthController::class,"logout"])->middleware("auth:sanctum");
Route::post('forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('reset-password', [PasswordResetController::class, 'reset']);
Route::middleware("auth:sanctum")->group(function(){
//user
    Route::post('refresh-token', [AuthController::class, 'refreshToken']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
//roles
    // Route::get("roles",[RoleController::class,'index']);
    Route::apiResource("user_roles",UserRoleController::class);
    Route::get('roles',[UserRoleController::class,'show_roles']);
//employees
    Route::apiResource('employees',EmployeeController::class);
//teachers
    Route::apiResource('teachers',TeacherController::class);
//students
    Route::apiResource('students',StudentController::class);

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

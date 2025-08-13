<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //     public function register(Request $request)
    // {
    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);
    //     // create token
    //     $token = $user->createToken("personal access token")->plainTextToken;
    //     $user->token = $token;
    //     return response()->json(['user' => $user]);
    // }


        public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
                'role' => $user->getRoleNames()->last(),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
                'errors' => $e->errors()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function logout(Request $request){
        if ($request->user()->currentAccessToken()->delete()){
            return response()->json(['msg' => "You have been successfully logged out!"]);
        }
        return response()->json(['msg' => "some thing went wrong"]);
    }

    public function refreshToken(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            $token = $request->user()->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Token refreshed successfully',
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token refresh failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

      public function updateProfile(Request $request)
    {
        $user = $request->user();
        // تحقق من الصلاحية
        if (!$user->hasRole('Admin')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only Admin can update profile.'
            ], 403);
        }
        // التحقق من صحة البيانات
        if ($user->name !== 'admin' && $user->email !== 'admin@gmail.com') {
            return response()->json([
                'status' => 'error',
                'message' => 'Profile already updated.'
            ], 400);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}

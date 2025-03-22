<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

use App\Models\Admin;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required',
            'username' => 'required|string|max:255',
            'name'=>'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'pin' => 'required|string|min:6|confirmed',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $admin = Admin::create([
            'role_id' => $request->role_id,
            'username' => $request->username,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'pin' => $request->pin,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User Successfully Registered',
            'admin' => $admin
        ], 201);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Admin::where('username', $request->username)->first();

        if (!$user) {
            Log::error('Login Failed: Username Not Found', ['username' => $request->username]);
            return response()->json(['success' => false, 'message' => 'Username Not registered'], 401);
        }

        if(!Hash::check($request->password, $user->password)) {
            Log::error('Login Failed: Password Not Match', ['username' => $request->username]);
            return response()->json(['success' => false, 'message' => 'Password Not Match'], 401);
        }

        $credentials = $request->only('username', 'password');
        if(!$token = JWTAuth::attempt($credentials)){
            Log::error('Login gagal: JWTAuth gagal', ['username' => $request->username]);
            return response()->json(['success' => false, 'message' => 'Email atau Password salah'], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'user' => auth('api')->user(),
            'token' => $token
        ], 200);
    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function profile()
    {
        return response()->json(auth()->user());
    }
}




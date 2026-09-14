<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // 1. CUSTOMER REGISTER
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        $token = $user->createToken('nexora_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // 2. VENDOR REGISTER (extra store info ke saath)
    public function registerVendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'store_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'vendor',
        ]);

        Vendor::create([
            'user_id' => $user->id,
            'store_name' => $request->store_name,
            'store_slug' => Str::slug($request->store_name) . '-' . Str::random(5),
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'pending', // admin approve karega baad me
        ]);

        $token = $user->createToken('nexora_token')->plainTextToken;

        return response()->json([
            'message' => 'Vendor registration successful. Waiting for admin approval.',
            'user' => $user->load('vendor'),
            'token' => $token,
        ], 201);
    }

    // 3. LOGIN (Customer, Vendor, Admin - sabke liye common)
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('nexora_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user->load('vendor'),
            'token' => $token,
        ]);
    }

    // 4. LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // 5. GET LOGGED-IN USER INFO (React app load hote hi yeh call karega)
    public function me(Request $request)
    {
        return response()->json($request->user()->load('vendor'));
    }
}
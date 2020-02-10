<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiRegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            $result = [
                'Message' => $validator->errors()->toArray(),
            ];
            return response()->json($result, 400);
        }

        User::create([
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        return response()->json(['message' => 'success'], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $result = [
                'Message' => $validator->errors()->toArray(),
            ];
            return response()->json($result, 400);
        }

        $user = User::query()->where('email', $request['email'])->first();

        if ($user && Hash::check($request['password'], $user->password)) {
            $token = Str::random(10);
            User::query()->update(['api_token' => $token]);
            return response()->json(['data' => $user, 'token' => $token], 200);
        } else {
            return response()->json('fail', 200);
        }

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $out['status'] = 'success';
        $out['user'] = $user;
        $out['token'] = $token;
        return response($out, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        //check email
        $user = User::where('email', $fields['email'])->first();

        //check password
        if(!$user || !Hash::check($fields['password'], $user->password) )
        {
            return response([
                'message' => 'Invalid Credentials.'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $out['status'] = 'success';
        $out['user'] = $user;
        $out['token'] = $token;
        return response($out, 201);
    }

    public function logout(Request $request)
    {
        Auth()->user()->tokens()->delete();
        $out['status'] = 'Logged out';
        return response($out, 200);
    }
}

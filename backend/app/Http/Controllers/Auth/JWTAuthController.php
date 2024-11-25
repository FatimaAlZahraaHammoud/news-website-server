<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JWTAuthController extends Controller
{

    // method to register
    public function register(Request $request){
        $user = new User;
        $user->type_id = 1; 
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->date_of_birth = $request->date_of_birth;
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            "user" => $user, 
            "token" => $token
        ], 201);
    }

    // method to login
    public function login(Request $request){

    }
}

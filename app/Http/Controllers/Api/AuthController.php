<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
   public function signup(SignupRequest $request){
         $data= $request-> validated();
    /** @var \App\Models\User $user */
      $user= User::create([
        'name'=> $data['name'],
        'email'=> $data['email'],
        'password'=> bcrypt($data['password']),
      ]);
      $token = $user->createToken(name: 'main')->plainTextToken;

      return response(compact('user', 'token'));
    }

     public function login(LoginRequest $request){
     $credentials = $request->validated();
     if(!Auth::attempt($credentials)){
        return response([
            'message'=> 'Provided email address or password is incorrect'
        ]);
     }
     /** @var User $user */
     $user=Auth::user();
     $token= $user->createToken('main')->plainTextToken;
    }

    public function logout(Request $request){
        /** @var User $user */
        $user= $request->user();
        $user->currentAccessToken()->delete();
        return response('', 204);
    }
}

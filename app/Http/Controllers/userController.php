<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AuthUserRequest;
use App\Http\Requests\StoreUserRequest;

class userController extends Controller
{
    public function store(StoreUserRequest $request){

        if($request->validated()){
          $user=  User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=>Hash::make($request->password) ,
            ]);

            return response()->json([
                'user'=>$user,
                'token'=>$user->createToken('tkn')->plainTextToken
                ]);
        }
    }


  public function auth( AuthUserRequest $request ){

    $user = User::whereEmail($request->email)->first();

    if(!$user || !Hash::check($request->password,$user->password)){
        return response()->json([
            'error'=>'invalid credentials'
            ]);
    }else{
        return response()->json([
            'user'=>$user,
            'token'=>$user->createToken('tkn')->plainTextToken
            ]);
    }
  }


  public function logout( Request $request ){

     $request->user()->currentAccessToken()->delete();
     return response()->json([
        'token'=>"logged out"
        ]);

  }


}



<?php

namespace App\Http\Controllers;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\userData;

class AuthController extends Controller
{
    public function register (Request $request){

        //dd($request);
        $data = $request->validate([
            "name"=> 'required|max:255',
            "email"=>'required|max:255|email|unique:users',
            "password"=>'required|min:4|confirmed'
        ]);


        $user = User::Create($data);


        Auth::login($user);

        // Auth::user()->userData()->create([
        //  'address'=>$data['email'],
        //  'phone'=>$data['email']
        // ]);

        return redirect()->route('home');


    }


    public function logout ( Request $request ){
   // dd($request);
   Auth::logout();
   $request->session()->invalidate();
   $request->session()->regenerateToken();

   return redirect()->route('home');

    }

    public function login (Request $request){

        //dd($request);
        $data = $request->validate([

            "email"=>'required|max:255|email',
            "password"=>'required'
        ]);


       if(Auth::attempt($data,$request->remember)){
      return redirect()->intended('dashboard');
       }else{

return back()->withErrors([
    'er' => " Invalid email or password "
]);
       }

        $user = User::Create($data);
        Auth::login($user);
        return redirect()->route('home');


    }
}

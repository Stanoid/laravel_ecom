<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class DashboardController extends Controller
{


    // public static function middleware()
    // {
    //  return [
    //     'auth',

    //  ];
    // }


public function index (){
    //$posts = Post::where('user_id',Auth::id())->get() ;
 $posts =  Auth::user()->posts()->latest()->paginate(6);
//     dd($posts);
    return View('users.dashboard',['posts'=>$posts]);

}


public function userPosts(User $user){

        return view('users.posts',['posts'=>$user->posts()->latest()->paginate(6),'user'=>$user]);
}



}

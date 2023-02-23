<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    public function AuthIdentity(Request $request){
        $user = new User();
        $userData = Auth::user();
        $test = Auth::login($user);

        $password = $request->input('password');
        $user->chgPassWord($password);
    }
}

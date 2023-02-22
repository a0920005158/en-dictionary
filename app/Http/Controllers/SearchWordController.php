<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class SearchWordController extends Controller
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
        echo '123';
    }

    public function searchWord(Request $request){
        $user = new User();
        $post = new Post();
        $post->search();
        $user->search();
    }
}

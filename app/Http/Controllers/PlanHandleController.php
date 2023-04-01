<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Session;
class PlanHandleController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'msg' => 'required|max:255',
            'userId' => 'required|max:255'
        ]);

        $sendMsg = $request->input("msg");
        $userId = $request->input("userId");

        return response()->json($result);
    }
}

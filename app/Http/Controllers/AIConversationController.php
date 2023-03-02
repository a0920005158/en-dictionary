<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class AIConversationController extends BaseController
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

    public function sendMsg(Request $request)
    {
        $sendMsg = $request->input("msg");
        $this->validate($request, [
            'msg' => 'required|max:255'
        ]);



        $result = $this->chatGpt($sendMsg);
        return response()->json($result);
    }
}

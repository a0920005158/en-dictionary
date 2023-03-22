<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Session;
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
        $this->validate($request, [
            'msg' => 'required|max:255',
            'userId' => 'required|max:255'
        ]);

        $sendMsg = $request->input("msg");
        $userId = $request->input("userId");
        $msgArr = [];

        $msgRecordArr = $request->session()->pull('msgRecord', '');

        if ($msgRecordArr != '') {
            $recordLg = count($msgRecordArr);
            if ($recordLg > 0) {
                for ($i = 3; $i > 0; $i--) {
                    if ($recordLg - $i >= 0) {
                        $RR = explode(":%", $msgRecordArr[$recordLg - $i]);
                        $msgArr[] = array(
                            "role" => $RR[0],
                            "content" => $RR[1]
                        );
                        $request->session()->push('msgRecord', $msgRecordArr[$recordLg - $i]);
                    }
                }
            }
        }

        $msgArr[] = array(
            "role" => "user",
            "content" => $sendMsg
        );

        $result = $this->chatGpt($msgArr, $userId);

        $result["msgArr"] = $msgArr;
        if (array_key_exists("choices",$result) && count($result["choices"]) > 0) {
            $assistantResponse = $result["choices"][0]["message"]["content"];
            $assistantRole = $result["choices"][0]["message"]["role"];
            $request->session()->push(
                "msgRecord",
                "user:%" . $sendMsg
            );
            $request->session()->push(
                'msgRecord',
                $assistantRole . ":%" . $assistantResponse
            );
        }

        return response()->json($result);
    }
}

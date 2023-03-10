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

        // $sss= $request->session()->get('msgRecord');
        $test = $request->session()->get('test',"nonono");
        // $msgRecordArr = $request->session()->pull('msgRecord', '');

        // echo "========<br>";
        // print_r($msgRecordArr);
        // echo "ssssssssssss<br>";
        // print_r($sss);
        // echo "test<br>";
        // print_r($test);


        // $request->session()->push('msgRecord', 'develop');
        // $request->session()->push('msgRecord', 'staging');
        // $request->session()->push('msgRecord', 'production');
        $request->session()->put("test","test2222");
        $test2 = $request->session()->get('test',"nonono");
        print_r($test2);
        $request->session()->save();
        return response()->json($test);
        // if ($msgRecordArr != '') {
        //     echo 'bbb';
        //     $recordLg = count($msgRecordArr);
        //     if ($recordLg > 0) {
        //         for ($i = 4; $i > 0; $i--) {
        //             if ($recordLg - $i >= 0) {
        //                 echo '====';
        //                 $RR = explode(":%", $msgRecordArr[$recordLg - $i]);
        //                 $msgArr[] = array(
        //                     "role" => $RR[0],
        //                     "content" => $RR[1]
        //                 );
        //                 $request->session()->push('msgRecord', $msgRecordArr[$recordLg - $i]);
        //             }
        //         }
        //     }
        // }else{
        //     echo 'aaa';
        // }

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
            // $result["session"] = $request->session()->get('msgRecord');
        }

        return response()->json($result);
    }
}

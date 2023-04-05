<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Session;
use App\Models\PlanRecord;
use Google_Client;
use Google_Exception;

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

    private $acc = "";
    public function handleGoogleLogin($idToken)
    {
        try {
            $client = new Google_Client(['client_id' => 'api-project-1026776228005']);
            $payload = $client->verifyIdToken($idToken);

            if ($payload) {
                // 驗證成功，取得使用者的資訊
                $userId = $payload['sub'];
                $email = $payload['email'];
                $name = $payload['name'];
                $this->acc = $name;
                return true;
            } else {
                // 驗證失敗
                return false;
            }
        } catch (Google_Exception $e) {
            // 發生例外
            return false;
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'idToken' => 'required|max:255',
            'title' => 'required|max:255',
            'context' => 'required'
        ]);

        $idToken = $request->input('idToken');
        $title = $request->input("title");
        $context = $request->input("context");
        $auth = $this->handleGoogleLogin($idToken);

        if ($auth) {
            $pr = new PlanRecord();
            $pr->token = $idToken;
            $pr->acc = $this->acc;
            $pr->title = $title;
            $pr->context = $context;
            $pr->isOp = false;
            $pr->save();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}

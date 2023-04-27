<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Session;
use App\Models\PlanRecord;
use App\Models\PlanComment;
use Google_Client;
use Google_Exception;

class PlanHandleController extends BaseController
{
    private $acc = "";
    private $email = "";
    public function handleGoogleLogin($idToken)
    {
        try {
            // $client = new Google_Client(['client_id' => '1084414905578-pknhub4mtdsq0jj57smh92i9ph025a8q.apps.googleusercontent.com']);
            $client = new Google_Client();
            $client->setClientId("1084414905578-pknhub4mtdsq0jj57smh92i9ph025a8q.apps.googleusercontent.com");
            $client->setClientSecret("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");
            $payload = $client->verifyIdToken($idToken);

            if ($payload) {
                // 驗證成功，取得使用者的資訊
                $userId = $payload['sub'];
                $email = $payload['email'];
                $name = $payload['name'];
                $this->acc = $name;
                $this->email = $email;
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
        $res = new Response;
        $this->validate($request, [
            'idToken' => 'required',
            'title' => 'required',
            'context' => 'required'
        ]);

        $idToken = $request->input('idToken');
        $title = $request->input("title");
        $context = $request->input("context");
        $auth = $this->handleGoogleLogin($idToken);

        if ($auth) {
            $pr = new PlanRecord();
            $pr->email = $this->email;
            $pr->acc = $this->acc;
            $pr->title = $title;
            $pr->context = $context;
            $pr->isOp = false;
            $pr->save();
            return response()->json(['errorCode' => 0, 'errorMsg' => '']);
        } else {
            $res->errorCode = 1;
            $res->errorMsg = "發生錯誤!";
        }

        return response()->json($res);
    }

    public function modify(Request $request)
    {
        $res = new Response;
        $this->validate($request, [
            'idToken' => 'required',
            'pid' => 'required',
            'title' => 'required',
            'context' => 'required'
        ]);

        $pid = $request->input("pid");
        $idToken = $request->input('idToken');
        $title = $request->input("title");
        $context = $request->input("context");
        $auth = $this->handleGoogleLogin($idToken);

        if ($auth) {
            $pr = new PlanRecord();
            $prQuery = $pr->where("id", $pid)->get()->first();
            if ($prQuery) {
                $prQuery->title = $title;
                $prQuery->context = $context;
                $prQuery->save();
                return response()->json(['errorCode' => 0, 'errorMsg' => '']);
            }
        }

        $res->errorCode = 1;
        $res->errorMsg = "發生錯誤!";

        return response()->json($res);
    }

    public function getMemPlan(Request $request)
    {
        $res = new Response;
        $this->validate($request, [
            'idToken' => 'required'
        ]);

        $idToken = $request->input('idToken');
        $pageIndex = $request->input('pg');
        if (!isset($pageIndex))
            $pageIndex = 0;

        $auth = $this->handleGoogleLogin($idToken);

        if ($auth) {
            $res->result = new MemPlanList;
            $pr = new PlanRecord();
            $memPlan = $pr->where("email", $this->email)->orderBy("created_at", "desc")->paginate(20, ['*'], '__p', $pageIndex);
            $res->result->List = $memPlan->items();
            $res->result->pg = new Response_pg($memPlan->currentPage(), $memPlan->total(), $memPlan->lastPage());
        } else {
            $res->errorCode = 1;
            $res->errorMsg = "發生錯誤!";
        }

        return response()->json($res);
    }

    public function releaseMemPlan(Request $request)
    {
        $res = new Response;
        $this->validate($request, [
            'idToken' => 'required',
            'pid' => 'required|numeric',
        ]);

        $idToken = $request->input('idToken');
        $pid = $request->input('pid');

        $auth = $this->handleGoogleLogin($idToken);

        if ($auth) {
            $pr = new PlanRecord();
            $memPlan = $pr->where("email", $this->email)->where("id", $pid)->get()->first();
            if ($memPlan) {
                $memPlan->isOp = !$memPlan->isOp;
                $memPlan->save();

                return response()->json($res);
            }
        }

        $res->errorCode = 1;
        $res->errorMsg = "發生錯誤!";

        return response()->json($res);
    }

    public function deleteMemPlan(Request $request)
    {
        $res = new Response;
        $this->validate($request, [
            'idToken' => 'required',
            'pid' => 'required|numeric',
        ]);

        $idToken = $request->input('idToken');
        $pid = $request->input('pid');

        $auth = $this->handleGoogleLogin($idToken);

        if ($auth) {
            $pr = new PlanRecord();
            $memPlan = $pr->where("email", $this->email)->where("id", $pid)->get()->first();
            if ($memPlan) {
                $memPlan->delete();

                return response()->json($res);
            }
        }

        $res->errorCode = 1;
        $res->errorMsg = "發生錯誤!";

        return response()->json($res);
    }

    public function getAllPlan(Request $request)
    {
        $pageIndex = $request->input('pg');
        if (!isset($pageIndex))
            $pageIndex = 0;

        $res = new Response;


        $res->result = new MemPlanList;
        $pr = new PlanRecord();
        $memPlan = $pr->where("isOp", 1)->orderBy("updated_at", "desc")->paginate(20, ['*'], '__p', $pageIndex);
        foreach ($memPlan->items() as $value) {
            $pr = new PlanComment();
            $value["stars"] = $pr->where("pid", $value->id)->avg('stars');;
        }
        $res->result->List = $memPlan->items();
        $res->result->pg = new Response_pg($memPlan->currentPage(), $memPlan->total(), $memPlan->lastPage());

        return response()->json($res);
    }

    public function comment(Request $request)
    {
        $res = new Response;
        $this->validate($request, [
            'idToken' => 'required',
            'pid' => 'required',
            'comment' => 'required',
            'stars' => 'required'
        ]);

        $pid = $request->input("pid");
        $idToken = $request->input('idToken');
        $comment = $request->input("comment");
        $stars = $request->input("stars");
        $auth = $this->handleGoogleLogin($idToken);

        if ($auth) {
            $pr = new PlanRecord();
            $prQuery = $pr->where("id", $pid)->get()->first();
            if ($prQuery) {
                $pc = new PlanComment();
                $pc->pid = $pid;
                $pc->comment = $comment;
                $pc->stars = $stars;
                $pc->comment_acc = $this->acc;
                $pc->comment_email = $this->email;
                $pc->save();
                return response()->json(['errorCode' => 0, 'errorMsg' => '']);
            }
        }

        $res->errorCode = 1;
        $res->errorMsg = "發生錯誤!";

        return response()->json($res);
    }

    public function getComment(Request $request)
    {
        $res = new Response;
        $this->validate($request, [
            'idToken' => 'required',
            'pid' => 'required'
        ]);

        $pid = $request->input("pid");
        $idToken = $request->input('idToken');
        $auth = $this->handleGoogleLogin($idToken);

        if ($auth) {
            $pr = new PlanComment();
            $prQuery = $pr->where("pid", $pid)->where("comment_email", $this->email)->get()->first();
            $comment = null;
            if ($prQuery) {
                $comment = new Comment();
                $comment->comment = $prQuery->comment;
                $comment->stars = $prQuery->stars;
                $comment->acc = $prQuery->comment_acc;
            }
            return response()->json(['errorCode' => 0, 'errorMsg' => '', 'result' => $comment]);
        }

        $res->result = null;
        $res->errorCode = 1;
        $res->errorMsg = "發生錯誤!";

        return response()->json($res);
    }

    public function getAllComment(Request $request)
    {
        $res = new Response;
        $this->validate($request, [
            'pid' => 'required'
        ]);
        $pid = $request->input("pid");
        $pageIndex = $request->input('pg');
        if (!isset($pageIndex))
            $pageIndex = 0;
        $pc = new PlanComment();
        $res->result = new MemPlanList;
        $memComment = $pc->where("pid", $pid)->orderBy("created_at", "desc")->paginate(3, ['*'], '__p', $pageIndex);
        $res->result->List = $memComment->items();
        $res->result->pg = new Response_pg($memComment->currentPage(), $memComment->total(), $memComment->lastPage());

        return response()->json($res);
    }
}

class Response
{
    public $errorCode = 0;
    public $errorMsg = "";
    public $result;
}

class MemPlanList
{
    public $List = array();
    public $pg;
}

class Response_pg
{
    public $current;
    public $amount;
    public $pagesize;
    public function __construct($current, $amount, $pagesize)
    {
        $this->current = $current;
        $this->amount = $amount;
        $this->pagesize = $pagesize;
    }
}

class Comment
{
    public $comment;
    public $stars;
    public $acc;
}

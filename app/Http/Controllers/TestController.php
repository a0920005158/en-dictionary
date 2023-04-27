<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanRecord;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test(Request $request){
        DB::beginTransaction();
        $pr = new PlanRecord();
        $pr->email = "test123";
        $pr->acc = "test123";
        $pr->title = "test123";
        $pr->context = "test123";
        $pr->isOp = false;
        $pr->save();
        $tt = PlanRecord::where("email","test123")->get()->first();
        print_r($tt);
        sleep(10);
        DB::commit();
    }
}

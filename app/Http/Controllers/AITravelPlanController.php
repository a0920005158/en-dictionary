<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Session;

class AITravelPlanController extends BaseController
{
    public function plan(Request $request)
    {
        $this->validate($request, [
            'attractions' => 'required',
            // 'hotel' => 'required',
            'food' => 'required',
            'trans' => 'required',
            // 'time' => 'required|max:50',
        ]);

        $attractions = $request->input("attractions");
        // $hotel = $request->input("hotel");
        $food = $request->input("food");
        $trans = $request->input("trans");
        // $time = $request->input("time");

        $txt = "以下景點、美食依" . $trans . "方式路線規劃一日行程`。";
        $txt .= "景點:" . $attractions . "。";
        $txt .= "美食:" . $food . "。";
        // $txt = "以下資訊隨機產生" . $time . "行程。\r\n";
        // $txt .= "景點:".$attractions."。";
        // $txt .= "旅店:".$hotel."。";
        // $txt .= "美食:".$food."。";
        $result = $this->chatGptEdits($txt);

        return response()->json($result);
    }
}

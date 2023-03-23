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
            'hotel' => 'required',
            'food' => 'required',
            'time' => 'required|max:50',
        ]);

        $attractions = $request->input("attractions");
        $hotel = $request->input("hotel");
        $food = $request->input("food");
        $time = $request->input("time");

        $txt = "扮演旅遊達人依下方景點、旅店、美食隨機挑選，產生" . $time . "行程。\r\n";
        $txt .= "景點:".$attractions."。\r\n";
        $txt .= "旅店:".$hotel."。\r\n";
        $txt .= "美食:".$food."。";
        $result = $this->chatGptEdits($txt);

        return response()->json($result);
    }
}

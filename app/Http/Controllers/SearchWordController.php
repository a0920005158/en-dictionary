<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WordDictionary;
use App\Http\Controllers\BaseController;

class SearchWordController extends BaseController
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

    public function searchWord(Request $request)
    {
        $this->validate($request, [
            'en' => 'required|unique:word-dictionary|max:255'
        ]);

        $searchEn = $request->input("en");

        $existWordData = WordDictionary::searchWord($searchEn);
        if ($existWordData != null) {
        } else {
            $test = $this->generateSituation(["author", "exclusive", "motion"], 200);
            // $test2 = $this->generateAIImg($searchEn, 1, "1024x1024");
            return response()->json($test);
        }
    }
}

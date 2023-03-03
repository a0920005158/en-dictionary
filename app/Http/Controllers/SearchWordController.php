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

    private $url = "https://api.wordnik.com/v4/word.json/";
    private $Wordnik_API_Key = "nxf8br2c6444ehxws2yz63o7tv2l6hiptwa1rjs6wonhizkqg";

    public function searchWord(Request $request)
    {
        $this->validate($request, [
            'en' => 'required|unique:word-dictionary|max:255'
        ]);

        $searchEn = $request->input("en");

        $existWordData = WordDictionary::searchWord($searchEn);
        if ($existWordData != null) {
            return response()->json();
        } else {
            // $test = $this->chatGpt($searchEn);
            // $test = $this->generateSituation(["author", "exclusive", "motion"], 200);
            // $test2 = $this->generateAIImg($searchEn, 1, "1024x1024");

            $url = $this->url + $searchEn + "/definitions";
            $useCanonical = "?useCanonical=false";
            $limitCount = "&limit=5";
            $apiKey = "&api_key=" + $this->Wordnik_API_Key;
            $re = $this->iCURL($url . $useCanonical . $limitCount . $apiKey, [], $header);

            return response()->json($re);
        }
    }
}

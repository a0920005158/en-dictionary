<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nette\Utils\Arrays;

class BaseController extends Controller
{
    function iCURL(string $url, array $params = array(), &$header)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);
        curl_close($ch);
        return $body;
    }

    public $API_KEY = "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";

    public function getOpenAIModel()
    {
        $url = "https://api.openai.com/v1/models";
        $curl_h = curl_init($url);

        curl_setopt(
            $curl_h,
            CURLOPT_HTTPHEADER,
            array(
                'Authorization: Bearer ' . $this->API_KEY
            )
        );
        curl_setopt($curl_h, CURLOPT_RETURNTRANSFER, true);

        $re = curl_exec($curl_h);

        return json_decode($re, true);
    }

    public function generateAIImg($text, $count, $size)
    {
        $url = "https://api.openai.com/v1/images/generations";
        $curl_h = curl_init($url);
        $arr = array();
        $arr["prompt"] = $text;
        $arr["n"] =  $count;
        $arr["size"] =  $size;
        $jsonData = json_encode($arr);

        curl_setopt(
            $curl_h,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->API_KEY
            )
        );
        curl_setopt($curl_h, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl_h, CURLOPT_RETURNTRANSFER, true);

        $re = curl_exec($curl_h);

        return json_decode($re, true);
    }

    public function generateSituation(array $wordArr, int $count)
    {
        $result = array();
        foreach ($wordArr as $key => $value) {
            if ($value) {
            } else {
                array_splice($wordArr, $key);
            }
        }
        $txt = "請用" . implode("、", $wordArr) . "這些單字產生總字數" . $count . "內能用圖片敘述的情境，每段對應的圖片內容分段顯示，並且使用到的單字用中括號標記";
        if (count($wordArr) > 0) {
            $result = $this->chatGpt($txt);
        } else {
        }
        return $result;
    }

    public function chatGpt($msg, $userId = "")
    {
        $url = "https://api.openai.com/v1/chat/completions";
        $curl_h = curl_init($url);

        $arr = array(
            "max_tokens" => 150,
            "model" => "gpt-3.5-turbo",
            "messages" => $msg,
            "user" => $userId
        );

        $jsonData = json_encode($arr);

        curl_setopt(
            $curl_h,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->API_KEY
            )
        );
        curl_setopt($curl_h, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl_h, CURLOPT_RETURNTRANSFER, true);

        $re = curl_exec($curl_h);

        return json_decode($re, true);
    }

    public function chatGptEdits($msg)
    {
        $url = "https://api.openai.com/v1/completions";
        $curl_h = curl_init($url);

        $arr = array(
            "model" => "text-davinci-003",
            "prompt" => "hello",
            "temperature" => 0,
            "max_tokens" => 4096,
        );

        $jsonData = json_encode($arr);

        curl_setopt(
            $curl_h,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->API_KEY
            )
        );
        curl_setopt($curl_h, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl_h, CURLOPT_RETURNTRANSFER, true);

        $re = curl_exec($curl_h);

        return json_decode($re, true);
    }


    public function chatGptSearch($search)
    {
        $url = "https://api.openai.com/v1/chat/completions";
        $curl_h = curl_init($url);

        $arr = array(
            "model" => "text-davinci-003",
            "prompt" => $search,
            "temperature" => 0.5,
            "max_tokens" => 200,
            "top_p" => 1.0,
            "frequency_penalty" => 0.5,
            "presence_penalty" => 0.0,
        );

        $jsonData = json_encode($arr);

        curl_setopt(
            $curl_h,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->API_KEY
            )
        );
        curl_setopt($curl_h, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl_h, CURLOPT_RETURNTRANSFER, true);

        $re = curl_exec($curl_h);

        return json_decode($re, true);
    }
}

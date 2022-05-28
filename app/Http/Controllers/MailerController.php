<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailerController extends Controller
{
    public function mailer(Request $request) {
        $ch = curl_init();
        $type = 1;
        $id = 1;
        $opcode = 2;
        $url = "http://localhost:3000/mailer/request/" . $type . "/" . $id . "/" . $opcode;
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        $req = curl_exec($ch);
        if(curl_errno($ch)) {
            \Log::error('Error while trying to get data.');
            \Log::error(curl_errno($ch));
            \Log::error(curl_error($ch));
        } else {
            if($req) {
                $result = json_decode($this->purifyJSON($req));
                \Log::info($result->msg);
                dd($result->msg);
            }
        }
        curl_close($ch);
    }

    public function purifyJSON($json) {
        if(substr($json, 0, 3) == pack("CCC", 0xEF, 0xBB, 0xBF)) {
            $json = substr($json, 3);
        }
        return $json;
    }
}

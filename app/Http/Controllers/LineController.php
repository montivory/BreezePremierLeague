<?php

namespace App\Http\Controllers;

use Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class LineController extends Controller
{
    public function auth(Request $request)
    {
        $lineResult = $this->lineAuth($request->code);
        if (!$lineResult->status) {
            // $error = $lineResult->response->error;
            // $error_description = $lineResult->response->error_description;
            return Redirect::route('home')->with('error', $lineResult->error);
        }
        $result = lineSignin($lineResult->response);
        if (!$result) {
            return Redirect::route('signup');
        }
        return Redirect::route('member');
    }

    public function lineAuth($code)
    {
        $responseAuth = Http::asForm()->post('https://api.line.me/oauth2/v2.1/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => config('app.line_login_clientid'),
            'client_secret' => config('app.line_login_secret'),
            'redirect_uri' => config('app.line_login_callback_url'),
        ]);
        $result = new \stdClass;
        $result->status = true;
        if ($responseAuth->failed()) {
            $result->status = false;
            $result->error = Lang::get('line.error');
            // $result->response = $responseAuth->object();
            return $result;
        }
        $response = Http::asForm()->post('https://api.line.me/oauth2/v2.1/verify', [
            'id_token' => $responseAuth->json('id_token'),
            'client_id' => config('app.line_login_clientid'),
        ]);
        // {"iss":"https://access.line.me","sub":"U6d5e36ad7e7d8370e1ee11c9c637aa8a","aud":"2000368336","exp":1691664343,"iat":1691660743,"amr":["linesso"],"name":"Piyawat","picture":"https://profile.line-scdn.net/0ho0cATOtEMB9eOCemOR9PSGJ9PnIpFjZXJlctLnM_aHwnXSBNMF8ofChobSt7WCVBa1Z6eS86bC8k"}
        //sub = userId

        // {
        //     "status": true,
        //     "response": {
        //       "iss": "https://access.line.me",
        //       "sub": "U5c16e21d75f2080a588b2bf6af33dc28",
        //       "aud": "1451948430",
        //       "exp": 1712042929,
        //       "iat": 1712039329,
        //       "amr": [
        //         "linesso"
        //       ],
        //       "name": "Piyawat",
        //       "picture": "https://profile.line-scdn.net/0ho0cATOtEMB9eOCemOR9PSGJ9PnIpFjZXJlctLnM_aHwnXSBNMF8ofChobSt7WCVBa1Z6eS86bC8k",
        //       "phone_number": "+66991914858",
        //       "email": "arunwatd@gmail.com"
        //     }
        //   }

        if ($response->failed()) {
            $result->status = false;
            $result->error = Lang::get('line.error');
            return $result;
        }

        $dataObj = $response->object();
        if (!isset($dataObj->sub) || strlen(trim($dataObj->sub)) == 0 || !isset($dataObj->name) || strlen(trim($dataObj->name)) == 0) {
            $result->status = false;
            $result->error = Lang::get('line.error');
            return $result;
        }
        $result->response = $dataObj;
        return $result;
    }
}

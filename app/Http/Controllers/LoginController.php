<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function login()
    {
//        $grant_type = 'authorization_code';
//        $code = '1234567890abcde'
//        $redirect_uri = 'http://127.0.0.1:8000/callback';
//        $client_id = '1657099272'
        $client_secret = '45904d2ef751e3ca51c19604befccf23';
//        $code_verifier = 'wJKN8qz5t8SSI9lMFhBB6qwNkQBkuPZoCxzRhwLRUo1';

//        $scope = 'profile%20openid%20email';
        $scope = 'profile%20openid%20email';
        return Redirect::to('https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id=1657099272&redirect_uri=http://127.0.0.1:8000/callback&state=12345abcde&scope='.$scope);
//        return Redirect::to('https://access.line.me/oauth2/v2.1/login?loginState=0bY6sKNNxWj2jQ01HQRJi6&loginChannelId=1657099272&returnUri=%2Foauth2%2Fv2.1%2Fauthorize%2Fconsent%3Fscope%3Dopenid%2Bprofile%2Breal_name%2Bgender%2Bbirthdate%2Bphone%2Baddress%26response_type%3Dcode%26redirect_uri%3Dhttp%253A%252F%252F127.0.0.1%253A8000%252Fcallback%26state%3D12345abcde%26nonce%3D0987654asd%26client_id%3D1657099272#/');
//        return Socialite::driver('line-login')->with([
//            'prompt' => 'consent',
//            'bot_prompt' => 'normal',
//        ])->redirect();
    }

    public function callback(Request $request)
    {
//        $apiURL = 'https://api.line.me/oauth2/v2.1/token';
//        $postInput = [
//            'grant_type' => 'authorization_code',
//            'client_id' => '1657099272',
//            'code' => $request->code,
//            'redirect_uri' => 'http://127.0.0.1:8000/callback',
//            'client_secret' => 'e1e1ad0b14e2d36074785be665b2d9e8'
//        ];
//        $headers = [
//            'Content-Type' => 'application/x-www-form-urlencoded'
//        ];

//        $response = Http::withHeaders($headers)->post($apiURL, $postInput);

//        $statusCode = $response->status();
//        $responseBody = json_decode($response->getBody(), true);

//        dd($responseBody);
//        return $request->code;

//        $apiURL = 'https://api.line.me/oauth2/v2.1/token';
//        $postInput = [
//            'grant_type' => 'authorization_code',
//            'client_id' => '1657099272',
//            'code' => $request->code,
//            'redirect_uri' => 'http://127.0.0.1:8000/callback',
//            'client_secret' => 'e1e1ad0b14e2d36074785be665b2d9e8'
//        ];
//
//        $client = new \GuzzleHttp\Client();
//        $response = $client->request('POST', $apiURL, ['form_params' => $postInput]);
//
//        $statusCode = $response->getStatusCode();
//        $responseBody = json_decode($response->getBody(), true);
//
//        dd($responseBody);
//        -------------------------------------------------------------------------------
//        CURL
//        -------------------------------------------------------------------------------


//        $curl = curl_init();
////
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => 'https://api.line.me/oauth2/v2.1/token',
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => '',
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => 'POST',
//            CURLOPT_POSTFIELDS => 'grant_type=authorization_code&client_id=1657099272&code='.$request->code.'&redirect_uri=http%3A%2F%2F127.0.0.1%3A8000%2Fcallback&client_secret=e1e1ad0b14e2d36074785be665b2d9e8',
//            CURLOPT_HTTPHEADER => array(
//                'Content-Type: application/x-www-form-urlencoded'
//            ),
//        ));
////
//        $response = curl_exec($curl);
////
//        curl_close($curl);
//        echo $response;
//        exit();
//        ========================================================================================
//        return $request->all();
        $code = $request->code;
        $state = $request->state;
        $response = Http::
//        withOptions([
//            'debug' => true,
//        ])->
        withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->
        asForm()->post('https://api.line.me/oauth2/v2.1/token', [
            'grant_type' => 'authorization_code',
            'client_id' => '1657099272',
            'code' => $code,
            'redirect_uri' => 'http://127.0.0.1:8000/callback',
            'client_secret' => '45904d2ef751e3ca51c19604befccf23'
        ]);
//        return $response->json();
        if ($request->missing('code')) {
            dd($request);
        }
//        store token in session
        $request->session()->put('token', $response->json());
//        GET https://api.line.me/v2/profile
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $response->json()['access_token']
        ])->get('https://api.line.me/v2/profile');
        return $response->json();

        /**
         * @var \Laravel\Socialite\Two\User
         */
//        $user = Socialite::driver('line-login')->user();

//        $loginUser = User::updateOrCreate([
//            'line_id' => $user->id,
//        ], [
//            'name' => 'User', //$user->nickname,
//            'avatar' => $user->avatar,
//            'access_token' => $user->token,
//            'refresh_token' => $user->refreshToken,
//        ]);

//        auth()->login($loginUser, true);

//        return redirect(RouteServiceProvider::HOME);
    }

//    test token
    public function test_token(Request $request)
    {
        $token = $request->session()->get('token');
        return $token;
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get('https://api.line.me/v2/profile');
        $data = json_decode($response, true);
        $data['token'] = $token;
        $json = json_encode($data);
        return json_decode($json);
//        $object = json_decode($response);
//        $object->token = $token;
//        $response = json_encode($object);
//        return $json;
//        return array_push($response, $token);
//        $response->token = $token;
//        return $response->json();
    }

    public function logout()
    {
        auth()->logout();

        return redirect('/');
    }
}

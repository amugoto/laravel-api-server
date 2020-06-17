<?php

namespace App\Http\Controllers\Social;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class KakaoController extends Controller
{

    public function getAuthToken(Request $request)
    {
        $response = Http::asForm()->post('https://kauth.kakao.com/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.kakao.appKey'),
            'redirect_uri' => config('services.kakao.redirect_uri'),
            'code' => $request->code
        ]);

        $resData = $response->json();
        if ($response->ok()) {
            $info = $this->getUserInfo($resData['access_token']);

            User::updateOrCreate(
                [
                    'id' => $info['id']
                ],
                [
                    'name' => $info['properties']['nickname'],
                    'platform' => 'kakao',
                    'access_token' => $resData['access_token'],
                    'refresh_token' => $resData['refresh_token'],
                    'refresh_token_expires_in' => $resData['refresh_token_expires_in']
                ]
            );

            $returnData = [
                'access_token' => $resData['access_token']
            ];

            return response($returnData, 200);
        }

        return response($resData, $response->status());
    }

    public function getUserInfo(string $accessToekn)
    {
        $userInfo = Http::withToken($accessToekn)->get('https://kapi.kakao.com/v2/user/me');
        return $userInfo->json();
    }
}

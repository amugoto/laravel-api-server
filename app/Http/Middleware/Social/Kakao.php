<?php

namespace App\Http\Middleware\Social;

use Closure;
use Illuminate\Support\Facades\Http;

class Kakao implements SocialInterface
{
    public function accessTokenValidation(string $accessToken)
    {
        $response = Http::withToken($accessToken)->get('https://kapi.kakao.com/v1/user/access_token_info');
        $resData = $response->json();

        $isRefresh = empty($resData) ? false : (array_key_exists('code', $resData) && $resData['code'] === -401);

        return [
            'status' => $response->ok(),
            'isRefresh' => $isRefresh,
            'data' => $resData
        ];
    }

    public function refreshAccessToken($refreshToken)
    {
        $response = Http::asForm()->post('https://kauth.kakao.com/oauth/token', [
            'grant_type' => 'refresh_token',
            'client_id'  => config('services.kakao.appKey'),
            'refresh_token' => $refreshToken
        ]);

        return $response->json();
    }
}

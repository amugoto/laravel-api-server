<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;
use App\User;
use App\Http\Middleware\Social\Kakao;

class SocialAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth = User::where('access_token', $request->bearerToken())->first();
        if (empty($auth)) {
            return response(null, 401);
        }

        $social = $this->getSocialPlatform($auth->platform);
        $accessTokenValide = $social->accessTokenValidation($request->bearerToken());

        if ($accessTokenValide['isRefresh'] && $accessTokenValide['status'] === false) {
            $newToken = $social->refreshAccessToken($auth->refresh_token);

            if (empty($newToken)) {
                return response(null, 401);
            }

            User::where('access_token', $request->bearerToken())
                ->update([
                    'access_token' => $newToken['access_token'],
                    'refresh_token' => empty($newToken['refresh_token']) ? $auth->refresh_token : $newToken['refresh_token']
                ]);

            $request->headers->set('Authorization', 'Bearer ' . $newToken['access_token']);
        }

        if ($accessTokenValide['isRefresh'] === false && $accessTokenValide['status'] === false) {
            return response(null, 401);
        }

        return $next($request);
    }

    private function getSocialPlatform(string $platform)
    {
        switch ($platform) {
            case 'kakao':
                $social = new Kakao;
                break;
        }

        return $social;
    }
}

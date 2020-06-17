<?php

namespace App\Http\Middleware\Social;

interface SocialInterface
{
  public function accessTokenValidation(string $accessToken);
  public function refreshAccessToken(string $refreshToken);
}

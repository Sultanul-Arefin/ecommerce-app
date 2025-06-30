<?php

namespace App\Helpers;

use Illuminate\Support\Facades\URL;

class SSOHelper
{
    public static function generateAppBUrl(string $email): string
    {
        // Point signed route to appB
        URL::forceRootUrl('http://127.0.0.1:8001');

        $signedUrl = URL::temporarySignedRoute(
            'foodpanda.sso-login',      // this must match appB's route name
            now()->addMinutes(2),
            ['email' => $email]
        );

        // Reset root URL so it doesn’t affect future links
        URL::forceRootUrl(config('app.url'));

        return $signedUrl;
    }
}

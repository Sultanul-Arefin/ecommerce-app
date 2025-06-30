<?php

use App\Helpers\SSOHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/sso-token', function (Request $request) {
    $email = $request->query('email');
    $user = Auth::user();

    if ($user && $user->email === $email) {
        return response()->json([
            'valid' => true,
            'email' => $user->email,
        ]);
    }

    return response()->json(['valid' => false]);
});

Route::middleware('web')->get('/sso-login-url', function (Request $request) {
    if (!Auth::check()) {
        return response()->json(['valid' => false], 401);
    }

    $user = Auth::user();

    return response()->json([
        'valid' => true,
        'sso_url' => SSOHelper::generateAppBUrl($user->email),
    ]);
});
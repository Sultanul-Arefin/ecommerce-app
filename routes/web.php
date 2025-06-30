<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/sso-login', function () {
    abort(404);
})->name('foodpanda.sso-login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        return redirect()->route('home'); // no redirect to appB
    }

    return back()->withErrors(['email' => 'Invalid credentials']);
});

Route::get('/logout', function () {
    $user = Auth::user();
    Auth::logout();

    if ($user) {
        Http::get('http://127.0.0.1:8001/sso-logout', [
            'email' => $user->email,
            'signature' => hash_hmac('sha256', $user->email, env('APP_KEY'))
        ]);
    }

    return redirect('/login');
});
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CheckAuthToken
{
    public function handle($request, Closure $next)
    {
        $token = Cookie::get('auth_token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Extract the plain-text token part
        $tokenParts = explode('|', $token, 2);
        $plainTextToken = $tokenParts[1] ?? null;

        if (!$plainTextToken) {
            return redirect()->route('login')->with('error', 'Invalid token.');
        }

        // Find the token in the database
        $hashedToken = hash('sha256', $plainTextToken);
        $tokenRecord = PersonalAccessToken::where('token', $hashedToken)->first();

        if (!$tokenRecord || !$tokenRecord->tokenable) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Authenticate the user
        Auth::login($tokenRecord->tokenable);

        return $next($request);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) { // Check if the user is logged in
            $user = Auth::user(); // Get the authenticated user
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard'); // Redirect to admin dashboard
            } elseif ($user->role === 'user') {
                return redirect()->route('user.dashboard'); // Redirect to user dashboard
            }
        }

        return view('auth.login'); // Show the login form if not authenticated
    }

    public function adminIndex()
    {
        return view('admin.dashboard');  // Return the login Blade view
    }

    public function userIndex()
    {
        return view('user.dashboard');  // Return the login Blade view
    }

    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:4',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->route('login')->withErrors($validator->errors());
        }

        // Attempt to authenticate the user
        if (Auth::attempt(['name' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();

            // Create a new token
            $token = $user->createToken('Personal Access Token', [], now()->addDays(7))->plainTextToken;

            // Determine redirect URL based on user role
            $redirectUrl = match ($user->role) {
                'admin' => route('admin.dashboard'),
                'user' => route('user.dashboard'),
                default => route('login'),
            };

            // Set the token in an HttpOnly cookie and redirect
            return redirect($redirectUrl)
                ->withCookie(cookie('auth_token', $token, 0, '/', null, false, false)); // Set cookie for the session
        }

        // Authentication failed
        return redirect()->route('login')->with('error', 'Invalid username or password.');
    }



    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Remove the auth_token cookie
        Cookie::queue(Cookie::forget('auth_token'));

        // Redirect to the login page with a success message
        return redirect('/login')->with('success', 'You have been logged out.');
    }
}

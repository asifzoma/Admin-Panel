<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            
            if (Auth::attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                
                // Update last_login_at
                $user = Auth::user();
                $user->last_login_at = now();
                $user->save();
                
                // Log activity (non-blocking)
                try {
                    if (class_exists(\App\Models\ActivityLog::class)) {
                        \App\Models\ActivityLog::create([
                            'user_id' => $user->id,
                            'action' => 'login',
                            'subject_type' => 'User',
                            'subject_id' => $user->id,
                            'description' => $user->name.' logged in',
                        ]);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to log login activity: ' . $e->getMessage());
                }

                return redirect()->intended('/admin');
            }

            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->withInput($request->only('email'));
            
        } catch (\Throwable $e) {
            Log::error('Login error: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'An error occurred during login. Please try again.',
            ])->withInput($request->only('email'));
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        } catch (\Throwable $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return redirect('/');
        }
    }
}

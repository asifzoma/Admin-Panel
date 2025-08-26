<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            // Update last_login_at
            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();
            // Log activity
            \App\Models\ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login',
                'subject_type' => 'User',
                'subject_id' => $user->id,
                'description' => $user->name.' logged in',
            ]);

            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

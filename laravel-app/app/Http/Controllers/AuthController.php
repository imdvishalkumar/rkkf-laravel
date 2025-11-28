<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if user exists and has role = 1 (admin)
        $user = User::where('email', $credentials['email'])
            ->where('role', 1)
            ->first();

        if ($user) {
            // Support both plain text (existing system) and hashed passwords
            $passwordMatch = false;
            
            // Check plain text password (for existing system compatibility)
            if ($user->password === $credentials['password']) {
                $passwordMatch = true;
            }
            // Check hashed password (for new Laravel system)
            elseif (Hash::check($credentials['password'], $user->password)) {
                $passwordMatch = true;
            }
            
            if ($passwordMatch) {
                Auth::login($user);
                $request->session()->regenerate();

                // Handle special email redirects (from original login.php)
                if ($credentials['email'] === 'savvyswaraj@gmail.com') {
                    return redirect()->route('club.view.fees');
                } elseif ($credentials['email'] === 'tmc@gmail.com') {
                    return redirect()->route('tmc.view.fees');
                } elseif ($credentials['email'] === 'baroda@gmail.com') {
                    return redirect()->route('baroda.view.fees');
                }

                return redirect()->intended(route('dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard
        if (session('user')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Demo authentication - ganti dengan database query di production
        if ($credentials['username'] === 'admin' && $credentials['password'] === 'password') {
            // Simpan user ke session
            session([
                'user' => [
                    'id' => 1,
                    'name' => 'Dr. Rochmad',
                    'email' => 'admin@eresep.com',
                    'username' => 'admin'
                ],
                'authenticated' => true
            ]);
            
            return redirect()->route('dashboard')->with('success', 'Berhasil login!');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Berhasil logout!');
    }
}
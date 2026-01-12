<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard berdasarkan role
        if (session('user')) {
            return $this->redirectByRole(session('user')['role']);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan username atau email
        $user = DB::table('users')
            ->where(function($query) use ($credentials) {
                $query->where('username', $credentials['username'])
                      ->orWhere('email', $credentials['username']);
            })
            ->first();

        // Cek user dan password
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Cek apakah user aktif
            if (!$user->is_active) {
                return back()->withErrors([
                    'username' => 'Akun Anda dinonaktifkan. Silakan hubungi administrator.',
                ])->onlyInput('username');
            }

            // Simpan user ke session
            session([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'role' => $user->role,
                    'specialization' => $user->specialization,
                    'phone' => $user->phone
                ],
                'authenticated' => true
            ]);

            // Redirect berdasarkan role
            return $this->redirectByRole($user->role)->with('success', 'Berhasil login!');
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

    /**
     * Redirect berdasarkan role user
     */
    private function redirectByRole($role)
    {
        return match($role) {
            'doctor' => redirect()->route('dashboard'),
            'pharmacist' => redirect()->route('payments.index'),
            'admin' => redirect()->route('dashboard'),
            default => redirect()->route('login'),
        };
    }
}
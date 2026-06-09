<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function klik_login()
    {
        if (Auth::check()) {
            $redirectPath = match(Auth::user()->role) {
                'admin' => '/admin',
                'supplier' => '/supplier',
                'produsen' => '/produsen',
                'reseller' => '/reseller/pesanan/pesanan-saya',
                default => '/login',
            };
            return redirect($redirectPath);
        }
        return view('login');
    }

    /**
     * Proses login dengan remember me
     */
    public function proses_login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Tambahkan remember token jika opsi remember me dicentang
        $remember = $request->has('remember_me');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role user
            $user = Auth::user();
            $redirectPath = match($user->role) {
                'admin' => '/admin',
                'supplier' => '/supplier',
                'produsen' => '/produsen',
                'reseller' => '/reseller/pesanan/pesanan-saya',
                default => '/login',
            };
            
            return redirect()->intended($redirectPath);
        }

        return back()->withErrors([
            'login_error' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Proses logout
     */
    public function proses_logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

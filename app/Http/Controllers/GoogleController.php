<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Arahkan user ke halaman Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Tangkap data dari Google setelah login berhasil
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            
            // Cek apakah user sudah ada, kalau belum bikin baru
            $finduser = User::updateOrCreate(
                ['email' => $user->email],
                [
                    'name' => $user->name,
                    'google_id' => $user->id,
                    'avatar' => $user->avatar,
                    'password' => bcrypt('password_random_aman_aja') // Password formalitas
                ]
            );

            Auth::login($finduser);

            return redirect()->intended('/'); // Balik ke halaman utama setelah login
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Login gagal, coba lagi bos!');
        }
    }
}
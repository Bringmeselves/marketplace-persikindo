<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\LoginNotificationMail;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            if (!$googleUser->getEmail()) {
                return redirect('/login')->with('error', 'Email Google tidak ditemukan.');
            }

            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'password' => bcrypt(uniqid()),
                    'email_verified_at' => now(),
                    'role' => 'user',
                    'status' => 'aktif',
                    'approved' => false,
                ]
            );

            Auth::login($user);

            // Kirim email notifikasi login
            Mail::to($user->email)->send(new LoginNotificationMail($user));

            return redirect()->route('user.marketplace.index')->with('success', 'Login berhasil!');
        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Gagal login dengan Google.');
        }
    }
}


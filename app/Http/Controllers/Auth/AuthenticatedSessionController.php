<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();

          // Flash session message untuk SweetAlert
        session()->flash('success', 'Selamat datang kembali, ' . $user->name . '!');
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.anggota.index');
        }

        if (in_array($user->role, ['user', 'anggota'])) {
            return redirect()->route('user.marketplace.index');
        }

        return redirect('/'); // fallback kalau role tidak dikenali
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

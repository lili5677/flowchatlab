<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors([
            'email' => 'Kombinasi email atau kata sandi tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration page.
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'school' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'same:password_confirmation'],
            'role' => ['nullable', 'string', 'in:siswa,guru'],
            'terms' => ['accepted'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'school.required' => 'Asal sekolah wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.same' => 'Konfirmasi kata sandi tidak cocok.',
            'terms.accepted' => 'Anda harus menyetujui Ketentuan Pembelajaran & Kebijakan Privasi.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'school' => $validated['school'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'siswa',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat belajar di Flowchart Lab.');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar.');
    }
}

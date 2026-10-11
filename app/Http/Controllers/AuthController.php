<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ── Show Login Page ────────────────────────────────────
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    // ── Process Login ──────────────────────────────────────
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        return back()
            ->withErrors(['username' => 'Username atau kata sandi salah.'])
            ->withInput($request->only('username'));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login', ['initialTab' => 'register']);
    }

    // ── Process Register ───────────────────────────────────
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users|regex:/^[a-zA-Z0-9_]+$/',
            'password' => ['required', 'confirmed', Password::min(6)],
            'role'     => 'required|in:student,teacher',
            'kelas'    => 'nullable|string|max:10',
            'sekolah'  => 'nullable|string|max:100',
        ], [
            'username.regex' => 'Username hanya boleh huruf, angka, dan underscore.',
            'username.unique' => 'Username sudah dipakai, coba yang lain.',
        ]);

        $avatarNumber = rand(1, 6);
        $avatarPrefix = $validated['role'] === 'teacher' ? 'teacher' : 'student';

        $user = User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'kelas'    => $validated['kelas'] ?? null,
            'sekolah'  => $validated['sekolah'] ?? null,
            'avatar'   => "{$avatarPrefix}_0{$avatarNumber}.png",
        ]);

        Auth::login($user);
        return $this->redirectByRole($user);
    }

    // ── Logout ─────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Sampai jumpa lagi! 👋');
    }

    // ── Helper: Redirect by Role ───────────────────────────
    private function redirectByRole(User $user)
    {
        return $user->isTeacher()
            ? redirect()->route('teacher.dashboard')
            : redirect()->route('student.dashboard');
    }
}

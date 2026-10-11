@extends('layouts.guest')
@section('title', 'Masuk')

@section('content')
<div x-data="{ tab: '{{ $initialTab ?? (request()->routeIs('register') ? 'register' : 'login') }}' }">

    {{-- Chunky Cartoon Tabs --}}
    <div class="flex border-b-3 border-slate-900 bg-slate-100">
        <button id="tab-login" @click="tab='login'"
                :class="tab==='login'
                    ? 'bg-yellow-400 text-slate-900 shadow-[inset_0_-3px_0_#0f172a]'
                    : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200'"
                class="flex-1 py-3.5 font-display text-base transition-all duration-150 flex items-center justify-center gap-2">
            <span>🔑</span> Masuk
        </button>
        <button id="tab-register" @click="tab='register'"
                :class="tab==='register'
                    ? 'bg-yellow-400 text-slate-900 shadow-[inset_0_-3px_0_#0f172a]'
                    : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200'"
                class="flex-1 py-3.5 font-display text-base transition-all duration-150 flex items-center justify-center gap-2">
            <span>✨</span> Daftar Akun
        </button>
    </div>

    {{-- ─── LOGIN FORM ── --}}
    <div x-show="tab==='login'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="p-6 md:p-7">

        <h2 class="font-display text-slate-900 text-2xl mb-0.5">Selamat Datang Kembali! 🎮</h2>
        <p class="text-slate-500 mb-5 text-xs md:text-sm font-bold">Masuk untuk melanjutkan petualangan kodingmu.</p>

        @if($errors->any())
        <div role="alert" class="bg-red-100 border-2 border-red-500 text-red-800 px-4 py-2.5 rounded-xl mb-4 text-xs md:text-sm font-bold flex items-center gap-2 shadow-sm">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs md:text-sm font-bold text-slate-700 mb-1" for="login-username">Username</label>
                <input id="login-username" type="text" name="username"
                       value="{{ old('username') }}"
                       placeholder="contoh: budi"
                       class="field"
                       required autofocus autocomplete="username">
            </div>

            <div>
                <label class="block text-xs md:text-sm font-bold text-slate-700 mb-1" for="login-password">Kata Sandi</label>
                <div class="relative" x-data="{ show: false }">
                    <input id="login-password" :type="show ? 'text' : 'password'" name="password"
                           placeholder="Masukkan kata sandi"
                           class="field pr-11"
                           required autocomplete="current-password">
                    <button type="button" @click="show=!show"
                            :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 rounded transition-colors">
                        <svg x-show="!show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg x-show="show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19M1 1l22 22"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="remember" name="remember"
                       class="w-4 h-4 rounded border-slate-300 accent-indigo-600 cursor-pointer">
                <label for="remember" class="text-xs md:text-sm text-slate-600 font-bold cursor-pointer">Ingat saya</label>
            </div>

            <button type="submit" id="btn-login" class="btn-primary">
                <span>🚀</span> MASUK KE ARENA
            </button>
        </form>

        {{-- Demo Accounts Sticker Box --}}
        <div class="mt-6 p-4 bg-yellow-50 rounded-2xl border-2.5 border-slate-900 shadow-[0_3px_0_#0f172a]">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-display text-slate-900 uppercase">⚡ Akun Coba Langsung (Demo)</p>
                <span class="cn-sticker bg-yellow-300 text-slate-900 text-[10px]">KLIK &amp; COBA</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="bg-white rounded-xl p-2.5 border-2 border-slate-900 shadow-sm cursor-pointer hover:bg-indigo-50 transition-colors"
                     onclick="document.getElementById('login-username').value='budi';document.getElementById('login-password').value='siswa123';">
                    <p class="font-display text-indigo-700">🧑 Siswa Budi</p>
                    <p class="text-slate-500 font-mono mt-0.5 text-[11px]">budi / siswa123</p>
                </div>
                <div class="bg-white rounded-xl p-2.5 border-2 border-slate-900 shadow-sm cursor-pointer hover:bg-yellow-50 transition-colors"
                     onclick="document.getElementById('login-username').value='busari';document.getElementById('login-password').value='guru123';">
                    <p class="font-display text-amber-700">👩‍🏫 Bu Guru Sari</p>
                    <p class="text-slate-500 font-mono mt-0.5 text-[11px]">busari / guru123</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── REGISTER FORM ── --}}
    <div x-show="tab==='register'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="p-6 md:p-7" style="display:none">

        <h2 class="font-display text-slate-900 text-2xl mb-0.5">Buat Akun Barumu! 🌟</h2>
        <p class="text-slate-500 mb-5 text-xs md:text-sm font-bold">Mulai belajar logika pemrograman sekarang.</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Role Selector --}}
            <div x-data="{ role: 'student' }">
                <label class="block text-xs md:text-sm font-bold text-slate-700 mb-1.5">Saya adalah:</label>
                <div class="grid grid-cols-2 gap-3 mb-4" role="group" aria-label="Pilih peran">
                    <button type="button" id="role-btn-student" @click="role='student'"
                            :class="role==='student'
                                ? 'border-slate-900 bg-yellow-300 text-slate-900 shadow-[0_2px_0_#0f172a]'
                                : 'border-slate-200 text-slate-500 hover:bg-slate-50'"
                            class="border-2.5 rounded-xl py-2.5 font-display text-sm transition-all"
                            :aria-pressed="role==='student'">
                        <input type="radio" name="role_display" value="student" :checked="role==='student'" class="sr-only">
                        🧑 Siswa
                    </button>
                    <button type="button" id="role-btn-teacher" @click="role='teacher'"
                            :class="role==='teacher'
                                ? 'border-slate-900 bg-yellow-300 text-slate-900 shadow-[0_2px_0_#0f172a]'
                                : 'border-slate-200 text-slate-500 hover:bg-slate-50'"
                            class="border-2.5 rounded-xl py-2.5 font-display text-sm transition-all"
                            :aria-pressed="role==='teacher'">
                        <input type="radio" name="role_display" value="teacher" :checked="role==='teacher'" class="sr-only">
                        👩‍🏫 Guru
                    </button>
                </div>
                <input type="hidden" name="role" :value="role">

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-1" for="reg-name">Nama Lengkap</label>
                        <input id="reg-name" type="text" name="name" value="{{ old('name') }}"
                               placeholder="Nama lengkap kamu" class="field" required autocomplete="name">
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-1" for="reg-username">Username</label>
                        <input id="reg-username" type="text" name="username" value="{{ old('username') }}"
                               placeholder="Tanpa spasi, cth: budi123"
                               class="field" pattern="[a-zA-Z0-9_]+" required autocomplete="username">
                    </div>

                    <div x-show="role==='student'" class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-1" for="reg-kelas">Kelas</label>
                            <input id="reg-kelas" type="text" name="kelas" placeholder="contoh: 4A"
                                   class="field" autocomplete="off">
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm font-bold text-slate-700 mb-1" for="reg-sekolah">Sekolah</label>
                            <input id="reg-sekolah" type="text" name="sekolah" placeholder="Nama sekolah"
                                   class="field" autocomplete="organization">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-1" for="reg-password">Kata Sandi</label>
                        <div class="relative" x-data="{ show: false }">
                            <input id="reg-password" :type="show ? 'text' : 'password'" name="password"
                                   placeholder="Minimal 6 karakter"
                                   class="field pr-11" minlength="6" required autocomplete="new-password">
                            <button type="button" @click="show=!show"
                                    :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 rounded transition-colors">
                                <svg x-show="!show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg x-show="show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19M1 1l22 22"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm font-bold text-slate-700 mb-1" for="reg-confirm">Ulangi Kata Sandi</label>
                        <div class="relative" x-data="{ show: false }">
                            <input id="reg-confirm" :type="show ? 'text' : 'password'" name="password_confirmation"
                                   placeholder="Ketik ulang kata sandi"
                                   class="field pr-11" required autocomplete="new-password">
                            <button type="button" @click="show=!show"
                                    :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 rounded transition-colors">
                                <svg x-show="!show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg x-show="show" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19M1 1l22 22"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                <div role="alert" class="bg-red-100 border-2 border-red-500 text-red-800 px-4 py-2.5 rounded-xl mt-3 text-xs md:text-sm font-bold flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
                @endif

                <button type="submit" id="btn-register" class="btn-primary mt-5">
                    <span>🎉</span> DAFTAR SEKARANG
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

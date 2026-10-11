@extends('layouts.guest')
@section('title', 'Masuk')

@section('content')
<div x-data="{ tab: '{{ $initialTab ?? (request()->routeIs('register') ? 'register' : 'login') }}' }">

    {{-- Tabs --}}
    <div class="flex border-b border-gray-100">
        <button @click="tab='login'"
                :class="tab==='login' ? 'border-b-4 border-primary text-primary bg-primary/5' : 'text-gray-400 hover:text-gray-600'"
                class="flex-1 py-4 font-bold text-lg transition-all duration-200 flex items-center justify-center gap-2">
            🔑 Masuk
        </button>
        <button @click="tab='register'"
                :class="tab==='register' ? 'border-b-4 border-secondary text-secondary bg-secondary/5' : 'text-gray-400 hover:text-gray-600'"
                class="flex-1 py-4 font-bold text-lg transition-all duration-200 flex items-center justify-center gap-2">
            ✨ Daftar
        </button>
    </div>

    {{-- ─── LOGIN FORM ──────────────────────────────── --}}
    <div x-show="tab==='login'" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-4"
         x-transition:enter-end="opacity-100 translate-x-0" class="p-8">

        <h2 class="text-2xl font-black text-gray-800 mb-1">Selamat Datang Kembali! 👋</h2>
        <p class="text-gray-500 mb-6 text-sm">Masuk untuk lanjut belajar dan kumpulkan bintang!</p>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-sm font-semibold">
            ❌ {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2" for="login-username">
                    👤 Username Kamu
                </label>
                <input id="login-username" type="text" name="username"
                       value="{{ old('username') }}"
                       placeholder="contoh: budi123"
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-lg font-semibold focus:outline-none focus:border-primary transition-colors"
                       required autofocus>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2" for="login-password">
                    🔒 Kata Sandi
                </label>
                <div class="relative" x-data="{ show: false }">
                    <input id="login-password" :type="show ? 'text' : 'password'" name="password"
                           placeholder="Masukkan kata sandimu"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-lg font-semibold focus:outline-none focus:border-primary transition-colors pr-12"
                           required>
                    <button type="button" @click="show=!show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xl">
                        <span x-text="show ? '🙈' : '👁️'"></span>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="remember" name="remember"
                       class="w-4 h-4 accent-primary rounded">
                <label for="remember" class="text-sm text-gray-600 font-semibold">Ingat saya</label>
            </div>

            <button type="submit"
                    class="w-full bg-primary hover:bg-primary-dark text-white font-black text-xl py-3.5 rounded-xl transition-all duration-200 hover:scale-105 active:scale-95 shadow-lg hover:shadow-primary/40 flex items-center justify-center gap-2">
                🚀 Masuk Sekarang!
            </button>
        </form>

        <div class="mt-6 p-4 bg-amber-50 rounded-xl border border-amber-200">
            <p class="text-xs font-bold text-amber-700 mb-2">💡 Akun Demo:</p>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="bg-white rounded-lg p-2 border border-amber-100">
                    <p class="font-bold text-gray-700">👩‍🏫 Guru</p>
                    <p class="text-gray-500">busari / guru123</p>
                </div>
                <div class="bg-white rounded-lg p-2 border border-amber-100">
                    <p class="font-bold text-gray-700">🧒 Siswa</p>
                    <p class="text-gray-500">budi / siswa123</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── REGISTER FORM ───────────────────────────── --}}
    <div x-show="tab==='register'" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-x-4"
         x-transition:enter-end="opacity-100 translate-x-0" class="p-8" style="display:none">

        <h2 class="text-2xl font-black text-gray-800 mb-1">Yuk Bergabung! 🎉</h2>
        <p class="text-gray-500 mb-6 text-sm">Buat akunmu dan mulai petualangan logika!</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- Role Selector --}}
            <div x-data="{ role: 'student' }">
                <label class="block text-sm font-bold text-gray-700 mb-2">Saya adalah:</label>
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <button type="button" @click="role='student'"
                            :class="role==='student' ? 'border-primary bg-primary/10 text-primary' : 'border-gray-200 text-gray-500'"
                            class="border-2 rounded-xl py-3 font-bold text-lg transition-all hover:scale-105">
                        🧒 Siswa
                        <input type="radio" name="role" value="student" :checked="role==='student'" class="hidden">
                    </button>
                    <button type="button" @click="role='teacher'"
                            :class="role==='teacher' ? 'border-secondary bg-secondary/10 text-secondary' : 'border-gray-200 text-gray-500'"
                            class="border-2 rounded-xl py-3 font-bold text-lg transition-all hover:scale-105">
                        👩‍🏫 Guru
                        <input type="radio" name="role" value="teacher" :checked="role==='teacher'" class="hidden">
                    </button>
                </div>
                <input type="hidden" name="role" :value="role">

                <div class="space-y-3">
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="📝 Nama Lengkap"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-semibold focus:outline-none focus:border-primary transition-colors"
                           required>

                    <input type="text" name="username" value="{{ old('username') }}" placeholder="👤 Username (tanpa spasi)"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-semibold focus:outline-none focus:border-primary transition-colors"
                           pattern="[a-zA-Z0-9_]+" required>

                    <div x-show="role==='student'" class="grid grid-cols-2 gap-3">
                        <input type="text" name="kelas" placeholder="📚 Kelas (4A)"
                               class="px-4 py-3 border-2 border-gray-200 rounded-xl font-semibold focus:outline-none focus:border-primary transition-colors">
                        <input type="text" name="sekolah" placeholder="🏫 Nama Sekolah"
                               class="px-4 py-3 border-2 border-gray-200 rounded-xl font-semibold focus:outline-none focus:border-primary transition-colors">
                    </div>

                    <input type="password" name="password" placeholder="🔒 Kata Sandi (min 6 karakter)"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-semibold focus:outline-none focus:border-primary transition-colors"
                           minlength="6" required>

                    <input type="password" name="password_confirmation" placeholder="🔒 Ulangi Kata Sandi"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-semibold focus:outline-none focus:border-primary transition-colors"
                           required>
                </div>

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mt-3 text-sm font-semibold">
                    ❌ {{ $errors->first() }}
                </div>
                @endif

                <button type="submit"
                        class="w-full mt-4 bg-pink-500 hover:bg-pink-600 text-white font-black text-xl py-3.5 rounded-xl transition-all duration-200 hover:scale-105 active:scale-95 shadow-lg flex items-center justify-center gap-2">
                    <span>🎉</span> <span>Daftar Akun Sekarang!</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

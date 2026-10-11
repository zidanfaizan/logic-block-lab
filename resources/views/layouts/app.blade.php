<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Logic Block Lab — Platform belajar logika pemrograman yang seru dan menyenangkan untuk anak SD!">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Logic Block Lab') 🧩</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN (will be replaced by build in production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Nunito', 'sans-serif'],
                        display: ['Fredoka One', 'cursive'],
                    },
                    colors: {
                        primary:   { DEFAULT: '#6C63FF', light: '#8B84FF', dark: '#5048E5' },
                        secondary: { DEFAULT: '#FF6B6B', light: '#FF8E8E', dark: '#E85555' },
                        accent:    { DEFAULT: '#4ECDC4', light: '#72D9D2', dark: '#38B2AA' },
                        fun:       { yellow: '#FFD93D', green: '#6BCB77', purple: '#A78BFA' },
                    },
                    animation: {
                        'bounce-slow': 'bounce 2s infinite',
                        'wiggle': 'wiggle 1s ease-in-out infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-glow': 'pulse-glow 2s ease-in-out infinite',
                    },
                    keyframes: {
                        wiggle: {
                            '0%, 100%': { transform: 'rotate(-3deg)' },
                            '50%': { transform: 'rotate(3deg)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        'pulse-glow': {
                            '0%, 100%': { boxShadow: '0 0 10px rgba(108,99,255,0.4)' },
                            '50%': { boxShadow: '0 0 25px rgba(108,99,255,0.8)' },
                        },
                    },
                },
            },
        }
    </script>

    <style>
        * { font-family: 'Nunito', sans-serif; }
        .font-display { font-family: 'Fredoka One', cursive; }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f64f59 100%);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .card-hover:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .stars-animation { animation: float 3s ease-in-out infinite; }
        .confetti-bg {
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='15' cy='15' r='3' fill='%23FFD93D' opacity='0.3'/%3E%3Ccircle cx='85' cy='25' r='2' fill='%236BCB77' opacity='0.3'/%3E%3Ccircle cx='50' cy='75' r='4' fill='%23FF6B6B' opacity='0.2'/%3E%3C/svg%3E");
        }
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide-in { animation: slideInUp 0.5s ease-out forwards; }
        .slide-in-delay-1 { animation: slideInUp 0.5s 0.1s ease-out both; }
        .slide-in-delay-2 { animation: slideInUp 0.5s 0.2s ease-out both; }
        .slide-in-delay-3 { animation: slideInUp 0.5s 0.3s ease-out both; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen confetti-bg">

    {{-- Navbar --}}
    <nav class="gradient-bg shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ auth()->user()->isTeacher() ? route('teacher.dashboard') : route('student.dashboard') }}"
                   class="flex items-center gap-2 hover:opacity-90 transition-opacity">
                    <span class="text-3xl animate-wiggle inline-block">🧩</span>
                    <span class="font-display text-white text-xl tracking-wide">Logic Block Lab</span>
                </a>

                {{-- User Info --}}
                <div class="flex items-center gap-4">
                    @if(auth()->user()->isStudent())
                    <div class="hidden sm:flex items-center gap-2 bg-white/20 rounded-full px-4 py-1.5 text-white text-sm font-bold">
                        <span>⭐ {{ auth()->user()->total_stars }}</span>
                        <span class="opacity-50">|</span>
                        <span>⚡ Lv.{{ auth()->user()->level }}</span>
                    </div>
                    @endif

                    {{-- Avatar Dropdown --}}
                    <div x-data="{ open: false }" class="relative" @click.outside="open=false">
                        <button @click="open=!open"
                                class="flex items-center gap-2 bg-white/20 hover:bg-white/30 rounded-full px-3 py-1.5 text-white font-bold transition-all">
                            <span class="text-lg">
                                {{ auth()->user()->isTeacher() ? '👩‍🏫' : '🧒' }}
                            </span>
                            <span class="hidden sm:inline text-sm">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                                <p class="font-bold text-gray-800">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->isTeacher() ? '👩‍🏫 Guru' : '🧒 Siswa Kelas ' . auth()->user()->kelas }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left px-4 py-3 text-red-500 font-bold hover:bg-red-50 transition-colors flex items-center gap-2">
                                    <span>👋</span> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition
         class="fixed top-20 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-2xl shadow-lg font-bold flex items-center gap-2">
        <span>✅</span> {{ session('success') }}
        <button @click="show=false" class="ml-2 opacity-70 hover:opacity-100">✕</button>
    </div>
    @endif

    @if(session('error') || $errors->any())
    <div x-data="{ show: true }" x-show="show" x-transition
         class="fixed top-20 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-2xl shadow-lg font-bold flex items-center gap-2 max-w-sm">
        <span>❌</span>
        <span>{{ session('error') ?? $errors->first() }}</span>
        <button @click="show=false" class="ml-2 opacity-70 hover:opacity-100">✕</button>
    </div>
    @endif

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="gradient-bg text-white text-center py-6 mt-12">
        <p class="font-display text-lg">🧩 Logic Block Lab</p>
        <p class="text-white/70 text-sm mt-1">Belajar Coding itu Seru! 🚀 | M-ONE Telkomsel Coding Competition 2024</p>
    </footer>

    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>

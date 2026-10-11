<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Logic Block Lab — Platform belajar logika pemrograman Cartoon Network style untuk anak SD!">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Logic Block Lab') — Cartoon Games</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet">

    {{-- Tailwind CSS CDN --}}
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
                        primary:   { DEFAULT: '#6366F1', light: '#818CF8', dark: '#4338CA' },
                        secondary: { DEFAULT: '#EC4899', light: '#F472B6', dark: '#DB2777' },
                        accent:    { DEFAULT: '#06B6D4', light: '#22D3EE', dark: '#0891B2' },
                        cn: {
                            yellow: '#FACC15',
                            pink: '#F43F5E',
                            purple: '#8B5CF6',
                            blue: '#0284C7',
                            green: '#10B981',
                            dark: '#0F172A',
                        },
                    },
                },
            },
        }
    </script>

    <style>
        * { font-family: 'Nunito', sans-serif; }
        .font-display { font-family: 'Fredoka One', cursive; }

        /* Colorful Playful Cartoon Background */
        .page-bg {
            background-color: #F8FAFC;
            background-image:
                radial-gradient(circle at 15% 20%, rgba(250, 204, 21, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 85% 30%, rgba(244, 63, 94, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 50% 80%, rgba(14, 165, 233, 0.15) 0%, transparent 45%),
                radial-gradient(#CBD5E1 1.2px, transparent 1.2px);
            background-size: 100% 100%, 100% 100%, 100% 100%, 28px 28px;
            background-attachment: fixed;
        }

        /* Cartoon Network Style Chunky Cards */
        .cn-card {
            background: #FFFFFF;
            border: 3px solid #0F172A;
            border-radius: 1.25rem;
            box-shadow: 0 6px 0px #0F172A;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .cn-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 0px #0F172A;
        }

        /* Chunky Arcade Button */
        .btn-cn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: 'Fredoka One', cursive;
            border: 2.5px solid #0F172A;
            border-radius: 0.875rem;
            box-shadow: 0 4px 0 #0F172A;
            cursor: pointer;
            transition: all 0.1s ease;
            text-decoration: none;
        }
        .btn-cn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 0 #0F172A;
        }
        .btn-cn:active {
            transform: translateY(3px);
            box-shadow: 0 1px 0 #0F172A;
        }

        /* Navbar */
        .navbar-cn {
            background: #FFFFFF;
            border-bottom: 3.5px solid #0F172A;
            box-shadow: 0 4px 0 rgba(15, 23, 42, 0.08);
        }

        /* Toast */
        .toast-success {
            background: #DCFCE7;
            color: #14532D;
            border: 2.5px solid #0F172A;
            box-shadow: 0 4px 0 #0F172A;
            border-radius: 1rem;
        }
        .toast-error {
            background: #FEE2E2;
            color: #7F1D1D;
            border: 2.5px solid #0F172A;
            box-shadow: 0 4px 0 #0F172A;
            border-radius: 1rem;
        }

        /* Slide In Animations */
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .slide-in          { animation: slideInUp 0.4s ease-out forwards; }
        .slide-in-delay-1  { animation: slideInUp 0.4s 0.06s ease-out both; }
        .slide-in-delay-2  { animation: slideInUp 0.4s 0.12s ease-out both; }
        .slide-in-delay-3  { animation: slideInUp 0.4s 0.18s ease-out both; }

        /* XP bar */
        .xp-bar {
            height: 12px;
            background: #E2E8F0;
            border: 2px solid #0F172A;
            border-radius: 9999px;
            overflow: hidden;
        }
        .xp-fill {
            height: 100%;
            background: linear-gradient(90deg, #FACC15, #F59E0B);
            border-radius: 9999px;
            transition: width 0.8s ease;
        }

        /* Cartoon Stickers & Pills */
        .cn-sticker {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.65rem;
            border-radius: 0.6rem;
            font-size: 0.72rem;
            font-family: 'Fredoka One', cursive;
            letter-spacing: 0.02em;
            border: 1.5px solid #0F172A;
            box-shadow: 0 2px 0 #0F172A;
        }
    </style>

    @stack('styles')
</head>
<body class="page-bg min-h-screen flex flex-col text-slate-800">

    {{-- ── Colorful Top Strip (Cartoon Network Multi-color) ── --}}
    <div class="h-2 w-full grid grid-cols-5 z-50">
        <div class="bg-yellow-400"></div>
        <div class="bg-pink-500"></div>
        <div class="bg-purple-600"></div>
        <div class="bg-cyan-400"></div>
        <div class="bg-emerald-400"></div>
    </div>

    {{-- ── Navbar ── --}}
    <nav class="navbar-cn sticky top-0 z-40" aria-label="Navigasi utama">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18 py-2">

                {{-- Logo & CN Games Vibe --}}
                <a href="{{ auth()->user()->isTeacher() ? route('teacher.dashboard') : route('student.dashboard') }}"
                   class="flex items-center gap-3 hover:scale-102 transition-transform"
                   aria-label="Logic Block Lab — beranda">
                    {{-- CN Block Logo --}}
                    <div class="w-11 h-11 rounded-xl bg-yellow-400 border-2.5 border-slate-900 shadow-[0_3px_0_#0f172a] flex items-center justify-center flex-shrink-0">
                        <svg width="24" height="24" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect x="1" y="1" width="7" height="7" rx="1.5" fill="#0F172A"/>
                            <rect x="10" y="1" width="7" height="7" rx="1.5" fill="#EC4899"/>
                            <rect x="1" y="10" width="7" height="7" rx="1.5" fill="#0284C7"/>
                            <rect x="10" y="10" width="7" height="7" rx="1.5" fill="#10B981"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-display text-slate-900 text-xl tracking-tight block leading-tight">Logic Block Lab</span>
                        <span class="inline-block bg-pink-500 text-white font-display text-[10px] px-2 py-0.5 rounded-full border border-slate-900 shadow-[0_1px_0_#0f172a] uppercase leading-none">
                            Cartoon Games
                        </span>
                    </div>
                </a>

                {{-- Nav Links & Student Level --}}
                <div class="flex items-center gap-3">
                    @if(auth()->user()->isStudent())
                    <a href="{{ route('student.quests') }}"
                       class="hidden md:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl font-display text-sm text-slate-800 bg-sky-100 border-2 border-slate-900 shadow-[0_2px_0_#0f172a] hover:bg-sky-200 transition-colors">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="5 3 19 12 5 21 5 3"/>
                        </svg>
                        Main Game
                    </a>

                    <div class="flex items-center gap-2 bg-yellow-300 border-2 border-slate-900 shadow-[0_2px_0_#0f172a] rounded-xl px-3 py-1 text-sm font-display text-slate-900">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#D97706" stroke="#78350F" stroke-width="1.5" aria-hidden="true">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        <span>{{ auth()->user()->total_stars }}</span>
                        <span class="text-slate-400">|</span>
                        <span class="text-purple-900">Lv.{{ auth()->user()->level }}</span>
                    </div>
                    @endif

                    {{-- User dropdown --}}
                    <div x-data="{ open: false }" class="relative" @click.outside="open=false">
                        <button id="user-menu-btn"
                                @click="open=!open"
                                :aria-expanded="open"
                                aria-haspopup="true"
                                aria-controls="user-menu-dropdown"
                                class="flex items-center gap-2 bg-white hover:bg-slate-50 border-2 border-slate-900 shadow-[0_2px_0_#0f172a] rounded-xl px-2.5 py-1 text-slate-800 font-bold text-sm transition-all">
                            {{-- Avatar --}}
                            <span class="w-7 h-7 rounded-lg bg-indigo-500 text-white font-display text-sm flex items-center justify-center border border-slate-900 flex-shrink-0" aria-hidden="true">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden sm:inline font-display text-xs">{{ auth()->user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-600 transition-transform" :class="open ? 'rotate-180' : ''"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div id="user-menu-dropdown"
                             x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl border-3 border-slate-900 shadow-[0_8px_0_#0f172a] overflow-hidden z-50"
                             role="menu">
                            <div class="px-4 py-3 border-b-2 border-slate-900 bg-yellow-100">
                                <p class="font-display text-slate-900 text-sm">{{ auth()->user()->name }}</p>
                                <p class="text-xs font-bold text-slate-600 mt-0.5">
                                    {{ auth()->user()->isTeacher() ? 'Guru Pembimbing' : 'Siswa — Kelas ' . auth()->user()->kelas }}
                                </p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" id="btn-logout" role="menuitem"
                                        class="w-full text-left px-4 py-3 text-red-600 font-display text-sm hover:bg-red-50 transition-colors flex items-center gap-2">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                        <polyline points="16 17 21 12 16 7"/>
                                        <line x1="21" y1="12" x2="9" y2="12"/>
                                    </svg>
                                    Keluar Game
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
    <div x-data="{ show: true }" x-show="show" x-transition role="status" aria-live="polite"
         class="toast-success fixed top-20 right-4 z-50 px-5 py-3 font-bold text-sm flex items-center gap-2 max-w-sm">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="20 6 9 17 4 12"/>
        </svg>
        <span>{{ session('success') }}</span>
        <button @click="show=false" aria-label="Tutup" class="ml-auto opacity-70 hover:opacity-100 transition-opacity">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>
    @endif

    @if(session('error') || $errors->any())
    <div x-data="{ show: true }" x-show="show" x-transition role="alert" aria-live="assertive"
         class="toast-error fixed top-20 right-4 z-50 px-5 py-3 font-bold text-sm flex items-center gap-2 max-w-sm">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span>{{ session('error') ?? $errors->first() }}</span>
        <button @click="show=false" aria-label="Tutup" class="ml-auto opacity-70 hover:opacity-100 transition-opacity">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>
    @endif

    {{-- Main Content --}}
    <main id="main-content" class="flex-1">
        @yield('content')
    </main>

    {{-- Footer with 2026 Competition Text --}}
    <footer class="bg-slate-900 border-t-4 border-slate-950 mt-16 text-white py-8" role="contentinfo">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-yellow-400 border-2 border-white flex items-center justify-center">
                    <svg width="16" height="16" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                        <rect x="1" y="1" width="7" height="7" rx="1.5" fill="#0F172A"/>
                        <rect x="10" y="1" width="7" height="7" rx="1.5" fill="#EC4899"/>
                        <rect x="1" y="10" width="7" height="7" rx="1.5" fill="#0284C7"/>
                        <rect x="10" y="10" width="7" height="7" rx="1.5" fill="#10B981"/>
                    </svg>
                </div>
                <div>
                    <span class="font-display text-lg text-white">Logic Block Lab</span>
                    <span class="text-xs text-yellow-300 font-bold block">Cartoon Games Edition</span>
                </div>
            </div>
            <div class="text-center sm:text-right">
                <p class="text-yellow-300 text-sm font-display tracking-wide">
                    M-ONE Telkomsel Coding Competition 2026
                </p>
                <p class="text-slate-400 text-xs font-semibold mt-0.5">
                    &copy; 2026 Logic Block Lab. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </footer>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>

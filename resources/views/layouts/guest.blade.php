<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login atau Daftar ke Logic Block Lab — Platform belajar logika untuk anak SD">
    <title>@yield('title', 'Masuk') — Logic Block Lab</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Nunito','sans-serif'], display: ['Fredoka One','cursive'] },
                    colors: {
                        primary:   { DEFAULT: '#6C63FF', light: '#8B84FF', dark: '#5048E5' },
                        secondary: { DEFAULT: '#FF6B6B', light: '#FF8E8E', dark: '#E85555' },
                        accent:    { DEFAULT: '#4ECDC4', light: '#72D9D2', dark: '#38B2AA' },
                        fun:       { yellow: '#FFD93D', green: '#6BCB77', purple: '#A78BFA' },
                    },
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Nunito', sans-serif; }
        .font-display { font-family: 'Fredoka One', cursive; }

        /* Colorful Cartoon Network style background */
        .gradient-bg {
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 35%, #EC4899 70%, #F59E0B 100%);
            background-size: 200% 200%;
            animation: gradientShift 12s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Playful geometric & confetti pattern */
        .geo-pattern {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        /* Logo animation */
        @keyframes blockFloat {
            0%, 100% { transform: translateY(0px) rotate(-1deg); }
            50%       { transform: translateY(-10px) rotate(2deg); }
        }
        .logo-anim { animation: blockFloat 4s ease-in-out infinite; }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(32px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .card-anim { animation: slideInUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) forwards; }

        /* Chunky Cartoon Card Shadow */
        .cn-card {
            box-shadow: 0 16px 0px rgba(15, 23, 42, 0.25), 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            border: 3.5px solid #0F172A;
        }

        /* Input fields */
        .field {
            width: 100%;
            padding: 0.85rem 1.1rem;
            border: 2.5px solid #E2E8F0;
            border-radius: 0.875rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            background: #F8FAFC;
            transition: all 0.15s;
            outline: none;
        }
        .field:focus {
            border-color: #6366F1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.18);
            background: #fff;
        }
        .field::placeholder { color: #94A3B8; font-weight: 600; }

        /* Chunky Toy Primary button */
        .btn-primary {
            width: 100%;
            background: #6366F1;
            color: #fff;
            font-family: 'Fredoka One', cursive;
            font-size: 1.15rem;
            letter-spacing: 0.02em;
            padding: 0.9rem 1.5rem;
            border-radius: 0.875rem;
            border: 2.5px solid #3730A3;
            box-shadow: 0 5px 0 #3730A3;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: transform 0.1s, box-shadow 0.1s, background 0.15s;
        }
        .btn-primary:hover  { background: #4F46E5; transform: translateY(-2px); box-shadow: 0 7px 0 #312E81; }
        .btn-primary:active { transform: translateY(3px); box-shadow: 0 2px 0 #312E81; }
        .btn-primary:focus-visible { outline: 3px solid #FDE047; outline-offset: 2px; }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4 overflow-y-auto relative">

    {{-- Colorful Cartoon Network Geometry Backdrop --}}
    <div class="geo-pattern" aria-hidden="true">
        <svg viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="blocks" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                    <circle cx="20" cy="20" r="14" fill="#FDE047" opacity="0.18"/>
                    <rect x="65" y="15" width="22" height="22" rx="5" fill="#38BDF8" opacity="0.2"/>
                    <polygon points="25,75 35,90 15,90" fill="#F43F5E" opacity="0.2"/>
                    <rect x="60" y="60" width="28" height="28" rx="8" fill="#A855F7" opacity="0.18"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#blocks)"/>
            {{-- Big playful floating cartoon bubbles --}}
            <circle cx="150" cy="180" r="80" fill="#FDE047" opacity="0.12"/>
            <circle cx="1280" cy="220" r="110" fill="#38BDF8" opacity="0.12"/>
            <circle cx="1150" cy="750" r="95" fill="#EC4899" opacity="0.14"/>
            <circle cx="200" cy="780" r="120" fill="#10B981" opacity="0.12"/>
        </svg>
    </div>

    <div class="relative z-10 w-full max-w-md card-anim py-6">
        {{-- Logo & Header --}}
        <div class="text-center mb-6">
            <div class="logo-anim inline-block mb-3">
                <div class="w-18 h-18 p-3 rounded-2xl bg-yellow-400 border-4 border-slate-900 shadow-[0_6px_0_#0f172a] flex items-center justify-center mx-auto">
                    <svg width="40" height="40" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="1" y="1" width="7" height="7" rx="1.5" fill="#1E1B4B"/>
                        <rect x="10" y="1" width="7" height="7" rx="1.5" fill="#EC4899"/>
                        <rect x="1" y="10" width="7" height="7" rx="1.5" fill="#0284C7"/>
                        <rect x="10" y="10" width="7" height="7" rx="1.5" fill="#10B981"/>
                    </svg>
                </div>
            </div>
            <div class="inline-block bg-slate-900 text-yellow-300 font-display text-xs px-3 py-1 rounded-full border border-yellow-300/40 shadow-sm uppercase tracking-wider mb-1.5">
                Cartoon Kids Coding
            </div>
            <h1 class="font-display text-white text-4xl drop-shadow-[0_4px_4px_rgba(0,0,0,0.4)] tracking-wide">
                Logic Block Lab
            </h1>
            <p class="text-yellow-100 font-bold text-sm drop-shadow-sm mt-1">Belajar Logika Pemrograman Super Seru!</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl cn-card overflow-hidden">
            @yield('content')
        </div>

        {{-- Footer Note with Competition Name and 2026 --}}
        <div class="text-center mt-5 bg-slate-900/60 backdrop-blur-sm border border-white/10 rounded-2xl py-2.5 px-4 shadow-md">
            <p class="text-white/90 text-xs font-bold">
                &copy; 2026 Logic Block Lab &middot; <span class="text-yellow-300">M-ONE Telkomsel Coding Competition 2026</span>
            </p>
        </div>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>

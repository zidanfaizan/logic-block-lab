<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login atau Daftar ke Logic Block Lab — Platform belajar logika untuk anak SD">
    <title>@yield('title', 'Masuk') — Logic Block Lab 🧩</title>

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
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f64f59 100%);
        }
        .float-anim {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(5deg); }
        }
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .card-anim { animation: slideInUp 0.6s ease-out forwards; }
        .bubble {
            position: absolute;
            border-radius: 50%;
            opacity: 0.2;
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4 overflow-y-auto">

    {{-- Decorative Bubbles --}}
    <div class="bubble bg-white w-32 h-32 top-10 left-10" style="animation-delay:0s"></div>
    <div class="bubble bg-yellow-300 w-20 h-20 top-1/3 left-5" style="animation-delay:1s"></div>
    <div class="bubble bg-pink-300 w-16 h-16 bottom-20 left-20" style="animation-delay:2s"></div>
    <div class="bubble bg-white w-24 h-24 top-20 right-10" style="animation-delay:0.5s"></div>
    <div class="bubble bg-green-300 w-28 h-28 bottom-10 right-20" style="animation-delay:1.5s"></div>

    <div class="relative z-10 w-full max-w-md card-anim">
        {{-- Logo Section --}}
        <div class="text-center mb-8">
            <div class="text-6xl float-anim inline-block mb-3">🧩</div>
            <h1 class="font-display text-white text-4xl drop-shadow-lg">Logic Block Lab</h1>
            <p class="text-white/80 mt-2 font-semibold">Belajar Coding itu Seru! 🚀</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            @yield('content')
        </div>

        {{-- Back to home link --}}
        <p class="text-center text-white/70 mt-6 text-sm">
            &copy; 2024 Logic Block Lab · M-ONE Telkomsel Coding Competition
        </p>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>

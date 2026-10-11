@extends('layouts.app')
@section('title', 'Dashboard Siswa')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- ── Hero Welcome ────────────────────────────────── --}}
    <div class="gradient-bg rounded-3xl p-6 md:p-8 mb-8 text-white relative overflow-hidden slide-in">
        <div class="absolute right-4 top-2 text-7xl opacity-20 select-none animate-bounce-slow">🎮</div>
        <div class="relative z-10">
            <p class="text-white/80 font-semibold text-lg">Halo, {{ auth()->user()->name }}! 👋</p>
            <h1 class="font-display text-3xl md:text-4xl mt-1 mb-4">
                Siap Berpetualang Hari Ini? 🚀
            </h1>

            {{-- Level & XP Bar --}}
            <div class="bg-white/20 rounded-2xl p-4 max-w-sm">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-bold">⚡ Level {{ $user->level }} — {{ $user->level_title }}</span>
                    <span class="text-sm opacity-80">{{ $user->level_progress_percent }}%</span>
                </div>
                <div class="bg-white/30 rounded-full h-3 overflow-hidden">
                    <div class="bg-fun-yellow h-full rounded-full transition-all duration-1000"
                         style="width: {{ $user->level_progress_percent }}%"></div>
                </div>
                <p class="text-xs mt-1 opacity-70">{{ $user->level_progress }}/200 XP menuju level {{ $user->level + 1 }}</p>
            </div>
        </div>
    </div>

    {{-- ── Stats Cards ──────────────────────────────────── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @php
        $stats = [
            ['icon' => '⭐', 'label' => 'Total Bintang', 'value' => $user->total_stars, 'color' => 'from-yellow-400 to-orange-400'],
            ['icon' => '⚡', 'label' => 'Total XP', 'value' => $user->total_xp, 'color' => 'from-purple-400 to-indigo-500'],
            ['icon' => '✅', 'label' => 'Quest Selesai', 'value' => $completedCount . '/' . $totalQuests, 'color' => 'from-green-400 to-teal-500'],
            ['icon' => '🔥', 'label' => 'Streak', 'value' => $user->streak_days . ' Hari', 'color' => 'from-red-400 to-pink-500'],
        ];
        @endphp

        @foreach($stats as $i => $stat)
        <div class="bg-gradient-to-br {{ $stat['color'] }} rounded-2xl p-5 text-white card-hover slide-in-delay-{{ $i % 3 + 1 }}">
            <div class="text-3xl mb-2 stars-animation" style="animation-delay: {{ $i * 0.3 }}s">{{ $stat['icon'] }}</div>
            <div class="font-display text-2xl md:text-3xl">{{ $stat['value'] }}</div>
            <div class="text-white/80 text-sm font-semibold mt-1">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        {{-- ── Featured Quest ───────────────────────────── --}}
        <div class="md:col-span-2">
            @if($featuredQuest)
            <h2 class="font-display text-2xl text-gray-800 mb-4">🌟 Quest Unggulan</h2>
            <a href="{{ route('student.quest.play', $featuredQuest->slug) }}"
               class="block bg-white rounded-3xl shadow-lg overflow-hidden card-hover border-2 border-transparent hover:border-primary/30 slide-in">
                <div class="h-3" style="background: {{ $featuredQuest->theme_color }}"></div>
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="text-5xl" style="animation: float 3s ease-in-out infinite">
                            {{ $featuredQuest->character_emoji }}
                        </div>
                        <div class="flex-1">
                            <span class="inline-block text-xs font-bold px-3 py-1 rounded-full mb-2 {{ $featuredQuest->difficulty_color }}">
                                {{ $featuredQuest->difficulty_label }}
                            </span>
                            <h3 class="font-display text-xl text-gray-800">{{ $featuredQuest->title }}</h3>
                            <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ $featuredQuest->description }}</p>
                            <div class="flex items-center gap-4 mt-3 text-sm text-gray-500 font-semibold">
                                <span>⭐ Maks {{ $featuredQuest->max_stars }} Bintang</span>
                                <span>⚡ +{{ $featuredQuest->xp_reward }} XP</span>
                                <span>⏱️ {{ $featuredQuest->time_limit_minutes }} menit</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 bg-primary text-white font-bold text-center py-3 rounded-xl hover:bg-primary-dark transition-colors">
                        🎮 Mulai Quest Ini!
                    </div>
                </div>
            </a>
            @endif

            {{-- Quick Quest Links --}}
            <h2 class="font-display text-2xl text-gray-800 mt-8 mb-4">🗺️ Pilih Kategori Quest</h2>
            <div class="grid grid-cols-2 gap-4">
                @php
                $categories = [
                    ['category' => 'sequence',  'icon' => '📋', 'label' => 'Urutan Langkah', 'color' => 'from-blue-400 to-cyan-500',   'desc' => 'Susun perintah dengan benar!'],
                    ['category' => 'condition', 'icon' => '🔀', 'label' => 'Pilihan Kondisi', 'color' => 'from-teal-400 to-green-500', 'desc' => 'Pilih keputusan yang tepat!'],
                    ['category' => 'loop',      'icon' => '🔄', 'label' => 'Pengulangan',    'color' => 'from-green-400 to-lime-500',  'desc' => 'Ulangi aksi dengan tepat!'],
                    ['category' => 'pattern',   'icon' => '🧩', 'label' => 'Pola Seru',      'color' => 'from-purple-400 to-pink-500', 'desc' => 'Temukan pola yang tersembunyi!'],
                ];
                @endphp

                @foreach($categories as $cat)
                <a href="{{ route('student.quests', ['category' => $cat['category']]) }}"
                   class="bg-gradient-to-br {{ $cat['color'] }} text-white rounded-2xl p-4 card-hover">
                    <div class="text-3xl mb-2">{{ $cat['icon'] }}</div>
                    <div class="font-bold text-lg">{{ $cat['label'] }}</div>
                    <div class="text-white/80 text-xs mt-1">{{ $cat['desc'] }}</div>
                </a>
                @endforeach
            </div>
        </div>

        {{-- ── Right Sidebar ─────────────────────────────── --}}
        <div class="space-y-6">

            {{-- Badges --}}
            <div class="bg-white rounded-3xl shadow-lg p-6 slide-in-delay-2">
                <h3 class="font-display text-xl text-gray-800 mb-4">🏅 Lencana Kamu</h3>
                @if($user->badges->count() > 0)
                <div class="grid grid-cols-3 gap-3">
                    @foreach($user->badges->take(6) as $badge)
                    <div class="text-center" title="{{ $badge->name }}">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl mx-auto mb-1"
                             style="background: {{ $badge->color }}22; border: 2px solid {{ $badge->color }}">
                            {{ $badge->icon_emoji }}
                        </div>
                        <p class="text-xs text-gray-500 font-semibold truncate">{{ $badge->name }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <div class="text-4xl mb-2">🔒</div>
                    <p class="text-gray-500 text-sm font-semibold">Selesaikan quest untuk<br>mendapatkan lencana!</p>
                </div>
                @endif
            </div>

            {{-- Leaderboard --}}
            <div class="bg-white rounded-3xl shadow-lg p-6 slide-in-delay-3">
                <h3 class="font-display text-xl text-gray-800 mb-4">🏆 Papan Skor</h3>
                <div class="space-y-3">
                    @foreach($leaderboard as $i => $student)
                    <div class="flex items-center gap-3 {{ $student->id === auth()->id() ? 'bg-primary/10 rounded-xl px-2 py-1' : '' }}">
                        <span class="font-display text-lg w-6 text-center {{ $i === 0 ? 'text-yellow-500' : ($i === 1 ? 'text-gray-400' : ($i === 2 ? 'text-amber-600' : 'text-gray-400')) }}">
                            {{ $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : $i + 1 . '.')) }}
                        </span>
                        <span class="flex-1 font-bold text-sm text-gray-700 truncate">
                            {{ $student->name }}
                            @if($student->id === auth()->id()) <span class="text-primary text-xs">(Kamu)</span> @endif
                        </span>
                        <span class="text-yellow-500 font-bold text-sm">⭐ {{ $student->total_stars }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Activity --}}
            @if($recentProgress->count() > 0)
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <h3 class="font-display text-xl text-gray-800 mb-4">📅 Aktivitas Terakhir</h3>
                <div class="space-y-3">
                    @foreach($recentProgress as $prog)
                    <div class="flex items-center gap-3">
                        <span class="text-xl">{{ $prog->quest->character_emoji }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sm text-gray-700 truncate">{{ $prog->quest->title }}</p>
                            <p class="text-xs text-gray-400">{{ $prog->completed_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-sm font-bold">
                            @for($s = 0; $s < $prog->stars_earned; $s++) ⭐ @endfor
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Dashboard — Cartoon Games')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- ── 1. Hero Cartoon Banner with Featured Game Showcase ── --}}
    <div class="cn-card overflow-hidden mb-8 slide-in bg-gradient-to-r from-violet-600 via-indigo-600 to-sky-500 text-white relative">
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-yellow-400/25 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute left-1/3 -bottom-10 w-48 h-48 bg-pink-500/20 rounded-full blur-xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row items-stretch">

            {{-- Left: Greeting + XP Progress + CTA --}}
            <div class="flex-1 p-6 md:p-8 flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 bg-yellow-300 text-slate-900 font-display text-xs px-3 py-1 rounded-full border-2 border-slate-900 shadow-[0_2px_0_#0f172a] mb-3 uppercase">
                        <span>👋</span> Selamat Datang Kembali!
                    </div>
                    <h1 class="font-display text-3xl md:text-5xl text-white drop-shadow-[0_2px_4px_rgba(0,0,0,0.3)]">
                        Halo, {{ auth()->user()->name }}! 🚀
                    </h1>
                    <p class="text-indigo-100 font-bold text-sm md:text-base mt-2 max-w-lg">
                        Petualangan logika barumu sudah siap! Kumpulkan bintang sebanyak-banyaknya dan taklukkan semua level!
                    </p>
                </div>

                {{-- XP & Level Progress Box --}}
                <div class="mt-6 bg-slate-900/40 backdrop-blur-md border-2 border-white/20 rounded-2xl p-4 max-w-md">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-display text-yellow-300 text-sm">
                            ⭐ Level {{ $user->level }} — {{ $user->level_title }}
                        </span>
                        <span class="bg-yellow-400 text-slate-900 font-display text-xs px-2 py-0.5 rounded-md border border-slate-900">
                            {{ $user->level_progress_percent }}%
                        </span>
                    </div>
                    <div class="xp-bar" role="progressbar"
                         aria-valuenow="{{ $user->level_progress_percent }}"
                         aria-valuemin="0" aria-valuemax="100"
                         aria-label="Progress XP">
                        <div class="xp-fill" style="width:{{ $user->level_progress_percent }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs font-bold text-slate-200 mt-1.5">
                        <span>{{ $user->level_progress }} / 200 XP</span>
                        <span>Level Berikutnya ➜ Lv.{{ $user->level + 1 }}</span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('student.quests') }}"
                       class="btn-cn bg-yellow-400 text-slate-900 px-6 py-3 text-base shadow-[0_5px_0_#0f172a]">
                        <span class="mr-2">🎮</span> LIHAT SEMUA GAME
                    </a>
                    @if($featuredQuest)
                    <a href="{{ route('student.quest.play', $featuredQuest->slug) }}"
                       class="btn-cn bg-emerald-400 text-slate-900 px-5 py-3 text-sm shadow-[0_5px_0_#0f172a]">
                        <span class="mr-1.5">▶</span> MAIN GAME UNGGULAN
                    </a>
                    @endif
                </div>
            </div>

            {{-- Right: Featured Game Card (With Real Picture!) --}}
            @if($featuredQuest)
            <div class="lg:w-80 bg-slate-900/30 p-6 flex flex-col justify-center border-t-2 lg:border-t-0 lg:border-l-2 border-white/20">
                <div class="bg-white rounded-2xl border-3 border-slate-900 shadow-[0_6px_0_#0f172a] overflow-hidden text-slate-800">
                    {{-- Game Image --}}
                    <div class="relative aspect-video w-full bg-slate-800 border-b-2 border-slate-900">
                        <img src="{{ $featuredQuest->thumbnail_url }}"
                             alt="{{ $featuredQuest->title }}"
                             class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 cn-sticker bg-yellow-300 text-slate-900 text-[10px]">
                            GAME PILIHAN
                        </span>
                        <span class="absolute top-2 right-2 cn-sticker bg-pink-500 text-white text-[10px]">
                            {{ $featuredQuest->difficulty_label }}
                        </span>
                    </div>
                    {{-- Text Info --}}
                    <div class="p-4">
                        <h2 class="font-display text-slate-900 text-base leading-snug truncate">
                            {{ $featuredQuest->title }}
                        </h2>
                        <div class="flex items-center gap-2 mt-2 text-xs font-bold text-slate-600">
                            <span class="text-amber-500">⭐ {{ $featuredQuest->max_stars }} Bintang</span>
                            <span>•</span>
                            <span class="text-indigo-600">+{{ $featuredQuest->xp_reward }} XP</span>
                        </div>
                        <a href="{{ route('student.quest.play', $featuredQuest->slug) }}"
                           class="btn-cn w-full mt-3 py-2 text-sm bg-yellow-400 text-slate-900">
                            Mainkan Sekarang ▶
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ── 2. Playful Cartoon Stats Row ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8 slide-in-delay-1">

        {{-- Total Stars --}}
        <div class="cn-card bg-amber-50 p-5 flex items-center gap-4">
            <div class="w-13 h-13 rounded-2xl bg-yellow-400 border-2.5 border-slate-900 shadow-[0_3px_0_#0f172a] flex items-center justify-center flex-shrink-0">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="#78350F" aria-hidden="true">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
            </div>
            <div>
                <p class="font-display text-3xl text-amber-900 leading-tight">{{ $user->total_stars }}</p>
                <p class="font-bold text-xs text-amber-700 uppercase tracking-wide">Total Bintang</p>
            </div>
        </div>

        {{-- Total XP --}}
        <div class="cn-card bg-indigo-50 p-5 flex items-center gap-4">
            <div class="w-13 h-13 rounded-2xl bg-indigo-500 border-2.5 border-slate-900 shadow-[0_3px_0_#0f172a] flex items-center justify-center flex-shrink-0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#FDE047" stroke="#0F172A" stroke-width="1.5" aria-hidden="true">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
            </div>
            <div>
                <p class="font-display text-3xl text-indigo-900 leading-tight">{{ $user->total_xp }}</p>
                <p class="font-bold text-xs text-indigo-700 uppercase tracking-wide">Total XP</p>
            </div>
        </div>

        {{-- Quests Completed --}}
        <div class="cn-card bg-emerald-50 p-5 flex items-center gap-4">
            <div class="w-13 h-13 rounded-2xl bg-emerald-400 border-2.5 border-slate-900 shadow-[0_3px_0_#0f172a] flex items-center justify-center flex-shrink-0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#064E3B" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <div>
                <p class="font-display text-3xl text-emerald-900 leading-tight">
                    {{ $completedCount }}<span class="text-emerald-700 text-lg">/{{ $totalQuests }}</span>
                </p>
                <p class="font-bold text-xs text-emerald-700 uppercase tracking-wide">Game Selesai</p>
            </div>
        </div>

        {{-- Daily Streak --}}
        <div class="cn-card bg-rose-50 p-5 flex items-center gap-4">
            <div class="w-13 h-13 rounded-2xl bg-rose-500 border-2.5 border-slate-900 shadow-[0_3px_0_#0f172a] flex items-center justify-center flex-shrink-0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="#FDE047" stroke="#0F172A" stroke-width="1.5" aria-hidden="true">
                    <path d="M12 2C9.14 2 7 4.14 7 7c0 1.64.8 3.09 2 4a6 6 0 0 0 3 7 6 6 0 0 0 3-7c1.2-.91 2-2.36 2-4 0-2.86-2.14-5-5-5z"/>
                </svg>
            </div>
            <div>
                <p class="font-display text-3xl text-rose-900 leading-tight">{{ $user->streak_days }} 🔥</p>
                <p class="font-bold text-xs text-rose-700 uppercase tracking-wide">Hari Berturut</p>
            </div>
        </div>
    </div>

    {{-- ── 3. Main Two-Column Layout ── --}}
    <div class="grid lg:grid-cols-3 gap-8">

        {{-- Left 2 Columns: Category Arcades + Recent Games --}}
        <div class="lg:col-span-2 space-y-8 slide-in-delay-2">

            {{-- Category Showcase --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-display text-2xl text-slate-900 flex items-center gap-2">
                        <span>🎯</span> Kategori Permainan Koding
                    </h2>
                    <a href="{{ route('student.quests') }}" class="font-display text-sm text-indigo-600 hover:text-indigo-800 underline">
                        Semua Kategori ➜
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                    $categories = [
                        [
                            'category' => 'sequence',
                            'label'    => 'Urutan Langkah',
                            'desc'     => 'Bantu karakter melangkah dengan instruksi urut!',
                            'bg'       => 'bg-rose-100', 'btn' => 'bg-rose-500', 'color' => 'text-rose-900', 'emoji' => '🐾',
                        ],
                        [
                            'category' => 'condition',
                            'label'    => 'Pilihan Kondisi',
                            'desc'     => 'Ambil keputusan pintar dengan aturan IF-ELSE!',
                            'bg'       => 'bg-sky-100',  'btn' => 'bg-sky-500',  'color' => 'text-sky-900',  'emoji' => '🌦️',
                        ],
                        [
                            'category' => 'loop',
                            'label'    => 'Pengulangan (Loop)',
                            'desc'     => 'Hitung langkah efisien dengan perulangan otomatis!',
                            'bg'       => 'bg-emerald-100', 'btn' => 'bg-emerald-500', 'color' => 'text-emerald-900', 'emoji' => '🔄',
                        ],
                        [
                            'category' => 'pattern',
                            'label'    => 'Pola & Deret Ajaib',
                            'desc'     => 'Pecahkan sandi algoritma tersembunyi!',
                            'bg'       => 'bg-purple-100', 'btn' => 'bg-purple-600', 'color' => 'text-purple-900', 'emoji' => '🧩',
                        ],
                    ];
                    @endphp

                    @foreach($categories as $cat)
                    <a href="{{ route('student.quests', ['category' => $cat['category']]) }}"
                       class="cn-card {{ $cat['bg'] }} p-5 flex items-start gap-4 group">
                        <div class="w-12 h-12 rounded-xl {{ $cat['btn'] }} text-white border-2 border-slate-900 shadow-[0_3px_0_#0f172a] flex items-center justify-center text-xl flex-shrink-0 group-hover:scale-110 transition-transform">
                            {{ $cat['emoji'] }}
                        </div>
                        <div>
                            <h3 class="font-display text-lg {{ $cat['color'] }} group-hover:text-indigo-600 transition-colors">
                                {{ $cat['label'] }}
                            </h3>
                            <p class="text-slate-600 text-xs font-semibold mt-1 leading-snug">
                                {{ $cat['desc'] }}
                            </p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Recent Activity --}}
            @if($recentProgress->count() > 0)
            <div class="cn-card p-6">
                <h2 class="font-display text-xl text-slate-900 mb-4 flex items-center gap-2">
                    <span>⚡</span> Petualangan Terakhir Kamu
                </h2>
                <div class="space-y-3">
                    @foreach($recentProgress as $prog)
                    <div class="flex items-center gap-3 p-3 bg-slate-50 border-2 border-slate-200 rounded-xl hover:bg-yellow-50 hover:border-yellow-300 transition-all">
                        {{-- Small Quest Thumbnail --}}
                        <div class="w-14 h-10 rounded-lg overflow-hidden border border-slate-900 flex-shrink-0 bg-slate-900">
                            <img src="{{ $prog->quest->thumbnail_url }}" alt="{{ $prog->quest->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-display text-sm text-slate-900 truncate">{{ $prog->quest->title }}</p>
                            <p class="text-xs text-slate-500 font-bold">{{ $prog->completed_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-1 bg-yellow-300 border border-slate-900 px-2 py-0.5 rounded-lg">
                            <span class="font-display text-xs text-slate-900">{{ $prog->stars_earned }}/3 ⭐</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Right Column: Leaderboard & Badges --}}
        <div class="space-y-8 slide-in-delay-3">

            {{-- Leaderboard --}}
            <div class="cn-card p-6 bg-yellow-50">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-display text-xl text-slate-900 flex items-center gap-2">
                        <span>🏆</span> Papan Juara
                    </h2>
                    <span class="cn-sticker bg-yellow-400 text-slate-900 text-[10px]">TOP SISWA</span>
                </div>

                <div class="space-y-2.5">
                    @foreach($leaderboard as $i => $student)
                    <div class="flex items-center gap-3 p-2.5 rounded-xl border-2 border-slate-900
                                {{ $student->id === auth()->id() ? 'bg-indigo-100 shadow-[0_2px_0_#0f172a]' : 'bg-white' }}">
                        {{-- Rank badge --}}
                        <span class="font-display text-sm w-7 h-7 rounded-lg border border-slate-900 flex items-center justify-center flex-shrink-0
                              {{ $i === 0 ? 'bg-yellow-400 text-slate-900' : ($i === 1 ? 'bg-slate-300 text-slate-800' : ($i === 2 ? 'bg-amber-600 text-white' : 'bg-slate-100 text-slate-600')) }}">
                            {{ $i + 1 }}
                        </span>

                        <span class="flex-1 font-display text-sm text-slate-800 truncate">
                            {{ $student->name }}
                            @if($student->id === auth()->id())
                            <span class="text-indigo-600 text-xs">(Kamu)</span>
                            @endif
                        </span>

                        <div class="flex items-center gap-1 font-display text-sm text-amber-600 bg-yellow-100 border border-yellow-300 px-2 py-0.5 rounded-md">
                            <span>⭐</span>
                            <span>{{ $student->total_stars }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Badges / Lencana --}}
            <div class="cn-card p-6 bg-white">
                <h2 class="font-display text-xl text-slate-900 mb-4 flex items-center gap-2">
                    <span>🎖️</span> Koleksi Lencana
                </h2>

                @if($user->badges->count() > 0)
                <div class="grid grid-cols-3 gap-3">
                    @foreach($user->badges->take(6) as $badge)
                    <div class="text-center group" title="{{ $badge->name }}">
                        <div class="w-14 h-14 rounded-2xl border-2.5 border-slate-900 shadow-[0_3px_0_#0f172a] flex items-center justify-center mx-auto mb-1.5 group-hover:scale-110 transition-transform"
                             style="background: {{ $badge->color }}25">
                            <span class="font-display text-base font-bold" style="color: {{ $badge->color }}">
                                {{ $badge->icon_emoji ?? strtoupper(substr($badge->name, 0, 2)) }}
                            </span>
                        </div>
                        <p class="text-[11px] font-display text-slate-700 truncate">{{ $badge->name }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-6 bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl p-4">
                    <span class="text-3xl block mb-2">🔒</span>
                    <p class="font-display text-slate-700 text-sm">Belum Ada Lencana</p>
                    <p class="text-xs text-slate-500 font-bold mt-1">Selesaikan game untuk membuka lencana emas!</p>
                </div>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection

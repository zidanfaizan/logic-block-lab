@extends('layouts.app')
@section('title', 'Pilih Game — Cartoon Games')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- ── Cartoon Network Style Banner Header ── --}}
    <div class="relative overflow-hidden rounded-3xl border-3 border-slate-900 shadow-[0_8px_0_#0f172a] bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 p-6 md:p-8 mb-8 text-white slide-in">
        {{-- Decorative Cartoon Shapes --}}
        <div class="absolute -right-8 -top-8 w-40 h-40 bg-yellow-400/20 rounded-full blur-xl pointer-events-none"></div>
        <div class="absolute right-12 bottom-0 w-32 h-32 bg-cyan-400/20 rounded-full blur-lg pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left">
                <div class="inline-flex items-center gap-2 bg-yellow-400 text-slate-900 font-display text-xs px-3.5 py-1 rounded-full border-2 border-slate-900 shadow-[0_2px_0_#0f172a] mb-2 uppercase tracking-wide">
                    <span>🎮</span> ARCADE PETUALANGAN KODING
                </div>
                <h1 class="font-display text-3xl md:text-5xl text-white drop-shadow-[0_3px_3px_rgba(0,0,0,0.4)]">
                    Pilih Game Favoritmu!
                </h1>
                <p class="text-yellow-100 font-bold text-sm md:text-base mt-2 max-w-xl leading-relaxed">
                    Setiap game punya cerita dan misi seru! Pecahkan puzzle algoritma, kumpulkan semua bintang emas, dan jadilah juara koding!
                </p>
            </div>

            {{-- Arcade Mascot / Quick Stat Box --}}
            <div class="bg-white/15 backdrop-blur-md border-2 border-white/30 rounded-2xl p-4 flex items-center gap-4 text-center flex-shrink-0">
                <div class="bg-yellow-400 border-2 border-slate-900 p-2.5 rounded-xl shadow-[0_3px_0_#0f172a]">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="#D97706" aria-hidden="true">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </div>
                <div class="text-left">
                    <p class="font-display text-2xl text-yellow-300 leading-none">{{ auth()->user()->total_stars }}</p>
                    <p class="text-white text-xs font-bold mt-1">Bintang Koleksimu</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Category Filter (Chunky Cartoon Buttons) ── --}}
    <div class="flex flex-wrap gap-3 mb-8 items-center">
        <span class="font-display text-slate-800 text-sm hidden sm:inline mr-1">Kategori:</span>
        @php
        $categories = [
            ['value' => 'all',       'label' => 'Semua Game',      'color' => 'bg-slate-900 text-white',  'tag' => '🌟'],
            ['value' => 'sequence',  'label' => 'Urutan Langkah',  'color' => 'bg-rose-500 text-white',   'tag' => '🐾'],
            ['value' => 'condition', 'label' => 'Pilihan Kondisi', 'color' => 'bg-sky-500 text-white',    'tag' => '🌦️'],
            ['value' => 'loop',      'label' => 'Pengulangan',     'color' => 'bg-emerald-500 text-white','tag' => '🔄'],
            ['value' => 'pattern',   'label' => 'Pola Seru',       'color' => 'bg-purple-600 text-white', 'tag' => '🧩'],
        ];
        @endphp

        @foreach($categories as $cat)
        <a href="{{ route('student.quests', ['category' => $cat['value']]) }}"
           class="btn-cn px-4 py-2 text-sm font-display transition-transform
                  {{ $category === $cat['value']
                      ? $cat['color'] . ' scale-105 ring-2 ring-yellow-400'
                      : 'bg-white text-slate-700 hover:bg-slate-100' }}">
            <span class="mr-1.5">{{ $cat['tag'] }}</span>
            {{ $cat['label'] }}
        </a>
        @endforeach
    </div>

    {{-- ── Game Grid with Pictures for Every Game! ── --}}
    @if($quests->isEmpty())
    <div class="text-center py-20 bg-white rounded-3xl border-3 border-slate-900 shadow-[0_6px_0_#0f172a] p-8">
        <div class="w-20 h-20 rounded-2xl bg-yellow-100 border-2.5 border-slate-900 flex items-center justify-center mx-auto mb-4">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
        </div>
        <h3 class="font-display text-2xl text-slate-800">Ups, belum ada game di kategori ini!</h3>
        <p class="text-slate-500 mt-2 font-bold text-sm">Pilih kategori lain atau cek lagi nanti ya!</p>
        <a href="{{ route('student.quests') }}" class="btn-cn bg-yellow-400 text-slate-900 px-6 py-2.5 mt-4 text-base">
            Lihat Semua Game
        </a>
    </div>
    @else
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($quests as $i => $quest)
        @php $progress = $progressMap[$quest->id] ?? null; @endphp

        <div class="cn-card group flex flex-col overflow-hidden slide-in-delay-{{ ($i % 3) + 1 }}">

            {{-- ── GAME COVER IMAGE (Cartoon Network Poster) ── --}}
            <div class="relative aspect-video w-full overflow-hidden bg-slate-900 border-b-3 border-slate-900">
                <img src="{{ $quest->thumbnail_url }}"
                     alt="Cover game {{ $quest->title }}"
                     class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-300 ease-out"
                     loading="lazy">

                {{-- Badges Overlaid on Picture --}}
                <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                    <span class="cn-sticker bg-yellow-300 text-slate-900">
                        {{ $quest->category_label }}
                    </span>
                    <span class="cn-sticker bg-white/95 text-slate-900 text-[11px]">
                        Kelas {{ $quest->min_grade }}-{{ $quest->max_grade }}
                    </span>
                </div>

                <div class="absolute top-3 right-3">
                    <span class="cn-sticker {{ $quest->difficulty === 1 ? 'bg-emerald-400 text-slate-900' : ($quest->difficulty === 2 ? 'bg-amber-400 text-slate-900' : 'bg-rose-500 text-white') }}">
                        {{ $quest->difficulty_label }}
                    </span>
                </div>

                {{-- Character Badge Pill at bottom of image --}}
                <div class="absolute bottom-2.5 left-3">
                    <span class="inline-flex items-center gap-1.5 bg-slate-900/90 text-yellow-300 px-2.5 py-1 rounded-lg text-xs font-display border border-yellow-300/40">
                        <span>{{ $quest->character_name }}</span>
                    </span>
                </div>
            </div>

            {{-- ── Content Details ── --}}
            <div class="p-5 flex-1 flex flex-col justify-between">
                <div>
                    {{-- Title --}}
                    <h3 class="font-display text-xl text-slate-900 group-hover:text-indigo-600 transition-colors leading-snug">
                        {{ $quest->title }}
                    </h3>

                    {{-- Description --}}
                    <p class="text-slate-600 text-xs font-semibold mt-2 line-clamp-2 leading-relaxed">
                        {{ $quest->description }}
                    </p>

                    {{-- Stars earned indicator (if completed) --}}
                    @if($progress && $progress->status === 'completed')
                    <div class="mt-3.5 inline-flex items-center gap-1 bg-yellow-100 border-2 border-yellow-400 px-2.5 py-1 rounded-xl">
                        @for($s = 0; $s < 3; $s++)
                        <svg width="15" height="15" viewBox="0 0 24 24" aria-hidden="true"
                             fill="{{ $s < $progress->stars_earned ? '#F59E0B' : '#CBD5E1' }}">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        @endfor
                        <span class="font-display text-xs text-amber-800 ml-1">Selesai ({{ $progress->stars_earned }}/3 ⭐)</span>
                    </div>
                    @endif
                </div>

                {{-- Stats and CTA --}}
                <div class="mt-4 pt-3 border-t-2 border-slate-100">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-3.5">
                        <span class="inline-flex items-center gap-1 text-amber-600">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="#F59E0B" aria-hidden="true">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            Maks {{ $quest->max_stars }} Bintang
                        </span>
                        <span class="inline-flex items-center gap-1 text-emerald-600">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                            </svg>
                            +{{ $quest->xp_reward }} XP
                        </span>
                        <span class="inline-flex items-center gap-1 text-slate-500">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                            {{ $quest->time_limit_minutes }} mnt
                        </span>
                    </div>

                    {{-- Chunky Cartoon Button --}}
                    <a href="{{ route('student.quest.play', $quest->slug) }}"
                       class="btn-cn w-full py-2.5 text-base text-white flex items-center justify-center gap-2"
                       style="background: {{ $quest->theme_color ?? '#6366F1' }};">
                        @if(!$progress)
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="5 3 19 12 5 21 5 3"/>
                            </svg>
                            <span>MAINKAN SEKARANG</span>
                        @elseif($progress->status === 'completed')
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M1 4v6h6M23 20v-6h-6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
                            </svg>
                            <span>MAIN LAGI</span>
                        @else
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="5 3 19 12 5 21 5 3"/>
                            </svg>
                            <span>LANJUTKAN</span>
                        @endif
                    </a>
                </div>
            </div>

        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection

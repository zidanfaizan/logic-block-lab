@extends('layouts.app')
@section('title', 'Pilih Quest')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-8 slide-in">
        <h1 class="font-display text-3xl md:text-4xl text-gray-800">🗺️ Pilih Petualanganmu!</h1>
        <p class="text-gray-500 mt-2 font-semibold">Selesaikan quest dan kumpulkan bintang sebanyak-banyaknya! ⭐</p>
    </div>

    {{-- Category Filter --}}
    <div class="flex flex-wrap gap-3 mb-8">
        @php
        $categories = [
            ['value' => 'all',       'label' => 'Semua Quest',    'icon' => '🎮'],
            ['value' => 'sequence',  'label' => 'Urutan Langkah', 'icon' => '📋'],
            ['value' => 'condition', 'label' => 'Pilihan Kondisi','icon' => '🔀'],
            ['value' => 'loop',      'label' => 'Pengulangan',    'icon' => '🔄'],
            ['value' => 'pattern',   'label' => 'Pola Seru',      'icon' => '🧩'],
        ];
        @endphp

        @foreach($categories as $cat)
        <a href="{{ route('student.quests', ['category' => $cat['value']]) }}"
           class="px-5 py-2.5 rounded-full font-bold text-sm transition-all duration-200 hover:scale-105 {{ $category === $cat['value'] ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-white text-gray-600 hover:bg-gray-100 shadow-md' }}">
            {{ $cat['icon'] }} {{ $cat['label'] }}
        </a>
        @endforeach
    </div>

    {{-- Quest Grid --}}
    @if($quests->isEmpty())
    <div class="text-center py-20">
        <div class="text-7xl mb-4 animate-bounce">🔍</div>
        <h3 class="font-display text-2xl text-gray-600">Belum ada quest di sini</h3>
        <p class="text-gray-400 mt-2">Quest baru akan segera hadir!</p>
    </div>
    @else
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($quests as $i => $quest)
        @php $progress = $progressMap[$quest->id] ?? null; @endphp

        <div class="bg-white rounded-3xl shadow-lg overflow-hidden card-hover slide-in-delay-{{ ($i % 3) + 1 }} border-2 border-transparent hover:border-primary/20">
            {{-- Color Bar --}}
            <div class="h-2" style="background: {{ $quest->theme_color }}"></div>

            <div class="p-6">
                {{-- Header --}}
                <div class="flex items-start justify-between mb-4">
                    <div class="text-5xl" style="animation: float {{ 2 + $i * 0.3 }}s ease-in-out infinite">
                        {{ $quest->character_emoji }}
                    </div>
                    <div class="text-right space-y-1">
                        <span class="inline-block text-xs font-black px-3 py-1 rounded-full {{ $quest->difficulty_color }} shadow-sm">
                            Level {{ $quest->difficulty }}: {{ $quest->difficulty_label }}
                        </span>
                        <span class="block text-[11px] font-extrabold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700">
                            Kelas {{ $quest->min_grade }}-{{ $quest->max_grade }} SD
                        </span>
                        <span class="block text-[11px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                            {{ $quest->category_icon }} {{ $quest->category_label }}
                        </span>
                    </div>
                </div>

                {{-- Title & Desc --}}
                <h3 class="font-display text-xl text-gray-800 leading-tight">{{ $quest->title }}</h3>
                <p class="text-gray-500 text-sm mt-2 line-clamp-2">{{ $quest->description }}</p>

                {{-- Progress Stars --}}
                @if($progress && $progress->status === 'completed')
                <div class="mt-3 flex items-center gap-1">
                    @for($s = 0; $s < 3; $s++)
                        <span class="text-xl {{ $s < $progress->stars_earned ? 'text-yellow-400' : 'text-gray-200' }}">
                            {{ $s < $progress->stars_earned ? '⭐' : '☆' }}
                        </span>
                    @endfor
                    <span class="text-xs text-gray-500 font-semibold ml-1">{{ $progress->stars_earned }}/3 bintang</span>
                </div>
                @endif

                {{-- Info Row --}}
                <div class="flex items-center gap-4 mt-4 text-xs text-gray-400 font-semibold">
                    <span>⭐ Max {{ $quest->max_stars }}</span>
                    <span>⚡ +{{ $quest->xp_reward }} XP</span>
                    <span>⏱️ {{ $quest->time_limit_minutes }} mnt</span>
                </div>

                {{-- CTA Button --}}
                <a href="{{ route('student.quest.play', $quest->slug) }}"
                   class="mt-4 block text-center text-white font-black py-3 rounded-xl transition-all duration-200 hover:opacity-90 hover:scale-105 active:scale-95 shadow-md"
                   style="background: {{ $quest->theme_color }}">
                    @if(!$progress)
                        🚀 Mulai Quest!
                    @elseif($progress->status === 'completed')
                        🔄 Main Lagi ({{ $progress->stars_earned }}⭐)
                    @else
                        ▶️ Lanjutkan
                    @endif
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

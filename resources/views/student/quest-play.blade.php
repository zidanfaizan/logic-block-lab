@extends('layouts.app')
@section('title', $quest->title)

@push('styles')
<style>
    /* ─── BLOCKLY & GAME ARENA STYLING ─── */
    .blockly-workspace {
        background-color: #f8fafc;
        background-image: radial-gradient(#cbd5e1 1.5px, transparent 1.5px);
        background-size: 20px 20px;
        min-height: 380px;
        border-radius: 24px;
    }
    
    /* Interlocking Puzzle Notch Block */
    .blockly-block {
        position: relative;
        font-weight: 800;
        font-size: 0.95rem;
        padding: 10px 16px;
        border-radius: 14px;
        color: white;
        user-select: none;
        cursor: grab;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin: 4px;
        box-shadow: 0 4px 0 rgba(0,0,0,0.18), inset 0 2px 0 rgba(255,255,255,0.3);
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .blockly-block:active {
        cursor: grabbing;
        transform: translateY(2px);
        box-shadow: 0 2px 0 rgba(0,0,0,0.2);
    }
    .blockly-block.active-executing {
        animation: blockPulse 0.4s infinite alternate;
        box-shadow: 0 0 22px #fde047, 0 4px 0 rgba(0,0,0,0.3);
        transform: scale(1.05);
        z-index: 20;
    }
    @keyframes blockPulse {
        from { filter: brightness(1); }
        to   { filter: brightness(1.35); }
    }

    /* Block Categories */
    .block-motion  { background: linear-gradient(135deg, #3B82F6, #2563EB); border: 2px solid #1D4ED8; }
    .block-control { background: linear-gradient(135deg, #F59E0B, #D97706); border: 2px solid #B45309; }
    .block-logic   { background: linear-gradient(135deg, #10B981, #059669); border: 2px solid #047857; }
    .block-action  { background: linear-gradient(135deg, #8B5CF6, #7C3AED); border: 2px solid #6D28D9; }

    /* ─── THEME 1: GARDEN THEME (KOKI KUCING & IKAN) ─── */
    .board-garden {
        background: radial-gradient(circle, #f0fdf4 0%, #dcfce7 100%);
        border: 4px solid #86efac;
        box-shadow: 0 10px 25px -5px rgba(34, 197, 94, 0.2), inset 0 2px 4px rgba(255,255,255,0.8);
    }
    .grid-tile-garden {
        width: 66px;
        height: 66px;
        border: 2px solid #bbf7d0;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        background: #ffffff;
        box-shadow: inset 0 -4px 0 #e2e8f0;
        transition: all 0.25s ease;
        position: relative;
    }
    .grid-tile-garden.tile-path { background: #ffffff; }
    .grid-tile-garden.tile-visited { background: #ecfdf5; border-color: #a7f3d0; }
    .grid-tile-garden.tile-obstacle { background: #475569; border-color: #334155; box-shadow: inset 0 -4px 0 #1e293b; }
    .grid-tile-garden.tile-player { background: #fef3c7; border-color: #f59e0b; box-shadow: 0 0 18px rgba(245, 158, 11, 0.5); }
    .grid-tile-garden.tile-goal { background: #dbeafe; border-color: #3b82f6; box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); animation: pulseGoal 1.5s infinite alternate; }

    /* ─── THEME 2: COSMIC SPACE THEME (PENJELAJAH ANTARIKSA KIKI) ─── */
    .board-space {
        background: radial-gradient(ellipse at bottom, #0f172a 0%, #030712 100%);
        border: 4px solid #6366f1;
        box-shadow: 0 0 35px rgba(99, 102, 241, 0.4), inset 0 0 20px rgba(79, 70, 229, 0.3);
        position: relative;
        overflow: hidden;
    }
    .board-space::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(#818cf8 1px, transparent 1px), radial-gradient(#c7d2fe 1px, transparent 1px);
        background-size: 35px 35px, 70px 70px;
        background-position: 0 0, 15px 15px;
        opacity: 0.3;
        pointer-events: none;
    }
    .grid-tile-space {
        width: 66px;
        height: 66px;
        border: 2px solid rgba(99, 102, 241, 0.4);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        background: rgba(15, 23, 42, 0.85);
        box-shadow: inset 0 0 12px rgba(99, 102, 241, 0.15);
        transition: all 0.25s ease;
        position: relative;
        color: #e0e7ff;
    }
    .grid-tile-space.tile-path { background: rgba(30, 41, 59, 0.7); }
    .grid-tile-space.tile-visited { background: rgba(67, 56, 202, 0.35); border-color: #818cf8; box-shadow: 0 0 10px rgba(99, 102, 241, 0.4); }
    .grid-tile-space.tile-obstacle { background: radial-gradient(circle, #312e81 0%, #0f172a 100%); border-color: #ef4444; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4); }
    .grid-tile-space.tile-player { background: radial-gradient(circle, #3b82f6 0%, #1e1b4b 100%); border-color: #38bdf8; box-shadow: 0 0 25px rgba(56, 189, 248, 0.8); }
    .grid-tile-space.tile-goal { background: radial-gradient(circle, #4f46e5 0%, #1e1b4b 100%); border-color: #a855f7; box-shadow: 0 0 25px rgba(168, 85, 247, 0.7); animation: pulseSpaceGoal 1.5s infinite alternate; }

    @keyframes pulseGoal {
        from { transform: scale(1); filter: drop-shadow(0 0 4px #3b82f6); }
        to   { transform: scale(1.08); filter: drop-shadow(0 0 12px #3b82f6); }
    }
    @keyframes pulseSpaceGoal {
        from { transform: scale(1); filter: drop-shadow(0 0 6px #a855f7); }
        to   { transform: scale(1.1); filter: drop-shadow(0 0 18px #c084fc); }
    }

    /* Player Directional Rotations with smooth transitions */
    .player-sprite {
        display: inline-block;
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        transform-origin: center center;
    }
    .player-dir-0 { transform: rotate(-90deg); } /* Atas */
    .player-dir-1 { transform: rotate(0deg); }   /* Kanan */
    .player-dir-2 { transform: rotate(90deg); }  /* Bawah */
    .player-dir-3 { transform: rotate(180deg); } /* Kiri */

    /* Screen Shake on Collision */
    @keyframes screenShake {
        0%, 100% { transform: translate(0, 0); }
        20% { transform: translate(-8px, 6px); }
        40% { transform: translate(8px, -6px); }
        60% { transform: translate(-6px, -4px); }
        80% { transform: translate(6px, 4px); }
    }
    .shake-active { animation: screenShake 0.4s ease-in-out; }

    /* Interactive Drag-and-Drop & Arcade Gadget Styles */
    .interactive-slot {
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .draggable-card {
        cursor: grab;
        user-select: none;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .draggable-card:active {
        cursor: grabbing;
        transform: scale(0.96);
    }

    /* Result Modal Overlay */
    .result-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100;
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .result-card { animation: popUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    @keyframes popUp {
        from { transform: scale(0.7); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6" x-data="BlocklyGame()" x-init="init()">

    {{-- Level Up Toast Notification --}}
    <div x-show="showLevelToast" x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         class="fixed top-20 right-6 z-50 bg-gradient-to-r from-emerald-500 via-teal-500 to-indigo-600 text-white font-black text-lg px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border-2 border-emerald-300">
        <span class="text-3xl animate-bounce">🎉</span>
        <span x-text="levelToastMessage"></span>
    </div>

    {{-- ─── HEADER BAR WITH CARTOON NETWORK STYLING & GAME ARTWORK ─── --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-white rounded-3xl p-4 md:p-5 border-3 border-slate-900 shadow-[0_6px_0_#0f172a]">
        <div class="flex items-center gap-4">
            <a href="{{ route('student.quests') }}"
               class="btn-cn bg-yellow-400 text-slate-900 px-3.5 py-2 text-sm flex items-center gap-1.5">
                <span>←</span> Keluar
            </a>

            {{-- Game Mini Cover Picture --}}
            <div class="w-16 h-12 rounded-xl overflow-hidden border-2 border-slate-900 shadow-[0_2px_0_#0f172a] flex-shrink-0 bg-slate-900">
                <img src="{{ $quest->thumbnail_url }}" alt="{{ $quest->title }}" class="w-full h-full object-cover">
            </div>

            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                    <span class="cn-sticker bg-yellow-300 text-slate-900 text-[10px]">{{ $quest->category_label }}</span>
                    <span class="cn-sticker {{ $quest->difficulty === 1 ? 'bg-emerald-400 text-slate-900' : ($quest->difficulty === 2 ? 'bg-amber-400 text-slate-900' : 'bg-rose-500 text-white') }} text-[10px]">
                        {{ $quest->difficulty_label }}
                    </span>
                    <span class="hidden md:inline-block cn-sticker bg-indigo-100 text-indigo-900 text-[10px]">
                        Kelas {{ $quest->min_grade }}-{{ $quest->max_grade }}
                    </span>
                </div>
                <h1 class="font-display text-xl md:text-2xl text-slate-900 tracking-wide mt-1">{{ $quest->title }}</h1>
            </div>
        </div>

        {{-- Level Indicator & Timer --}}
        <div class="flex items-center gap-4">
            {{-- Multi-Level / Stage Indicator --}}
            <template x-if="levels.length > 0">
                <div class="flex items-center gap-2 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 px-4 py-2 rounded-2xl text-indigo-900 font-black text-sm shadow-sm">
                    <span class="text-lg">🎮</span>
                    <span>Level: <span class="text-indigo-600 text-base" x-text="`${currentLevelIndex + 1} / ${levels.length}`"></span></span>
                </div>
            </template>
            <template x-if="stages.length > 0">
                <div class="flex items-center gap-2 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 px-4 py-2 rounded-2xl text-emerald-900 font-black text-sm shadow-sm">
                    <span class="text-lg">🧩</span>
                    <span>Tantangan: <span class="text-emerald-600 text-base" x-text="`${currentStage + 1} / ${stages.length}`"></span></span>
                </div>
            </template>

            {{-- Timer Ring --}}
            <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 px-4 py-2 rounded-2xl">
                <div class="relative w-10 h-10 flex items-center justify-center">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                        <path class="text-amber-200" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="transition-all duration-1000" :class="timeLeft <= 15 ? 'text-red-500 animate-pulse' : 'text-amber-500'"
                              :stroke-dasharray="`${timerPercent}, 100`" stroke-width="4" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <span class="absolute text-xs font-black" :class="timeLeft <= 15 ? 'text-red-600 animate-pulse' : 'text-amber-800'" x-text="timerDisplay"></span>
                </div>
                <div>
                    <p class="text-xs text-amber-600 font-bold">Sisa Waktu</p>
                    <p class="text-sm font-black text-amber-900" x-text="timerDisplay"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── STORY / INSTRUCTION BUBBLE (THEMED) ─── --}}
    <div class="rounded-3xl p-5 mb-6 text-white shadow-lg flex items-center gap-4 transition-all"
         :class="isSpaceTheme ? 'bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border-2 border-indigo-500/50 shadow-indigo-500/20' : 'bg-gradient-to-r from-amber-400 via-orange-400 to-amber-500'">
        <div class="text-5xl animate-bounce-slow bg-white/20 p-3 rounded-2xl backdrop-blur-sm">
            <span x-text="isSpaceTheme ? '👨‍🚀' : '{{ $quest->character_emoji }}'"></span>
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-3">
                <h3 class="font-display text-xl text-white drop-shadow-sm"
                    x-text="isSpaceTheme ? 'Pusat Komando Kokpit Kiki' : '{{ $quest->character_name }} Minta Bantuan!'"></h3>
                <template x-if="levels.length > 0">
                    <span class="bg-white/20 text-white font-black text-xs px-3 py-1 rounded-full backdrop-blur-sm border border-white/30"
                          x-text="currentLevelData?.title || `Level ${currentLevelIndex+1}`"></span>
                </template>
                <template x-if="stages.length > 0 && stages[currentStage]?.title">
                    <span class="bg-white/20 text-white font-black text-xs px-3 py-1 rounded-full backdrop-blur-sm border border-white/30"
                          x-text="stages[currentStage]?.title"></span>
                </template>
            </div>
            <p class="text-white/95 text-sm md:text-base font-semibold mt-1"
               x-text="currentLevelData?.description || (stages.length > 0 ? (stages[currentStage]?.description || stages[currentStage]?.condition) : '{{ $quest->description }}')"></p>
        </div>
        <template x-if="questType === 'grid_navigation' && currentLevelData?.target_moves">
            <div class="hidden md:flex flex-col items-center bg-black/25 px-4 py-2 rounded-2xl border border-white/20 backdrop-blur-sm">
                <span class="text-xs font-bold text-amber-300">Target Efisiensi:</span>
                <span class="text-sm font-black text-white" x-text="`≤ ${currentLevelData.target_moves} Blok (⭐⭐⭐)`"></span>
            </div>
        </template>
    </div>

    {{-- ─── MAIN GAME ARENA ─── --}}
    <div class="grid lg:grid-cols-12 gap-6 mb-8">

        {{-- ─── LEFT: GAME BOARD / PUZZLE STAGE (5 Cols) ─── --}}
        <div class="lg:col-span-5 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between"
             :class="{'shake-active': isShaking}">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-display text-lg text-gray-800 flex items-center gap-2">
                        <span x-text="isSpaceTheme ? '🛰️ Radar Kosmik Galaksi' : '🗺️ Peta Petualangan'"></span>
                    </h2>
                    <template x-if="levels.length > 0">
                        <span class="text-xs px-3 py-1 rounded-full font-black"
                              :class="isSpaceTheme ? 'bg-indigo-900 text-indigo-200' : 'bg-indigo-50 text-indigo-700'"
                              x-text="`Level ${currentLevelIndex + 1} dari ${levels.length}`"></span>
                    </template>
                </div>

                {{-- ── 1. GRID NAVIGATION ARENA (SUPPORT SPACE & GARDEN THEMES) ── --}}
                <template x-if="questType === 'grid_navigation'">
                    <div class="flex flex-col items-center">

                        {{-- Cockpit / Garden Board Wrapper --}}
                        <div class="inline-block p-4 rounded-3xl transition-all"
                             :class="isSpaceTheme ? 'board-space' : 'board-garden'">

                            {{-- Starfield twinkling badge for space --}}
                            <div x-show="isSpaceTheme" class="flex items-center justify-between text-xs font-mono font-bold text-indigo-300 mb-2 px-1">
                                <span>RADAR: X:<span x-text="playerCol"></span> Y:<span x-text="playerRow"></span></span>
                                <span class="animate-pulse text-cyan-400">● TARGET INDUK: TERKUNCI</span>
                            </div>

                            <template x-for="row in gridSize" :key="row">
                                <div class="flex gap-2 mb-2">
                                    <template x-for="col in gridSize" :key="col">
                                        <div :class="[
                                                isSpaceTheme ? 'grid-tile-space' : 'grid-tile-garden',
                                                isObstacle(row-1, col-1) ? 'tile-obstacle' : '',
                                                isPlayer(row-1, col-1) ? 'tile-player' : '',
                                                isGoal(row-1, col-1) ? 'tile-goal' : '',
                                                isVisited(row-1, col-1) ? 'tile-visited' : 'tile-path'
                                             ]">
                                            {{-- Player Sprite with Directional CSS Rotation --}}
                                            <template x-if="isPlayer(row-1, col-1)">
                                                <div class="player-sprite"
                                                     :class="`player-dir-${direction}`">
                                                    <span x-text="playerEmoji"></span>
                                                </div>
                                            </template>

                                            {{-- Other Cell Entities --}}
                                            <template x-if="!isPlayer(row-1, col-1)">
                                                <span x-text="getCellEmoji(row-1, col-1)"></span>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Dynamic Themed Legend --}}
                        <div class="flex flex-wrap gap-4 mt-4 text-xs font-bold px-4 py-2 rounded-2xl"
                             :class="isSpaceTheme ? 'bg-slate-900 text-slate-300 border border-slate-800' : 'bg-gray-50 text-gray-600'">
                            <span class="flex items-center gap-1.5" x-text="isSpaceTheme ? '🚀 Pesawat Kiki' : '🐱 Koki'"></span>
                            <span class="flex items-center gap-1.5" x-text="isSpaceTheme ? '🛰️ Stasiun Alpha' : '🐟 Ikan Segar'"></span>
                            <span class="flex items-center gap-1.5" x-text="isSpaceTheme ? '☄️ Asteroid' : '🪨 Batu Taman'"></span>
                            <span class="flex items-center gap-1.5" x-text="isSpaceTheme ? '✨ Jejak Ion' : '🐾 Jejak Kaki'"></span>
                        </div>
                    </div>
                </template>

                {{-- ── 2. IF-ELSE LOGIC ARENA (SENSOR COCKPIT & RUNIC GATES) ── --}}
                <template x-if="questType === 'if_else_puzzle'">
                    <div class="py-2" x-show="currentStage < stages.length">
                        {{-- Top Stage Icon --}}
                        <div class="text-center mb-4">
                            <div class="text-6xl mb-2 animate-bounce-slow" x-text="stages[currentStage]?.emoji"></div>
                            <span class="text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-3.5 py-1 rounded-full uppercase tracking-wider">
                                Analisis Logika Komputasi
                            </span>
                        </div>

                        {{-- Active Situation / Sensor Data Box --}}
                        <div class="bg-gradient-to-br from-slate-900 to-indigo-950 text-white rounded-2xl p-4 border border-indigo-500/40 shadow-inner mb-4">
                            <div class="flex items-center justify-between text-xs font-mono font-bold text-indigo-300 pb-2 border-b border-indigo-800/60 mb-2">
                                <span>📡 STATUS SENSOR / KASUS AKTIF:</span>
                                <span class="text-emerald-400">AKTIF</span>
                            </div>
                            <p class="font-bold text-sm md:text-base text-cyan-200 whitespace-pre-line"
                               x-text="stages[currentStage]?.condition"></p>
                        </div>

                        {{-- Algorithmic Rule Box (Syntax / Mantra) --}}
                        <template x-if="stages[currentStage]?.rule_box">
                            <div class="bg-amber-50/80 border-2 border-amber-300 rounded-2xl p-3.5 mb-4 text-amber-950 font-mono text-xs">
                                <p class="font-bold text-amber-800 text-[11px] uppercase tracking-wider mb-1 flex items-center gap-1">
                                    <span>⚙️</span> Aturan Logika Algoritma (IF-ELSE):
                                </p>
                                <pre class="whitespace-pre-wrap font-bold font-mono text-xs text-amber-900" x-text="stages[currentStage]?.rule_box"></pre>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- ── 3. PATTERN RECOGNITION ARENA ── --}}
                <template x-if="questType === 'pattern_completion'">
                    <div class="text-center py-4" x-show="currentStage < stages.length">
                        <span class="text-xs font-black text-purple-700 bg-purple-100 border border-purple-300 px-3 py-1 rounded-full uppercase tracking-wider">
                            Dekoder Deret Algoritma
                        </span>
                        <h3 class="font-display text-lg text-gray-800 mt-2 mb-4" x-text="stages[currentStage]?.title || 'Pecahkan Pola Berikut:'"></h3>
                        
                        {{-- Holographic Sequence Grid with Interactive Drop Slot --}}
                        <div class="flex items-center justify-center gap-2.5 flex-wrap p-4 bg-gradient-to-r from-purple-50 via-indigo-50 to-purple-50 rounded-2xl border border-purple-200 shadow-inner">
                            <template x-for="(item, idx) in stages[currentStage]?.sequence" :key="idx">
                                <div>
                                    <template x-if="item !== '?'">
                                        <div class="w-13 h-13 rounded-2xl border-2.5 border-purple-300 bg-white text-purple-900 flex items-center justify-center font-display text-2xl shadow-sm">
                                            <span x-text="item"></span>
                                        </div>
                                    </template>
                                    <template x-if="item === '?'">
                                        <div class="interactive-slot w-15 h-15 rounded-2xl border-3 border-dashed flex items-center justify-center font-display text-2xl shadow-md cursor-pointer relative"
                                             :class="slottedPattern !== null ? 'border-purple-600 bg-purple-600 text-white scale-110 shadow-purple-500/50' : (dragOverPattern ? 'border-yellow-400 bg-yellow-200 scale-115 animate-bounce' : 'border-indigo-400 bg-indigo-50 text-indigo-500 animate-pulse')"
                                             @dragover.prevent="dragOverPattern = true"
                                             @dragleave="dragOverPattern = false"
                                             @drop.prevent="dropPattern($event)"
                                             @click="if(slottedPattern !== null && !stageAnswered) slottedPattern = null">
                                            <span x-text="slottedPattern !== null ? slottedPattern.value : '?'"></span>
                                            <span x-show="slottedPattern !== null && !stageAnswered" class="absolute -top-1.5 -right-1.5 bg-red-500 text-white w-4 h-4 rounded-full text-[10px] flex items-center justify-center shadow">✕</span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <p class="text-xs font-bold text-purple-600 mt-3" x-text="`💡 Petunjuk Deret: ${stages[currentStage]?.hint}`"></p>
                    </div>
                </template>

                {{-- ── 4. LOOP PROGRAMMING ARENA ── --}}
                <template x-if="questType === 'loop_puzzle'">
                    <div class="py-2" x-show="currentStage < stages.length">
                        <div class="text-center mb-3">
                            <div class="text-5xl mb-2 animate-wiggle">🔄</div>
                            <span class="text-xs font-black text-emerald-700 bg-emerald-100 border border-emerald-300 px-3 py-1 rounded-full uppercase tracking-wider">
                                Simulasi Perulangan (Loop)
                            </span>
                        </div>

                        {{-- Task Scenario Card --}}
                        <div class="bg-emerald-50/70 border-2 border-emerald-200 rounded-2xl p-4 mb-4">
                            <h4 class="font-display text-base text-emerald-900 mb-1" x-text="stages[currentStage]?.title"></h4>
                            <p class="text-sm font-bold text-emerald-800" x-text="stages[currentStage]?.description"></p>
                        </div>

                        {{-- Code IDE Preview for Loop --}}
                        <div class="bg-gray-900 text-green-400 font-mono rounded-2xl p-4 text-xs md:text-sm shadow-inner border border-gray-800">
                            <p><span class="text-amber-400">ULANGI</span> <span class="bg-amber-400/20 text-amber-300 font-black px-2 py-0.5 rounded border border-amber-400/40">??? KALI</span> <span class="text-amber-400">LAKUKAN:</span></p>
                            <p class="ml-6 my-1.5 text-cyan-300 font-bold" x-text="`    ${stages[currentStage]?.correct_action?.replace(/_/g, ' ')?.toUpperCase()}()`"></p>
                            <p><span class="text-amber-400">SELESAI_LOOP</span></p>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Controls Bottom Bar (Hint & Target) --}}
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <button @click="playSound('click'); showHint()"
                        class="px-4 py-2.5 rounded-2xl bg-amber-100 hover:bg-amber-200 text-amber-800 font-bold text-sm transition-all flex items-center gap-2">
                    💡 Petunjuk <span class="text-xs opacity-75">(<span x-text="hintsUsed"></span>)</span>
                </button>
                <div class="text-xs font-bold text-gray-500" x-text="`XP Hadiah: +${gameConfig.xp_reward || 100} XP`"></div>
            </div>
        </div>

        {{-- ─── RIGHT: BLOCKLY CODING WORKSPACE & MINIGAME CONTROLS (7 Cols) ─── --}}
        <div class="lg:col-span-7 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">

            {{-- ── A. GRID NAVIGATION WORKSPACE (REORDERABLE BLOCKLY PALETTE) ── --}}
            <template x-if="questType === 'grid_navigation'">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="font-display text-lg text-gray-800 flex items-center gap-2">
                                <span>🧩</span> Toolbox Perintah Algoritma (Blockly)
                            </h2>
                            <p class="text-xs text-gray-400 font-semibold">Klik blok di bawah untuk menyusun instruksi gerak</p>
                        </div>
                        <button @click="clearProgram()" class="text-xs text-red-500 hover:text-red-700 font-bold bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-xl transition-all">
                            🗑️ Reset Semua
                        </button>
                    </div>

                    {{-- Tool Palette Buttons --}}
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 mb-4 flex flex-wrap gap-2.5">
                        <button @click="addCommand('forward')"
                                class="blockly-block block-motion hover:scale-105 active:scale-95">
                            <span>⬆️</span> <span>Maju 1 Langkah</span>
                        </button>
                        <button @click="addCommand('turn_left')"
                                class="blockly-block block-control hover:scale-105 active:scale-95">
                            <span>↩️</span> <span>Belok Kiri 90°</span>
                        </button>
                        <button @click="addCommand('turn_right')"
                                class="blockly-block block-logic hover:scale-105 active:scale-95">
                            <span>↪️</span> <span>Belok Kanan 90°</span>
                        </button>
                    </div>

                    {{-- Blockly Workspace Drop & Execution Area --}}
                    <div class="blockly-workspace p-4 border-2 border-dashed border-gray-300 relative flex flex-col items-start gap-2 overflow-y-auto max-h-[340px]">
                        <div class="w-full flex items-center justify-between mb-1">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">⚡ Urutan Kode Program:</span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full"
                                      x-text="`${program.length} Blok Disusun`"></span>
                                <template x-if="currentLevelData?.target_moves">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-md"
                                          :class="program.length <= currentLevelData.target_moves ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
                                          x-text="`Target: ≤${currentLevelData.target_moves}`"></span>
                                </template>
                            </div>
                        </div>

                        {{-- Empty Workspace Placeholder --}}
                        <div x-show="program.length === 0" class="m-auto text-center py-10 text-gray-400">
                            <div class="text-5xl mb-2 opacity-50">🧩</div>
                            <p class="font-bold text-sm text-gray-600">Workspace Masih Kosong</p>
                            <p class="text-xs text-gray-400 mt-1">Klik blok warna di atas untuk menyusun algoritma navigasi!</p>
                        </div>

                        {{-- Stacked Blocks with Reorder Controls (🔼 🔽 ✕) --}}
                        <template x-for="(cmd, idx) in program" :key="idx">
                            <div class="blockly-block w-full max-w-md justify-between transition-all"
                                 :class="{
                                    'block-motion': cmd === 'forward',
                                    'block-control': cmd === 'turn_left',
                                    'block-logic':   cmd === 'turn_right',
                                    'active-executing': currentExecIndex === idx
                                 }">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs opacity-75 font-mono bg-black/20 px-2 py-0.5 rounded-md" x-text="`#${idx+1}`"></span>
                                    <span x-text="cmd === 'forward' ? '⬆️ Maju 1 Langkah' : (cmd === 'turn_left' ? '↩️ Belok Kiri 90°' : '↪️ Belok Kanan 90°')"></span>
                                </div>
                                <div class="flex items-center gap-1">
                                    {{-- Move Up Button --}}
                                    <button @click.stop="moveBlockUp(idx)"
                                            :disabled="idx === 0"
                                            class="opacity-75 hover:opacity-100 disabled:opacity-20 hover:scale-110 text-xs bg-black/20 hover:bg-black/40 px-2 py-1 rounded-md transition-all">
                                        🔼
                                    </button>
                                    {{-- Move Down Button --}}
                                    <button @click.stop="moveBlockDown(idx)"
                                            :disabled="idx === program.length - 1"
                                            class="opacity-75 hover:opacity-100 disabled:opacity-20 hover:scale-110 text-xs bg-black/20 hover:bg-black/40 px-2 py-1 rounded-md transition-all">
                                        🔽
                                    </button>
                                    {{-- Delete Button --}}
                                    <button @click.stop="removeCommand(idx)"
                                            class="opacity-75 hover:opacity-100 hover:scale-110 text-xs bg-red-600/60 hover:bg-red-600 px-2 py-1 rounded-md transition-all font-black">
                                        ✕
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- ── B. INTERACTIVE PUZZLE STAGES (IF-ELSE, PATTERN, LOOP) ── --}}
            <template x-if="questType !== 'grid_navigation'">
                <div x-show="currentStage < stages.length">

                    {{-- ── 1. IF-ELSE DRAG & DROP DECISION WORKSPACE ── --}}
                    <template x-if="questType === 'if_else_puzzle'">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h2 class="font-display text-lg text-slate-800 flex items-center gap-2">
                                        <span>🎯</span> Pasang Keputusan Logika (Drag &amp; Drop):
                                    </h2>
                                    <p class="text-xs text-slate-500 font-semibold">Tarik kartu solusi ke slot di bawah atau klik untuk memasang</p>
                                </div>
                                <span class="cn-sticker bg-sky-300 text-slate-900 text-[10px]">INTERAKTIF</span>
                            </div>

                            {{-- Interactive Decision Drop Socket --}}
                            <div class="interactive-slot p-4 mb-4 rounded-2xl border-3 border-dashed transition-all"
                                 :class="slottedCondition !== null ? 'bg-sky-50 border-sky-500 shadow-sm' : (dragOverCondition ? 'bg-yellow-100 border-yellow-400 scale-102' : 'bg-slate-50 border-slate-300')"
                                 @dragover.prevent="dragOverCondition = true"
                                 @dragleave="dragOverCondition = false"
                                 @drop.prevent="dropCondition($event)">
                                <div x-show="slottedCondition === null" class="text-center py-2.5 text-slate-400">
                                    <span class="text-3xl block mb-1">📥</span>
                                    <p class="font-display text-xs text-slate-600 uppercase">Slot Keputusan Logika Masih Kosong</p>
                                    <p class="text-[11px] font-bold text-slate-400 mt-0.5">Tarik salah satu kartu aksi di bawah ke sini!</p>
                                </div>
                                <div x-show="slottedCondition !== null" class="flex items-center gap-3">
                                    <span class="text-4xl flex-shrink-0" x-text="stages[currentStage]?.options[slottedCondition]?.emoji"></span>
                                    <div class="flex-1 min-w-0">
                                        <span class="cn-sticker bg-sky-400 text-slate-900 text-[9px] mb-1">KARTU TERPASANG</span>
                                        <p class="font-display text-sm text-slate-900 leading-snug" x-text="stages[currentStage]?.options[slottedCondition]?.text"></p>
                                    </div>
                                    <button x-show="!stageAnswered" @click="slottedCondition = null"
                                            class="text-xs bg-red-100 text-red-600 hover:bg-red-200 px-2.5 py-1.5 rounded-xl font-bold flex-shrink-0">
                                        ✕ Lepas
                                    </button>
                                </div>
                            </div>

                            {{-- Draggable Action Cards Tray --}}
                            <div class="space-y-2.5 mb-4">
                                <template x-for="(option, idx) in stages[currentStage]?.options" :key="idx">
                                    <div draggable="true"
                                         @dragstart="startDragCondition(idx, $event)"
                                         @click="selectCondition(idx)"
                                         class="draggable-card p-3.5 rounded-2xl border-2.5 font-bold text-left transition-all flex items-center gap-3 shadow-[0_2px_0_#0f172a] cursor-grab"
                                         :class="slottedCondition === idx ? 'bg-sky-100 border-sky-600 ring-2 ring-sky-400' : 'bg-white border-slate-900 hover:bg-slate-50'">
                                        <span class="text-3xl flex-shrink-0" x-text="option.emoji"></span>
                                        <p class="text-slate-800 text-xs md:text-sm font-bold flex-1" x-text="option.text"></p>
                                        <span class="text-[10px] font-display text-slate-400 hidden sm:inline">Tarik ➜</span>
                                    </div>
                                </template>
                            </div>

                            {{-- Action Button --}}
                            <button @click="executeCondition()"
                                    :disabled="slottedCondition === null || stageAnswered"
                                    class="btn-cn w-full py-3 text-base text-white disabled:opacity-40"
                                    :class="slottedCondition !== null && !stageAnswered ? 'bg-indigo-600 hover:bg-indigo-700 shadow-[0_4px_0_#0f172a]' : 'bg-slate-400'">
                                <span>⚡</span> EVALUASI &amp; EKSEKUSI KEPUTUSAN
                            </button>

                            {{-- Live Feedback Alert --}}
                            <div x-show="stageAnswered" x-transition class="mt-4 p-4 rounded-2xl font-bold text-sm border-2"
                                 :class="lastAnswerCorrect ? 'bg-emerald-50 text-emerald-900 border-emerald-400' : 'bg-red-50 text-red-900 border-red-400'">
                                <p x-text="lastAnswerCorrect ? stages[currentStage]?.feedback_correct : stages[currentStage]?.feedback_wrong"></p>
                            </div>
                        </div>
                    </template>

                    {{-- ── 2. PATTERN DRAG & DROP DECODER WORKSPACE ── --}}
                    <template x-if="questType === 'pattern_completion'">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h2 class="font-display text-lg text-slate-800 flex items-center gap-2">
                                        <span>🧩</span> Pasang Keping Pola Dekoder:
                                    </h2>
                                    <p class="text-xs text-slate-500 font-semibold">Tarik balok teka-teki ke kotak [ ? ] di sebelah kiri atau klik</p>
                                </div>
                                <span class="cn-sticker bg-purple-300 text-slate-900 text-[10px]">DRAG &amp; DROP</span>
                            </div>

                            {{-- Draggable Pieces Tray --}}
                            <div class="p-4 bg-purple-50/70 border-2.5 border-dashed border-purple-300 rounded-2xl mb-4">
                                <p class="font-display text-xs text-purple-900 mb-2.5 uppercase">Koleksi Kepingan Pola Tersedia:</p>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <template x-for="(opt, idx) in stages[currentStage]?.options" :key="idx">
                                        <div draggable="true"
                                             @dragstart="startDragPattern(opt, idx, $event)"
                                             @click="selectPattern(opt, idx)"
                                             class="draggable-card h-20 rounded-2xl border-2.5 font-display text-2xl flex flex-col items-center justify-center cursor-grab shadow-[0_3px_0_#0f172a] transition-all"
                                             :class="slottedPattern?.idx === idx ? 'bg-yellow-300 border-slate-900 scale-105' : 'bg-white border-slate-900 hover:bg-yellow-100 text-purple-900'">
                                            <span x-text="opt"></span>
                                            <span class="text-[9px] font-sans font-extrabold text-slate-500">Tarik / Klik</span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Verification Trigger Button --}}
                            <button @click="verifyPattern()"
                                    :disabled="slottedPattern === null || stageAnswered"
                                    class="btn-cn w-full py-3 text-base text-white disabled:opacity-40"
                                    :class="slottedPattern !== null && !stageAnswered ? 'bg-purple-600 hover:bg-purple-700 shadow-[0_4px_0_#0f172a]' : 'bg-slate-400'">
                                <span>✨</span> COCOKKAN &amp; UJI POLA DEKODER
                            </button>

                            {{-- Live Feedback Alert --}}
                            <div x-show="stageAnswered" x-transition class="mt-4 p-4 rounded-2xl font-bold text-sm border-2"
                                 :class="lastAnswerCorrect ? 'bg-emerald-50 text-emerald-900 border-emerald-400' : 'bg-red-50 text-red-900 border-red-400'">
                                <p x-text="lastAnswerCorrect ? 'Brilian! Tebakan polamu cocok 100% dengan deret algoritma! 🌟' : `Kurang tepat! Kepingan yang pas adalah ${stages[currentStage]?.answer}. Coba pelajari petunjuknya!`"></p>
                            </div>
                        </div>
                    </template>

                    {{-- ── 3. LOOP PROGRAMMING & SIMULATION WORKSPACE ── --}}
                    <template x-if="questType === 'loop_puzzle'">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h2 class="font-display text-lg text-slate-800 flex items-center gap-2">
                                        <span>⚙️</span> Rakit Perulangan Loop &amp; Uji Mesin:
                                    </h2>
                                    <p class="text-xs text-slate-500 font-semibold">Tarik chip angka perulangan ke slot program</p>
                                </div>
                                <span class="cn-sticker bg-emerald-300 text-slate-900 text-[10px]">SIMULATOR LOOP</span>
                            </div>

                            {{-- Interactive Code IDE with Drop Socket --}}
                            <div class="bg-slate-900 text-white rounded-2xl p-4 border-3 border-slate-900 shadow-[0_4px_0_#0f172a] font-mono text-xs md:text-sm mb-4">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-amber-400 font-black">ULANGI</span>
                                    {{-- Loop Socket --}}
                                    <div class="interactive-slot inline-flex items-center justify-center min-w-[75px] h-9 px-3 rounded-xl border-2 border-dashed font-display text-lg cursor-pointer transition-all"
                                         :class="slottedLoop !== null ? 'bg-amber-400 text-slate-900 border-amber-300 font-black' : (dragOverLoop ? 'bg-yellow-200 text-slate-900 border-yellow-400 scale-110' : 'bg-slate-800 text-amber-300 border-amber-400/60 animate-pulse')"
                                         @dragover.prevent="dragOverLoop = true"
                                         @dragleave="dragOverLoop = false"
                                         @drop.prevent="dropLoop($event)"
                                         @click="if(slottedLoop !== null && !simulatingLoop && !stageAnswered) slottedLoop = null">
                                        <span x-text="slottedLoop !== null ? `${slottedLoop.value} KALI` : '[ ? KALI ]'"></span>
                                    </div>
                                    <span class="text-amber-400 font-black">LAKUKAN:</span>
                                </div>
                                <p class="ml-6 my-2 text-cyan-300 font-bold" x-text="`    ${stages[currentStage]?.correct_action?.replace(/_/g, ' ')?.toUpperCase()}()`"></p>
                                <span class="text-amber-400 font-black">SELESAI_LOOP</span>
                            </div>

                            {{-- Loop Chips Tray --}}
                            <p class="font-bold text-xs text-slate-600 mb-2">Tarik angka pengulangan ke slot atau klik:</p>
                            <div class="grid grid-cols-4 gap-2.5 mb-4">
                                <template x-for="(opt, idx) in stages[currentStage]?.options_repeat" :key="idx">
                                    <div draggable="true"
                                         @dragstart="startDragLoop(opt, idx, $event)"
                                         @click="selectLoop(opt, idx)"
                                         class="draggable-card h-16 rounded-xl border-2.5 border-slate-900 font-display text-2xl flex flex-col items-center justify-center cursor-grab shadow-[0_3px_0_#0f172a] transition-all"
                                         :class="slottedLoop?.idx === idx ? 'bg-emerald-400 text-slate-900 scale-105' : 'bg-white text-slate-800 hover:bg-emerald-50'">
                                        <span x-text="opt"></span>
                                        <span class="text-[9px] font-sans font-extrabold text-slate-500">Putaran</span>
                                    </div>
                                </template>
                            </div>

                            {{-- Live Step Simulation Gauge --}}
                            <div x-show="simulatingLoop || stageAnswered" class="p-3 bg-emerald-50 border-2 border-emerald-400 rounded-xl mb-3">
                                <div class="flex justify-between text-xs font-display text-emerald-900 mb-1">
                                    <span>Simulasi Mesin Berputar:</span>
                                    <span x-text="`Putaran ${currentLoopStep} / ${slottedLoop?.value || 0}`"></span>
                                </div>
                                <div class="w-full bg-emerald-200 h-3 rounded-full overflow-hidden border border-emerald-400">
                                    <div class="bg-emerald-500 h-full transition-all duration-150"
                                         :style="`width: ${(currentLoopStep / (slottedLoop?.value || 1)) * 100}%`"></div>
                                </div>
                            </div>

                            {{-- Simulation Trigger Button --}}
                            <button @click="runLoopSimulation()"
                                    :disabled="slottedLoop === null || simulatingLoop || stageAnswered"
                                    class="btn-cn w-full py-3 text-base text-white disabled:opacity-40"
                                    :class="slottedLoop !== null && !stageAnswered ? 'bg-emerald-500 hover:bg-emerald-600 shadow-[0_4px_0_#0f172a]' : 'bg-slate-400'">
                                <span x-text="simulatingLoop ? '⏳ MESIN SEDANG BERPUTAR...' : '⚙️ JALANKAN SIMULASI MESIN LOOP'"></span>
                            </button>

                            {{-- Live Feedback Alert --}}
                            <div x-show="stageAnswered" x-transition class="mt-4 p-4 rounded-2xl font-bold text-sm border-2"
                                 :class="lastAnswerCorrect ? 'bg-emerald-50 text-emerald-900 border-emerald-400' : 'bg-red-50 text-red-900 border-red-400'">
                                <p x-text="lastAnswerCorrect ? 'Hebat! Kalkulasi pengulangan tepat dan tugas selesai efisien!' : `Kurang tepat! Pengulangan yang dibutuhkan adalah ${stages[currentStage]?.correct_repeat} kali.`"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- ── RUN PROGRAM / NEXT STAGE BUTTON ── --}}
            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between gap-4">
                <template x-if="questType === 'grid_navigation'">
                    <button @click="runProgram()"
                            :disabled="program.length === 0 || isRunning"
                            class="w-full text-white font-black text-lg py-4 rounded-2xl shadow-lg transition-all hover:scale-101 flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="isSpaceTheme ? 'bg-indigo-600 hover:bg-indigo-500 shadow-indigo-500/30' : 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/30'">
                        <span class="text-2xl" x-text="isRunning ? '⏳' : '▶️'"></span>
                        <span x-text="isRunning ? 'Pesawat Meluncur Menjalankan Algoritma...' : 'JALANKAN KODE PROGRAM (RUN)'"></span>
                    </button>
                </template>

                <template x-if="questType !== 'grid_navigation'">
                    <button x-show="stageAnswered" @click="nextStage()"
                            class="w-full bg-primary hover:bg-primary-dark text-white font-black text-lg py-4 rounded-2xl shadow-lg transition-all hover:scale-101 flex items-center justify-center gap-2">
                        <span x-text="currentStage < stages.length - 1 ? '➡️ Soal Tantangan Berikutnya' : '🏁 Selesaikan Quest!'"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    {{-- Hint Bubble --}}
    <div x-show="currentHint" x-transition class="p-4 bg-amber-50 border-2 border-amber-300 text-amber-900 rounded-2xl font-bold text-sm shadow-md mb-6 flex items-center gap-3">
        <span class="text-2xl">💡</span>
        <span x-text="currentHint"></span>
    </div>

    {{-- ─── RESULT MODAL ─── --}}
    <div class="result-overlay" x-show="showResult" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="result-card bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full mx-4 text-center border-4 border-indigo-100">
            <div class="text-7xl mb-3 animate-bounce-slow" x-text="resultEmoji"></div>
            <h2 class="font-display text-3xl text-gray-800 mb-1" x-text="resultTitle"></h2>
            <p class="text-gray-500 font-semibold text-sm mb-6" x-text="starsEarned > 0 ? 'Luar biasa! Kamu berhasil menaklukkan tantangan logika komputasi ini!' : 'Jangan menyerah! Analisis kodenya kembali dan coba lagi!'"></p>

            {{-- Stars --}}
            <div class="flex justify-center gap-3 my-4">
                <template x-for="i in 3" :key="i">
                    <span class="text-5xl transition-all duration-500"
                          :class="i <= starsEarned ? 'text-amber-400 drop-shadow-md scale-110' : 'text-gray-200'"
                          x-text="i <= starsEarned ? '⭐' : '★'"></span>
                </template>
            </div>

            {{-- Stats Card --}}
            <div class="bg-gray-50 rounded-2xl p-4 my-6 space-y-3 border border-gray-100">
                <div class="flex justify-between items-center text-sm font-bold">
                    <span class="text-gray-500">⚡ XP Diperoleh</span>
                    <span class="text-indigo-600 font-black text-base" x-text="`+${xpEarned} XP`"></span>
                </div>
                <div class="flex justify-between items-center text-sm font-bold">
                    <span class="text-gray-500">⭐ Bintang Performa</span>
                    <span class="text-amber-500 font-black text-base" x-text="`${starsEarned} / 3`"></span>
                </div>
                <div class="flex justify-between items-center text-sm font-bold">
                    <span class="text-gray-500">📊 Nilai Akhir</span>
                    <span class="text-gray-800 font-black text-base" x-text="`${finalScore} / 100`"></span>
                </div>
            </div>

            {{-- Modal Buttons --}}
            <div class="flex gap-3">
                <button @click="retryQuest()"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3.5 rounded-2xl transition-all hover:scale-105 active:scale-95">
                    🔄 Coba Lagi
                </button>
                <a :href="dashboardUrl"
                   class="flex-1 bg-primary hover:bg-primary-dark text-white font-bold py-3.5 rounded-2xl transition-all hover:scale-105 active:scale-95 flex items-center justify-center gap-2 shadow-lg">
                    🏠 Dashboard
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
// Web Audio 8-Bit Arcade Sound Synthesizer
const SoundFX = {
    ctx: null,
    init() {
        if (!this.ctx) {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (AudioCtx) this.ctx = new AudioCtx();
        }
    },
    playClick() {
        this.init();
        if (!this.ctx) return;
        const osc = this.ctx.createOscillator();
        const gain = this.ctx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(450, this.ctx.currentTime);
        osc.frequency.exponentialRampToValueAtTime(900, this.ctx.currentTime + 0.07);
        gain.gain.setValueAtTime(0.15, this.ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, this.ctx.currentTime + 0.07);
        osc.connect(gain); gain.connect(this.ctx.destination);
        osc.start(); osc.stop(this.ctx.currentTime + 0.07);
    },
    playStep(isSpace) {
        this.init();
        if (!this.ctx) return;
        const osc = this.ctx.createOscillator();
        const gain = this.ctx.createGain();
        if (isSpace) {
            // Futuristic Thruster / Laser Pulse
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(320, this.ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(140, this.ctx.currentTime + 0.15);
            gain.gain.setValueAtTime(0.18, this.ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, this.ctx.currentTime + 0.15);
        } else {
            // Cute step click
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(280, this.ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(560, this.ctx.currentTime + 0.12);
            gain.gain.setValueAtTime(0.2, this.ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, this.ctx.currentTime + 0.12);
        }
        osc.connect(gain); gain.connect(this.ctx.destination);
        osc.start(); osc.stop(this.ctx.currentTime + 0.15);
    },
    playCollect() {
        this.init();
        if (!this.ctx) return;
        // Crystal / Star chime
        [659.25, 880, 1046.5].forEach((freq, idx) => {
            setTimeout(() => {
                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, this.ctx.currentTime);
                gain.gain.setValueAtTime(0.2, this.ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, this.ctx.currentTime + 0.2);
                osc.connect(gain); gain.connect(this.ctx.destination);
                osc.start(); osc.stop(this.ctx.currentTime + 0.2);
            }, idx * 70);
        });
    },
    playWin() {
        this.init();
        if (!this.ctx) return;
        [523.25, 659.25, 783.99, 1046.50].forEach((freq, idx) => {
            setTimeout(() => {
                const osc = this.ctx.createOscillator();
                const gain = this.ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(freq, this.ctx.currentTime);
                gain.gain.setValueAtTime(0.25, this.ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, this.ctx.currentTime + 0.3);
                osc.connect(gain); gain.connect(this.ctx.destination);
                osc.start(); osc.stop(this.ctx.currentTime + 0.3);
            }, idx * 90);
        });
    },
    playOops() {
        this.init();
        if (!this.ctx) return;
        const osc = this.ctx.createOscillator();
        const gain = this.ctx.createGain();
        osc.type = 'sawtooth';
        osc.frequency.setValueAtTime(240, this.ctx.currentTime);
        osc.frequency.linearRampToValueAtTime(80, this.ctx.currentTime + 0.35);
        gain.gain.setValueAtTime(0.25, this.ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, this.ctx.currentTime + 0.35);
        osc.connect(gain); gain.connect(this.ctx.destination);
        osc.start(); osc.stop(this.ctx.currentTime + 0.35);
    }
};

function BlocklyGame() {
    return {
        // Quest Configurations
        questType: '{{ $quest->game_config["type"] }}',
        gameConfig: @json($quest->game_config),
        questSlug: '{{ $quest->slug }}',
        questId: {{ $quest->id }},
        timeLimit: {{ $quest->time_limit_seconds }},
        submitUrl: '{{ route("student.quest.submit", $quest) }}',
        dashboardUrl: '{{ route("student.dashboard") }}',

        // Theme Differentiation (Space vs Garden)
        get isSpaceTheme() {
            return this.gameConfig.theme === 'space' || this.questSlug.includes('antariksa') || this.questSlug.includes('kiki');
        },
        get playerEmoji() {
            return this.isSpaceTheme ? '🚀' : (this.gameConfig.player_emoji || '🐱');
        },
        get goalEmoji() {
            return this.isSpaceTheme ? '🛰️' : (this.gameConfig.goal_emoji || '🐟');
        },
        get obstacleEmoji() {
            return this.isSpaceTheme ? '☄️' : (this.gameConfig.obstacle_emoji || '🪨');
        },

        // Levels & Multi-Stage State
        levels: [],
        currentLevelIndex: 0,
        showLevelToast: false,
        levelToastMessage: '',
        get currentLevelData() {
            if (this.levels.length > 0 && this.levels[this.currentLevelIndex]) {
                return this.levels[this.currentLevelIndex];
            }
            return null;
        },

        // Timer State
        timeLeft: {{ $quest->time_limit_seconds }},
        timerPercent: 100,
        timerInterval: null,
        get timerDisplay() {
            const safeSeconds = Math.max(0, this.timeLeft);
            const m = Math.floor(safeSeconds / 60).toString().padStart(2, '0');
            const s = (safeSeconds % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        },

        // Grid Navigation State
        gridSize: 5,
        playerRow: 0, playerCol: 0,
        direction: 1, // 0=up, 1=right, 2=down, 3=left
        program: [],
        isRunning: false,
        isShaking: false,
        currentExecIndex: null,
        obstacles: [],
        collectibles: [],
        collectedItems: [],
        visitedTiles: [],
        goalRow: 4, goalCol: 4,

        // Multi-Stage Game State (If-else, Pattern, Loop)
        stages: [],
        currentStage: 0,
        stageAnswered: false,
        lastAnswerCorrect: false,
        selectedAnswer: null,
        correctAnswers: 0,

        // Interactive Drag & Drop / Slot State
        slottedPattern: null,
        dragOverPattern: false,
        slottedLoop: null,
        dragOverLoop: false,
        simulatingLoop: false,
        currentLoopStep: 0,
        slottedCondition: null,
        dragOverCondition: false,

        // Results State
        hintsUsed: 0,
        currentHint: '',
        hintIndex: 0,
        showResult: false,
        isSubmitting: false,
        starsEarned: 0,
        xpEarned: 0,
        finalScore: 0,
        resultEmoji: '🎉',
        resultTitle: '',
        startTime: Date.now(),

        init() {
            this.startTimer();
            if (this.questType === 'grid_navigation') {
                this.levels = this.gameConfig.levels || [];
                this.initGrid();
            } else {
                this.stages = this.gameConfig.stages || [];
            }
        },

        playSound(type) {
            if (type === 'click')   SoundFX.playClick();
            if (type === 'step')    SoundFX.playStep(this.isSpaceTheme);
            if (type === 'collect') SoundFX.playCollect();
            if (type === 'win')     SoundFX.playWin();
            if (type === 'oops')    SoundFX.playOops();
        },

        // ── Timer ──
        startTimer() {
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.timerInterval = setInterval(() => {
                if (this.showResult || this.isSubmitting) {
                    clearInterval(this.timerInterval);
                    return;
                }
                if (this.timeLeft > 0) {
                    this.timeLeft--;
                    this.timerPercent = Math.max(0, (this.timeLeft / this.timeLimit) * 100);
                } else {
                    this.timeLeft = 0;
                    this.timerPercent = 0;
                    clearInterval(this.timerInterval);
                    if (!this.showResult && !this.isSubmitting) {
                        this.playSound('oops');
                        this.submitResult(0);
                    }
                }
            }, 1000);
        },

        resetPlayerPosition() {
            if (this.levels.length > 0 && this.levels[this.currentLevelIndex]) {
                const lvl = this.levels[this.currentLevelIndex];
                this.playerRow = lvl.start?.row ?? 0;
                this.playerCol = lvl.start?.col ?? 0;
            } else {
                this.playerRow = this.gameConfig.start?.row ?? 0;
                this.playerCol = this.gameConfig.start?.col ?? 0;
            }
            this.direction = 1; // Face right
            this.currentExecIndex = null;
            this.visitedTiles = [[this.playerRow, this.playerCol]];
        },

        // ── Grid Level Loading ──
        initGrid() {
            if (this.levels.length > 0) {
                this.loadLevel(this.currentLevelIndex);
            } else {
                this.gridSize = this.gameConfig.grid_size || 5;
                this.goalRow   = this.gameConfig.end?.row ?? 4;
                this.goalCol   = this.gameConfig.end?.col ?? 4;
                this.obstacles = this.gameConfig.obstacles || [];
                this.collectibles = this.gameConfig.collectibles || [];
                this.collectedItems = [];
                this.program = [];
                this.resetPlayerPosition();
            }
        },

        loadLevel(index) {
            if (!this.levels[index]) return;
            const lvl = this.levels[index];
            this.gridSize = lvl.grid_size || 5;
            this.goalRow   = lvl.end?.row ?? 4;
            this.goalCol   = lvl.end?.col ?? 4;
            this.obstacles = lvl.obstacles || [];
            this.collectibles = lvl.collectibles || [];
            this.collectedItems = [];
            this.program = [];
            this.resetPlayerPosition();
        },

        isPlayer(r, c) { return r === this.playerRow && c === this.playerCol; },
        isGoal(r, c)   { return r === this.goalRow   && c === this.goalCol; },
        isObstacle(r, c) { return this.obstacles.some(o => o.row === r && o.col === c); },
        isVisited(r, c)  { return this.visitedTiles.some(([vr, vc]) => vr === r && vc === c); },
        isCollectible(r, c) {
            return this.collectibles.some(item => item.row === r && item.col === c && !this.collectedItems.includes(`${r},${c}`));
        },

        getCellEmoji(r, c) {
            if (this.isGoal(r, c))     return this.goalEmoji;
            if (this.isObstacle(r, c)) return this.obstacleEmoji;
            if (this.isCollectible(r, c)) {
                const item = this.collectibles.find(it => it.row === r && it.col === c);
                return item?.emoji || (this.isSpaceTheme ? '⭐' : '🥛');
            }
            if (this.isVisited(r, c))  return this.isSpaceTheme ? '✨' : '🐾';
            return '';
        },

        // ── Reorderable Blockly Toolbox ──
        addCommand(cmd) {
            if (this.program.length < 25 && !this.isRunning) {
                this.playSound('click');
                this.program.push(cmd);
            }
        },
        removeCommand(idx) {
            if (!this.isRunning) {
                this.playSound('click');
                this.program.splice(idx, 1);
            }
        },
        moveBlockUp(idx) {
            if (idx > 0 && !this.isRunning) {
                this.playSound('click');
                const temp = this.program[idx];
                this.program[idx] = this.program[idx - 1];
                this.program[idx - 1] = temp;
            }
        },
        moveBlockDown(idx) {
            if (idx < this.program.length - 1 && !this.isRunning) {
                this.playSound('click');
                const temp = this.program[idx];
                this.program[idx] = this.program[idx + 1];
                this.program[idx + 1] = temp;
            }
        },
        clearProgram() {
            if (!this.isRunning) {
                this.playSound('click');
                this.program = [];
                this.resetPlayerPosition();
            }
        },

        // ── Animated Algorithm Program Execution ──
        async runProgram() {
            if (this.isRunning || this.program.length === 0) return;
            this.isRunning = true;
            this.resetPlayerPosition();
            await new Promise(r => setTimeout(r, 200));

            const dirMoves = [[-1,0],[0,1],[1,0],[0,-1]];
            for (let i = 0; i < this.program.length; i++) {
                this.currentExecIndex = i;
                const cmd = this.program[i];
                this.playSound('step');
                await new Promise(r => setTimeout(r, 450));

                if (cmd === 'forward') {
                    const [dr, dc] = dirMoves[this.direction];
                    const nr = this.playerRow + dr;
                    const nc = this.playerCol + dc;

                    // Obstacle or Wall Collision Detection
                    if (nr < 0 || nr >= this.gridSize || nc < 0 || nc >= this.gridSize || this.isObstacle(nr, nc)) {
                        this.isRunning = false;
                        this.currentExecIndex = null;
                        this.isShaking = true;
                        setTimeout(() => { this.isShaking = false; }, 400);
                        this.playSound('oops');
                        this.currentHint = this.isSpaceTheme
                            ? '💥 Menabrak Asteroid! Periksa kembali arah dan jumlah langkah roket.'
                            : '💥 Ups, Koki menabrak rintangan! Periksa kembali urutan belokan.';
                        return;
                    }

                    this.playerRow = nr;
                    this.playerCol = nc;
                    this.visitedTiles.push([nr, nc]);

                    // Check Collectible pickup
                    if (this.isCollectible(nr, nc)) {
                        this.collectedItems.push(`${nr},${nc}`);
                        this.playSound('collect');
                    }

                } else if (cmd === 'turn_right') {
                    this.direction = (this.direction + 1) % 4;
                } else if (cmd === 'turn_left') {
                    this.direction = (this.direction + 3) % 4;
                }

                if (this.isGoal(this.playerRow, this.playerCol)) break;
            }

            this.isRunning = false;
            this.currentExecIndex = null;

            // Destination Reached!
            if (this.isGoal(this.playerRow, this.playerCol)) {
                this.playSound('win');

                if (this.levels.length > 0 && this.currentLevelIndex < this.levels.length - 1) {
                    this.currentLevelIndex++;
                    this.levelToastMessage = `🎉 Level ${this.currentLevelIndex} Selesai! Melaju ke Level ${this.currentLevelIndex + 1}!`;
                    this.showLevelToast = true;
                    setTimeout(() => { this.showLevelToast = false; }, 2800);
                    setTimeout(() => {
                        this.loadLevel(this.currentLevelIndex);
                    }, 1100);
                } else {
                    // All levels finished! Calculate efficiency stars
                    let stars = 3;
                    const target = this.currentLevelData?.target_moves;
                    if (target && this.program.length > target + 4) stars = 2;
                    const score = 100;
                    this.submitResult(score, stars);
                }
            } else {
                this.playSound('oops');
                this.currentHint = this.isSpaceTheme
                    ? '😅 Pesawat belum berlabuh di Stasiun Alpha! Coba tambahkan langkah atau belokan.'
                    : '😅 Koki belum sampai ke mangkuk ikan! Coba susun kembali blok kode.';
            }
        },

        // ── 1. Condition (IF-ELSE) Handlers ──
        startDragCondition(idx, ev) {
            if (this.stageAnswered) return;
            ev.dataTransfer.setData('text/plain', JSON.stringify({type: 'condition', idx: idx}));
        },
        dropCondition(ev) {
            this.dragOverCondition = false;
            if (this.stageAnswered) return;
            try {
                const data = JSON.parse(ev.dataTransfer.getData('text/plain'));
                if (data.type === 'condition' && data.idx !== undefined) {
                    this.selectCondition(data.idx);
                }
            } catch(e) {}
        },
        selectCondition(idx) {
            if (this.stageAnswered) return;
            this.playSound('click');
            this.slottedCondition = idx;
        },
        executeCondition() {
            if (this.slottedCondition === null || this.stageAnswered) return;
            this.stageAnswered = true;
            this.selectedAnswer = this.slottedCondition;
            this.lastAnswerCorrect = this.stages[this.currentStage].options[this.slottedCondition].correct;
            if (this.lastAnswerCorrect) {
                this.correctAnswers++;
                this.playSound('win');
            } else {
                this.playSound('oops');
            }
        },

        // ── 2. Pattern Handlers ──
        startDragPattern(opt, idx, ev) {
            if (this.stageAnswered) return;
            ev.dataTransfer.setData('text/plain', JSON.stringify({type: 'pattern', value: opt, idx: idx}));
        },
        dropPattern(ev) {
            this.dragOverPattern = false;
            if (this.stageAnswered) return;
            try {
                const data = JSON.parse(ev.dataTransfer.getData('text/plain'));
                if (data.type === 'pattern') {
                    this.selectPattern(data.value, data.idx);
                }
            } catch(e) {}
        },
        selectPattern(opt, idx) {
            if (this.stageAnswered) return;
            this.playSound('click');
            this.slottedPattern = { value: opt, idx: idx };
        },
        removeSlottedPattern() {
            if (!this.stageAnswered) {
                this.playSound('click');
                this.slottedPattern = null;
            }
        },
        verifyPattern() {
            if (this.slottedPattern === null || this.stageAnswered) return;
            this.stageAnswered = true;
            this.selectedAnswer = this.slottedPattern.idx;
            const answer = this.stages[this.currentStage].answer;
            this.lastAnswerCorrect = this.slottedPattern.value == answer;
            if (this.lastAnswerCorrect) {
                this.correctAnswers++;
                this.playSound('win');
            } else {
                this.playSound('oops');
            }
        },

        // ── 3. Loop Handlers & Live Step Simulation ──
        startDragLoop(opt, idx, ev) {
            if (this.stageAnswered || this.simulatingLoop) return;
            ev.dataTransfer.setData('text/plain', JSON.stringify({type: 'loop', value: opt, idx: idx}));
        },
        dropLoop(ev) {
            this.dragOverLoop = false;
            if (this.stageAnswered || this.simulatingLoop) return;
            try {
                const data = JSON.parse(ev.dataTransfer.getData('text/plain'));
                if (data.type === 'loop') {
                    this.selectLoop(data.value, data.idx);
                }
            } catch(e) {}
        },
        selectLoop(opt, idx) {
            if (this.stageAnswered || this.simulatingLoop) return;
            this.playSound('click');
            this.slottedLoop = { value: opt, idx: idx };
            this.currentLoopStep = 0;
        },
        async runLoopSimulation() {
            if (this.slottedLoop === null || this.simulatingLoop || this.stageAnswered) return;
            this.simulatingLoop = true;
            this.currentLoopStep = 0;
            const total = this.slottedLoop.value;

            for (let step = 1; step <= total; step++) {
                await new Promise(r => setTimeout(r, 200));
                this.currentLoopStep = step;
                this.playSound('step');
            }

            await new Promise(r => setTimeout(r, 250));
            this.simulatingLoop = false;
            this.stageAnswered = true;
            this.selectedAnswer = this.slottedLoop.idx;

            const correct = this.stages[this.currentStage].correct_repeat;
            this.lastAnswerCorrect = total == correct;
            if (this.lastAnswerCorrect) {
                this.correctAnswers++;
                this.playSound('win');
            } else {
                this.playSound('oops');
            }
        },

        nextStage() {
            this.playSound('click');
            if (this.currentStage < this.stages.length - 1) {
                this.currentStage++;
                this.stageAnswered = false;
                this.selectedAnswer = null;
                this.lastAnswerCorrect = false;
                this.slottedPattern = null;
                this.slottedLoop = null;
                this.slottedCondition = null;
                this.simulatingLoop = false;
                this.currentLoopStep = 0;
            } else {
                const total = this.stages.length;
                const score = Math.round((this.correctAnswers / total) * 100);
                const thresholds = this.gameConfig.star_thresholds || {'3':4,'2':3,'1':2};
                let stars = 0;
                if (this.correctAnswers >= thresholds['3']) stars = 3;
                else if (this.correctAnswers >= thresholds['2']) stars = 2;
                else if (this.correctAnswers >= thresholds['1']) stars = 1;
                this.submitResult(score, stars);
            }
        },

        showHint() {
            const hints = this.gameConfig.hints || [];
            if (hints.length > 0) {
                this.currentHint = hints[this.hintIndex % hints.length];
                this.hintIndex++;
                this.hintsUsed++;
                setTimeout(() => { this.currentHint = ''; }, 6000);
            }
        },

        // ── Submit Result ──
        async submitResult(score, starsOverride = null) {
            if (this.isSubmitting || this.showResult) return;
            this.isSubmitting = true;

            if (this.timerInterval) {
                clearInterval(this.timerInterval);
                this.timerInterval = null;
            }

            const timeTaken = Math.round((Date.now() - this.startTime) / 1000);

            let stars = starsOverride ?? 0;
            if (starsOverride === null) {
                if (score >= 90) stars = 3;
                else if (score >= 60) stars = 2;
                else if (score >= 30) stars = 1;
            }

            this.starsEarned = stars;
            this.finalScore  = score;
            this.xpEarned    = Math.round(({{ $quest->xp_reward ?? 100 }}) * (score / 100));

            if (score === 0 || stars === 0) {
                this.resultEmoji = '😅';
                this.resultTitle = 'Waktu Habis / Belum Berhasil!';
            } else if (stars === 3) {
                this.resultEmoji = '🏆';
                this.resultTitle = 'SEMPURNA! Ahli Algoritma Cilik!';
            } else if (stars === 2) {
                this.resultEmoji = '🎉';
                this.resultTitle = 'Bagus Sekali! Hebat!';
            } else {
                this.resultEmoji = '👍';
                this.resultTitle = 'Berhasil Menyelesaikan!';
            }

            this.showResult = true;
            this.playSound(stars > 0 ? 'win' : 'oops');

            try {
                await fetch(this.submitUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                    },
                    body: JSON.stringify({
                        score,
                        stars_earned: stars,
                        time_taken_seconds: timeTaken,
                        hints_used: this.hintsUsed,
                    }),
                });
            } catch(e) { console.error('Submit error:', e); }

            this.isSubmitting = false;
        },

        retryQuest() {
            this.playSound('click');
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.showResult = false;
            this.isSubmitting = false;
            this.program = [];
            this.currentStage = 0;
            this.currentLevelIndex = 0;
            this.correctAnswers = 0;
            this.stageAnswered = false;
            this.selectedAnswer = null;
            this.lastAnswerCorrect = false;
            this.slottedPattern = null;
            this.dragOverPattern = false;
            this.slottedLoop = null;
            this.dragOverLoop = false;
            this.simulatingLoop = false;
            this.currentLoopStep = 0;
            this.slottedCondition = null;
            this.dragOverCondition = false;
            this.hintsUsed = 0;
            this.timeLeft = this.timeLimit;
            this.timerPercent = 100;
            this.startTime = Date.now();
            this.initGrid();
            this.startTimer();
        },
    }
}
</script>
@endpush

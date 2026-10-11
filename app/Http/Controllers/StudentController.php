<?php

namespace App\Http\Controllers;

use App\Models\Quest;
use App\Models\Progress;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // ── Dashboard ──────────────────────────────────────────
    public function dashboard()
    {
        try {
            \App\Services\QuestDataService::syncDatabase();
        } catch (\Throwable $e) {}

        $user = Auth::user()->load(['progress.quest', 'badges']);

        $completedCount = $user->progress->where('status', 'completed')->count();
        $totalQuests    = Quest::active()->count();

        $recentProgress = $user->progress()
            ->with('quest')
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->take(3)
            ->get();

        $featuredQuest = Quest::active()->where('is_featured', true)->first();

        // Leaderboard (top 5 students)
        $leaderboard = \App\Models\User::where('role', 'student')
            ->orderBy('total_stars', 'desc')
            ->orderBy('total_xp', 'desc')
            ->take(5)
            ->get();

        return view('student.dashboard', compact(
            'user', 'completedCount', 'totalQuests',
            'recentProgress', 'featuredQuest', 'leaderboard'
        ));
    }

    // ── Quest List ─────────────────────────────────────────
    public function questIndex(Request $request)
    {
        try {
            \App\Services\QuestDataService::syncDatabase();
        } catch (\Throwable $e) {}

        $user     = Auth::user();
        $category = $request->get('category', 'all');
        $query    = Quest::active();

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $quests = $query->get();

        // Map user progress to quests
        $progressMap = $user->progress()
            ->whereIn('quest_id', $quests->pluck('id'))
            ->get()
            ->keyBy('quest_id');

        return view('student.quests', compact('quests', 'progressMap', 'category'));
    }

    // ── Play Quest ─────────────────────────────────────────
    public function playQuest(Quest $quest)
    {
        if (!$quest->is_active) {
            return redirect()->route('student.quests')->with('error', 'Quest ini belum tersedia.');
        }

        // Ensure quest has latest configuration from QuestDataService
        try {
            \App\Services\QuestDataService::syncDatabase();
            $quest->refresh();
        } catch (\Throwable $e) {}

        $user = Auth::user();

        // Find or create progress record
        $progress = Progress::firstOrCreate(
            ['user_id' => $user->id, 'quest_id' => $quest->id],
            ['status' => 'in_progress', 'started_at' => now()]
        );

        // If it was already completed, update attempts on retry
        if ($progress->status === 'completed') {
            $progress->increment('attempts');
            $progress->status = 'in_progress';
            $progress->save();
        }

        return view('student.quest-play', compact('quest', 'progress'));
    }

    // ── Submit Quest Result ────────────────────────────────
    public function submitQuest(Request $request, Quest $quest)
    {
        $validated = $request->validate([
            'score'               => 'required|integer|min:0|max:100',
            'stars_earned'        => 'nullable|integer|min:0|max:3',
            'time_taken_seconds'  => 'required|integer|min:0',
            'hints_used'          => 'integer|min:0',
            'answers_log'         => 'nullable|array',
        ]);

        $user     = Auth::user();
        $score    = $validated['score'];
        $timeTaken = $validated['time_taken_seconds'];
        $hintsUsed = $validated['hints_used'] ?? 0;

        // Calculate stars (prefer explicit stars_earned from frontend if passed)
        if (isset($validated['stars_earned'])) {
            $starsEarned = (int) $validated['stars_earned'];
        } else {
            $thresholds = $quest->game_config['star_thresholds'] ?? [3 => 90, 2 => 60, 1 => 30];
            $starsEarned = 0;
            if ($score >= ($thresholds[3] ?? 90)) $starsEarned = 3;
            elseif ($score >= ($thresholds[2] ?? 60)) $starsEarned = 2;
            elseif ($score >= ($thresholds[1] ?? 30)) $starsEarned = 1;
        }

        $xpEarned = (int)($quest->xp_reward * ($score / 100));

        DB::transaction(function () use ($user, $quest, $score, $starsEarned, $xpEarned, $timeTaken, $hintsUsed, $validated) {
            $progress = Progress::updateOrCreate(
                ['user_id' => $user->id, 'quest_id' => $quest->id],
                [
                    'status'              => 'completed',
                    'score'               => $score,
                    'stars_earned'        => $starsEarned,
                    'xp_earned'           => $xpEarned,
                    'time_taken_seconds'  => $timeTaken,
                    'hints_used'          => $hintsUsed,
                    'answers_log'         => $validated['answers_log'] ?? null,
                    'completed_at'        => now(),
                ]
            );

            // Recalculate total stars & XP
            $totalStars = $user->progress()->where('status', 'completed')->sum('stars_earned');
            $totalXp    = $user->progress()->where('status', 'completed')->sum('xp_earned');
            $newLevel   = max(1, (int)($totalXp / 200) + 1);

            $user->update([
                'total_stars' => $totalStars,
                'total_xp'    => $totalXp,
                'level'       => min($newLevel, 10),
            ]);

            $user->updateStreak();
        });

        // Check and award badges
        $newBadges = $this->checkAndAwardBadges($user->fresh());

        return response()->json([
            'success'     => true,
            'stars'       => $starsEarned,
            'xp'          => $xpEarned,
            'score'       => $score,
            'new_badges'  => $newBadges,
            'redirect_url'=> route('student.dashboard'),
        ]);
    }

    // ── Badge Checker ──────────────────────────────────────
    private function checkAndAwardBadges(User $user): array
    {
        $newBadges = [];
        $earnedIds = $user->badges->pluck('id')->toArray();
        $badges    = Badge::where('is_active', true)
                          ->whereNotIn('id', $earnedIds)
                          ->get();

        $completedCount = $user->progress()->where('status', 'completed')->count();
        $totalStars     = $user->total_stars;
        $streakDays     = $user->streak_days;

        foreach ($badges as $badge) {
            $earned = false;
            switch ($badge->type) {
                case 'quest_count':
                    $earned = $completedCount >= $badge->threshold;
                    break;
                case 'star_count':
                    $earned = $totalStars >= $badge->threshold;
                    break;
                case 'streak':
                    $earned = $streakDays >= $badge->threshold;
                    break;
                case 'perfect_score':
                    $earned = $user->progress()->where('stars_earned', 3)->exists();
                    break;
                case 'category_master':
                    $categoryQuests = Quest::active()->where('category', $badge->category)->count();
                    $categoryDone   = $user->progress()
                        ->whereHas('quest', fn($q) => $q->where('category', $badge->category))
                        ->where('status', 'completed')->count();
                    $earned = $categoryQuests > 0 && $categoryDone >= $categoryQuests;
                    break;
            }

            if ($earned) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
                $user->increment('total_xp', $badge->xp_reward);
                $newBadges[] = [
                    'name'       => $badge->name,
                    'icon_emoji' => $badge->icon_emoji,
                    'color'      => $badge->color,
                ];
            }
        }

        return $newBadges;
    }
}

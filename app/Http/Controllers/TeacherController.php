<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Quest;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    // ── Dashboard ──────────────────────────────────────────
    public function dashboard()
    {
        $teacher = Auth::user();

        // Summary stats
        $totalStudents   = User::where('role', 'student')->count();
        $totalQuests     = Quest::active()->count();
        $totalCompletions= Progress::where('status', 'completed')->count();
        $avgStars        = Progress::where('status', 'completed')->avg('stars_earned') ?? 0;

        // All students with their progress
        $students = User::where('role', 'student')
            ->withCount(['progress as completed_count' => fn($q) => $q->where('status', 'completed')])
            ->with(['progress' => fn($q) => $q->where('status', 'completed')])
            ->orderBy('total_stars', 'desc')
            ->get();

        // Quest completion rate
        $questStats = Quest::active()
            ->withCount(['progress as completion_count' => fn($q) => $q->where('status', 'completed')])
            ->get();

        // Recent activity
        $recentActivity = Progress::with(['user', 'quest'])
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->take(10)
            ->get();

        return view('teacher.dashboard', compact(
            'teacher', 'totalStudents', 'totalQuests',
            'totalCompletions', 'avgStars',
            'students', 'questStats', 'recentActivity'
        ));
    }

    // ── Student Detail ─────────────────────────────────────
    public function studentDetail(User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $student->load(['progress.quest', 'badges']);
        $allQuests = Quest::active()->get();

        return view('teacher.student-detail', compact('student', 'allQuests'));
    }
}

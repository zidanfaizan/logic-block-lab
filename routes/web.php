<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isTeacher()
            ? redirect()->route('teacher.dashboard')
            : redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});

// ── Auth Routes ────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ── Student Routes ─────────────────────────────────────────
Route::middleware(['auth', 'role:student'])->prefix('siswa')->name('student.')->group(function () {
    Route::get('/dashboard',          [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/quest',              [StudentController::class, 'questIndex'])->name('quests');
    Route::get('/quest/{quest:slug}', [StudentController::class, 'playQuest'])->name('quest.play');
    Route::post('/quest/{quest}/submit', [StudentController::class, 'submitQuest'])->name('quest.submit');
});

// ── Teacher Routes ─────────────────────────────────────────
Route::middleware(['auth', 'role:teacher'])->prefix('guru')->name('teacher.')->group(function () {
    Route::get('/dashboard',             [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::get('/siswa/{student}',       [TeacherController::class, 'studentDetail'])->name('student.detail');
});

Route::get('/run-migrations', function () {
    try {
        // Tentukan path ke file sqlite
        $dbPath = database_path('database.sqlite');
        
        // Jika file belum ada, buat file kosong
        if (!file_exists($dbPath)) {
            touch($dbPath);
        }

        // Jalankan migrasi
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        
        return 'File database.sqlite berhasil dibuat dan migrasi sukses!';
    } catch (\Exception $e) {
        return 'Error migrasi: ' . $e->getMessage();
    }
});
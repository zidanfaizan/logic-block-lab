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
        $dbPath = config('database.connections.sqlite.database', database_path('database.sqlite'));
        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        @chmod($dir, 0777);

        // Check if $dir and $dbPath are writable
        $isDirWritable = is_dir($dir) && is_writable($dir);
        $isFileWritable = file_exists($dbPath) ? is_writable($dbPath) : $isDirWritable;

        if (!$isDirWritable || !$isFileWritable) {
            $safeDbPath = storage_path('database.sqlite');
            $storageDir = storage_path();
            if (!is_dir($storageDir)) {
                @mkdir($storageDir, 0777, true);
            }
            @chmod($storageDir, 0777);

            if (file_exists($dbPath) && !file_exists($safeDbPath)) {
                @copy($dbPath, $safeDbPath);
            }

            if (!file_exists($safeDbPath)) {
                @touch($safeDbPath);
            }
            @chmod($safeDbPath, 0666);

            config(['database.connections.sqlite.database' => $safeDbPath]);
            \Illuminate\Support\Facades\DB::purge('sqlite');
            $dbPath = $safeDbPath;
        }

        $fileCreated = false;
        if (!file_exists($dbPath)) {
            @touch($dbPath);
            @chmod($dbPath, 0666);
            $fileCreated = true;
        }

        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = \Illuminate\Support\Facades\Artisan::output();

        \App\Services\QuestDataService::syncDatabase();

        return response()->json([
            'status' => 'success',
            'message' => 'Database SQLite berhasil diinisialisasi, dimigrasi, dan di-seed!',
            'database_path' => $dbPath,
            'is_writable' => is_writable($dbPath),
            'dir_is_writable' => is_writable(dirname($dbPath)),
            'file_created' => $fileCreated,
            'migrate_output' => trim($migrateOutput),
            'seed_output' => trim($seedOutput),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});
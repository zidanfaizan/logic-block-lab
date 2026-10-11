<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'username', 'email', 'password',
        'role', 'avatar', 'kelas', 'sekolah',
        'total_stars', 'total_xp', 'level',
        'streak_days', 'last_activity_date',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_activity_date' => 'date',
            'password' => 'hashed',
        ];
    }

    // ── Relationships ──────────────────────────────────────
    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
                    ->withPivot('earned_at')
                    ->withTimestamps();
    }

    // ── Helpers ────────────────────────────────────────────
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function completedQuests(): HasMany
    {
        return $this->progress()->where('status', 'completed');
    }

    public function getLevelTitleAttribute(): string
    {
        return match(true) {
            $this->level >= 10 => 'Master Logika 🏆',
            $this->level >= 7  => 'Ahli Kode ⭐',
            $this->level >= 4  => 'Penjelajah 🚀',
            default            => 'Pemula 🌱',
        };
    }

    public function getLevelProgressAttribute(): int
    {
        $xpPerLevel = 200;
        return ($this->total_xp % $xpPerLevel);
    }

    public function getLevelProgressPercentAttribute(): int
    {
        $xpPerLevel = 200;
        return (int)(($this->total_xp % $xpPerLevel) / $xpPerLevel * 100);
    }

    public function getAvatarUrlAttribute(): string
    {
        return asset('images/avatars/' . $this->avatar);
    }

    /**
     * Update streak and last_activity_date
     */
    public function updateStreak(): void
    {
        $today = now()->toDateString();
        if ($this->last_activity_date?->toDateString() === $today) {
            return; // Already updated today
        }

        $yesterday = now()->subDay()->toDateString();
        if ($this->last_activity_date?->toDateString() === $yesterday) {
            $this->increment('streak_days');
        } else {
            $this->streak_days = 1;
        }
        $this->last_activity_date = $today;
        $this->save();
    }
}

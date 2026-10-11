<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quest extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'thumbnail',
        'category', 'difficulty', 'min_grade', 'max_grade',
        'max_stars', 'xp_reward', 'time_limit_seconds',
        'game_config', 'character_name', 'character_emoji',
        'theme_color', 'order_index', 'is_active', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'game_config' => 'array',
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────────────
    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    // ── Scopes ─────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order_index');
    }

    public function scopeForGrade($query, int $grade)
    {
        return $query->where('min_grade', '<=', $grade)
                     ->where('max_grade', '>=', $grade);
    }

    // ── Helpers ────────────────────────────────────────────
    public function getDifficultyLabelAttribute(): string
    {
        return match($this->difficulty) {
            1 => 'Mudah',
            2 => 'Sedang',
            3 => 'Susah',
            default => 'Mudah',
        };
    }

    public function getDifficultyColorAttribute(): string
    {
        return match($this->difficulty) {
            1 => 'text-green-600 bg-green-100',
            2 => 'text-yellow-600 bg-yellow-100',
            3 => 'text-red-600 bg-red-100',
            default => 'text-green-600 bg-green-100',
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'sequence'  => 'Urutan Langkah',
            'condition' => 'Pilihan Kondisi',
            'loop'      => 'Pengulangan',
            'pattern'   => 'Pola Seru',
            default     => 'Urutan Langkah',
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'sequence'  => '📋',
            'condition' => '🔀',
            'loop'      => '🔄',
            'pattern'   => '🧩',
            default     => '📋',
        };
    }

    public function getTimeLimitMinutesAttribute(): string
    {
        $minutes = intdiv($this->time_limit_seconds, 60);
        $seconds = $this->time_limit_seconds % 60;
        return $seconds > 0 ? "{$minutes}:{$seconds}" : "{$minutes}:00";
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('images/quests/' . $this->thumbnail);
        }
        return asset('images/quests/default.png');
    }
}

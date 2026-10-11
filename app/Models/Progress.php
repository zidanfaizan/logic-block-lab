<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progress extends Model
{
    protected $table = 'progress';

    protected $fillable = [
        'user_id', 'quest_id', 'status',
        'stars_earned', 'xp_earned', 'score',
        'attempts', 'time_taken_seconds', 'hints_used',
        'answers_log', 'started_at', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'answers_log'  => 'array',
            'started_at'   => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quest(): BelongsTo
    {
        return $this->belongsTo(Quest::class);
    }

    // ── Scopes ─────────────────────────────────────────────
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // ── Helpers ────────────────────────────────────────────
    public function getStarsHtmlAttribute(): string
    {
        $filled = str_repeat('⭐', $this->stars_earned);
        $empty  = str_repeat('☆', 3 - $this->stars_earned);
        return $filled . $empty;
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}

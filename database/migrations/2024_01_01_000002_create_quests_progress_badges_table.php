<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('thumbnail')->nullable();
            $table->enum('category', ['sequence', 'condition', 'loop', 'pattern'])->default('sequence');
            $table->unsignedTinyInteger('difficulty')->default(1);
            $table->unsignedTinyInteger('min_grade')->default(1);
            $table->unsignedTinyInteger('max_grade')->default(6);
            $table->unsignedInteger('max_stars')->default(3);
            $table->unsignedInteger('xp_reward')->default(50);
            $table->unsignedInteger('time_limit_seconds')->default(300);
            $table->json('game_config');
            $table->string('character_name')->default('Koki');
            $table->string('character_emoji')->default('🐱');
            $table->string('theme_color')->default('#FF6B6B');
            $table->unsignedInteger('order_index')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->index(['is_active', 'order_index']);
            $table->index('category');
        });

        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('icon_emoji')->default('🏅');
            $table->string('icon_image')->nullable();
            $table->string('color')->default('#FFD700');
            $table->enum('type', ['quest_count', 'star_count', 'streak', 'category_master', 'perfect_score', 'special'])->default('quest_count');
            $table->unsignedInteger('threshold')->default(1);
            $table->string('category')->nullable();
            $table->unsignedInteger('xp_reward')->default(100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('quest_id')->constrained('quests')->cascadeOnDelete();
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'failed'])->default('in_progress');
            $table->unsignedTinyInteger('stars_earned')->default(0);
            $table->unsignedInteger('xp_earned')->default(0);
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('attempts')->default(1);
            $table->unsignedInteger('time_taken_seconds')->nullable();
            $table->unsignedInteger('hints_used')->default(0);
            $table->json('answers_log')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'quest_id'], 'unique_user_quest_progress');
            $table->index(['user_id', 'status']);
        });

        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained('badges')->cascadeOnDelete();
            $table->timestamp('earned_at');
            $table->unique(['user_id', 'badge_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('progress');
        Schema::dropIfExists('badges');
        Schema::dropIfExists('quests');
    }
};

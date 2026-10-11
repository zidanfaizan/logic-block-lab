<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Quest;
use App\Models\Badge;
use App\Models\Progress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Seed Demo Users ─────────────────────────────────────
        $teacher = User::create([
            'name'      => 'Bu Sari Dewi',
            'username'  => 'busari',
            'email'     => 'guru@logicblocklab.id',
            'password'  => Hash::make('guru123'),
            'role'      => 'teacher',
            'avatar'    => 'teacher_01.png',
            'sekolah'   => 'SDN Nusantara 01',
        ]);

        $studentsData = [
            ['name' => 'Budi Santoso',    'username' => 'budi',   'kelas' => '4A', 'stars' => 12, 'xp' => 450, 'level' => 3],
            ['name' => 'Siti Rahma',      'username' => 'siti',   'kelas' => '4A', 'stars' => 9,  'xp' => 320, 'level' => 2],
            ['name' => 'Doni Pratama',    'username' => 'doni',   'kelas' => '4B', 'stars' => 6,  'xp' => 210, 'level' => 2],
            ['name' => 'Anisa Putri',     'username' => 'anisa',  'kelas' => '4B', 'stars' => 15, 'xp' => 680, 'level' => 4],
        ];

        foreach ($studentsData as $idx => $s) {
            User::create([
                'name'         => $s['name'],
                'username'     => $s['username'],
                'email'        => "{$s['username']}@student.id",
                'password'     => Hash::make('siswa123'),
                'role'         => 'student',
                'kelas'        => $s['kelas'],
                'sekolah'      => 'SDN Nusantara 01',
                'avatar'       => 'student_0' . ($idx + 1) . '.png',
                'total_stars'  => $s['stars'],
                'total_xp'     => $s['xp'],
                'level'        => $s['level'],
                'streak_days'  => rand(1, 5),
            ]);
        }

        // ── Seed Quests via QuestDataService ────────────────────
        foreach (\App\Services\QuestDataService::getQuests() as $questData) {
            Quest::create($questData);
        }

        // ── Seed Badges ─────────────────────────────────────────
        $badges = [
            ['name' => 'Penjelajah Pertama', 'slug' => 'penjelajah-pertama',
             'description' => 'Selesaikan quest pertamamu!',
             'icon_emoji' => '🚀', 'color' => '#60A5FA', 'type' => 'quest_count', 'threshold' => 1, 'xp_reward' => 50],
            ['name' => 'Petualang Handal', 'slug' => 'petualang-handal',
             'description' => 'Selesaikan 3 quest berbeda!',
             'icon_emoji' => '🗺️', 'color' => '#34D399', 'type' => 'quest_count', 'threshold' => 3, 'xp_reward' => 100],
            ['name' => 'Master Koding', 'slug' => 'master-koding',
             'description' => 'Selesaikan 6 quest berbeda!',
             'icon_emoji' => '👑', 'color' => '#EC4899', 'type' => 'quest_count', 'threshold' => 6, 'xp_reward' => 250],
            ['name' => 'Kolektor Bintang', 'slug' => 'kolektor-bintang',
             'description' => 'Kumpulkan 10 bintang!',
             'icon_emoji' => '⭐', 'color' => '#FCD34D', 'type' => 'star_count', 'threshold' => 10, 'xp_reward' => 100],
            ['name' => 'Raja Bintang', 'slug' => 'raja-bintang',
             'description' => 'Kumpulkan 20 bintang!',
             'icon_emoji' => '🌟', 'color' => '#F59E0B', 'type' => 'star_count', 'threshold' => 20, 'xp_reward' => 200],
            ['name' => 'Rajin Belajar', 'slug' => 'rajin-belajar',
             'description' => 'Belajar 3 hari berturut-turut!',
             'icon_emoji' => '🔥', 'color' => '#F97316', 'type' => 'streak', 'threshold' => 3, 'xp_reward' => 150],
            ['name' => 'Nilai Sempurna', 'slug' => 'nilai-sempurna',
             'description' => 'Dapatkan 3 bintang di satu quest!',
             'icon_emoji' => '💎', 'color' => '#8B5CF6', 'type' => 'perfect_score', 'threshold' => 1, 'xp_reward' => 300],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}

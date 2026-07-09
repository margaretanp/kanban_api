<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        $user1 = User::create([
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ]);

        $users = [$admin, $user1];

        foreach ($users as $user) {
            $board = \App\Models\Board::create([
                'user_id' => $user->id,
                'title' => "Board - {$user->name}",
            ]);

            $columnTitles = ['Backlog', 'To Do', 'In Progress', 'Done'];
            $columns = [];

            foreach ($columnTitles as $title) {
                $columns[$title] = \App\Models\Column::create([
                    'board_id' => $board->id,
                    'title' => $title,
                ]);
            }

            // minimal 2 card contoh di kolom Backlog
            \App\Models\Card::create([
                'board_id' => $board->id,
                'column_id' => $columns['Backlog']->id,
                'title' => 'Fix Login Bug',
                'description' => 'User cannot login with valid credentials in Safari.',
                'priority' => 'high',
                'deadline' => now()->addDays(2),
            ]);

            \App\Models\Card::create([
                'board_id' => $board->id,
                'column_id' => $columns['Backlog']->id,
                'title' => 'Design Landing Page',
                'description' => 'Create a new design for the marketing landing page.',
                'priority' => 'medium',
                'deadline' => now()->addDays(5),
            ]);
        }
    }
}

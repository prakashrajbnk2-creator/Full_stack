<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $demoUser = User::updateOrCreate(
            ['email' => 'demo@taskflow.local'],
            [
                'name' => 'demo',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        $sampleTasks = [
            [
                'title' => 'Design user interface wireframes',
                'description' => 'Create high-fidelity mockups for desktop and mobile dashboard views in Figma.',
                'is_completed' => true,
            ],
            [
                'title' => 'Implement RESTful task endpoints',
                'description' => 'Build controllers, request validation, and status toggle logic with authorization.',
                'is_completed' => true,
            ],
            [
                'title' => 'Review security and CSRF protections',
                'description' => 'Audit form submissions, session tokens, and middleware authentication checks.',
                'is_completed' => true,
            ],
            [
                'title' => 'Optimize SQLite query indexes & pagination',
                'description' => 'Add eager loading, compound indexes, and ensure fast query execution.',
                'is_completed' => false,
            ],
            [
                'title' => 'Prepare product launch checklist',
                'description' => 'Draft release notes, verify browser compatibility, and test production deployment.',
                'is_completed' => false,
            ],
        ];

        foreach ($sampleTasks as $taskData) {
            Task::firstOrCreate(
                [
                    'user_id' => $demoUser->id,
                    'title' => $taskData['title'],
                ],
                [
                    'description' => $taskData['description'],
                    'is_completed' => $taskData['is_completed'],
                ]
            );
        }
    }
}

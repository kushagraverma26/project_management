<?php

// namespace Database\Seeders;

// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;

// class DatabaseSeeder extends Seeder
// {
//     /**
//      * Seed the application's database.
//      */
//     public function run(): void
//     {
//         // User::factory(10)->create();

//         User::factory()->create([
//             'name' => 'Test User',
//             'email' => 'test@example.com',
//         ]);
//     }
// }

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $projects = [
            [
                'title' => 'Website Redesign',
                'description' => 'Redesign the company website',
                'status' => 'open',
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Build the new mobile application',
                'status' => 'in_progress',
            ],
            [
                'title' => 'Marketing Campaign',
                'description' => 'Plan and execute marketing campaign',
                'status' => 'open',
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);
            for ($i = 1; $i <= 3; $i++) {
                Task::create([
                    'project_id' => $project->id,
                    'title' => "Task $i for {$project->title}",
                    'description' => "Details for task $i of the {$project->title} project",
                    'assigned_to' => "User $i",
                    'due_date' => now()->addDays($i * 5),
                    'status' => $i === 1 ? 'to_do' : ($i === 2 ? 'in_progress' : 'done'),
                ]);
            }
        }
    }
}


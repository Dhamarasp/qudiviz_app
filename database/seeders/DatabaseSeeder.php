<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Seed languages
        $this->call(LanguageSeeder::class);

        // Seed English course
        $this->call(EnglishCourseSeeder::class);

        // Seed practice categories
        $this->call(PracticeCategorySeeder::class);

        // Seed quiz questions
        $this->call(QuizQuestionSeeder::class);
    }
}

<?php

namespace Database\Seeders;

use App\Models\PracticeCategory;
use Illuminate\Database\Seeder;

class PracticeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Grammar',
                'slug' => 'grammar',
                'icon' => 'fas fa-pencil-alt',
                'description' => 'Review grammar rules and sentence structures.',
                'status' => 'active',
            ],
            [
                'name' => 'Vocabulary',
                'slug' => 'vocabulary',
                'icon' => 'fas fa-book',
                'description' => 'Practice words and phrases you\'ve learned.',
                'status' => 'active',
            ],
            [
                'name' => 'Reading',
                'slug' => 'reading',
                'icon' => 'fas fa-glasses',
                'description' => 'Improve your reading comprehension with short texts.',
                'status' => 'active',
            ],
            [
                'name' => 'Listening',
                'slug' => 'listening',
                'icon' => 'fas fa-headphones',
                'description' => 'Improve your listening comprehension skills.',
                'status' => 'active',
            ],
            [
                'name' => 'Speaking',
                'slug' => 'speaking',
                'icon' => 'fas fa-microphone',
                'description' => 'Practice pronunciation and speaking skills.',
                'status' => 'active',
            ],
            [
                'name' => 'Writing',
                'slug' => 'writing',
                'icon' => 'fas fa-pen',
                'description' => 'Practice writing sentences and short paragraphs.',
                'status' => 'active',
            ],
        ];

        foreach ($categories as $category) {
            PracticeCategory::create($category);
        }
    }
}

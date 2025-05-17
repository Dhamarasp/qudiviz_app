<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\Lesson;
use App\Models\SubLesson;
use App\Models\SubLessonContent;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class EnglishCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'title' => 'Grammar',
                'language_id' => 1,
                'description' => 'Learn Grammar',
                'order' => 1,
                'status' => 'active'
            ],
            [
                'title' => 'Conversation',
                'language_id' => 1,
                'description' => 'Learn Conversation',
                'order' => 2,
                'status' => 'active'
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }

        $lessons = [
            [
                'language_id' => 1,
                'unit_id' => 1,
                'title' => 'BE',
                'icon' => 'fa-solid fa-book',
                'order' => 1,
                'status' => 'active'
            ],
            [
                'language_id' => 1,
                'unit_id' => 1,
                'title' => 'Present Simple',
                'icon' => 'fa-solid fa-book',
                'order' => 2,
                'status' => 'active'
            ],
            [
                'language_id' => 1,
                'unit_id' => 1,
                'title' => 'Present Continuous',
                'icon' => 'fa-solid fa-book',
                'order' => 3,
                'status' => 'active'
            ],
            [
                'language_id' => 1,
                'unit_id' => 1,
                'title' => 'Imperative',
                'icon' => 'fa-solid fa-book',
                'order' => 4,
                'status' => 'active'
            ],
            [
                'language_id' => 1,
                'unit_id' => 1,
                'title' => 'Past Simple',
                'icon' => 'fa-solid fa-book',
                'order' => 5,
                'status' => 'active'
            ],
            [
                'language_id' => 1,
                'unit_id' => 2,
                'title' => 'Past Continuos',
                'icon' => 'fa-solid fa-book',
                'order' => 1,
                'status' => 'active'
            ],
            [
                'language_id' => 1,
                'unit_id' => 2,
                'title' => 'Present Perfect',
                'icon' => 'fa-solid fa-book',
                'order' => 2,
                'status' => 'active'
            ],
            [
                'language_id' => 1,
                'unit_id' => 2,
                'title' => 'Past Continuous',
                'icon' => 'fa-solid fa-book',
                'order' => 3,
                'status' => 'active'
            ],
        ];

        foreach ($lessons as $lesson) {
            Lesson::create($lesson);
        }

        $subLessons = [
            [
                'lesson_id' => 1,
                'title' => 'BE Part 1',
                'description' => 'Learn BE Part 1',
                'icon' => 'fa-solid fa-book',
                'type' => 'lesson',
                'order' => 1,
                'status' => 'active',
                'duration' => 50
            ],
            [
                'lesson_id' => 1,
                'title' => 'BE Part 2',
                'description' => 'Learn BE Part 2',
                'icon' => 'fa-solid fa-book',
                'type' => 'lesson',
                'order' => 2,
                'status' => 'active',
                'duration' => 50
            ],
        ];

        foreach ($subLessons as $subLesson) {
            SubLesson::create($subLesson);
        }

        $subLessonContents = [
            [
                'sub_lesson_id' => 1,
                'content_type' => 'video',
                'content' => '',
                'order' => 2,
                'youtube_video_id' => 'M7RWlZJSNKA',
                'thumbnail_url' => '',
                'duration' => 429,
            ],
            [
                'sub_lesson_id' => 2,
                'content_type' => 'video',
                'content' => '',
                'order' => 2,
                'youtube_video_id' => 'mcV0EdwRrsw',
                'thumbnail_url' => '',
                'duration' => 506,
            ],
        ];

        foreach ($subLessonContents as $subLessonContent) {
            SubLessonContent::create($subLessonContent);
        }
    }
}

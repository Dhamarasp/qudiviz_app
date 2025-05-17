<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            [
                'name' => 'English',
                'code' => 'en',
                'flag_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_the_United_Kingdom_%283-5%29.svg/330px-Flag_of_the_United_Kingdom_%283-5%29.svg.png',
                'is_popular' => true,
                'status' => 'active',
                'description' => 'Most Popular',
            ],
            [
                'name' => 'Spanish',
                'code' => 'es',
                'flag_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Flag_of_Spain.svg/330px-Flag_of_Spain.svg.png',
                'is_popular' => true,
                'status' => 'active',
                'description' => 'Most Popular',
            ],
            [
                'name' => 'French',
                'code' => 'fr',
                'flag_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c3/Flag_of_France.svg/330px-Flag_of_France.svg.png',
                'is_popular' => true,
                'status' => 'active',
                'description' => 'Trending',
            ],
            [
                'name' => 'German',
                'code' => 'de',
                'flag_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/ba/Flag_of_Germany.svg/320px-Flag_of_Germany.svg.png',
                'is_popular' => true,
                'status' => 'active',
                'description' => 'Popular',
            ],
            [
                'name' => 'Italian',
                'code' => 'it',
                'flag_url' => 'https://upload.wikimedia.org/wikipedia/en/thumb/0/03/Flag_of_Italy.svg/330px-Flag_of_Italy.svg.png',
                'is_popular' => false,
                'status' => 'active',
                'description' => 'Popular',
            ],
            [
                'name' => 'Japanese',
                'code' => 'ja',
                'flag_url' => 'https://upload.wikimedia.org/wikipedia/en/thumb/9/9e/Flag_of_Japan.svg/330px-Flag_of_Japan.svg.png',
                'is_popular' => true,
                'status' => 'active',
                'description' => 'Trending',
            ],
            [
                'name' => 'Korean',
                'code' => 'ko',
                'flag_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/09/Flag_of_South_Korea.svg/330px-Flag_of_South_Korea.svg.png',
                'is_popular' => false,
                'status' => 'active',
                'description' => 'Popular',
            ],
            [
                'name' => 'Chinese',
                'code' => 'zh',
                'flag_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_the_People%27s_Republic_of_China.svg/330px-Flag_of_the_People%27s_Republic_of_China.svg.png',
                'is_popular' => false,
                'status' => 'active',
                'description' => 'Mandarin',
            ],
            [
                'name' => 'Portuguese',
                'code' => 'pt',
                'flag_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Flag_of_Portugal.svg/330px-Flag_of_Portugal.svg.png',
                'is_popular' => false,
                'status' => 'active',
                'description' => 'Brazilian',
            ],
        ];

        foreach ($languages as $language) {
            Language::create($language);
        }
    }
}

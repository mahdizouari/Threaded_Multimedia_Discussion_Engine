<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['label' => 'Technology', 'description' => 'Latest in gadgets, software, and AI'],
            ['label' => 'Entertainment', 'description' => 'Movies, music, and celebrity news'],
            ['label' => 'News', 'description' => 'Breaking news from around the world'],
            ['label' => 'Gaming', 'description' => 'Video games, esports, and industry updates'],
            ['label' => 'Science', 'description' => 'Discoveries, space, and environment'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

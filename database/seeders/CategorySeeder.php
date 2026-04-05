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
            ['label' => 'Technology'],
            ['label' => 'Entertainment'],
            ['label' => 'News'],
            ['label' => 'Gaming'],
            ['label' => 'Science'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

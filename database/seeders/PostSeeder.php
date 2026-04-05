<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $mod = User::where('role', 'moderator')->first();
        
        $tech = Category::where('label', 'Technology')->first();
        $gaming = Category::where('label', 'Gaming')->first();
        $news = Category::where('label', 'News')->first();

        $p1 = Post::create([
            'user_id' => $admin->id,
            'category_id' => $tech->id,
            'title' => 'The Future of AI in 2026',
            'content' => 'Artificial Intelligence is evolving faster than ever. We are seeing major breakthroughs in neural networks and practical applications in daily life.',
            'image_path' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&q=80&w=800',
            'is_approved' => true,
            'published_at' => now(),
        ]);
        \App\Models\Conversation::create(['post_id' => $p1->id, 'created_at' => now()]);

        $p2 = Post::create([
            'user_id' => $mod->id,
            'category_id' => $gaming->id,
            'title' => 'GTA VI: New Leaks and Release Date',
            'content' => 'Latest rumors suggest that Rockstar is polishing the final build. The trailer showed incredible graphics and a living world like never before.',
            'image_path' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&q=80&w=800',
            'is_approved' => true,
            'published_at' => now(),
        ]);
        \App\Models\Conversation::create(['post_id' => $p2->id, 'created_at' => now()]);

        $p3 = Post::create([
            'user_id' => $admin->id,
            'category_id' => $news->id,
            'title' => 'SpaceX Mission to Mars Updates',
            'content' => 'The Starship is ready for its next flight test. This mission marks a historic step towards multi-planetary civilization.',
            'image_path' => 'https://images.unsplash.com/photo-1517976487492-5750f3195933?auto=format&fit=crop&q=80&w=800',
            'is_approved' => true,
            'published_at' => now(),
        ]);
        \App\Models\Conversation::create(['post_id' => $p3->id, 'created_at' => now()]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the specified user profile and their published posts.
     */
    public function show(User $user)
    {
        // Load relationships needed for the profile view
        $user->load(['profile', 'interests']);

        // Fetch their latest approved posts
        $posts = $user->posts()
            ->with(['user.profile', 'category', 'reactions.appreciation'])
            ->withCount('comments')
            ->where('is_approved', true)
            ->latest('published_at')
            ->paginate(15);

        return view('user.profile.show', compact('user', 'posts'));
    }
}

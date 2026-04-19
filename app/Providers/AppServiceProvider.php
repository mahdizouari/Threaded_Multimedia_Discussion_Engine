<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            // Always provide nav categories
            $view->with('nav_categories', \App\Models\Category::all());

            if (\Illuminate\Support\Facades\Auth::check()) {
                $user = \Illuminate\Support\Facades\Auth::user();

                // --- Sidebar Badge Counts (always live, always accurate) ---

                // 1. Unread messages count + sender IDs (for per-user dots)
                $unreadQuery = $user->receivedMessages()->unread();
                $unreadMessages = (clone $unreadQuery)->count();
                $unreadMessageSenders = (clone $unreadQuery)->pluck('sender_id')->unique()->toArray();

                // 2. Staff: Pending Approvals
                $pendingCount = 0;
                if (in_array($user->role, ['admin', 'moderator'])) {
                    $query = \App\Models\Post::where('is_approved', false);
                    if ($user->role === 'moderator') {
                        $categoryIds = $user->moderatedCategories()->pluck('categories.id')->toArray();
                        $query->whereIn('category_id', $categoryIds);
                    }
                    $pendingCount = $query->count();
                }

                // 3. Staff: Reported Posts
                $reportCount = 0;
                $reportedCommentsCount = 0;
                if (in_array($user->role, ['admin', 'moderator'])) {
                    $reportQuery = \App\Models\Post::where('is_reported', true);
                    if ($user->role === 'moderator') {
                        $categoryIds = $user->moderatedCategories()->pluck('categories.id')->toArray();
                        $reportQuery->whereIn('category_id', $categoryIds);
                    }
                    $reportCount = $reportQuery->count();

                    // Reported Comments (yellow badge)
                    $reportedCommentsCount = \App\Models\Comment::where('is_reported', true)->count();
                }

                // 4. User: Own posts newly approved (for profile notification dot)
                $newApprovals = 0;
                if ($user->role === 'user') {
                    $newApprovals = \App\Models\Post::where('user_id', $user->id)
                        ->where('is_approved', true)
                        ->where('is_approval_notified', false)
                        ->count();
                }

                $view->with([
                    'unread_messages_count'    => $unreadMessages,
                    'unread_message_senders'   => $unreadMessageSenders,
                    'pending_approvals_count'  => $pendingCount,
                    'reported_posts_count'     => $reportCount,
                    'reported_comments_count'  => $reportedCommentsCount,
                    'new_user_approvals_count' => $newApprovals,
                ]);
            } else {
                $view->with([
                    'unread_messages_count'   => 0,
                    'unread_message_senders'  => [],
                    'pending_approvals_count' => 0,
                    'reported_posts_count'    => 0,
                    'reported_comments_count' => 0,
                    'new_user_approvals_count'=> 0,
                ]);
            }
        });
    }
}

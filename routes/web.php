<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\UserController;

Route::get('/', [PostController::class, 'index'])->name('home');

Route::get('/dashboard', [ModerationController::class, 'index'])
    ->middleware(['auth', 'role:admin,moderator'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// PUBLIC ROUTES
Route::resource('posts', PostController::class)->only([
    'index',
    'show',
]);
Route::get('/u/{user:username}', [UserController::class, 'show'])->name('users.show');



// AUTHENTICATED USER ROUTES
Route::middleware(['auth'])->group(function () {
    Route::view('/contact', 'user.contact.index')->name('contact.index');

    // Posts
    Route::resource('posts', PostController::class)->except([
        'index',
        'show',
    ]);

    // Report a post
    Route::patch('/posts/{post}/report', [PostController::class, 'report'])
        ->name('posts.report');

    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->name('comments.store');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    // Report a comment
    Route::post('/comments/{comment}/report', [CommentController::class, 'report'])
        ->name('comments.report');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');

    // Reactions for posts
    Route::post('/posts/{post}/reactions', [ReactionController::class, 'reactToPost'])
        ->name('react.post');

    // Reactions for comments
    Route::post('/comments/{comment}/reactions', [ReactionController::class, 'reactToComment'])
        ->name('react.comment');

    // Messaging
    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::get('/api/messages/{user}', [\App\Http\Controllers\MessageController::class, 'fetchMessages'])->name('api.messages.fetch');
    Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
});


// MODERATOR + ADMIN ROUTES
Route::middleware(['auth', 'role:admin,moderator'])->group(function () {
    Route::get('/admin/approvals', [ModerationController::class, 'approvals'])
        ->name('admin.approvals');

    Route::get('/admin/reports', [ModerationController::class, 'reports'])
        ->name('admin.reports');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Moderation Actions
    Route::post('/posts/{post}/approve', [ModerationController::class, 'approvePost'])
        ->name('posts.approve');

    Route::delete('/posts/{post}/reject', [ModerationController::class, 'rejectPost'])
        ->name('posts.reject');

    Route::post('/posts/{post}/dismiss-report', [ModerationController::class, 'dismissReport'])
        ->name('posts.dismiss-report');

    Route::post('/comments/{comment}/dismiss-report', [ModerationController::class, 'dismissCommentReport'])
        ->name('comments.dismiss-report');

    Route::delete('/comments/{comment}/reject', [ModerationController::class, 'rejectComment'])
        ->name('comments.reject');
});


// ADMIN ONLY ROUTES
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/team', [ModerationController::class, 'team'])
        ->name('admin.team');

    Route::post('/users/{user}/toggle-moderator', [ModerationController::class, 'toggleModerator'])
        ->name('admin.toggle-moderator');

    Route::post('/users/{user}/toggle-block', [ModerationController::class, 'toggleBlock'])
        ->name('admin.toggle-block');
});

require __DIR__ . '/auth.php';






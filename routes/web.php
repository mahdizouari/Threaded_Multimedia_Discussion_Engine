<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'role:admin,moderator'])->name('dashboard');

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


// AUTHENTICATED USER ROUTES
Route::middleware(['auth'])->group(function () {

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
    Route::patch('/comments/{comment}/report', [CommentController::class, 'report'])
        ->name('comments.report');

    // Reactions for posts
    Route::post('/posts/{post}/reactions', [ReactionController::class, 'reactToPost'])
        ->name('posts.reactions.store');

    // Reactions for comments
    Route::post('/comments/{comment}/reactions', [ReactionController::class, 'reactToComment'])
        ->name('comments.reactions.store');
});


// MODERATOR + ADMIN ROUTES
Route::middleware(['auth', 'role:admin,moderator'])->group(function () {

    // Categories
    Route::resource('categories', CategoryController::class);

    // Approve a post
    Route::patch('/posts/{post}/approve', [PostController::class, 'approve'])
        ->name('posts.approve');
});


// ADMIN ONLY ROUTES
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Future admin-only routes
    // Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

require __DIR__.'/auth.php';






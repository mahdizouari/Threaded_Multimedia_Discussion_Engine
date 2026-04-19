@extends('layouts.pulse')

@section('title', 'Post Detail — Pulse')

@section('content')
<style>
    /* Specific styles for post detail page */
    .back-nav {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 24px;
        transition: var(--transition);
        padding: 8px 16px;
        border-radius: var(--radius-pill);
        background: rgba(0,0,0,0.02);
    }
    .back-nav:hover {
        background: var(--bg-glass-hover);
        color: var(--text-primary);
    }
    
    .post-detail {
        margin-bottom: 32px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05); /* slightly bigger shadow for emphasis */
    }

    .post-detail .post-title {
        font-size: 24px;
        margin-top: 8px;
        margin-bottom: 16px;
    }

    .post-detail .post-content {
        font-size: 16px;
        line-height: 1.7;
        color: var(--text-primary);
    }

    /* Comments Section */
    .comments-section {
        background: var(--bg-glass);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-glass);
        padding: 24px;
    }

    .comments-header {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .comment-form {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 32px;
    }

    .comment-textarea {
        width: 100%;
        min-height: 120px;
        padding: 16px;
        border-radius: var(--radius-md);
        border: 1px solid rgba(0,0,0,0.1);
        background: #ffffff;
        color: var(--text-primary);
        font-size: 15px;
        resize: vertical;
        transition: var(--transition);
    }
    
    .comment-textarea:focus {
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }

    .comment-form-actions {
        display: flex;
        justify-content: flex-end;
    }

    /* Single Comment */
    .comment-thread {
        margin-top: 24px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .comment {
        display: flex;
        gap: 12px;
    }

    .comment-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .comment-body {
        flex: 1;
        background: rgba(0,0,0,0.02);
        border-radius: var(--radius-md);
        padding: 16px;
        border: 1px solid rgba(0,0,0,0.04);
    }

    .comment-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .comment-author {
        font-weight: 700;
        color: var(--text-primary);
    }

    .comment-time {
        color: var(--text-muted);
    }

    .comment-text {
        font-size: 15px;
        line-height: 1.6;
        color: var(--text-secondary);
        margin-bottom: 12px;
    }

    .comment-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .com-action-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        transition: var(--transition);
        padding: 4px 8px;
        border-radius: var(--radius-sm);
        border: none;
        background: transparent;
        cursor: pointer;
    }

    .com-action-btn:hover {
        background: rgba(0,0,0,0.05);
        color: var(--text-primary);
    }
    
    .com-action-btn.like-btn:hover {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }
    
    .com-action-btn.like-btn.active {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }
    
    .com-action-btn.like-btn.active svg {
        fill: #22c55e;
    }

    .com-action-btn.dislike-btn:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .com-action-btn.dislike-btn.active {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .com-action-btn.dislike-btn.active svg {
        fill: #ef4444;
    }

    .com-action-btn svg {
        width: 16px;
        height: 16px;
    }
</style>

<!-- Moderation Toolbar (Staff Only) -->
@php
    $canModerate = false;
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            $canModerate = true;
        } elseif (Auth::user()->role === 'moderator') {
            $canModerate = Auth::user()->moderatedCategories()
                ->where('categories.id', $post->category_id)
                ->exists();
        }
    }
@endphp

@if($canModerate)
<div class="moderation-toolbar" style="margin-bottom: 24px; padding: 16px 24px; background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(8px); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: space-between; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
    <div style="display: flex; align-items: center; gap: 12px;">
        <div style="width: 32px; height: 32px; background: var(--accent-gradient); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
        </div>
        <div>
            <div style="font-size: 13px; font-weight: 800; color: white; letter-spacing: 0.5px; text-transform: uppercase;">Staff Controls</div>
            <div style="font-size: 11px; color: rgba(255,255,255,0.6); font-weight: 500;">
                @if(!$post->is_approved) <span style="color: #fbbf24;">● Awaiting Approval</span> @elseif($post->is_reported) <span style="color: #ef4444;">● Reported ({{ $post->reports_count }} flags)</span> @else <span style="color: #22c55e;">● Public Content</span> @endif
            </div>
        </div>
    </div>
    <div style="display: flex; gap: 10px;">
        @if(!$post->is_approved)
            <form action="{{ route('posts.approve', $post->id) }}" method="POST">
                @csrf
                <button type="submit" class="mod-btn approve" style="background: #22c55e; color: white; border: none; padding: 8px 16px; border-radius: var(--radius-pill); font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    Approve
                </button>
            </form>
        @endif

        @if($post->is_reported)
            <form action="{{ route('posts.dismiss-report', $post->id) }}" method="POST">
                @csrf
                <button type="submit" class="mod-btn neutral" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 7px 16px; border-radius: var(--radius-pill); font-size: 12px; font-weight: 700; cursor: pointer;">
                    Dismiss Flags
                </button>
            </form>
        @endif

        <form action="{{ route('posts.reject', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post permanently?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="mod-btn danger" style="background: #ef4444; color: white; border: none; padding: 8px 16px; border-radius: var(--radius-pill); font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                Delete Post
            </button>
        </form>
    </div>
</div>
@endif

<!-- Navigation Back -->
<a href="/" class="back-nav">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
    </svg>
    Back to Home
</a>

<!-- Post Detail -->
<article class="post post-detail">
    <div class="post-header">
        <div class="post-header-left">
            <a href="{{ route('users.show', $post->user->username) }}" class="community-avatar" style="display: block; width: fit-content;">
                <img src="{{ $post->user->profile && $post->user->profile->avatar_path ? asset('storage/' . $post->user->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($post->user->username ?? $post->user->name) }}" alt="author" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </a>
            <div>
                <div class="post-community" style="display: flex; gap: 6px; align-items: center; margin-bottom: 2px;">
                    <a href="{{ route('users.show', $post->user->username) }}" style="color: inherit;">u/{{ $post->user->username ?? $post->user->name }}</a>
                    @if($post->user->role === 'admin')
                        <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: #ef4444; color: white;">ADMIN</span>
                    @elseif($post->user->role === 'moderator')
                        <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: #8b5cf6; color: white;">MOD</span>
                    @endif
                    @auth
                        @if(Auth::id() !== $post->user_id)
                        <a href="{{ route('messages.show', $post->user_id) }}" title="Send Chat" style="margin-left: 4px; color: var(--accent-primary); display: inline-flex; align-items: center;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        </a>
                        @endif
                    @endauth
                </div>
                <div class="post-meta">
                    <span style="color: var(--accent-primary); font-weight: 600;">p/{{ strtolower($post->category->label ?? 'all') }}</span> • {{ $post->published_at->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>

    <h1 class="post-title">{{ $post->title }}</h1>
    
    <div class="post-content">
        {!! nl2br(e($post->content)) !!}
    </div>

    @if($post->image_path)
    <div class="post-media">
        <img src="{{ filter_var($post->image_path, FILTER_VALIDATE_URL) ? $post->image_path : asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}">
    </div>
    @endif

    <div class="post-actions" style="margin-top: 24px;">
        <div class="action-group" data-post-id="{{ $post->id }}">
            @if(Auth::check())
                <button type="button"
                    class="action-btn like-btn {{ $post->reactions->where('user_id', Auth::id())->where('appreciation.type', 'TOP')->count() ? 'active' : '' }}"
                    title="Like"
                    onclick="reactToPost({{ $post->id }}, 'TOP', this)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"></path>
                        <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                    </svg>
                </button>
                <span class="vote-count like-count" data-post-likes="{{ $post->id }}">{{ $post->reactions->where('appreciation.type', 'TOP')->count() }}</span>
            @else
                <div class="action-btn like-btn" style="cursor: default; opacity: 0.5;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"></path>
                        <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                    </svg>
                </div>
                <span class="vote-count like-count">{{ $post->reactions->where('appreciation.type', 'TOP')->count() }}</span>
            @endif

            @if(Auth::check())
                <button type="button"
                    class="action-btn dislike-btn {{ $post->reactions->where('user_id', Auth::id())->where('appreciation.type', 'FLOP')->count() ? 'active' : '' }}"
                    title="Dislike"
                    onclick="reactToPost({{ $post->id }}, 'FLOP', this)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3H10z"></path>
                        <path d="M17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path>
                    </svg>
                </button>
                <span class="vote-count dislike-count" data-post-dislikes="{{ $post->id }}">{{ $post->reactions->where('appreciation.type', 'FLOP')->count() }}</span>
            @else
                <div class="action-btn dislike-btn" style="cursor: default; opacity: 0.5;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3H10z"></path>
                        <path d="M17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path>
                    </svg>
                </div>
                <span class="vote-count dislike-count">{{ $post->reactions->where('appreciation.type', 'FLOP')->count() }}</span>
            @endif
        </div>
        <button class="interaction-btn"
            style="transition: all 0.2s;"
            onmouseover="this.style.color='var(--accent-primary)'; this.style.background='rgba(124, 58, 237, 0.1)';"
            onmouseout="this.style.color=''; this.style.background='';">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
            {{ $post->conversation->comments->count() ?? 0 }} Comments
        </button>


        @if(Auth::check() && Auth::id() !== $post->user_id)
        <form action="{{ route('posts.report', $post->id) }}" method="POST" style="margin-left: auto;">
            @csrf
            @method('PATCH')
            <button type="submit" class="interaction-btn report-btn" title="Report this content" style="transition: all 0.2s;" onmouseover="this.style.color='#f97316'; this.style.background='rgba(249, 115, 22, 0.1)';" onmouseout="this.style.color=''; this.style.background='';">
                 <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                 Report
            </button>
        </form>
        @endif
    </div>
</article>

<script>
    function toggleEdit(commentId) {
        const textElement = document.getElementById('comment-text-' + commentId);
        const formElement = document.getElementById('comment-edit-' + commentId);
        
        if (textElement.style.display === 'none') {
            textElement.style.display = 'block';
            formElement.style.display = 'none';
        } else {
            textElement.style.display = 'none';
            formElement.style.display = 'block';
        }
    }
</script>


<!-- Comments Section -->
<div class="comments-section">
    <div class="comments-header">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
        Comments
    </div>

    <!-- Create Comment -->
    @if(Auth::check())
    <div class="comment-form">
        <form action="{{ route('comments.store', $post->id) }}" method="POST">
            @csrf
            <textarea name="text" class="comment-textarea" placeholder="Add to the discussion..." required></textarea>
            <div class="comment-form-actions">
                <button type="submit" class="btn btn-primary" style="padding: 12px 32px;">Post Comment</button>
            </div>
        </form>
    </div>
    @elseif(!Auth::check())
    <div class="comment-form">
        <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 12px 32px; text-align: center;">Log In to Comment</a>
    </div>
    @endif

    <!-- Comments List -->
    <div class="comment-thread">
        
        @forelse($post->conversation->comments ?? [] as $comment)
        <!-- Individual Comment -->
        <div class="comment">
            <a href="{{ route('users.show', $comment->user->username) }}" style="text-decoration: none;">
                <img src="{{ $comment->user->profile && $comment->user->profile->avatar_path ? asset('storage/' . $comment->user->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($comment->user->username) }}" alt="avatar" class="comment-avatar" style="object-fit: cover;">
            </a>
            <div class="comment-body">
                <div class="comment-header">
                    <a href="{{ route('users.show', $comment->user->username) }}" class="comment-author" style="text-decoration: none; color: inherit; font-weight: 700; display: flex; align-items: center; gap: 4px;">
                        u/{{ $comment->user->username }}
                        @if($comment->user->isAdmin())
                            <span style="font-size: 8px; font-weight: 800; padding: 1px 4px; border-radius: 3px; background: #ef4444; color: white;">ADMIN</span>
                        @elseif($comment->user->isModerator())
                            <span style="font-size: 8px; font-weight: 800; padding: 1px 4px; border-radius: 3px; background: #8b5cf6; color: white;">MOD</span>
                        @endif
                    </a>
                    <span class="comment-time">{{ $comment->commented_at->diffForHumans() }}</span>
                    @if($comment->user_id === $post->user_id)
                    <span style="font-size: 11px; background: var(--accent-gradient); color: white; padding: 2px 8px; border-radius: 4px; font-weight: bold; margin-left: 4px; letter-spacing: 0.5px;">AUTHOR</span>
                    @endif
                </div>
                <div class="comment-text" id="comment-text-{{ $comment->id }}">
                    @if($comment->reports_count >= 5 && !(Auth::check() && in_array(Auth::user()->role, ['admin', 'moderator'])))
                        <span style="color: var(--text-muted); font-style: italic; font-size: 14px; background: rgba(0,0,0,0.03); padding: 8px 12px; border-radius: 8px; display: block; border: 1px dashed var(--border-glass);">
                            This comment has been hidden due to community reports.
                        </span>
                    @else
                        {{ $comment->text }}
                        @if($comment->reports_count >= 5)
                            <div style="margin-top: 8px; font-size: 11px; color: #ef4444; font-weight: 700; text-transform: uppercase;">[ Flagged for Staff Review ]</div>
                        @endif
                    @endif
                </div>
                
                @if(Auth::id() === $comment->user_id)
                <div class="comment-edit-form" id="comment-edit-{{ $comment->id }}" style="display: none; margin-top: 12px;">
                    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <textarea name="text" class="comment-textarea" style="min-height: 80px; margin-bottom: 8px;">{{ $comment->text }}</textarea>
                        <div style="display: flex; gap: 8px;">
                            <button type="submit" class="btn btn-primary" style="padding: 6px 16px; font-size: 12px;">Save</button>
                            <button type="button" class="btn-sm" style="background: rgba(0,0,0,0.05); border: none;" onclick="toggleEdit('{{ $comment->id }}')">Cancel</button>
                        </div>
                    </form>
                </div>
                @endif
                <div class="comment-actions">
                    @if(Auth::check())
                    <button type="button" class="com-action-btn like-btn {{ $comment->reactions->where('user_id', Auth::id())->where('appreciation.type', 'TOP')->count() > 0 ? 'active' : '' }}" title="Like" style="{{ $comment->reactions->where('user_id', Auth::id())->where('appreciation.type', 'TOP')->count() > 0 ? 'color: #22c55e; background: rgba(34, 197, 94, 0.1);' : '' }}" onclick="reactToComment({{ $comment->id }}, 'TOP', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"></path>
                            <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                        </svg>
                        <span data-comment-likes="{{ $comment->id }}">{{ $comment->reactions->filter(fn($r) => $r->appreciation->type === 'TOP')->count() }}</span>
                    </button>
                    <button type="button" class="com-action-btn dislike-btn {{ $comment->reactions->where('user_id', Auth::id())->where('appreciation.type', 'FLOP')->count() > 0 ? 'active' : '' }}" title="Dislike" style="{{ $comment->reactions->where('user_id', Auth::id())->where('appreciation.type', 'FLOP')->count() > 0 ? 'color: #ef4444; background: rgba(239, 68, 68, 0.1);' : '' }}" onclick="reactToComment({{ $comment->id }}, 'FLOP', this)">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3H10z"></path>
                            <path d="M17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path>
                        </svg>
                        <span data-comment-dislikes="{{ $comment->id }}">{{ $comment->reactions->filter(fn($r) => $r->appreciation->type === 'FLOP')->count() }}</span>
                    </button>
                    @else
                    <div class="com-action-btn like-btn" style="cursor: default; opacity: 0.5;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"></path>
                            <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                        </svg>
                        {{ $comment->reactions->filter(fn($r) => $r->appreciation->type === 'TOP')->count() }}
                    </div>
                    <div class="com-action-btn dislike-btn" style="cursor: default; opacity: 0.5;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3H10z"></path>
                            <path d="M17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path>
                        </svg>
                        {{ $comment->reactions->filter(fn($r) => $r->appreciation->type === 'FLOP')->count() }}
                    </div>
                    @endif

                    @if(Auth::id() === $comment->user_id)
                    <button class="com-action-btn" title="Edit comment" onclick="toggleEdit('{{ $comment->id }}')">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        Edit
                    </button>
                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="com-action-btn" title="Delete comment" style="color: #ef4444;" onclick="return confirm('Delete this comment?')">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            Delete
                        </button>
                    </form>
                    @endif

                    @if(Auth::check() && Auth::id() !== $comment->user_id)
                    <form action="{{ route('comments.report', $comment->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="com-action-btn report-btn" title="Report this content" style="transition: all 0.2s;" onmouseover="this.style.color='#f97316'; this.style.background='rgba(249, 115, 22, 0.1)';" onmouseout="this.style.color=''; this.style.background='';">
                             <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                             Report
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 40px; color: var(--text-muted);">
            <p>No comments yet. Be the first to share your thoughts!</p>
        </div>
        @endforelse

    </div>
</div>

@endsection

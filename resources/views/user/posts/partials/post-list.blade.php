@forelse($posts as $post)
    <article class="post">
        <div class="post-header">
            <div class="post-header-left">
                <a href="{{ route('users.show', $post->user->username) }}" class="community-avatar" style="display: block; width: fit-content;">
                    <img src="{{ $post->user->profile && $post->user->profile->avatar_path ? asset('storage/' . $post->user->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($post->user->username) }}" alt="author" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                </a>
                <div>
                    <div class="post-community" style="display: flex; gap: 6px; align-items: center; margin-bottom: 2px;">
                        <a href="{{ route('users.show', $post->user->username) }}" class="post-author" style="font-weight: 800; color: var(--text-primary); text-decoration: none;">u/{{ $post->user->username }}</a>
                        @if(isset($post->user) && $post->user->isAdmin())
                            <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: #ef4444; color: white;">ADMIN</span>
                        @elseif(isset($post->user) && $post->user->isModerator())
                            <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: #8b5cf6; color: white;">MOD</span>
                        @endif
                    </div>
                    <div class="post-meta">
                        <span style="color: var(--accent-primary); font-weight: 600;">p/{{ strtolower($post->category->label ?? 'general') }}</span> • {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            @auth
                @if(Auth::id() !== $post->user_id)
                    <a href="{{ route('messages.show', $post->user_id) }}" class="interaction-btn"
                        style="padding: 6px 12px; border-radius: var(--radius-pill); font-size: 11px; transition: all 0.2s;"
                        onmouseover="this.style.color='var(--accent-primary)'; this.style.background='rgba(124, 58, 237, 0.1)';"
                        onmouseout="this.style.color=''; this.style.background='';">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Chat
                    </a>
                @endif
            @endauth
        </div>

        <a href="{{ route('posts.show', $post->id) }}" style="text-decoration: none; display: block; color: inherit; cursor: pointer;">
            <h2 class="post-title">{{ $post->title }}</h2>
            <div class="post-content">
                {{ Str::limit($post->content, 300) }}
            </div>

            @if($post->image_path)
                <div class="post-media">
                    <img src="{{ filter_var($post->image_path, FILTER_VALIDATE_URL) ? $post->image_path : asset('storage/' . $post->image_path) }}"
                        alt="Post image">
                </div>
            @endif
        </a>

        <div class="post-actions">
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
            <a href="{{ route('posts.show', $post->id) }}" class="interaction-btn"
                style="transition: all 0.2s;"
                onmouseover="this.style.color='var(--accent-primary)'; this.style.background='rgba(124, 58, 237, 0.1)';"
                onmouseout="this.style.color=''; this.style.background='';">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path
                        d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                    </path>
                </svg>
                {{ $post->comments_count }} Comments
            </a>
            @if(Auth::check() && Auth::id() !== $post->user_id)
                <button type="button" 
                        onclick="openGlobalReportModal('post', {{ $post->id }}, '{{ addslashes($post->title) }}')"
                        class="interaction-btn report-btn" title="Report this content" 
                        style="transition: all 0.2s; border: none; background: none; cursor: pointer; display: flex; align-items: center; gap: 4px;" 
                        onmouseover="this.style.color='#f97316'; this.style.background='rgba(249, 115, 22, 0.1)';" 
                        onmouseout="this.style.color=''; this.style.background='';">
                    <svg viewBox="0 0 24 24" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                        <line x1="4" y1="22" x2="4" y2="15"></line>
                    </svg>
                    Report
                </button>
            @endif
        </div>
    </article>
@empty
    <div
        style="text-align: center; padding: 48px; background: var(--bg-glass); border: 1px solid var(--border-glass); border-radius: var(--radius-lg); color: var(--text-muted);">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            style="margin-bottom: 16px; opacity: 0.5;">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        <p>No posts found. Be the first to share something!</p>
    </div>
@endforelse

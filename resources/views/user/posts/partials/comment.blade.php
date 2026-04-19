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
                            <button type="button" class="btn-pill ghost" style="padding: 6px 14px; font-size: 11px;" onclick="toggleEdit('{{ $comment->id }}')">Cancel</button>
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
                    <button type="button" 
                            onclick="openGlobalConfirmModal('{{ route('comments.destroy', $comment->id) }}', 'This comment will be permanently removed.')"
                            class="com-action-btn" title="Delete comment" 
                            style="color: #ef4444; border: none; background: none; cursor: pointer; display: flex; align-items: center; gap: 4px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        Delete
                    </button>
                    @endif

                    @if(Auth::check() && Auth::id() !== $comment->user_id)
                    <button type="button" 
                            onclick="openGlobalReportModal('comment', {{ $comment->id }}, '{{ addslashes(Str::limit($comment->text, 40)) }}')"
                            class="com-action-btn report-btn" title="Report this content" 
                            style="transition: all 0.2s; border: none; background: none; cursor: pointer; display: flex; align-items: center; gap: 4px;" 
                            onmouseover="this.style.color='#f97316'; this.style.background='rgba(249, 115, 22, 0.1)';" 
                            onmouseout="this.style.color=''; this.style.background='';">
                         <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                         Report
                    </button>
                    @endif
                </div>
            </div>
        </div>

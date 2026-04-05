@extends('layouts.pulse')

@section('title', 'Flagged Activity — Pulse')

@section('content')
<div class="reports-container" style="padding: 24px; max-width: 1200px; margin: 0 auto;">
    
    <div class="glass-panel" style="padding: 32px; border-radius: var(--radius-lg); margin-bottom: 32px; background: #dc2626; color: white; box-shadow: var(--shadow-lg);">
        <h1 style="font-size: 28px; font-weight: 800; margin-bottom: 8px;">Flagged Activity</h1>
        <p style="opacity: 0.9; font-size: 15px;">Review content reported by users and ensure community standards are being met.</p>
        
        @if(auth()->user()->role === 'moderator')
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.15); display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <span style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.7);">Assigned Scope:</span>
            @forelse(auth()->user()->moderatedCategories as $cat)
                <span style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding: 4px 12px; border-radius: var(--radius-pill); font-size: 12px; font-weight: 700;">{{ $cat->label }}</span>
            @empty
                <span style="background: rgba(0,0,0,0.3); padding: 4px 12px; border-radius: var(--radius-pill); font-size: 12px; font-weight: 700;">Global Override</span>
            @endforelse
        </div>
        @endif
    </div>

    <!-- Flagged Posts -->
    <section style="margin-bottom: 48px;">
        <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 24px; color: #ef4444; display: flex; align-items: center; gap: 12px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            Flagged Posts
            <span style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 4px 12px; border-radius: var(--radius-pill); font-size: 13px;">{{ $reportedPosts->count() }}</span>
        </h2>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            @forelse($reportedPosts as $post)
            <div class="glass-panel" style="padding: 24px; border-radius: var(--radius-md); display: flex; justify-content: space-between; align-items: center; border-left: 6px solid #ef4444;">
                <div style="flex: 1;">
                    <div style="font-size: 11px; color: #ef4444; margin-bottom: 4px; font-weight: 900; text-transform: uppercase;">CRITICAL FLAG</div>
                    <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 8px;">{{ $post->title }}</h3>
                    <p style="font-size: 14px; color: var(--text-secondary);">Author: u/{{ $post->user->username }}</p>
                </div>
                <div style="display: flex; gap: 12px; margin-left: 32px;">
                    <form action="{{ route('posts.dismiss-report', $post->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-report ghost">Dismiss Report</button>
                    </form>
                    <form action="{{ route('posts.reject', $post->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-report danger">Delete & Issue Violation</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty-state">No flagged posts. The community is behaving well!</div>
            @endforelse
        </div>
    </section>

    <!-- Flagged Comments -->
    <section>
        <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 24px; color: #f59e0b; display: flex; align-items: center; gap: 12px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
            Flagged Comments
            <span style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; padding: 4px 12px; border-radius: var(--radius-pill); font-size: 13px;">{{ $reportedComments->count() }}</span>
        </h2>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            @forelse($reportedComments as $comment)
            <div class="glass-panel" style="padding: 24px; border-radius: var(--radius-md); display: flex; justify-content: space-between; align-items: center; border-left: 6px solid #f59e0b;">
                <div style="flex: 1;">
                    <p style="font-style: italic; color: var(--text-primary); font-size: 16px; margin-bottom: 8px;">"{{ $comment->text }}"</p>
                    <div style="font-size: 12px; color: var(--text-muted);">Author: u/{{ $comment->user->username }} | On "{{ $comment->conversation->post->title ?? 'Deleted' }}"</div>
                </div>
                <div style="display: flex; gap: 12px; margin-left: 32px;">
                    <form action="{{ route('comments.dismiss-report', $comment->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-report ghost">Dismiss</button>
                    </form>
                    <form action="{{ route('comments.reject', $comment->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-report warning">Delete & Issue Violation</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty-state">No flagged comments.</div>
            @endforelse
        </div>
    </section>
</div>

<style>
    .btn-report {
        padding: 10px 20px; border-radius: var(--radius-pill); font-weight: 800; font-size: 12px; cursor: pointer;
        border: none; transition: all 0.2s ease;
    }
    .btn-report.danger { background: #ef4444; color: white; }
    .btn-report.danger:hover { background: #dc2626; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }

    .btn-report.warning { background: #f59e0b; color: white; }
    .btn-report.warning:hover { background: #d97706; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); }

    .btn-report.ghost { background: rgba(0,0,0,0.05); color: var(--text-secondary); }
    .btn-report.ghost:hover { background: rgba(0,0,0,0.1); color: var(--text-primary); }

    .empty-state {
        text-align: center; padding: 48px; color: var(--text-muted); border: 2px dashed var(--border-glass); 
        border-radius: var(--radius-md); font-weight: 700; font-size: 15px;
    }
</style>
@endsection

@extends('layouts.pulse')

@section('title', 'Pending Approvals — Pulse')

@section('content')
<div class="moderation-container" style="padding: 24px; max-width: 1200px; margin: 0 auto;">
    
    <div class="page-header">
        <div style="position: relative; z-index: 1;">
            <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.5px;">Pending Approvals</h1>
            <p style="opacity: 0.9; font-size: 16px; font-weight: 500;">Review and publish new community content waiting for verification.</p>
        </div>
        
        @if(auth()->user()->role === 'moderator')
        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.15); display: flex; align-items: center; gap: 12px; flex-wrap: wrap; position: relative; z-index: 1;">
            <span style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.7);">Assigned Scope:</span>
            @forelse(auth()->user()->moderatedCategories as $cat)
                <span class="badge" style="background: rgba(255,255,255,0.2); color: white; border: none;">{{ $cat->label }}</span>
            @empty
                <span class="badge danger" style="background: #ef4444; color: white; border: none;">Global Bypass</span>
            @endforelse
        </div>
        @endif
    </div>

    <!-- Approvals List -->
    <div style="display: flex; flex-direction: column; gap: 20px;">
        @forelse($pendingPosts as $post)
        <div class="glass-panel" style="padding: 24px; border-radius: var(--radius-md); display: flex; justify-content: space-between; align-items: center; border-left: 6px solid var(--accent-primary); transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1); cursor: default;" onmouseover="this.style.transform='translateX(12px)'" onmouseout="this.style.transform='translateX(0)'">
            <div style="flex: 1;">
                <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 8px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 8px;">
                    <span style="color: var(--accent-primary);">p/{{ strtolower($post->category->label) }}</span>
                    <span>•</span>
                    <span>u/{{ $post->user->username }}</span>
                    <span>•</span>
                    @php
                        $waitHours = $post->created_at->diffInHours();
                        $waitColor = $waitHours > 24 ? '#ef4444' : ($waitHours > 12 ? '#f97316' : 'var(--text-muted)');
                    @endphp
                    <span style="display: flex; align-items: center; gap: 4px; color: {{ $waitColor }};">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                        Waiting for {{ $post->created_at->diffForHumans(null, true) }}
                    </span>
                </div>
                <h3 style="font-size: 20px; font-weight: 800; margin-bottom: 12px; color: var(--text-primary);">{{ $post->title }}</h3>
                <p style="font-size: 15px; color: var(--text-secondary); line-height: 1.6; max-width: 800px;">{{ Str::limit($post->content, 200) }}</p>
                
                @if($post->image_path)
                <div style="margin-top: 16px; border-radius: 8px; overflow: hidden; max-width: 300px;">
                    <img src="{{ asset('storage/' . $post->image_path) }}" style="width: 100%; height: auto; display: block;">
                </div>
                @endif
            </div>
            <div style="display: flex; flex-direction: column; gap: 12px; margin-left: 32px;">
                <form action="{{ route('posts.approve', $post->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-action primary">Approve & Publish</button>
                </form>
                <form action="{{ route('posts.reject', $post->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-action danger-ghost">Reject Content</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 16px; opacity: 0.3;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <p>Queue is empty! All posts have been reviewed.</p>
        </div>
        @endforelse
    </div>
</div>

<style>
    .btn-action {
        width: 160px; padding: 12px; border-radius: var(--radius-pill); font-weight: 800; font-size: 13px; cursor: pointer;
        border: none; transition: all 0.2s ease; text-align: center;
    }
    .btn-action.primary { background: var(--accent-gradient); color: white; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3); }
    .btn-action.primary:hover { transform: scale(1.05); box-shadow: 0 6px 16px rgba(124, 58, 237, 0.4); }
    
    .btn-action.danger-ghost { background: transparent; border: 1.5px solid #ef4444; color: #ef4444; }
    .btn-action.danger-ghost:hover { background: #ef4444; color: white; }

    .empty-state {
        text-align: center; padding: 80px; color: var(--text-muted); border: 2.5px dashed var(--border-glass); 
        border-radius: var(--radius-lg); font-weight: 700; font-size: 16px; background: rgba(0,0,0,0.01);
    }
</style>
@endsection

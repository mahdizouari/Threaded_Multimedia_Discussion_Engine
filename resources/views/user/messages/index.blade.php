@extends('layouts.pulse')

@section('title', 'Direct Messages — Pulse')

@section('content')
<div class="messages-container glass-panel" style="border-radius: var(--radius-lg); padding: 0; overflow: hidden; min-height: 80vh; display: flex; flex-direction: column;">
    <div class="messages-header" style="padding: 24px 32px; border-bottom: 1px solid var(--border-glass); background: rgba(0,0,0,0.02);">
        <h1 style="font-size: 24px; font-weight: 800;">Direct Messages</h1>
    </div>

    <div class="messages-layout" style="display: grid; grid-template-columns: 320px 1fr; flex: 1;">
        <!-- Sidebar: Conversations List -->
        <aside class="conversations-sidebar" style="border-right: 1px solid var(--border-glass); padding: 20px; display: flex; flex-direction: column;">
            <div style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 16px;">Conversations</div>
            
            <!-- User Search -->
            <form action="{{ route('messages.index') }}" method="GET" style="margin-bottom: 20px; position: relative;">
                <input type="text" name="search" placeholder="Search users..." value="{{ $search ?? '' }}" 
                    style="width: 100%; padding: 10px 16px 10px 36px; border-radius: var(--radius-pill); border: 1px solid var(--border-glass); background: rgba(0,0,0,0.02); font-size: 14px; outline: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%);">
                    <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </form>

            <div class="conv-list" style="display: flex; flex-direction: column; gap: 8px; flex: 1; overflow-y: auto;">
                @forelse($conversations as $user)
                <a href="{{ route('messages.show', $user->id) }}" class="conv-item {{ (isset($targetUser) && $targetUser->id === $user->id) ? 'active' : '' }}" style="display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: var(--radius-md); text-decoration: none; color: inherit; transition: var(--transition);">
                    <img src="{{ $user->profile && $user->profile->avatar_path ? asset('storage/' . $user->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($user->username ?? $user->name) }}" alt="avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    <div style="flex: 1; overflow: hidden;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="font-weight: 700; font-size: 15px; color: var(--text-primary);">{{ $user->username ?? $user->name }}</span>
                            @if($user->isAdmin())
                                <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: #ef4444; color: white; text-transform: uppercase; letter-spacing: 0.05em;">Admin</span>
                            @elseif($user->isModerator())
                                <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: #8b5cf6; color: white; text-transform: uppercase; letter-spacing: 0.05em;">Mod</span>
                            @endif
                        </div>
                        <div style="font-size: 13px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Click to chat</div>
                    </div>
                </a>
                @empty
                <div style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 14px;">
                    No messages yet. Start a conversation from a post!
                </div>
                @endforelse
            </div>
        </aside>

        <!-- Main: Chat Window placeholder -->
        <main class="chat-area" style="display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.01);">
            <div style="text-align: center; max-width: 400px; padding: 40px;">
                <div style="background: var(--accent-gradient); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; color: white;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </div>
                <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;">Your Conversations</h2>
                <p style="color: var(--text-secondary); font-size: 15px;">Select a person from the left to start chatting with them. Keep it friendly!</p>
            </div>
        </main>
    </div>
</div>

<style>
    .conv-item:hover { background: rgba(124, 58, 237, 0.05); }
    .conv-item.active { background: rgba(124, 58, 237, 0.1); border: 1px solid rgba(124, 58, 237, 0.2); }
</style>
@endsection

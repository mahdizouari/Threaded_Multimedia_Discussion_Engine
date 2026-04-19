@extends('layouts.pulse')

@section('title', 'Direct Chats — Pulse')

@section('content')
<div class="messages-container glass-panel" style="border-radius: var(--radius-lg); padding: 0; overflow: hidden; min-height: 80vh; display: flex; flex-direction: column;">
    <div class="messages-header" style="padding: 24px 32px; border-bottom: 1px solid var(--border-glass); background: rgba(0,0,0,0.02);">
        <h1 style="font-size: 24px; font-weight: 800;">Direct Chats</h1>
    </div>

    <div class="messages-layout" style="display: grid; grid-template-columns: 320px 1fr; flex: 1;">
        <!-- Sidebar: Conversations List -->
        <aside class="conversations-sidebar" style="border-right: 1px solid var(--border-glass); padding: 20px; display: flex; flex-direction: column;">
            <div style="font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 16px;">Conversations</div>
            
            <!-- User Search -->
            <form action="{{ route('messages.index') }}" method="GET" style="margin-bottom: 20px; position: relative;">
                <input type="text" name="u_search" placeholder="Search users..." value="{{ $search ?? '' }}" 
                    style="width: 100%; padding: 10px 16px 10px 36px; border-radius: var(--radius-pill); border: 1px solid var(--border-glass); background: rgba(0,0,0,0.02); font-size: 14px; outline: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%);">
                    <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </form>

            <div style="display: flex; flex-direction: column; gap: 8px; flex: 1; overflow-y: auto;">
                <!-- Recent Conversations (History) -->
                <div id="history-list" style="display: flex; flex-direction: column; gap: 8px;">
                    @forelse($conversations as $user)
                        @php
                            $isUnread = isset($unread_message_senders) && in_array($user->id, $unread_message_senders);
                        @endphp
                    <a href="{{ route('messages.show', $user->id) }}" 
                       class="conv-item {{ (isset($targetUser) && $targetUser->id === $user->id) ? 'active' : '' }} {{ $isUnread ? 'unread' : '' }}" 
                       style="display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: var(--radius-md); text-decoration: none; color: inherit; transition: var(--transition); position: relative; {{ $isUnread ? 'background: rgba(124, 58, 237, 0.05);' : '' }}">
                        
                        <div style="position: relative; flex-shrink: 0;">
                            <div style="width: 44px; height: 44px; border-radius: 50%; padding: 2px; {{ $isUnread ? 'background: var(--accent-gradient);' : '' }}">
                                <img src="{{ $user->profile && $user->profile->avatar_path ? asset('storage/' . $user->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($user->username ?? 'user') }}" 
                                     alt="avatar" 
                                     style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 2px solid white;">
                            </div>
                            @if($isUnread)
                                <div class="nav-badge-dot" style="position: absolute; bottom: 2px; right: 2px; width: 12px; height: 12px; background: var(--accent-primary); border: 2px solid white; border-radius: 50%;"></div>
                            @endif
                        </div>

                        <div style="flex: 1; overflow: hidden;">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 6px; min-width: 0;">
                                    <span class="conv-username" style="font-weight: {{ $isUnread ? '800' : '700' }}; font-size: 15px; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">u/{{ $user->username ?? 'user' }}</span>
                                    @if($user->isAdmin())
                                        <span style="font-size: 7px; font-weight: 900; padding: 1px 4px; border-radius: 3px; background: #ef4444; color: white; text-transform: uppercase;">ADM</span>
                                    @endif
                                </div>
                                @if($isUnread)
                                    <span style="font-size: 9px; font-weight: 900; color: var(--accent-primary); text-transform: uppercase; letter-spacing: 0.5px;">NEW</span>
                                @endif
                            </div>
                            <div style="font-size: 13px; color: {{ $isUnread ? 'var(--accent-primary)' : 'var(--text-muted)' }}; font-weight: {{ $isUnread ? '700' : '500' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $isUnread ? 'Sent you a new message' : 'Click to chat' }}
                            </div>
                        </div>
                    </a>
                    @empty
                    <div id="no-chats-msg" style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 14px;">
                        No chats yet. Start a conversation from a post!
                    </div>
                    @endforelse
                </div>

                <!-- Live Search Results -->
                <div id="search-results-list" style="display: none; flex-direction: column; gap: 8px;">
                    <!-- Injected by JS -->
                </div>
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
    
    #search-results-list .conv-item {
        animation: fadeIn 0.3s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    const searchInput = document.querySelector('input[name="u_search"]');
    const historyList = document.getElementById('history-list');
    const searchResultsList = document.getElementById('search-results-list');
    let searchTimeout;

    // Prevent form submission
    searchInput.closest('form').addEventListener('submit', (e) => e.preventDefault());

    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.trim().toLowerCase();
        
        if (query.length === 0) {
            historyList.style.display = 'flex';
            searchResultsList.style.display = 'none';
            return;
        }

        // Switch to search mode
        historyList.style.display = 'none';
        searchResultsList.style.display = 'flex';
        searchResultsList.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 13px;">Searching Pulse...</div>';

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetch(`/api/users/search?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(users => {
                    renderSearchResults(users);
                })
                .catch(err => {
                    console.error('Search error:', err);
                    searchResultsList.innerHTML = '<div style="text-align: center; padding: 20px; color: #ef4444; font-size: 13px;">Error performing search.</div>';
                });
        }, 300);
    });

    function renderSearchResults(users) {
        if (users.length === 0) {
            searchResultsList.innerHTML = '<div style="text-align: center; padding: 24px; color: var(--text-muted); font-size: 14px;">No users found matching that name.</div>';
            return;
        }

        searchResultsList.innerHTML = users.map(user => `
            <a href="${user.show_url}" class="conv-item" style="display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: var(--radius-md); text-decoration: none; color: inherit; transition: var(--transition);">
                <img src="${user.avatar_url}" alt="avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                <div style="flex: 1; overflow: hidden;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="font-weight: 700; font-size: 15px; color: var(--text-primary);">u/${user.username}</span>
                        ${user.is_admin ? '<span style="font-size: 8px; font-weight: 900; padding: 2px 6px; border-radius: 4px; background: #ef4444; color: white; text-transform: uppercase;">ADMINISTRATOR</span>' : ''}
                        ${user.is_moderator ? '<span style="font-size: 8px; font-weight: 900; padding: 2px 6px; border-radius: 4px; background: #8b5cf6; color: white; text-transform: uppercase;">MODERATOR</span>' : ''}
                    </div>
                    <div style="font-size: 13px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Click to start chatting</div>
                </div>
            </a>
        `).join('');
    }
</script>
@endsection

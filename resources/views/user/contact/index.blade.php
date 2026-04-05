@extends('layouts.pulse')

@section('title', 'Messages - Pulse')

@section('content')
<style>
    .chat-container {
        display: flex;
        background: var(--bg-glass);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-glass);
        height: 600px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    /* Left Sidebar: Conversations list */
    .chat-sidebar {
        width: 280px;
        background: rgba(255, 255, 255, 0.5);
        border-right: 1px solid var(--border-glass);
        display: flex;
        flex-direction: column;
    }

    .chat-header {
        padding: 20px;
        border-bottom: 1px solid var(--border-glass);
        font-weight: 700;
        font-size: 18px;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .new-chat-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--accent-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .new-chat-btn:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-glow);
    }

    .conv-list {
        flex: 1;
        overflow-y: auto;
        padding: 12px 0;
    }

    .conv-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        cursor: pointer;
        transition: var(--transition);
        border-right: 3px solid transparent;
    }

    .conv-item:hover {
        background: rgba(0,0,0,0.02);
    }

    .conv-item.active {
        background: rgba(124, 58, 237, 0.05);
        border-right-color: var(--accent-primary);
    }

    .conv-avatar {
        position: relative;
    }

    .conv-avatar img {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
    }

    .status-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #10b981;
        border: 2px solid white;
    }

    .conv-info {
        flex: 1;
        overflow: hidden;
    }

    .conv-name {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .conv-lastmsg {
        font-size: 12px;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Right Area: Active Chat */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: rgba(255,255,255,0.8);
    }

    .chat-active-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--border-glass);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chat-active-header img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .chat-active-name {
        font-weight: 700;
        font-size: 16px;
        color: var(--text-primary);
    }

    .chat-active-status {
        font-size: 12px;
        color: #10b981;
    }

    .chat-messages {
        flex: 1;
        padding: 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .msg {
        max-width: 75%;
        display: flex;
        flex-direction: column;
    }

    .msg.incoming {
        align-self: flex-start;
    }

    .msg.outgoing {
        align-self: flex-end;
        align-items: flex-end;
    }

    .msg-bubble {
        padding: 12px 16px;
        border-radius: var(--radius-lg);
        font-size: 14px;
        line-height: 1.5;
        position: relative;
    }

    .msg.incoming .msg-bubble {
        background: #f1f5f9;
        color: var(--text-primary);
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .msg.outgoing .msg-bubble {
        background: var(--accent-gradient);
        color: white;
        border-bottom-right-radius: 4px;
        box-shadow: 0 4px 10px rgba(124, 58, 237, 0.15);
    }

    .msg-time {
        font-size: 11px;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .chat-input-area {
        padding: 20px;
        border-top: 1px solid var(--border-glass);
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255,255,255,0.9);
    }

    .chat-input {
        flex: 1;
        padding: 14px 20px;
        border-radius: var(--radius-pill);
        border: 1px solid rgba(0,0,0,0.1);
        background: #f8fafc;
        outline: none;
        font-size: 14px;
        transition: var(--transition);
    }

    .chat-input:focus {
        border-color: var(--accent-primary);
        background: #ffffff;
    }

    .btn-send {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--accent-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: var(--transition);
        cursor: pointer;
    }

    .btn-send:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-glow);
    }

    /* Scrollbar for chat messages */
    .chat-messages::-webkit-scrollbar, .conv-list::-webkit-scrollbar {
        width: 6px;
    }
    .chat-messages::-webkit-scrollbar-thumb, .conv-list::-webkit-scrollbar-thumb {
        background: rgba(0,0,0,0.1);
        border-radius: 10px;
    }
</style>

<div style="margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700; color: var(--text-primary);">Messages</h2>
    <p style="color: var(--text-secondary); font-size: 15px;">Communicate privately with other members</p>
</div>

<div class="chat-container">
    
    <!-- Left Sidebar -->
    <div class="chat-sidebar">
        <div class="chat-header">
            Conversations
            <button class="new-chat-btn" title="New Message">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>
        </div>
        
        <div class="conv-list">
            <!-- Active Conversation -->
            <div class="conv-item active">
                <div class="conv-avatar">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=design_junkie" alt="u/design_junkie">
                    <div class="status-dot"></div>
                </div>
                <div class="conv-info">
                    <div class="conv-name">u/design_junkie</div>
                    <div class="conv-lastmsg">Hey! I saw your post...</div>
                </div>
            </div>

            <!-- Inactive Conversation 1 -->
            <div class="conv-item">
                <div class="conv-avatar">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=cyber_ninja" alt="u/cyber_ninja">
                </div>
                <div class="conv-info">
                    <div class="conv-name">u/cyber_ninja</div>
                    <div class="conv-lastmsg">Sure, let me check the files and get back to you.</div>
                </div>
            </div>

            <!-- Inactive Conversation 2 -->
            <div class="conv-item">
                <div class="conv-avatar">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=moderator_jane" alt="u/moderator_jane">
                </div>
                <div class="conv-info">
                    <div class="conv-name">u/moderator_jane</div>
                    <div class="conv-lastmsg">Thanks for reporting that post. We took action.</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Main Chat -->
    <div class="chat-main">
        <div class="chat-active-header">
            <img src="https://i.pravatar.cc/100?img=33" alt="u/design_junkie">
            <div>
                <div class="chat-active-name">u/design_junkie</div>
                <div class="chat-active-status">Online</div>
            </div>
            
            <div style="margin-left: auto; display: flex; gap: 16px; color: var(--text-muted);">
                <button style="color: inherit; transition: var(--transition);" onmouseover="this.style.color='var(--accent-primary)'" onmouseout="this.style.color='inherit'">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                </button>
            </div>
        </div>

        <div class="chat-messages">
            <div class="msg incoming">
                <div class="msg-bubble">
                    Hey! I saw your post about the new UI. It looks truly fantastic. Did you use any specific framework or just vanilla CSS variables?
                </div>
                <div class="msg-time">Today, 10:23 AM</div>
            </div>
            
            <div class="msg outgoing">
                <div class="msg-bubble">
                    Hey, thanks a lot! I just used vanilla CSS variables. It gave me a lot more control over the transitions and backdrop filters.
                </div>
                <div class="msg-time">Today, 10:30 AM</div>
            </div>

            <div class="msg incoming">
                <div class="msg-bubble">
                    That makes perfect sense. I was struggling doing something similar with Tailwind because the blurs sometimes stack weirdly. Would you mind sharing the specific `--bg-glass` rgba values?
                </div>
                <div class="msg-time">Today, 10:35 AM</div>
            </div>
        </div>

        <div class="chat-input-area">
            <button style="color: var(--text-muted); cursor: pointer;" title="Attach file">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
            </button>
            <button style="color: var(--text-muted); cursor: pointer;" title="Emoji">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M8 14s1.5 2 4 2 4-2 4-2"></path><line x1="9" y1="9" x2="9.01" y2="9"></line><line x1="15" y1="9" x2="15.01" y2="9"></line></svg>
            </button>
            
            <input type="text" class="chat-input" placeholder="Type a message...">
            
            <button class="btn-send">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </div>
    </div>
</div>

@endsection

@extends('layouts.pulse')

@section('title', 'Chat with ' . ($user->username ?? $user->name) . ' — Pulse')

@section('content')
<div class="messages-container glass-panel" style="border-radius: var(--radius-lg); padding: 0; overflow: hidden; min-height: 80vh; display: flex; flex-direction: column;">
    <div class="messages-header" style="padding: 16px 32px; border-bottom: 1px solid var(--border-glass); background: rgba(0,0,0,0.02); display: flex; align-items: center; gap: 12px;">
        <a href="{{ route('messages.index') }}" style="color: var(--text-muted); border-radius: 50%; padding: 8px; transition: var(--transition);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </a>
        <div style="position: relative;">
            <img src="{{ $user->profile && $user->profile->avatar_path ? asset('storage/' . $user->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($user->username ?? $user->name) }}" alt="avatar" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
            <div style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background: #22c55e; border: 2px solid white; border-radius: 50%;"></div>
        </div>
        <div style="flex: 1;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <h1 style="font-size: 16px; font-weight: 800; line-height: 1;">{{ $user->username ?? $user->name }}</h1>
                @if($user->isAdmin())
                    <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: #ef4444; color: white; text-transform: uppercase;">Admin</span>
                @elseif($user->isModerator())
                    <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; background: #8b5cf6; color: white; text-transform: uppercase;">Mod</span>
                @endif
            </div>
            <span style="font-size: 11px; color: #22c55e; font-weight: 600;">Active Now</span>
        </div>
    </div>

    <div class="chat-area" style="flex: 1; display: flex; flex-direction: column; height: 500px;">
        <!-- Chat body -->
        <div id="chat-thread" style="flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 16px;">
            @forelse($messages as $msg)
            <div style="display: flex; flex-direction: column; gap: 4px; align-items: {{ $msg->sender_id === Auth::id() ? 'flex-end' : 'flex-start' }};">
                <div style="max-width: 80%; display: flex; flex-direction: column; gap: 4px; align-items: {{ $msg->sender_id === Auth::id() ? 'flex-end' : 'flex-start' }};">
                    @if($msg->file_path)
                        <div class="message-attachment" style="margin-bottom: 4px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border-glass);">
                            @if(Str::startsWith($msg->file_type, 'image/'))
                                <img src="{{ asset('storage/' . $msg->file_path) }}" alt="attachment" style="max-width: 100%; max-height: 300px; display: block; cursor: pointer;" onclick="window.open(this.src)">
                            @else
                                <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank" style="display: flex; align-items: center; gap: 10px; padding: 12px; background: rgba(0,0,0,0.05); color: var(--text-primary); text-decoration: none;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                    <span style="font-size: 13px; font-weight: 600;">Download Attachment</span>
                                </a>
                            @endif
                        </div>
                    @endif

                    @if($msg->body)
                    <div style="padding: 12px 18px; border-radius: 16px; line-height: 1.5; font-size: 15px; 
                        {{ $msg->sender_id === Auth::id() ? 'background: var(--accent-gradient); color: white; border-bottom-right-radius: 4px;' : 'background: rgba(0,0,0,0.05); color: var(--text-primary); border-bottom-left-radius: 4px;' }}">
                        {{ $msg->body }}
                    </div>
                    @endif
                </div>
                <div style="font-size: 11px; color: var(--text-muted);">{{ $msg->created_at->diffForHumans() }}</div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                Start a new conversation with {{ $user->username ?? $user->name }}. Be respectful and kind.
            </div>
            @endforelse
        </div>

        <!-- Image Preview Container (Initially Hidden) -->
        <div id="image-preview-container" style="display: none; padding: 12px 24px; border-top: 1px solid var(--border-glass); background: rgba(0,0,0,0.01);">
            <div style="position: relative; display: inline-block; border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-sm); border: 2px solid white;">
                <img id="image-preview" src="" alt="preview" style="max-width: 200px; max-height: 150px; display: block; object-fit: cover;">
                <button type="button" onclick="clearImagePreview()" style="position: absolute; top: 4px; right: 4px; width: 24px; height: 24px; border-radius: 50%; background: rgba(0,0,0,0.5); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); transition: var(--transition);" onmouseover="this.style.background='rgba(239, 68, 68, 0.8)'" onmouseout="this.style.background='rgba(0,0,0,0.5)'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px; font-weight: 600;">Image selected — ready to send</div>
        </div>

        <!-- Chat form -->
        <div style="padding: 24px; border-top: 1px solid var(--border-glass);">
            <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 12px; align-items: center;">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                
                <label for="chat-file" id="chat-file-label" style="cursor: pointer; color: var(--text-muted); padding: 8px; border-radius: 50%; transition: var(--transition);" onmouseover="this.style.background='rgba(0,0,0,0.05)'" onmouseout="this.style.background='transparent'">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                    <input type="file" id="chat-file" name="attachment" style="display: none;" onchange="handleFileSelect(this)">
                </label>

                <input type="text" name="body" placeholder="Type your message..." style="flex: 1; padding: 12px 20px; border-radius: var(--radius-pill); border: 1px solid var(--border-glass); background: rgba(0,0,0,0.02); outline: none;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px; border-radius: var(--radius-pill); display: flex; align-items: center; gap: 8px;">
                    <span>Send</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Auto scroll to bottom
    const thread = document.getElementById('chat-thread');
    thread.scrollTop = thread.scrollHeight;

    function handleFileSelect(input) {
        const container = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        const label = document.getElementById('chat-file-label');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Only preview images
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                    label.style.color = 'var(--accent-primary)';
                    // Scroll to see preview
                    thread.scrollTop = thread.scrollHeight;
                }
                reader.readAsDataURL(file);
            } else {
                // For non-image files, just show the primary color on label
                container.style.display = 'none';
                label.style.color = 'var(--accent-primary)';
            }
        }
    }

    function clearImagePreview() {
        const container = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        const input = document.getElementById('chat-file');
        const label = document.getElementById('chat-file-label');

        input.value = ''; // Clear file
        preview.src = '';
        container.style.display = 'none';
        label.style.color = 'var(--text-muted)';
    }
</script>
@endsection

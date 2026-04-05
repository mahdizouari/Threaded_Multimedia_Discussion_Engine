@extends('layouts.pulse')

@section('title', 'Create a new Post - Pulse')

@section('content')
<style>
    .create-header {
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-glass);
    }

    .create-header h2 {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .create-card {
        background: var(--bg-glass);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-glass);
        padding: 32px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 8px;
    }

    .form-input, .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border-radius: var(--radius-md);
        border: 1px solid rgba(0,0,0,0.1);
        background: #ffffff;
        color: var(--text-primary);
        font-size: 15px;
        transition: var(--transition);
        outline: none;
    }

    .form-textarea {
        min-height: 200px;
        resize: vertical;
        line-height: 1.6;
    }

    .form-input:focus, .form-textarea:focus {
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }

    .form-select {
        width: 100%;
        max-width: 300px;
        padding: 12px 16px;
        border-radius: var(--radius-md);
        border: 1px solid rgba(0,0,0,0.1);
        background: #ffffff;
        color: var(--text-primary);
        font-size: 15px;
        outline: none;
        cursor: pointer;
    }

    .form-select:focus {
        border-color: var(--accent-primary);
    }

    .btn-submit {
        padding: 12px 32px;
        background: var(--accent-gradient);
        color: white;
        border-radius: var(--radius-pill);
        font-size: 15px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.2);
    }

    .btn-submit:hover {
        box-shadow: var(--shadow-glow);
        transform: translateY(-1px);
    }

    .media-upload-area {
        border: 2px dashed rgba(0,0,0,0.1);
        border-radius: var(--radius-md);
        padding: 40px;
        text-align: center;
        background: rgba(0,0,0,0.02);
        cursor: pointer;
        transition: var(--transition);
    }

    .media-upload-area:hover {
        border-color: var(--accent-primary);
        background: rgba(124, 58, 237, 0.05);
    }

    .media-upload-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 16px auto;
        color: var(--text-muted);
    }

    .media-upload-text {
        font-size: 15px;
        color: var(--text-secondary);
        font-weight: 500;
    }
</style>

<div class="create-header">
    <h2>Create a post</h2>
</div>

<div class="create-card">
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="category_id" class="form-label">Category (Community)</label>
            <select id="category_id" name="category_id" class="form-select" required>
                <option value="" disabled selected>Choose a community...</option>
                <option value="1">p/technology</option>
                <option value="2">p/design</option>
                <option value="3">p/gaming</option>
                <option value="4">p/hardware</option>
            </select>
        </div>

        <div class="form-group">
            <label for="title" class="form-label">Title</label>
            <input type="text" id="title" name="title" class="form-input" placeholder="An interesting title" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="content" class="form-label">Body Text</label>
            <textarea id="content" name="content" class="form-textarea" placeholder="What are your thoughts? You can write your post here..."></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Attach Media (Optional)</label>
            <div class="media-upload-area" onclick="document.getElementById('media_file').click()">
                <svg class="media-upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <p class="media-upload-text">Click to upload image or video</p>
                <input type="file" id="media_file" name="media" style="display: none;" accept="image/*,video/*">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 32px;">
            <button type="submit" class="btn-submit">Post</button>
        </div>
    </form>
</div>

@endsection

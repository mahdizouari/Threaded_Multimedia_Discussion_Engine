@extends('layouts.pulse')

@section('title', 'Flagged Activity — Pulse')

@section('content')
    <div class="reports-container" style="padding: 24px; max-width: 1200px; margin: 0 auto; animation: fadeIn 0.5s ease;">

        <!-- Premium Header -->
        <div class="page-header">
            <div style="position: relative; z-index: 1;">
                <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.5px;">Flagged Activity</h1>
                <p style="opacity: 0.9; font-size: 16px; font-weight: 500;">
                    Review content reported by users and ensure community standards are maintained.
                </p>
            </div>

            @if(auth()->user()->role === 'moderator')
                <div
                    style="margin-top: 24px; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.15); display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <span
                        style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.7);">Assigned
                        Scope:</span>
                    @forelse(auth()->user()->moderatedCategories as $cat)
                        <span class="badge"
                            style="background: rgba(255,255,255,0.2); color: white; border: none;">{{ $cat->label }}</span>
                    @empty
                        <span class="badge warning" style="background: rgba(0,0,0,0.3); color: white; border: none;">Global
                            Override</span>
                    @endforelse
                </div>
            @endif
        </div>

        <!-- Flagged Posts -->
        <section style="margin-bottom: 56px;">
            <h2
                style="font-size: 20px; font-weight: 800; margin-bottom: 24px; color: var(--text-primary); display: flex; align-items: center; gap: 12px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                Flagged Posts
                <span class="badge danger" style="font-size: 12px; padding: 4px 10px;">{{ $reportedPosts->count() }}</span>
            </h2>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                @forelse($reportedPosts as $post)
                    <div class="data-list-item" style="border-left: 6px solid #ef4444;">
                        <div style="flex: 1;">
                            <div
                                style="font-size: 11px; color: #ef4444; margin-bottom: 6px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px;">
                                CRITICAL FLAG</div>
                            <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 8px; color: var(--text-primary);">
                                {{ $post->title }}</h3>
                            <div
                                style="font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                                <span>Author: <strong>u/{{ $post->user->username }}</strong></span>
                                <span>•</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 12px; margin-left: 32px; align-items: center;">
                            <button type="button" onclick="openReviewModal(this)" data-type="post" data-id="{{ $post->id }}"
                                data-title="{{ $post->title }}" data-author="u/{{ $post->user->username }}"
                                data-content="{{ $post->content }}"
                                data-image="{{ $post->image_path ? (Str::startsWith($post->image_path, ['http://', 'https://']) ? $post->image_path : asset('storage/' . $post->image_path)) : '' }}"
                                class="btn-pill ghost"
                                style="color: var(--accent-primary); border: 1.5px solid var(--accent-primary); font-size: 11px;">
                                Review Content
                            </button>
                            <button onclick="openDismissModal('post', {{ $post->id }}, '{{ addslashes($post->title) }}')"
                                class="btn-pill ghost" style="font-size: 11px;">Dismiss Report</button>
                            <button onclick="openRejectModal('post', {{ $post->id }}, '{{ addslashes($post->title) }}')"
                                class="btn-pill danger" style="font-size: 11px;">Rejection & violation</button>
                        </div>
                    </div>
                @empty
                    <div
                        style="text-align: center; padding: 64px; color: var(--text-muted); border: 2.5px dashed var(--border-glass); border-radius: var(--radius-lg); background: rgba(0,0,0,0.01);">
                        <p style="font-weight: 700; font-size: 16px;">All clear! No flagged posts.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Flagged Comments -->
        <section>
            <h2
                style="font-size: 20px; font-weight: 800; margin-bottom: 24px; color: var(--text-primary); display: flex; align-items: center; gap: 12px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.5">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                Flagged Comments
                <span class="badge warning"
                    style="font-size: 12px; padding: 4px 10px;">{{ $reportedComments->count() }}</span>
            </h2>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                @forelse($reportedComments as $comment)
                    <div class="data-list-item" style="border-left: 6px solid #f59e0b;">
                        <div style="flex: 1;">
                            <p
                                style="font-style: italic; color: var(--text-primary); font-size: 16px; margin-bottom: 10px; line-height: 1.5;">
                                "{{ Str::limit($comment->text, 150) }}"</p>
                            <div
                                style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 8px;">
                                <span>Author: <strong>u/{{ $comment->user->username }}</strong></span>
                                <span>•</span>
                                <span>On "{{ $comment->conversation->post->title ?? 'Deleted' }}"</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 12px; margin-left: 32px; align-items: center;">
                            <button type="button" onclick="openReviewModal(this)" data-type="comment"
                                data-id="{{ $comment->id }}" data-title="Reported Comment"
                                data-author="u/{{ $comment->user->username }}" data-content="{{ $comment->text }}" data-image=""
                                class="btn-pill ghost"
                                style="color: var(--accent-primary); border: 1.5px solid var(--accent-primary); font-size: 11px;">
                                Review
                            </button>
                            <button
                                onclick="openDismissModal('comment', {{ $comment->id }}, '{{ addslashes(Str::limit($comment->text, 30)) }}')"
                                class="btn-pill ghost" style="font-size: 11px;">Dismiss</button>
                            <button
                                onclick="openRejectModal('comment', {{ $comment->id }}, '{{ addslashes(Str::limit($comment->text, 30)) }}')"
                                class="btn-pill danger" style="font-size: 11px;">Delete & Violation</button>
                        </div>
                    </div>
                @empty
                    <div
                        style="text-align: center; padding: 48px; color: var(--text-muted); border: 2px dashed var(--border-glass); border-radius: var(--radius-md);">
                        <p style="font-weight: 700;">No flagged comments.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- REVIEW MODAL -->
    <div id="reviewModal" class="modal-overlay">
        <div class="modal-card" style="max-width: 480px;">
            <div class="modal-header" style="background: rgba(0,0,0,0.01);">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div id="review_badge" class="badge" style="font-size: 8px; padding: 2px 6px;">POST REVIEW</div>
                    <h2 id="review_title" class="modal-title"
                        style="font-size: 16px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">
                    </h2>
                </div>
                <button onclick="closeCurrentModal()"
                    style="background: none; border: none; font-size: 20px; color: var(--text-muted); cursor: pointer; line-height: 1;">&times;</button>
            </div>
            <div style="padding: 16px 20px; max-height: 50vh; overflow-y: auto;">
                <div
                    style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(0,0,0,0.03);">
                    <div id="review_author_avatar"
                        style="width: 32px; height: 32px; border-radius: 50%; background: #eee; flex-shrink: 0;"></div>
                    <div>
                        <div id="review_author" style="font-weight: 800; color: var(--text-primary); font-size: 13px;">
                        </div>
                        <div style="font-size: 10px; color: var(--text-muted);">Author</div>
                    </div>
                </div>

                <div id="review_image_container"
                    style="display: none; margin-bottom: 12px; text-align: center; background: rgba(0,0,0,0.02); border-radius: 6px; padding: 4px; border: 1px solid rgba(0,0,0,0.04);">
                    <img id="review_image" src=""
                        style="max-width: 100%; max-height: 180px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.04); border: 1px solid white; object-fit: contain;">
                </div>

                <div id="review_content"
                    style="font-size: 14px; color: var(--text-primary); line-height: 1.5; white-space: pre-wrap; word-wrap: break-word;">
                    <!-- Content Inject -->
                </div>
            </div>
            <div class="modal-footer" style="padding: 12px 20px;">
                <button onclick="closeCurrentModal()" class="btn-pill primary"
                    style="width: 100%; height: 38px; font-size: 13px;">Finish Review</button>
            </div>
        </div>
    </div>

    <!-- REJECTION MODAL -->
    <div id="rejectionModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header" style="border-bottom: none; padding: 32px 32px 0;">
                <h2 class="modal-title" style="color: #ef4444; font-size: 24px;">Are you sure?</h2>
                <button onclick="closeCurrentModal()"
                    style="background: none; border: none; font-size: 24px; color: var(--text-muted); cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 24px 32px;">
                <p style="font-size: 15px; color: var(--text-secondary); line-height: 1.6; margin-bottom: 20px;">
                    You are about to <strong style="color: #ef4444;">Reject and Delete</strong> the following content:
                </p>
                <div style="padding: 16px; background: rgba(239, 68, 68, 0.05); border-radius: 12px; border: 1px solid rgba(239, 68, 68, 0.1); font-style: italic; color: var(--text-primary); margin-bottom: 24px;"
                    id="rejection_preview">
                    <!-- Preview Inject -->
                </div>
                <p style="font-size: 13px; color: var(--text-muted);">
                    This action will remove the content permanently and issue a system violation to the author.
                </p>
            </div>
            <form id="rejectionForm" method="POST" style="padding: 0 32px 32px;">
                @csrf
                @method('DELETE')
                <div class="modal-footer" style="border-top: none; padding: 0; gap: 12px;">
                    <button type="button" onclick="closeCurrentModal()" class="btn-pill ghost"
                        style="flex: 1;">Cancel</button>
                    <button type="submit" class="btn-pill danger" style="flex: 2;">Yes, Delete Content</button>
                </div>
            </form>
        </div>
    </div>

    <!-- DISMISS MODAL -->
    <div id="dismissModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header" style="border-bottom: none; padding: 32px 32px 0;">
                <h2 class="modal-title" style="color: var(--accent-primary);">Dismiss Report</h2>
                <button onclick="closeCurrentModal()"
                    style="background: none; border: none; font-size: 24px; color: var(--text-muted); cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 24px 32px;">
                <p style="font-size: 15px; color: var(--text-secondary); line-height: 1.6;">
                    Are you sure you want to dismiss the flags for this content? It will be cleared from the moderation
                    queue.
                </p>
            </div>
            <form id="dismissForm" method="POST" style="padding: 0 32px 32px;">
                @csrf
                <div class="modal-footer" style="border-top: none; padding: 0; gap: 12px;">
                    <button type="button" onclick="closeCurrentModal()" class="btn-pill ghost" style="flex: 1;">Go
                        Back</button>
                    <button type="submit" class="btn-pill primary" style="flex: 2;">Confirm Dismissals</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeCurrentModal() {
            document.querySelectorAll('.modal-overlay').forEach(m => m.style.display = 'none');
            document.body.style.overflow = 'auto';
        }

        function openReviewModal(target) {
            const type = target.dataset.type;
            const title = target.dataset.title;
            const author = target.dataset.author;
            const content = target.dataset.content;
            const imagePath = target.dataset.image;

            document.getElementById('review_title').innerText = title;
            document.getElementById('review_author').innerText = author;
            document.getElementById('review_content').innerText = content;

            const badge = document.getElementById('review_badge');
            badge.innerText = type === 'post' ? 'POST REVIEW' : 'COMMENT REVIEW';
            badge.className = type === 'post' ? 'badge danger' : 'badge warning';

            const imgContainer = document.getElementById('review_image_container');
            const img = document.getElementById('review_image');

            if (imagePath && imagePath.length > 5) { // Ensure path is somewhat valid
                img.src = imagePath;
                imgContainer.style.display = 'block';
            } else {
                imgContainer.style.display = 'none';
                img.src = ''; // Clear image to avoid flickering next time
            }

            // Set dynamic avatar
            document.getElementById('review_author_avatar').style.background = `url('https://api.dicebear.com/7.x/avataaars/svg?seed=${author}') center/cover`;

            openModal('reviewModal');
        }

        function openRejectModal(type, id, preview) {
            const form = document.getElementById('rejectionForm');
            const previewEl = document.getElementById('rejection_preview');

            // Dynamic Route Construction
            form.action = type === 'post' ? `/posts/${id}/reject` : `/comments/${id}/reject`;
            previewEl.innerText = `"${preview}"`;

            openModal('rejectionModal');
        }

        function openDismissModal(type, id, preview) {
            const form = document.getElementById('dismissForm');

            // Dynamic Route Construction
            form.action = type === 'post' ? `/posts/${id}/dismiss-report` : `/comments/${id}/dismiss-report`;

            openModal('dismissModal');
        }

        // Close on outside click
        window.onclick = function (event) {
            if (event.target.classList.contains('modal-overlay')) {
                closeCurrentModal();
            }
        }
    </script>
@endsection
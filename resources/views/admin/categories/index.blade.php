@extends('layouts.pulse')

@section('title', 'Manage Categories — Pulse')

@section('content')
    <div class="categories-container"
        style="padding: 24px; max-width: 1200px; margin: 0 auto; animation: fadeIn 0.5s ease;">

        <!-- Premium Header -->
        <div class="page-header">
            <div
                style="position: relative; z-index: 1; display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <div>
                    <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 8px; letter-spacing: -0.5px;">Categories
                        Management</h1>
                    <p style="opacity: 0.9; font-size: 16px; font-weight: 500;">
                        Organize the forum by creating and refining community spaces.
                    </p>
                </div>
                <button onclick="openModal('addCategoryModal')" class="btn-pill primary"
                    style="background: white; color: var(--accent-primary); box-shadow: 0 10px 20px rgba(0,0,0,0.1); border: none; padding: 14px 28px; font-size: 14px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                        style="margin-right: 8px;">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    New Category
                </button>
            </div>
        </div>

        <!-- Categories List -->
        <div class="pulse-card" style="padding: 0; overflow: hidden;">

            <!-- Table summary bar -->
            <div style="padding: 16px 24px; background: rgba(0,0,0,0.01); border-bottom: 1px solid var(--border-glass); display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 13px; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
                    {{ $categories->total() }} {{ $categories->total() === 1 ? 'category' : 'categories' }} registered
                </span>
                <span style="font-size: 12px; color: var(--text-muted); font-style: italic;">
                    Each category organises a forum community space
                </span>
            </div>

            <table class="pulse-table">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center; color: var(--text-muted); font-size: 12px;">#</th>
                        <th style="width: 55%;">Category Name</th>
                        <th style="width: 18%; text-align: center;">Posts</th>
                        <th style="width: 22%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $i => $cat)
                        @php
                            $colors = ['#7c3aed','#db2777','#2563eb','#059669','#d97706','#dc2626','#0891b2','#7c3aed'];
                            $color = $colors[$i % count($colors)];
                        @endphp
                        <tr>
                            <td style="text-align: center; font-size: 12px; font-weight: 700; color: var(--text-muted);">{{ $i + 1 }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 38px; height: 38px; border-radius: 10px; background: {{ $color }}18;
                                                display: flex; align-items: center; justify-content: center;
                                                flex-shrink: 0; border: 1px solid {{ $color }}30;">
                                        <span style="font-size: 15px; font-weight: 900; color: {{ $color }};">{{ strtoupper(substr($cat->label, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; color: var(--text-primary); font-size: 15px; line-height: 1.2;">{{ $cat->label }}</div>
                                        <div style="font-size: 11.5px; color: var(--text-muted); font-weight: 500; margin-top: 2px;">
                                            p/{{ strtolower(str_replace(' ', '-', $cat->label)) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <span class="badge primary" style="font-size: 12px; padding: 4px 12px;">{{ $cat->posts()->count() }}</span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                    <button type="button" onclick="openEditModal({{ $cat->id }}, '{{ addslashes($cat->label) }}')"
                                        class="btn-pill ghost" style="padding: 7px 16px; font-size: 12px; font-weight: 700;">Edit</button>
                                    <button type="button" onclick="openDeleteModal({{ $cat->id }}, '{{ addslashes($cat->label) }}')"
                                        class="btn-pill danger"
                                        style="padding: 7px 16px; font-size: 12px; font-weight: 700; background: rgba(239, 68, 68, 0.08); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($categories->hasPages())
                <div style="padding: 24px; border-top: 1px solid rgba(0,0,0,0.05);">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- ADD CATEGORY MODAL -->
    <div id="addCategoryModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h2 class="modal-title">Create New Category</h2>
                <button onclick="closeCurrentModal()"
                    style="background: none; border: none; font-size: 24px; color: var(--text-muted); cursor: pointer;">&times;</button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div style="padding: 24px 32px;">
                    <div style="margin-bottom: 20px;">
                        <label
                            style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px; letter-spacing: 0.5px;">Category
                            Name</label>
                        <input type="text" name="label" required placeholder="e.g. Artificial Intelligence"
                            style="width: 100%; padding: 14px; border-radius: 12px; border: 1.5px solid rgba(0,0,0,0.1); font-weight: 600; transition: border-color 0.2s;"
                            onfocus="this.style.borderColor='var(--accent-primary)'"
                            onblur="this.style.borderColor='rgba(0,0,0,0.1)'">
                    </div>
                    <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5;">
                        This name will be displayed across the forum as a community space for users to explore and post in.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeCurrentModal()" class="btn-pill ghost">Cancel</button>
                    <button type="submit" class="btn-pill primary">Create Community</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT CATEGORY MODAL -->
    <div id="editCategoryModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h2 class="modal-title">Edit Category</h2>
                <button onclick="closeCurrentModal()"
                    style="background: none; border: none; font-size: 24px; color: var(--text-muted); cursor: pointer;">&times;</button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PATCH')
                <div style="padding: 24px 32px;">
                    <div style="margin-bottom: 20px;">
                        <label
                            style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px; letter-spacing: 0.5px;">Update
                            Name</label>
                        <input type="text" name="label" id="edit_label" required
                            style="width: 100%; padding: 14px; border-radius: 12px; border: 1.5px solid rgba(0,0,0,0.1); font-weight: 600;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="closeCurrentModal()" class="btn-pill ghost">Cancel</button>
                    <button type="submit" class="btn-pill primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE CATEGORY MODAL -->
    <div id="deleteCategoryModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
                <h2 class="modal-title" style="color: #ef4444;">Are you sure?</h2>
                <button onclick="closeCurrentModal()"
                    style="background: none; border: none; font-size: 24px; color: var(--text-muted); cursor: pointer;">&times;</button>
            </div>
            <div style="padding: 24px 32px;">
                <p style="font-size: 15px; color: var(--text-secondary); line-height: 1.6;">
                    You are about to delete <strong id="delete_label_preview" style="color: var(--text-primary);"></strong>.
                    This action will affect any posts associated with this community.
                </p>
            </div>
            <form id="deleteCategoryForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer" style="border-top: none; padding-top: 0;">
                    <button type="button" onclick="closeCurrentModal()" class="btn-pill ghost">Cancel</button>
                    <button type="submit" class="btn-pill danger">Yes, Delete Permanently</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeCurrentModal() {
            document.querySelectorAll('.modal-overlay').forEach(m => {
                m.classList.remove('active');
                setTimeout(() => {
                    m.style.display = 'none';
                }, 300);
            });
            document.body.style.overflow = 'auto';
        }

        function openEditModal(id, label) {
            const form = document.getElementById('editCategoryForm');
            form.action = `/categories/${id}`;
            document.getElementById('edit_label').value = label;
            openModal('editCategoryModal');
        }

        function openDeleteModal(id, label) {
            const form = document.getElementById('deleteCategoryForm');
            form.action = `/categories/${id}`;
            document.getElementById('delete_label_preview').innerText = label;
            openModal('deleteCategoryModal');
        }

        // Close on outside click
        window.onclick = function (event) {
            if (event.target.classList.contains('modal-overlay')) {
                closeCurrentModal();
            }
        }
    </script>

    <style>
        /* Premium Table Styling (Aligned with Team Hub) */
        .pulse-table tr {
            transition: background 0.2s ease;
            border-bottom: 1px solid var(--border-glass);
        }

        .pulse-table tr:hover {
            background: rgba(0, 0, 0, 0.01) !important;
        }

        /* Animation Hooks */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
@endsection
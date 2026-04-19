@extends('layouts.pulse')

@section('title', 'Team Management — Pulse')

@section('content')
    <div class="team-container"
        style="padding: 32px 24px; max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 32px;">

        <div class="page-header">
            <div style="position: relative; z-index: 1;">
                <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.8px;">Team Management
                </h1>
                <p style="opacity: 0.9; font-size: 16px; font-weight: 500; max-width: 720px; line-height: 1.6;">
                    Administer roles and category-specific moderation scopes. Assigned moderators are exclusively restricted
                    to their designated categories, ensuring focused community oversight.
                </p>
            </div>
        </div>
        <div class="pulse-card" style="padding: 0; overflow: hidden;">
            <div style="overflow-x: auto;">
                <table class="pulse-table">
                    <thead>
                        <tr>
                            <th style="width: 45%;">User Identity & Scope</th>
                            <th style="width: 20%;">Account Integrity</th>
                            <th style="width: 15%;">Role</th>
                            <th style="width: 20%; text-align: right;">Management</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                            @php $totalFlags = $user->reported_posts_count + $user->reported_comments_count; @endphp
                            <tr style="animation: rowIn 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) both; animation-delay: {{ $index * 0.05 }}s;">
                                <td style="vertical-align: top;">
                                    <div style="display: flex; align-items: center; gap: 16px;">
                                        <div
                                            style="width: 48px; height: 48px; border-radius: 14px; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; color: white; font-size: 18px; font-weight: 900; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.2); flex-shrink: 0;">
                                            {{ strtoupper(substr($user->username, 0, 1)) }}
                                        </div>
                                        <div style="min-width: 0;">
                                            <div style="font-weight: 800; color: var(--text-primary); font-size: 15px; display: flex; align-items: center; gap: 8px;">
                                                u/{{ $user->username }}
                                                @if($user->is_blocked)
                                                    <span style="background: #ef4444; width: 6px; height: 6px; border-radius: 50%;" title="Blocked"></span>
                                                @endif
                                            </div>


                                            
                                            {{-- Scope integrated into ID --}}
                                            @if($user->role === 'moderator')
                                                <div style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 8px;">
                                                    @forelse($user->moderatedCategories as $cat)
                                                        <span style="font-size: 9px; font-weight: 800; padding: 2px 8px; border-radius: 4px; background: rgba(124, 58, 237, 0.1); color: var(--accent-primary); border: 1px solid rgba(124, 58, 237, 0.1);">
                                                            {{ strtoupper($cat->label) }}
                                                        </span>
                                                    @empty
                                                        <span style="font-size: 9px; font-weight: 800; padding: 2px 8px; border-radius: 4px; background: rgba(0, 0, 0, 0.05); color: var(--text-muted);">GLOBAL</span>
                                                    @endforelse
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td style="vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; gap: 8px;">
                                        @if($totalFlags > 0)
                                            <div style="display: flex; align-items: center; gap: 6px; color: #f59e0b; font-size: 11px; font-weight: 700;">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                                                {{ $totalFlags }} User Reports
                                            </div>
                                        @else
                                            <div style="color: var(--text-muted); font-size: 11px; font-weight: 600; opacity: 0.5;">No Reports</div>
                                        @endif

                                        @if($user->violations_count > 0)
                                            <div style="display: flex; align-items: center; gap: 6px; color: {{ $user->violations_count >= 3 ? '#ef4444' : '#f59e0b' }}; font-size: 11px; font-weight: 800;">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                                System: {{ $user->violations_count }}/5
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td style="vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                        <div class="badge {{ $user->role === 'moderator' ? 'primary' : 'ghost' }}" style="font-size: 10px; font-weight: 800; width: fit-content;">
                                            {{ strtoupper($user->role) }}
                                        </div>
                                        @if($user->is_blocked)
                                            <span style="font-size: 10px; font-weight: 800; color: #ef4444;">SUSPENDED</span>
                                        @else
                                            <span style="font-size: 10px; font-weight: 800; color: #22c55e;">ACTIVE</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="text-align: right; vertical-align: top;">
                                    <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-end;">
                                        @if($user->role === 'moderator')
                                            <form action="{{ route('admin.toggle-moderator', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-pill danger" style="padding: 6px 12px; font-size: 11px;">Revoke</button>
                                            </form>
                                        @else
                                            <button type="button" onclick="togglePromoteForm('{{ $user->id }}')" class="btn-pill ghost" style="padding: 6px 12px; font-size: 11px; border: 1px solid var(--accent-primary); color: var(--accent-primary); font-weight: 700;">Promote to Moderator</button>
                                        @endif

                                        @if($user->is_blocked)
                                            <form action="{{ route('admin.toggle-block', $user->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-pill primary" style="padding: 6px 12px; font-size: 11px;">Unblock</button>
                                            </form>
                                        @endif
                                    </div>

                                    <!-- Inline Promotion Form -->
                                    <div id="promote-form-{{ $user->id }}"
                                        style="display: none; margin-top: 12px; text-align: left; padding: 16px; background: rgba(0,0,0,0.03); border-radius: 12px; border: 1px solid var(--border-glass); animation: fadeIn 0.3s ease;">
                                        <form action="{{ route('admin.toggle-moderator', $user->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="promote" value="1">
                                            <label style="display: block; font-size: 10px; font-weight: 900; color: var(--text-secondary); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Assign Categories:</label>
                                            <div style="display: grid; grid-template-columns: 1fr; gap: 8px; margin-bottom: 16px;">
                                                @foreach($categories as $cat)
                                                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: var(--text-primary);">
                                                        <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}" style="accent-color: var(--accent-primary); width: 16px; height: 16px;">
                                                        {{ $cat->label }}
                                                    </label>
                                                @endforeach
                                            </div>
                                            <div style="display: flex; gap: 8px;">
                                                <button type="submit" class="btn-pill primary" style="flex: 1; padding: 10px;">Confirm</button>
                                                <button type="button" onclick="togglePromoteForm('{{ $user->id }}')" class="btn-pill ghost" style="padding: 10px;">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function togglePromoteForm(id) {
            const form = document.getElementById('promote-form-' + id);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>

    <style>
        .role-badge {
            padding: 4px 12px;
            border-radius: var(--radius-pill);
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        .role-badge.moderator {
            background: rgba(124, 58, 237, 0.1);
            color: var(--accent-primary);
            border: 1px solid rgba(124, 58, 237, 0.2);
        }

        .role-badge.user {
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-secondary);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .team-btn {
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            font-weight: 800;
            font-size: 12px;
            cursor: pointer;
            border: 1.5px solid transparent;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .team-btn.promote {
            background: transparent;
            border-color: #22c55e;
            color: #22c55e;
        }

        .team-btn.promote:hover {
            background: #22c55e;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .team-btn.revoke {
            background: transparent;
            border-color: #ef4444;
            color: #ef4444;
        }

        .team-btn.revoke:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .team-btn.ghost {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-glass);
        }

        .team-btn.ghost:hover {
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-primary);
        }
    </style>
@endsection
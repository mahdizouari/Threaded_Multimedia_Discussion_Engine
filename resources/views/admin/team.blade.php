@extends('layouts.pulse')

@section('title', 'Team Management — Pulse')

@section('content')
    <div class="team-container" style="padding: 32px 24px; max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 32px;">

        <div class="glass-panel"
            style="padding: 40px; border-radius: var(--radius-lg); background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white; box-shadow: var(--shadow-lg); border: 1px solid rgba(255,255,255,0.05);">
            <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.8px;">Team Management</h1>
            <p style="opacity: 0.8; font-size: 16px; font-weight: 500; max-width: 700px; line-height: 1.6;">
                Administer roles and category-specific moderation scopes. Assigned moderators are exclusively restricted to their designated categories.
            </p>
        </div>
        <div class="glass-panel"
            style="overflow: hidden; border-radius: var(--radius-lg); border: 1px solid var(--border-glass); background: var(--bg-glass); box-shadow: var(--shadow-lg); width: 100%;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 900px;">
                <thead>
                    <tr style="background: rgba(0,0,0,0.02); border-bottom: 2px solid var(--border-glass);">
                        <th
                            style="padding: 20px 24px; font-size: 11px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 1.2px; width: 30%;">
                            User Identity</th>
                        <th
                            style="padding: 20px 24px; font-size: 11px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 1.2px; width: 20%;">
                            Moderation Scope</th>
                        <th
                            style="padding: 20px 24px; font-size: 11px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 1.2px; width: 15%;">
                            Status</th>
                        <th
                            style="padding: 20px 24px; font-size: 11px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 1.2px; width: 15%;">
                            Active Flags</th>
                        <th
                            style="padding: 20px 24px; font-size: 11px; font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 1.2px; width: 20%; text-align: right;">
                            Management</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        @php $totalFlags = $user->reported_posts_count + $user->reported_comments_count; @endphp
                        <tr style="border-bottom: 1px solid var(--border-glass); transition: background 0.2s ease; cursor: default;"
                            onmouseover="this.style.background='rgba(0,0,0,0.01)'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding: 20px 24px; vertical-align: top;">
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <div
                                        style="width: 44px; height: 44px; border-radius: 12px; background: var(--accent-gradient); display: flex; align-items: center; justify-content: center; color: white; font-size: 16px; font-weight: 900; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.15);">
                                        {{ substr($user->name, 0, 1) }}</div>
                                    <div>
                                        <div style="font-weight: 800; color: var(--text-primary); font-size: 15px;">
                                            {{ $user->name }}</div>
                                        <div
                                            style="font-size: 12px; color: var(--text-muted); margin-top: 2px; font-family: monospace;">
                                            u/{{ $user->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 20px 24px; vertical-align: top;">
                                @if($user->role === 'moderator')
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                        @forelse($user->moderatedCategories as $cat)
                                            <span
                                                style="font-size: 10px; font-weight: 800; background: rgba(124, 58, 237, 0.08); color: var(--accent-primary); padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(124, 58, 237, 0.1);">{{ strtoupper($cat->label) }}</span>
                                        @empty
                                            <span
                                                style="font-size: 11px; color: #ef4444; font-weight: 700; letter-spacing: -0.2px;">GLOBAL
                                                BYPASS</span>
                                        @endforelse
                                    </div>
                                @else
                                    <span
                                        style="color: var(--text-muted); font-size: 12px; font-weight: 600; opacity: 0.6;">STANDARD
                                        MEMBER</span>
                                @endif
                            </td>
                            <td style="padding: 20px 24px; vertical-align: top;">
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <span class="role-badge {{ $user->role === 'moderator' ? 'moderator' : 'user' }}"
                                        style="font-size: 9px;">
                                        {{ strtoupper($user->role) }}
                                    </span>
                                    @if($user->is_blocked)
                                        <span class="role-badge blocked"
                                            style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">SUSPENDED</span>
                                    @else
                                        <span class="role-badge"
                                            style="background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); font-size: 9px;">ACTIVE</span>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 20px 24px; vertical-align: top;">
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <div
                                        style="font-size: 14px; font-weight: 800; color: {{ $totalFlags > 0 ? '#f59e0b' : 'var(--text-muted)' }}; display: flex; align-items: center; gap: 6px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                        @if($totalFlags > 0)
                                            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(245, 158, 11, 0.1); color: #b45309; padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(245, 158, 11, 0.2); width: fit-content;">
                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"></circle></svg>
                                                <span style="font-size: 11px; font-weight: 900;">{{ $totalFlags }} FLAG{{ $totalFlags > 1 ? 'S' : '' }}</span>
                                            </div>
                                        @else
                                            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(0, 0, 0, 0.03); color: var(--text-muted); padding: 6px 12px; border-radius: 8px; border: 1px solid var(--border-glass); width: fit-content; opacity: 0.6;">
                                                <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.5px;">NO REPORTS</span>
                                            </div>
                                        @endif

                                        <div style="margin-top: 2px;">
                                            @if($user->violations_count > 0)
                                                <span style="color: {{ $user->violations_count >= 3 ? '#ef4444' : '#f59e0b' }}; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 4px;">
                                                    <span style="width: 4px; height: 4px; border-radius: 50%; background: currentColor;"></span>
                                                    System Violation: {{ $user->violations_count }}/5
                                                </span>
                                            @else
                                                <span style="font-size: 10px; font-weight: 700; color: var(--text-muted); opacity: 0.5; text-transform: uppercase;">Clear Record</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 20px 24px; text-align: right; vertical-align: top;">
                                <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-end;">
                                    @if($user->role === 'moderator')
                                        <form action="{{ route('admin.toggle-moderator', $user->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <button type="submit" class="team-btn revoke"
                                                style="padding: 6px 12px; font-size: 11px;">Revoke Role</button>
                                        </form>
                                    @else
                                        <button onclick="togglePromoteForm('{{ $user->id }}')" class="team-btn promote"
                                            style="padding: 6px 12px; font-size: 11px;">Promote to Mod</button>
                                    @endif

                                    @if($user->is_blocked)
                                        <form action="{{ route('admin.toggle-block', $user->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="team-btn promote" style="width: 120px; font-size: 11px;">
                                                Unblock Access
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Inline Promotion Form -->
                                <div id="promote-form-{{ $user->id }}"
                                    style="display: none; margin-top: 12px; text-align: left; padding: 16px; background: rgba(0,0,0,0.03); border-radius: 12px; border: 1px solid var(--border-glass); transition: all 0.3s ease;">
                                    <form action="{{ route('admin.toggle-moderator', $user->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="promote" value="1">
                                        <label
                                            style="display: block; font-size: 10px; font-weight: 900; color: var(--text-secondary); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Assign
                                            Moderated Categories:</label>
                                        <div style="display: grid; grid-template-columns: 1fr; gap: 8px; margin-bottom: 16px;">
                                            @foreach($categories as $cat)
                                                <label
                                                    style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: var(--text-primary); padding: 4px;">
                                                    <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}"
                                                        style="accent-color: var(--accent-primary); width: 16px; height: 16px;">
                                                    {{ $cat->label }}
                                                </label>
                                            @endforeach
                                        </div>
                                        <div style="display: flex; gap: 8px;">
                                            <button type="submit" class="team-btn promote"
                                                style="flex: 1; padding: 10px;">Confirm Role</button>
                                            <button type="button" onclick="togglePromoteForm('{{ $user->id }}')"
                                                class="team-btn ghost" style="padding: 10px;">Cancel</button>
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
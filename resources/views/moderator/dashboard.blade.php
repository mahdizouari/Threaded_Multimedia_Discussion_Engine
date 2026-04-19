@extends('layouts.pulse')

@section('title', 'Admin Overview — Pulse')

@section('content')
    <div class="dashboard-container" style="padding: 24px; max-width: 1200px; margin: 0 auto;">

        <!-- Premium Header -->
        <div class="glass-panel"
            style="padding: 40px; border-radius: var(--radius-lg); margin-bottom: 32px; background: var(--accent-gradient); color: white; position: relative; overflow: hidden; box-shadow: var(--shadow-lg);">
            <div
                style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; filter: blur(40px);">
            </div>
            <div style="position: relative; z-index: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h1 style="font-size: 32px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.5px;">Forum
                            Overview</h1>
                        <p style="opacity: 0.9; font-size: 16px; font-weight: 500;">
                            Welcome back, <strong>u/{{ auth()->user()->username }}</strong>. Here is the operational status of
                            your community.
                        </p>
                    </div>
                    <div
                        style="text-align: right; background: rgba(255,255,255,0.2); padding: 16px 24px; border-radius: var(--radius-md); backdrop-filter: blur(10px);">
                        <div
                            style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">
                            Community Health</div>
                        <div style="font-size: 28px; font-weight: 900;">{{ $stats['health_score'] }}%</div>
                        <div
                            style="width: 100px; height: 6px; background: rgba(255,255,255,0.3); border-radius: 3px; margin-top: 8px; overflow: hidden;">
                            <div
                                style="width: {{ $stats['health_score'] }}%; height: 100%; background: white; box-shadow: 0 0 10px white;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; margin-bottom: 40px;">
            <a href="{{ route('admin.approvals') }}" class="stat-card" style="animation-delay: 0.1s; text-decoration: none;">
                <div class="stat-label">Pending Approval</div>
                <div class="stat-value" style="color: var(--accent-primary);">{{ $stats['pending_posts'] }}</div>
                @if($stats['urgent_approvals'] > 0)
                    <div class="priority-badge urgent">{{ $stats['urgent_approvals'] }} URGENT</div>
                @else
                    <div class="stat-footer">Requires action</div>
                @endif
            </a>
            <a href="{{ route('admin.reports') }}" class="stat-card" style="animation-delay: 0.2s; text-decoration: none;">
                <div class="stat-label">Reported Content</div>
                <div class="stat-value" style="color: #ef4444;">{{ $stats['reported_posts'] + $stats['reported_comments'] }}
                </div>
                @if($stats['critical_flags'] > 0)
                    <div class="priority-badge critical">{{ $stats['critical_flags'] }} CRITICAL</div>
                @else
                    <div class="stat-footer">Flagged for safety</div>
                @endif
            </a>
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.team') }}" class="stat-card" style="animation-delay: 0.3s; text-decoration: none;">
                <div class="stat-label">Active Users</div>
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-footer">Community size</div>
            </a>
            @else
            <div class="stat-card" style="animation-delay: 0.3s;">
                <div class="stat-label">Active Users</div>
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-footer">Community size</div>
            </div>
            @endif
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('categories.index') }}" class="stat-card" style="animation-delay: 0.4s; text-decoration: none;">
                <div class="stat-label">Categories</div>
                <div class="stat-value">{{ $stats['total_categories'] }}</div>
                <div class="stat-footer">Communities active</div>
            </a>
            @else
            <div class="stat-card" style="animation-delay: 0.4s;">
                <div class="stat-label">Categories</div>
                <div class="stat-value">{{ $stats['total_categories'] }}</div>
                <div class="stat-footer">Communities active</div>
            </div>
            @endif
        </div>

        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 32px;">
            <!-- Recent Pending Activity -->
            <section>
                <h2
                    style="font-size: 20px; font-weight: 800; margin-bottom: 24px; color: var(--text-primary); display: flex; align-items: center; gap: 12px;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 20l4-9-4-9-4 9 4 9z"></path>
                    </svg>
                    Recent Unapproved Posts
                </h2>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @forelse($recent_pending as $post)
                        <div class="glass-panel"
                            style="padding: 20px; border-radius: var(--radius-md); display: flex; justify-content: space-between; align-items: center; border-left: 4px solid var(--accent-primary);">
                            <div>
                                <div style="font-weight: 700; color: var(--text-primary);">{{ $post->title }}</div>
                                <div style="font-size: 13px; color: var(--text-muted);">by u/{{ $post->user->username }} •
                                    {{ $post->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <a href="{{ route('admin.approvals') }}" class="btn-sm">Review</a>
                        </div>
                    @empty
                        <div
                            style="padding: 32px; text-align: center; color: var(--text-muted); border: 2.5px dashed var(--border-glass); border-radius: var(--radius-md);">
                            All clear! No pending posts.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Quick Actions Sidebar -->
            <aside>
                <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 24px; color: var(--text-primary);">Quick
                    Actions</h2>
                <div class="glass-panel"
                    style="padding: 24px; border-radius: var(--radius-md); display: flex; flex-direction: column; gap: 16px;">
                    <a href="{{ route('admin.approvals') }}" class="action-link">
                        <div class="action-icon" style="background: rgba(124, 58, 237, 0.1); color: var(--accent-primary);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight: 700;">Pending Approvals</div>
                            <div style="font-size: 12px; color: var(--text-muted);">Validate new content</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports') }}" class="action-link">
                        <div class="action-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path
                                    d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <div style="font-weight: 700;">Flagged Activity</div>
                            <div style="font-size: 12px; color: var(--text-muted);">Review community reports</div>
                        </div>
                    </a>

                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('categories.index') }}" class="action-link">
                            <div class="action-icon" style="background: rgba(0,0,0,0.05); color: var(--text-secondary);">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 700;">Manage Categories</div>
                                <div style="font-size: 12px; color: var(--text-muted);">Structure forum communities</div>
                            </div>
                        </a>
                    @endif

                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.team') }}" class="action-link">
                            <div class="action-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div>
                                <div style="font-weight: 700;">Team Management</div>
                                <div style="font-size: 12px; color: var(--text-muted);">Appoint moderators</div>
                            </div>
                        </a>
                    @endif
                </div>
            </aside>
        </div>
    </div>

    <style>
        .stat-card {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            padding: 24px;
            border-radius: var(--radius-md);
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.6s ease both;
            position: relative;
            overflow: hidden;
            display: block;
            cursor: pointer;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .priority-badge {
            font-size: 9px;
            font-weight: 900;
            padding: 4px 10px;
            border-radius: var(--radius-pill);
            display: inline-block;
            margin-top: 8px;
            letter-spacing: 0.5px;
        }

        .priority-badge.urgent {
            background: rgba(124, 58, 237, 0.1);
            color: var(--accent-primary);
            border: 1px solid rgba(124, 58, 237, 0.2);
        }

        .priority-badge.critical {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
            border-color: var(--accent-primary);
        }

        .stat-label {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 8px;
            letter-spacing: 0.8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 900;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .stat-footer {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            opacity: 0.8;
        }

        .btn-sm {
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            background: rgba(0, 0, 0, 0.05);
            color: var(--text-primary);
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-sm:hover {
            background: var(--accent-primary);
            color: white;
        }

        .action-link {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: inherit;
            transition: background 0.2s ease;
        }

        .action-link:hover {
            background: rgba(0, 0, 0, 0.02);
        }

        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection
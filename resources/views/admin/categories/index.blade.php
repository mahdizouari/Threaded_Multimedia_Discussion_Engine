@extends('layouts.pulse')

@section('title', 'Manage Categories — Pulse')

@section('content')
<div class="categories-container" style="padding: 24px; max-width: 1200px; margin: 0 auto;">
    
    <div class="glass-panel" style="padding: 32px; border-radius: var(--radius-lg); margin-bottom: 32px; background: var(--accent-gradient); color: white; box-shadow: var(--shadow-lg);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="font-size: 28px; font-weight: 800; margin-bottom: 8px;">Categories</h1>
                <p style="opacity: 0.9; font-size: 15px;">Create and manage the communities that power Pulse Multimedia Forum.</p>
            </div>
            <a href="{{ route('categories.create') }}" class="btn-create" style="text-decoration: none;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                New Category
            </a>
        </div>
    </div>

    <div class="glass-panel" style="overflow: hidden; border-radius: var(--radius-lg); background: var(--bg-glass); border: 1px solid var(--border-glass);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: rgba(0,0,0,0.02); border-bottom: 1px solid var(--border-glass);">
                    <th style="padding: 20px 24px; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Label</th>
                    <th style="padding: 20px 24px; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Slug</th>
                    <th style="padding: 20px 24px; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Posts Count</th>
                    <th style="padding: 20px 24px; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                <tr style="border-bottom: 1px solid var(--border-glass); transition: all 0.2s ease;">
                    <td style="padding: 16px 24px;">
                        <div style="font-weight: 800; color: var(--text-primary); font-size: 15px;">{{ $cat->label }}</div>
                    </td>
                    <td style="padding: 16px 24px; color: var(--text-muted); font-family: monospace;">{{ Str::slug($cat->label) }}</td>
                    <td style="padding: 16px 24px;">
                        <span style="background: rgba(0,0,0,0.05); padding: 4px 10px; border-radius: var(--radius-pill); font-size: 13px; font-weight: 700;">{{ $cat->posts()->count() }}</span>
                    </td>
                    <td style="padding: 16px 24px; text-align: right; display: flex; justify-content: flex-end; gap: 8px;">
                        <a href="{{ route('categories.edit', $cat->id) }}" class="btn-edit" style="text-decoration: none; font-weight: 700; font-size: 13px; color: var(--accent-primary); padding: 8px 16px; border-radius: var(--radius-sm); transition: all 0.2s ease;">Edit</a>
                        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete this category? All related posts will be affected.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div style="padding: 20px; display: flex; justify-content: center;">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Modal for adding category removed in favor of separate page -->
</div>

<style>
    .btn-create {
        background: white; color: var(--accent-primary); border: none; padding: 12px 24px; border-radius: var(--radius-pill);
        font-weight: 800; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 10px;
        transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .btn-create:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.15); }

    .btn-delete {
        background: transparent; border: none; color: #ef4444; font-weight: 700; font-size: 13px; cursor: pointer;
        padding: 8px 16px; transition: all 0.2s ease; border-radius: var(--radius-sm);
    }
    .btn-delete:hover { background: rgba(239, 68, 68, 0.1); }

    .btn-edit:hover { background: rgba(124, 58, 237, 0.1); }

    .btn-mod.primary {
        background: var(--accent-gradient); color: white; border: none; padding: 12px 24px; border-radius: var(--radius-pill);
        font-weight: 800; font-size: 14px; cursor: pointer; transition: all 0.2s ease;
    }
    .btn-mod.primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(124, 58, 237, 0.4); }
</style>
@endsection

@extends('layouts.pulse')

@section('title', 'Edit Category — Pulse')

@section('content')
<div class="category-form-container" style="max-width: 600px; margin: 40px auto; padding: 20px;">
    <div class="glass-panel" style="padding: 40px; border-radius: var(--radius-lg); border: 1px solid var(--border-glass); background: var(--bg-glass); box-shadow: var(--shadow-lg);">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 32px;">
            <div style="width: 48px; height: 48px; background: var(--accent-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2v20M2 12h20"></path></svg>
            </div>
            <div>
                <h1 style="font-size: 24px; font-weight: 800; color: var(--text-primary); margin-bottom: 2px;">Edit Category</h1>
                <p style="font-size: 14px; color: var(--text-muted);">Manage your community space</p>
            </div>
        </div>

        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="form-group" style="margin-bottom: 24px;">
                <label for="label" style="display: block; font-size: 13px; font-weight: 700; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Category Label</label>
                <input type="text" name="label" id="label" value="{{ old('label', $category->label) }}" required
                    style="width: 100%; padding: 14px 16px; border-radius: var(--radius-md); border: 1px solid rgba(0,0,0,0.1); background: rgba(255,255,255,0.5); color: var(--text-primary); font-size: 15px; outline: none; transition: var(--transition);"
                    onfocus="this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 4px rgba(124, 58, 237, 0.1)';" 
                    onblur="this.style.borderColor='rgba(0,0,0,0.1)'; this.style.boxShadow='none';">
                @error('label')
                    <span style="color: #ef4444; font-size: 12px; margin-top: 6px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 32px;">
                <label for="description" style="display: block; font-size: 13px; font-weight: 700; color: var(--text-secondary); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Description (Optional)</label>
                <textarea name="description" id="description" rows="4"
                    style="width: 100%; padding: 14px 16px; border-radius: var(--radius-md); border: 1px solid rgba(0,0,0,0.1); background: rgba(255,255,255,0.5); color: var(--text-primary); font-size: 15px; outline: none; transition: var(--transition); resize: none;"
                    onfocus="this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 4px rgba(124, 58, 237, 0.1)';" 
                    onblur="this.style.borderColor='rgba(0,0,0,0.1)'; this.style.boxShadow='none';">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <span style="color: #ef4444; font-size: 12px; margin-top: 6px; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" class="btn-primary" style="flex: 2; padding: 14px; border-radius: var(--radius-pill); background: var(--accent-gradient); color: white; font-weight: 700; border: none; cursor: pointer; transition: var(--transition); box-shadow: 0 4px 15px rgba(124, 58, 237, 0.3);">
                    Save Changes
                </button>
                <a href="{{ route('categories.index') }}" class="btn-secondary" style="flex: 1; padding: 14px; border-radius: var(--radius-pill); background: rgba(0,0,0,0.05); color: var(--text-secondary); font-weight: 700; border: none; text-align: center; text-decoration: none; cursor: pointer; transition: var(--transition);">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

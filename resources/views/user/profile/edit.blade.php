@extends('layouts.pulse')

@section('title', 'Profile Settings - Pulse')

@section('content')
<style>
    .profile-header {
        margin-bottom: 32px;
    }
    
    .profile-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 8px;
    }
    
    .profile-header p {
        color: var(--text-secondary);
        font-size: 15px;
    }

    .settings-card {
        background: var(--bg-glass);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-glass);
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); /* Slight shadow */
    }

    .settings-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .settings-desc {
        font-size: 14px;
        color: var(--text-secondary);
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 20px;
        max-width: 480px;
    }
    
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 8px;
    }
    
    .form-input {
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
    
    .form-input:focus {
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }
    
    .error-msg {
        color: #ef4444;
        font-size: 13px;
        margin-top: 6px;
        display: block;
    }
    
    .success-msg {
        color: #10b981;
        font-size: 13px;
        margin-top: 12px;
        display: block;
        font-weight: 500;
    }

    .btn-save {
        padding: 10px 24px;
        background: var(--text-primary);
        color: white;
        border-radius: var(--radius-pill);
        font-size: 14px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
    }
    
    .btn-save:hover {
        background: black;
        transform: translateY(-1px);
    }

    .btn-danger {
        padding: 10px 24px;
        background: #ef4444;
        color: white;
        border-radius: var(--radius-pill);
        font-size: 14px;
        font-weight: 600;
        border: none;
        transition: var(--transition);
    }
    
    .btn-danger:hover {
        background: #dc2626;
    }
    
    .interests-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }
    
    .interest-pill {
        padding: 6px 12px;
        border-radius: var(--radius-pill);
        background: rgba(0,0,0,0.05);
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid transparent;
        transition: var(--transition);
        user-select: none;
    }
    
    .interest-pill.active {
        background: var(--accent-gradient);
        color: white;
        box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);
        border-color: transparent;
        font-weight: 700;
    }

    .interest-pill:hover:not(.active) {
        background: rgba(124, 58, 237, 0.08);
        border-color: rgba(124, 58, 237, 0.25);
        color: var(--accent-primary);
    }
</style>

<div class="profile-header">
    <h2>Profile Settings</h2>
    <p>Update your account's profile information, interests, and email address.</p>
</div>

<!-- Profile Info Form -->
<section class="settings-card">
    <header>
        <h3 class="settings-title">Profile Information</h3>
        <p class="settings-desc">Update your account's profile information and email address.</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Private Identity Section (UML Strict Compliance) -->
        <div style="margin-bottom: 24px; padding: 20px; background: rgba(124, 58, 237, 0.03); border-radius: 16px; border: 1px solid rgba(124, 58, 237, 0.08);">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="color: var(--accent-primary);"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                <span style="font-size: 11px; font-weight: 800; color: var(--accent-primary); text-transform: uppercase; letter-spacing: 0.5px;">Private System Identity</span>
            </div>
            
            <div style="display: flex; gap: 16px;">
                <div class="form-group" style="margin-bottom:0; flex:1;">
                    <label for="first_name" class="form-label">First Name</label>
                    <input id="first_name" name="first_name" type="text" class="form-input" value="{{ old('first_name', $user->profile->first_name ?? '') }}" required />
                    @error('first_name')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom:0; flex:1;">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input id="last_name" name="last_name" type="text" class="form-input" value="{{ old('last_name', $user->profile->last_name ?? '') }}" required />
                    @error('last_name')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <p style="font-size: 11px; color: var(--text-muted); margin-top: 12px; font-weight: 500;">These details are strictly used for internal system references and are never visible to other users.</p>
        </div>

        <div style="margin-bottom: 32px; display: flex; align-items: center; gap: 24px;">
            <div style="position: relative;">
                <img id="avatar-preview" 
                     src="{{ $user->profile && $user->profile->avatar_path ? asset('storage/' . $user->profile->avatar_path) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . ($user->username) }}" 
                     alt="Avatar Preview" 
                     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid var(--border-glass); background: white;">
                <label for="avatar" style="position: absolute; bottom: 0; right: 0; background: var(--text-primary); color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid white; transition: var(--transition);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                    <input type="file" id="avatar" name="avatar" style="display: none;" onchange="previewAvatar(this)">
                </label>
            </div>
            <div>
                <h4 style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">Profile Photo</h4>
                <p style="font-size: 13px; color: var(--text-secondary);">Click the camera icon to upload a personal photo or keep your comic-style avatar.</p>
                @error('avatar')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input id="username" name="username" type="text" class="form-input" value="{{ old('username', $user->username ?? '') }}" required />
            @error('username')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-input" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Interests & Preferences</label>
            @php
                $currentInterests = $user->interests()->pluck('categories.id')->toArray();
                $currentInterestsStr = implode(',', $currentInterests);
            @endphp
            <div class="interests-grid" id="profile-interests-container">
                @foreach($categories as $cat)
                    <div class="interest-pill {{ in_array($cat->id, $currentInterests) ? 'active' : '' }}"
                         data-value="{{ $cat->id }}">{{ $cat->label }}</div>
                @endforeach
            </div>
            <input type="hidden" name="interests" id="profile-interests-input" value="{{ $currentInterestsStr }}">
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-save">Save Changes</button>

            @if (session('status') === 'profile-updated')
                <p class="success-msg">Profile successfully updated.</p>
            @endif
        </div>
    </form>
</section>

<script>
    // Preview Avatar
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Handle interests selection for profile
    document.addEventListener('DOMContentLoaded', () => {
        const pills = document.querySelectorAll('#profile-interests-container .interest-pill');
        const input = document.getElementById('profile-interests-input');

        // Parse current selected interests from the hidden input
        let selectedInterests = input.value ? input.value.split(',').map(s => s.trim()).filter(Boolean) : [];

        // Sync hidden input in case it drifts
        input.value = selectedInterests.join(',');

        pills.forEach(pill => {
            pill.addEventListener('click', () => {
                pill.classList.toggle('active');
                const val = pill.getAttribute('data-value');
                if (pill.classList.contains('active')) {
                    if (!selectedInterests.includes(val)) selectedInterests.push(val);
                } else {
                    selectedInterests = selectedInterests.filter(i => i !== val);
                }
                input.value = selectedInterests.join(',');
            });
        });
    });
</script>

<!-- Update Password Form -->
<section class="settings-card">
    <header>
        <h3 class="settings-title">Update Password</h3>
        <p class="settings-desc">Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password" class="form-label">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-input" autocomplete="current-password" />
            @error('current_password', 'updatePassword')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="update_password_password" class="form-label">New Password</label>
            <input id="update_password_password" name="password" type="password" class="form-input" autocomplete="new-password" />
            @error('password', 'updatePassword')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation" class="form-label">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-input" autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-save">Update Password</button>

            @if (session('status') === 'password-updated')
                <p class="success-msg">Password successfully updated.</p>
            @endif
        </div>
    </form>
</section>

<!-- Delete User Form -->
<section class="settings-card">
    <header>
        <h3 class="settings-title" style="color: #ef4444;">Delete Account</h3>
        <p class="settings-desc">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')

        <div class="form-group">
            <label for="password_delete" class="form-label">Verify your password to delete</label>
            <input id="password_delete" name="password" type="password" class="form-input" placeholder="Password" />
            @error('password', 'userDeletion')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-danger">Delete Account</button>
    </form>
</section>

@endsection

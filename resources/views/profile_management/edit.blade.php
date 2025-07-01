@extends('layouts.app')

@section('content')
<div class="wp-container">
    <div class="wp-header-content">
        <h1 class="wp-title">Edit Profile</h1>
        <div class="wp-actions">
            <a href="{{ route('profile-management.index') }}" class="wp-button wp-button-secondary">
                <span class="dashicons dashicons-arrow-left-alt"></span>
                Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="wp-notice wp-notice-success">
            <span class="dashicons dashicons-yes-alt"></span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="wp-notice wp-notice-error">
            <span class="dashicons dashicons-warning"></span>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="wp-notice wp-notice-error">
            <span class="dashicons dashicons-warning"></span>
            <ul class="wp-error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="wp-content">
        <div class="wp-card">
            <div class="wp-card-header">
                <h2 class="wp-card-title">Profile Information</h2>
                <p class="wp-card-description">Update user profile details and settings</p>
            </div>
            
            <div class="wp-card-body">
                <div class="wp-profile-layout">
                    <!-- Avatar Section -->
                    <div class="wp-avatar-section">
                        <div class="wp-avatar-container">
                            <h3 class="wp-section-title">Profile Picture</h3>
                            <div class="wp-avatar-display">
                                <x-user-avatar :user="$user" size="profile" class="wp-avatar" />
                            </div>
                            
                            <form action="{{ route('profile-management.avatar', $user) }}" method="POST" enctype="multipart/form-data" class="wp-avatar-form" onsubmit="return validateAvatarUpload(this)">
                                @csrf
                                <div class="wp-file-upload">
                                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/gif" required onchange="previewAvatar(this)">
                                    <p class="wp-file-info">JPG, PNG, GIF up to 2MB</p>
                                </div>
                                <button type="submit" class="wp-button wp-button-primary">
                                    <span class="dashicons dashicons-upload"></span>
                                    Upload Avatar
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Profile Form -->
                    <div class="wp-form-section">
                        <form action="{{ route('profile-management.update', $user) }}" method="POST" class="wp-form" onsubmit="return validateProfileForm(this)">
                            @csrf
                            @method('PUT')
                            
                            <div class="wp-form-grid">
                                <!-- Basic Information -->
                                <div class="wp-form-column">
                                    <h3 class="wp-section-title">Basic Information</h3>
                                    
                                    <div class="wp-form-field">
                                        <label class="wp-label">Full Name <span class="wp-required">*</span></label>
                                        <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" 
                                               class="wp-input" required maxlength="100" 
                                               pattern="[a-zA-Z\s\-\.']+" 
                                               title="Only letters, spaces, hyphens, dots, and apostrophes allowed">
                                    </div>
                                    
                                    <div class="wp-form-field">
                                        <label class="wp-label">Email <span class="wp-required">*</span></label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                               class="wp-input" required maxlength="100">
                                    </div>
                                    
                                    <div class="wp-form-field">
                                        <label class="wp-label">Phone</label>
                                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                                               class="wp-input" pattern="[\d\s\-\+\(\)]{7,20}" maxlength="20" 
                                               title="7-20 characters: numbers, spaces, hyphens, plus, parentheses only">
                                    </div>
                                    
                                    <div class="wp-form-field">
                                        <label class="wp-label">Date of Birth</label>
                                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}" 
                                               class="wp-input" min="{{ now()->subYears(100)->format('Y-m-d') }}" 
                                               max="{{ now()->subYears(16)->format('Y-m-d') }}">
                                    </div>
                                </div>

                                <!-- Work Information -->
                                <div class="wp-form-column">
                                    <h3 class="wp-section-title">Work Information</h3>
                                    
                                    <div class="wp-form-field">
                                        <label class="wp-label">Department</label>
                                        <input type="text" name="department" value="{{ old('department', $user->department) }}" 
                                               class="wp-input" maxlength="100">
                                    </div>
                                    
                                    <div class="wp-form-field">
                                        <label class="wp-label">Position</label>
                                        <input type="text" name="position" value="{{ old('position', $user->position) }}" 
                                               class="wp-input" maxlength="100">
                                    </div>
                                    
                                    <div class="wp-form-field">
                                        <label class="wp-label">User Role <span class="wp-required">*</span></label>
                                        <select name="user_role" class="wp-select" required>
                                            <option value="user" {{ old('user_role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                                            <option value="admin" {{ old('user_role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="super_admin" {{ old('user_role', $user->role) === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                        </select>
                                    </div>
                                    
                                    <div class="wp-form-field">
                                        <label class="wp-label">Status <span class="wp-required">*</span></label>
                                        <select name="status" class="wp-select" required>
                                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="pending" {{ old('status', $user->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="wp-form-section-full">
                                <h3 class="wp-section-title">Additional Information</h3>
                                
                                <div class="wp-form-field">
                                    <label class="wp-label">Address</label>
                                    <textarea name="address" rows="3" maxlength="500" class="wp-textarea">{{ old('address', $user->address) }}</textarea>
                                    <p class="wp-field-help">Maximum 500 characters</p>
                                </div>
                                
                                <div class="wp-form-field">
                                    <label class="wp-label">Bio</label>
                                    <textarea name="bio" rows="4" maxlength="500" class="wp-textarea" oninput="updateCharCount(this, 'bioCount')">{{ old('bio', $user->bio) }}</textarea>
                                    <p class="wp-field-help">Maximum 500 characters (<span id="bioCount">{{ strlen(old('bio', $user->bio)) }}</span>/500)</p>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="wp-form-actions">
                                <button type="submit" class="wp-button wp-button-primary">
                                    <span class="dashicons dashicons-update"></span>
                                    Update Profile
                                </button>
                                <a href="{{ route('profile-management.index') }}" class="wp-button wp-button-secondary">
                                    <span class="dashicons dashicons-no-alt"></span>
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* WordPress-inspired Design System */
.wp-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    line-height: 1.6;
    color: #1d2327;
    background: #f0f0f1;
    min-height: 100vh;
}

/* Header */
.wp-header {
    background: #fff;
    border-bottom: 1px solid #c3c4c7;
    margin: 0 -20px 20px -20px;
    padding: 0 20px;
}

.wp-header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
}

.wp-title {
    font-size: 23px;
    font-weight: 400;
    margin: 0;
    color: #1d2327;
}

/* Notices */
.wp-notice {
    padding: 12px 16px;
    margin-bottom: 20px;
    border-radius: 4px;
    border-left: 4px solid;
    display: flex;
    align-items: flex-start;
    gap: 8px;
}

.wp-notice-success {
    background: #f0f6fc;
    border-left-color: #00a32a;
    color: #1d2327;
}

.wp-notice-error {
    background: #fcf0f1;
    border-left-color: #d63638;
    color: #1d2327;
}

.wp-error-list {
    margin: 0;
    padding-left: 20px;
}

/* Content */
.wp-content {
    background: #fff;
    border-radius: 4px;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.wp-card {
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    background: #fff;
}

.wp-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #c3c4c7;
    background: #f6f7f7;
}

.wp-card-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 4px 0;
    color: #1d2327;
}

.wp-card-description {
    margin: 0;
    color: #646970;
    font-size: 14px;
}

.wp-card-body {
    padding: 24px;
}

/* Layout */
.wp-profile-layout {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 32px;
}

/* Avatar Section */
.wp-avatar-section {
    border-right: 1px solid #c3c4c7;
    padding-right: 24px;
}

.wp-avatar-container {
    text-align: center;
}

.wp-section-title {
    font-size: 16px;
    font-weight: 600;
    margin: 0 0 16px 0;
    color: #1d2327;
}

.wp-avatar-display {
    margin-bottom: 20px;
}

.wp-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #c3c4c7;
}

.wp-avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #f6f7f7;
    border: 2px solid #c3c4c7;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.wp-avatar-placeholder .dashicons {
    font-size: 48px;
    color: #646970;
}

.wp-avatar-form {
    margin-top: 16px;
}

.wp-file-upload {
    margin-bottom: 16px;
}

.wp-file-upload input[type="file"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #8c8f94;
    border-radius: 4px;
    background: #fff;
    font-size: 14px;
}

.wp-file-info {
    margin: 8px 0 0 0;
    font-size: 12px;
    color: #646970;
}

/* Form */
.wp-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    margin-bottom: 32px;
}

.wp-form-column {
    min-width: 0;
}

.wp-form-section-full {
    margin-bottom: 32px;
}

.wp-form-field {
    margin-bottom: 20px;
}

.wp-label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #1d2327;
    font-size: 14px;
}

.wp-required {
    color: #d63638;
}

.wp-input, .wp-select, .wp-textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #8c8f94;
    border-radius: 4px;
    background: #fff;
    font-size: 14px;
    line-height: 1.4;
    transition: border-color 0.15s ease-in-out;
}

.wp-input:focus, .wp-select:focus, .wp-textarea:focus {
    border-color: #2271b1;
    outline: none;
    box-shadow: 0 0 0 1px #2271b1;
}

.wp-textarea {
    resize: vertical;
    min-height: 80px;
}

.wp-field-help {
    margin: 6px 0 0 0;
    font-size: 12px;
    color: #646970;
}

/* Buttons - Sharp WordPress Style */
.wp-button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: 1px solid;
    border-radius: 3px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.15s ease-in-out;
    line-height: 1.4;
    min-height: 30px;
}

.wp-button-primary {
    background: #2271b1;
    border-color: #2271b1;
    color: #fff;
}

.wp-button-primary:hover {
    background: #135e96;
    border-color: #135e96;
    color: #fff;
}

.wp-button-secondary {
    background: #f6f7f7;
    border-color: #8c8f94;
    color: #1d2327;
}

.wp-button-secondary:hover {
    background: #f0f0f1;
    border-color: #646970;
    color: #1d2327;
}

.wp-form-actions {
    display: flex;
    gap: 12px;
    padding-top: 20px;
    border-top: 1px solid #c3c4c7;
}

/* Icons */
.dashicons {
    font-family: dashicons;
    font-size: 16px;
    line-height: 1;
}

.dashicons-arrow-left-alt:before { content: "←"; }
.dashicons-yes-alt:before { content: "✓"; }
.dashicons-warning:before { content: "⚠"; }
.dashicons-admin-users:before { content: "👤"; }
.dashicons-upload:before { content: "↑"; }
.dashicons-update:before { content: "↻"; }
.dashicons-no-alt:before { content: "✕"; }

/* Responsive */
@media (max-width: 768px) {
    .wp-profile-layout {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .wp-avatar-section {
        border-right: none;
        border-bottom: 1px solid #c3c4c7;
        padding-right: 0;
        padding-bottom: 24px;
    }
    
    .wp-form-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .wp-header-content {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
}
</style>

<script>
function validateAvatarUpload(form) {
    const fileInput = form.querySelector('input[type="file"]');
    const file = fileInput.files[0];
    if (!file) { alert('Please select an image file.'); return false; }
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!allowedTypes.includes(file.type)) { alert('Please select a valid image file (JPG, PNG, or GIF).'); return false; }
    const maxSize = 2 * 1024 * 1024; // 2MB
    if (file.size > maxSize) { alert('File size must be less than 2MB.'); return false; }
    if (file.size === 0) { alert('File appears to be empty. Please select a valid image.'); return false; }
    return true;
}

function validateProfileForm(form) {
    const fullName = form.full_name.value.trim();
    const email = form.email.value.trim();
    const phone = form.phone.value.trim();
    const bio = form.bio.value.trim();
    if (fullName.length < 2) { alert('Full name must be at least 2 characters long.'); form.full_name.focus(); return false; }
    if (fullName.length > 100) { alert('Full name cannot exceed 100 characters.'); form.full_name.focus(); return false; }
    if (!/^[a-zA-Z\s\-\.']+$/.test(fullName)) { alert('Full name contains invalid characters. Only letters, spaces, hyphens, dots, and apostrophes are allowed.'); form.full_name.focus(); return false; }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) { alert('Please enter a valid email address.'); form.email.focus(); return false; }
    if (phone && !/^[\d\s\-\+\(\)]{7,20}$/.test(phone)) { alert('Phone number must be 7-20 characters and contain only numbers, spaces, hyphens, plus signs, and parentheses.'); form.phone.focus(); return false; }
    if (bio.length > 500) { alert('Bio cannot exceed 500 characters.'); form.bio.focus(); return false; }
    return true;
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('.wp-avatar, .wp-avatar-placeholder');
            if (preview) {
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Avatar" class="wp-avatar">`;
                }
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updateCharCount(textarea, counterId) {
    const counter = document.getElementById(counterId);
    if (counter) {
        counter.textContent = textarea.value.length;
    }
}
</script>
@endsection 
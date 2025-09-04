@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-user-edit text-primary me-2"></i>
                    Edit User: {{ $user->nama }}
                </h2>
                <div class="text-muted mt-1">Modify user information and permissions</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info">
                        <i class="fas fa-eye me-2"></i>View User
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- User Information Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user me-2"></i>User Information
                            </h3>
                            <div class="card-actions">
                                <span class="badge bg-blue">
                                    ID: {{ $user->kd }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Full Name</label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                               name="nama" value="{{ old('nama', $user->nama) }}" placeholder="Enter full name">
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Username</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                               name="username" value="{{ old('username', $user->username) }}" placeholder="Enter username">
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-hint">Must be unique, used for login</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email', $user->email) }}" placeholder="Enter email address">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-hint">Must be unique and valid email format</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Role</label>
                                        <select class="form-select @error('role_id') is-invalid @enderror" name="role_id" id="roleSelect">
                                            <option value="">Select a role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" 
                                                    {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                                    {{ $role->display_name }}
                                                    @if($role->description)
                                                        - {{ $role->description }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-hint">User's role determines their permissions</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Created</label>
                                        <div class="form-control-plaintext">
                                            {{ $user->date_created ? $user->date_created->format('M d, Y \a\t H:i') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Last Updated</label>
                                        <div class="form-control-plaintext">
                                            {{ $user->date_updated ? $user->date_updated->format('M d, Y \a\t H:i') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Role Information -->
                    @if($user->role)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-shield-alt me-2"></i>Current Role & Permissions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Current Role</label>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary me-2">{{ $user->role->display_name }}</span>
                                            <small class="text-muted">{{ $user->role->description }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Permissions Count</label>
                                        <div>
                                            <span class="badge bg-blue">{{ $user->role && $user->role->permissions ? $user->role->permissions->count() : 0 }} permissions</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($user->role && $user->role->permissions && $user->role->permissions->count() > 0)
                            <div class="mb-3">
                                <label class="form-label text-muted">Current Permissions</label>
                                <div class="mt-2">
                                    @foreach($user->role->permissions->groupBy('category') as $category => $permissions)
                                    <div class="mb-2">
                                        <strong class="text-primary">{{ ucfirst(str_replace('_', ' ', $category)) }}:</strong>
                                        <div class="mt-1">
                                            @foreach($permissions as $permission)
                                                <span class="badge bg-light text-dark me-1 mb-1">{{ $permission->display_name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- New Role Preview (shown when role changes) -->
                    <div class="card" id="roleChangePreview" style="display: none;">
                        <div class="card-header bg-warning-lt">
                            <h3 class="card-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>Role Change Preview
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle me-2"></i>
                                Changing the user's role will update their permissions immediately after saving.
                            </div>
                            <div id="newRolePermissions">
                                <!-- New role permissions will be shown here -->
                            </div>
                        </div>
                    </div>

                    <!-- Password Reset Section -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-key me-2"></i>Password Management
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Password cannot be changed from this form. Use the separate password reset feature if needed.
                            </div>
                            <div class="row">
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-warning" onclick="resetPassword('{{ $user->kd }}')">
                                        <i class="fas fa-redo me-2"></i>Reset Password
                                    </button>
                                </div>
                                <div class="col">
                                    <small class="text-muted">
                                        This will generate a new temporary password and send it to the user's email.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save Changes -->
                    <div class="card">
                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Update User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('roleSelect');
    const roleChangePreview = document.getElementById('roleChangePreview');
    const newRolePermissions = document.getElementById('newRolePermissions');
    const originalRoleId = '{{ $user->role_id }}';
    
    // Mock role permissions data (in real app, fetch from API)
    const rolePermissionsData = {
        @foreach($roles as $role)
        '{{ $role->id }}': {
            name: '{{ $role->display_name }}',
            description: '{{ $role->description }}',
            permissions: @json($role->permissions ? $role->permissions->groupBy('category') : [])
        },
        @endforeach
    };
    
    roleSelect.addEventListener('change', function() {
        const newRoleId = this.value;
        
        if (newRoleId && newRoleId !== originalRoleId && rolePermissionsData[newRoleId]) {
            const role = rolePermissionsData[newRoleId];
            let permissionsHtml = `
                <div class="mb-3">
                    <h5 class="text-warning">New Role: ${role.name}</h5>
                    <p class="text-muted">${role.description}</p>
                </div>
                <div>
                    <strong>New Permissions:</strong>
                    <div class="mt-2">
            `;
            
            Object.entries(role.permissions).forEach(([category, permissions]) => {
                if (permissions.length > 0) {
                    permissionsHtml += `
                        <div class="mb-2">
                            <strong class="text-warning">${category.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}:</strong>
                            <div class="mt-1">
                    `;
                    permissions.forEach(permission => {
                        permissionsHtml += `<span class="badge bg-warning text-dark me-1 mb-1">${permission.display_name}</span>`;
                    });
                    permissionsHtml += '</div></div>';
                }
            });
            
            permissionsHtml += '</div></div>';
            newRolePermissions.innerHTML = permissionsHtml;
            roleChangePreview.style.display = 'block';
        } else {
            roleChangePreview.style.display = 'none';
        }
    });
});

function resetPassword(userId) {
    if (confirm('Are you sure you want to reset this user\'s password? A new temporary password will be generated and sent to their email.')) {
        // In a real implementation, this would make an AJAX call to reset the password
        fetch(`/api/users/${userId}/reset-password`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Password reset successfully! A temporary password has been sent to the user\'s email.');
            } else {
                alert('Failed to reset password: ' + data.message);
            }
        })
        .catch(error => {
            alert('Password reset feature is not yet implemented.');
        });
    }
}
</script>

<style>
.form-hint {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.badge {
    font-size: 0.75rem;
}

.form-control-plaintext {
    padding-top: 0.375rem;
    padding-bottom: 0.375rem;
    margin-bottom: 0;
    font-size: inherit;
    line-height: 1.5;
    color: #495057;
    background-color: transparent;
    border: solid transparent;
    border-width: 1px 0;
}

.bg-warning-lt {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
</style>
@endsection

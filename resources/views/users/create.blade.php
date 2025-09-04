@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-user-plus text-primary me-2"></i>
                    Create New User
                </h2>
                <div class="text-muted mt-1">Add a new user to the system</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Users
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user me-2"></i>User Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Full Name</label>
                                        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                               name="nama" value="{{ old('nama') }}" placeholder="Enter full name" autofocus>
                                        @error('nama')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Username</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                               name="username" value="{{ old('username') }}" placeholder="Enter username">
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
                                               name="email" value="{{ old('email') }}" placeholder="Enter email address">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-hint">Must be unique and valid email format</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Role</label>
                                        <select class="form-select @error('role_id') is-invalid @enderror" name="role_id">
                                            <option value="">Select a role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
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
                                        <label class="form-label required">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   name="password" id="password" placeholder="Enter password">
                                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                                <i class="fas fa-eye" id="passwordIcon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-hint">Minimum 8 characters required</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" 
                                                   name="password_confirmation" id="confirmPassword" placeholder="Confirm password">
                                            <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                                <i class="fas fa-eye" id="confirmPasswordIcon"></i>
                                            </button>
                                        </div>
                                        <div class="form-hint">Must match the password above</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Role Preview Card -->
                    <div class="card" id="rolePreview" style="display: none;">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-shield-alt me-2"></i>Role Permissions Preview
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="rolePermissions">
                                <!-- Permissions will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create User
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
    // Password visibility toggle
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const passwordIcon = document.getElementById('passwordIcon');
    
    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        passwordIcon.classList.toggle('fa-eye');
        passwordIcon.classList.toggle('fa-eye-slash');
    });
    
    // Confirm password visibility toggle
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPassword = document.getElementById('confirmPassword');
    const confirmPasswordIcon = document.getElementById('confirmPasswordIcon');
    
    toggleConfirmPassword.addEventListener('click', function() {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        confirmPasswordIcon.classList.toggle('fa-eye');
        confirmPasswordIcon.classList.toggle('fa-eye-slash');
    });
    
    // Password strength indicator
    password.addEventListener('input', function() {
        const value = this.value;
        let strength = 0;
        
        if (value.length >= 8) strength++;
        if (value.match(/[a-z]+/)) strength++;
        if (value.match(/[A-Z]+/)) strength++;
        if (value.match(/[0-9]+/)) strength++;
        if (value.match(/[$@#&!]+/)) strength++;
        
        // Update password strength indicator if desired
    });
    
    // Role preview
    const roleSelect = document.querySelector('select[name="role_id"]');
    const rolePreview = document.getElementById('rolePreview');
    const rolePermissions = document.getElementById('rolePermissions');
    
    // Mock role permissions data (in real app, fetch from API)
    const rolePermissionsData = {
        @foreach($roles as $role)
        '{{ $role->id }}': {
            name: '{{ $role->display_name }}',
            description: '{{ $role->description }}',
            permissions: @json($role->permissions ? $role->permissions->pluck('display_name') : [])
        },
        @endforeach
    };
    
    roleSelect.addEventListener('change', function() {
        const roleId = this.value;
        if (roleId && rolePermissionsData[roleId]) {
            const role = rolePermissionsData[roleId];
            rolePermissions.innerHTML = `
                <div class="mb-3">
                    <h5 class="text-primary">${role.name}</h5>
                    <p class="text-muted">${role.description}</p>
                </div>
                <div>
                    <strong>Permissions (${role.permissions.length}):</strong>
                    <div class="mt-2">
                        ${role.permissions.map(permission => `
                            <span class="badge bg-blue me-1 mb-1">${permission}</span>
                        `).join('')}
                    </div>
                </div>
            `;
            rolePreview.style.display = 'block';
        } else {
            rolePreview.style.display = 'none';
        }
    });
    
    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }
    });
});
</script>

<style>
.password-strength {
    height: 4px;
    margin-top: 5px;
    border-radius: 2px;
    transition: all 0.3s ease;
}

.strength-weak { background-color: #dc3545; width: 25%; }
.strength-fair { background-color: #fd7e14; width: 50%; }
.strength-good { background-color: #ffc107; width: 75%; }
.strength-strong { background-color: #198754; width: 100%; }

.form-hint {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endsection

@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-key text-primary me-2"></i>
                    Change Password
                    @if($user->kd === auth()->id())
                        <span class="text-muted">(Your Account)</span>
                    @else
                        <span class="text-muted">for {{ $user->nama }}</span>
                    @endif
                </h2>
                <div class="text-muted mt-1">Update user login credentials</div>
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
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-shield-alt text-warning me-2"></i>
                            Password Security
                        </h3>
                    </div>
                    
                    <form method="POST" action="{{ route('users.update-password', $user) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="card-body">
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <div class="d-flex">
                                        <div>
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <strong>Error!</strong> Please correct the following issues:
                                            <ul class="mb-0 mt-2">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                                </div>
                            @endif

                            <!-- User Info -->
                            <div class="row mb-4">
                                <div class="col">
                                    <div class="card bg-light">
                                        <div class="card-body py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md me-3">
                                                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $user->nama }}</div>
                                                    <div class="text-muted">{{ $user->email }}</div>
                                                    <div class="text-muted small">
                                                        Role: {{ $user->getRoleDisplayName() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($user->kd === auth()->id())
                            <!-- Current Password (only for self) -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    Current Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       name="current_password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       placeholder="Enter your current password"
                                       autocomplete="current-password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            <!-- New Password -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-key me-1"></i>
                                    New Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Enter new password (minimum 8 characters)"
                                       autocomplete="new-password"
                                       minlength="8">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-hint">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Password must be at least 8 characters long
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-check-double me-1"></i>
                                    Confirm New Password <span class="text-danger">*</span>
                                </label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       placeholder="Confirm your new password"
                                       autocomplete="new-password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Security Notice -->
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <div>
                                        <i class="fas fa-shield-alt me-2"></i>
                                        <strong>Security Notice:</strong>
                                    </div>
                                </div>
                                <ul class="mb-0 mt-2">
                                    <li>Passwords are encrypted and cannot be recovered</li>
                                    <li>Use a strong, unique password for security</li>
                                    @if($user->kd === auth()->id())
                                        <li>You will remain logged in after changing your password</li>
                                    @else
                                        <li>The user will need to log in with the new password</li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-key me-2"></i>Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.querySelector('input[name="password"]');
    const confirmInput = document.querySelector('input[name="password_confirmation"]');
    
    // Password strength indicator
    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        return strength;
    }
    
    // Real-time password confirmation check
    function checkPasswordMatch() {
        if (confirmInput.value && passwordInput.value !== confirmInput.value) {
            confirmInput.setCustomValidity('Passwords do not match');
            confirmInput.classList.add('is-invalid');
        } else {
            confirmInput.setCustomValidity('');
            confirmInput.classList.remove('is-invalid');
        }
    }
    
    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmInput.addEventListener('input', checkPasswordMatch);
});
</script>
@endsection

@extends('layouts.app')

@section('title', 'User Details')

@push('styles')
<style>
.profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.profile-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    border-radius: 50%;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 2rem;
    color: white;
    text-transform: uppercase;
    background: linear-gradient(45deg, #4299e1, #667eea);
    border: 4px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    margin: 0 auto 1rem;
}

.info-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 35px rgba(0, 0, 0, 0.12);
}

.info-card .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    padding: 1.5rem;
}

.info-card .card-body {
    padding: 2rem;
}

.stat-item {
    text-align: center;
    padding: 1.5rem;
    border-radius: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    margin-bottom: 1rem;
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.permission-badge {
    background: linear-gradient(45deg, #2fb344, #48c78e);
    color: white;
    border: none;
    border-radius: 20px;
    padding: 0.5rem 1rem;
    margin: 0.25rem;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
}

.permission-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(47, 179, 68, 0.4);
}

.permission-category {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.activity-timeline {
    position: relative;
    padding-left: 2rem;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #667eea, #764ba2);
}

.activity-item {
    position: relative;
    margin-bottom: 1.5rem;
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
}

.activity-item::before {
    content: '';
    position: absolute;
    left: -2.5rem;
    top: 1rem;
    width: 12px;
    height: 12px;
    background: #667eea;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.btn-enhanced {
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.btn-enhanced:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.animate-fade-in {
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stagger-1 { animation-delay: 0.1s; }
.stagger-2 { animation-delay: 0.2s; }
.stagger-3 { animation-delay: 0.3s; }
.stagger-4 { animation-delay: 0.4s; }
</style>
@endpush

@section('content')
<div class="page-body">
    <div class="container-xl">
        <!-- Enhanced Profile Header -->
        <div class="profile-header animate-fade-in">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    @php
                        $initials = strtoupper(substr($user->nama, 0, 1) . (strpos($user->nama, ' ') ? substr($user->nama, strpos($user->nama, ' ') + 1, 1) : ''));
                    @endphp
                    <div class="profile-avatar">
                        {{ $initials }}
                    </div>
                </div>
                <div class="col-md-7">
                    <div>
                        <h1 class="mb-2">{{ $user->nama }}</h1>
                        <p class="text-white-50 mb-1">
                            <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                        </p>
                        <p class="text-white-50 mb-1">
                            <i class="fas fa-user me-2"></i>{{ $user->username }}
                        </p>
                        <div class="mt-3">
                            @php
                                $roleName = '';
                                if (is_object($user->role) && isset($user->role->name)) {
                                    $roleName = $user->role->name;
                                } elseif (is_string($user->role)) {
                                    $roleName = $user->role;
                                }
                                $isAdmin = $roleName === 'admin';
                            @endphp
                            <span class="badge {{ $isAdmin ? 'bg-red' : 'bg-blue' }} badge-lg px-3 py-2">
                                <i class="fas fa-shield-alt me-2"></i>{{ $user->getRoleDisplayName() }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 text-end">
                    <div class="btn-list">
                        <a href="{{ route('users.change-password', $user) }}" class="btn btn-light btn-enhanced">
                            <i class="fas fa-key me-2"></i>Change Password
                        </a>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-enhanced">
                            <i class="fas fa-edit me-2"></i>Edit User
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-light btn-enhanced">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Main User Information -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="info-card animate-fade-in stagger-1">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-info-circle text-primary me-2"></i>Basic Information
                        </h3>
                        <div class="card-actions">
                            <span class="badge bg-primary">
                                ID: {{ $user->kd }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Full Name</label>
                                    <div class="fw-bold fs-4">{{ $user->nama }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Username</label>
                                    <div class="fw-bold">{{ $user->username }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email Address</label>
                                    <div>
                                        <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Current Role</label>
                                    <div>
                                        @php
                                            $roleName = '';
                                            if (is_object($user->role) && isset($user->role->name)) {
                                                $roleName = $user->role->name;
                                            } elseif (is_string($user->role)) {
                                                $roleName = $user->role;
                                            }
                                            $isAdmin = $roleName === 'admin';
                                        @endphp
                                        <span class="badge {{ $isAdmin ? 'bg-red' : 'bg-blue' }} badge-lg">
                                            {{ $user->getRoleDisplayName() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Account Created</label>
                                    <div>
                                        {{ $user->date_created ? $user->date_created->format('M d, Y \a\t H:i') : 'N/A' }}
                                        @if($user->date_created)
                                            <small class="text-muted d-block">
                                                {{ $user->date_created->diffForHumans() }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Last Updated</label>
                                    <div>
                                        {{ $user->date_updated ? $user->date_updated->format('M d, Y \a\t H:i') : 'N/A' }}
                                        @if($user->date_updated)
                                            <small class="text-muted d-block">
                                                {{ $user->date_updated->diffForHumans() }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($user->dibuat_oleh)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Created By</label>
                                    <div>{{ $user->dibuat_oleh }}</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Role & Permissions -->
                @if($user->role)
                <div class="info-card animate-fade-in stagger-2">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-shield-alt text-primary me-2"></i>Role & Permissions
                        </h3>
                        <div class="card-actions">
                            <span class="badge bg-green">{{ $user->role && $user->role->permissions ? $user->role->permissions->count() : 0 }} permissions</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-user-shield fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold fs-5">{{ $user->getRoleDisplayName() }}</div>
                                        <div class="text-muted">
                                            @if(is_object($user->role) && isset($user->role->description))
                                                {{ $user->role->description }}
                                            @else
                                                {{ ucfirst($user->getRoleName()) }} role access
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-end">
                                    <div class="text-h4 text-primary">{{ $user->role && $user->role->permissions ? $user->role->permissions->count() : 0 }}</div>
                                    <div class="text-muted">Total Permissions</div>
                                </div>
                            </div>
                        </div>
                        
                        @if($user->role && $user->role->permissions && $user->role->permissions->count() > 0)
                        <div class="mb-3">
                            <h5 class="mb-3">Permissions by Category</h5>
                            @foreach($user->role->permissions->groupBy('category') as $category => $permissions)
                            <div class="permission-category">
                                <h6 class="text-primary mb-3 fw-bold">
                                    <i class="fas fa-folder me-2"></i>{{ ucfirst(str_replace('_', ' ', $category)) }}
                                    <span class="badge bg-primary ms-2">{{ $permissions->count() }}</span>
                                </h6>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissions as $permission)
                                    <span class="permission-badge" title="{{ $permission->description }}">
                                        <i class="fas fa-check me-1"></i>
                                        {{ $permission->display_name }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4">
                            <div class="empty">
                                <div class="empty-img">
                                    <i class="fas fa-key fa-3x text-muted"></i>
                                </div>
                                <p class="empty-title">No permissions assigned</p>
                                <p class="empty-subtitle text-muted">
                                    This user's role has no specific permissions
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Activity Log (Placeholder) -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history me-2"></i>Recent Activity
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <div class="empty">
                                <div class="empty-img">
                                    <i class="fas fa-clock fa-3x text-muted"></i>
                                </div>
                                <p class="empty-title">Activity tracking not implemented</p>
                                <p class="empty-subtitle text-muted">
                                    User activity logging will be available in a future update
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- User Statistics -->
                <div class="row">
                    <div class="col-6">
                        <div class="stat-item animate-fade-in stagger-3">
                            <div class="stat-number">{{ $user->role && $user->role->permissions ? $user->role->permissions->count() : 0 }}</div>
                            <div class="stat-label">Permissions</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item animate-fade-in stagger-4">
                            <div class="stat-number">{{ $user->date_created ? $user->date_created->diffInDays(now()) : 0 }}</div>
                            <div class="stat-label">Days Active</div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="info-card animate-fade-in stagger-4">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-bolt text-primary me-2"></i>Quick Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-enhanced">
                                <i class="fas fa-edit me-2"></i>Edit User
                            </a>
                            
                            <a href="{{ route('users.change-password', $user) }}" class="btn btn-info btn-enhanced">
                                <i class="fas fa-key me-2"></i>Change Password
                            </a>
                            
                            <button type="button" class="btn btn-outline-secondary btn-enhanced" onclick="sendEmail('{{ $user->email }}')">
                                <i class="fas fa-envelope me-2"></i>Send Email
                            </button>
                            
                            @if($user->kd !== auth()->id())
                            <hr>
                            <form method="POST" action="{{ route('users.destroy', $user) }}" 
                                  onsubmit="return confirm('⚠️ Are you sure you want to delete this user?\\n\\nThis action cannot be undone!')"
                                  class="d-grid">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-enhanced">
                                    <i class="fas fa-trash me-2"></i>Delete User
                                </button>
                            </form>
                            @else
                            <button type="button" class="btn btn-danger" disabled title="Cannot delete your own account">
                                <i class="fas fa-trash me-2"></i>Delete User
                            </button>
                            <small class="text-muted">
                                You cannot delete your own account
                            </small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- User Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar me-2"></i>User Statistics
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="text-h3 text-primary">{{ $user->role && $user->role->permissions ? $user->role->permissions->count() : 0 }}</div>
                                <div class="text-muted">Permissions</div>
                            </div>
                            <div class="col-6">
                                <div class="text-h3 text-success">{{ $user->date_created ? $user->date_created->diffInDays(now()) : 0 }}</div>
                                <div class="text-muted">Days Active</div>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="text-h4 text-info">0</div>
                                <div class="text-muted small">Login Count</div>
                            </div>
                            <div class="col-6">
                                <div class="text-h4 text-warning">N/A</div>
                                <div class="text-muted small">Last Login</div>
                            </div>
                        </div>
                        <small class="text-muted d-block text-center mt-2">
                            * Login tracking not yet implemented
                        </small>
                    </div>
                </div>

                <!-- System Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info me-2"></i>System Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between">
                                <span>User ID</span>
                                <strong>{{ $user->kd }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Role</span>
                                <strong>{{ $user->getRoleDisplayName() }}</strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Status</span>
                                <span class="badge bg-success">Active</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Account Type</span>
                                <strong>{{ $user->getRoleDisplayName() }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

function sendEmail(email) {
    // Open default email client
    window.location.href = `mailto:${email}`;
}
</script>

<style>
.badge-lg {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}

.empty-img {
    height: 6rem;
    margin-bottom: 1rem;
    opacity: 0.4;
}

.list-group-flush .list-group-item {
    padding: 0.75rem 0;
    border: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.list-group-flush .list-group-item:last-child {
    border-bottom: none;
}
</style>
@endsection

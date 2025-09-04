@extends('layouts.app')

@section('title', 'Role Details')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-user-shield text-primary me-2"></i>
                    Role Details: {{ $role->display_name }}
                </h2>
                <div class="text-muted mt-1">View role information and permissions</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Role
                    </a>
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Roles
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <!-- Role Information -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle me-2"></i>Role Information
                        </h3>
                        <div class="card-actions">
                            <span class="badge {{ $role->is_active ? 'bg-green' : 'bg-red' }} badge-pill">
                                {{ $role->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Role Name</label>
                                    <div class="fw-bold">{{ $role->name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Display Name</label>
                                    <div class="fw-bold">{{ $role->display_name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Description</label>
                            <div>{{ $role->description ?: 'No description provided' }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Created</label>
                                    <div>{{ $role->created_at->format('M d, Y \a\t H:i') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Last Updated</label>
                                    <div>{{ $role->updated_at->format('M d, Y \a\t H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-key me-2"></i>Permissions
                        </h3>
                        <div class="card-actions">
                            <span class="badge bg-blue">{{ $role->permissions->count() }} permissions</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($role->permissions->count() > 0)
                            @foreach($role->permissions->groupBy('category') as $category => $categoryPermissions)
                            <div class="mb-4">
                                <h4 class="text-primary mb-3">
                                    <i class="fas fa-folder me-2"></i>{{ ucfirst(str_replace('_', ' ', $category)) }}
                                    <span class="badge bg-light text-dark ms-2">{{ $categoryPermissions->count() }}</span>
                                </h4>
                                <div class="row">
                                    @foreach($categoryPermissions as $permission)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check text-success me-2"></i>
                                            <div>
                                                <strong>{{ $permission->display_name }}</strong>
                                                @if($permission->description)
                                                    <br><small class="text-muted">{{ $permission->description }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-img">
                                        <i class="fas fa-key fa-3x text-muted"></i>
                                    </div>
                                    <p class="empty-title">No permissions assigned</p>
                                    <p class="empty-subtitle text-muted">
                                        This role has no permissions assigned yet
                                    </p>
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-2"></i>Assign Permissions
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Users with this role -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users me-2"></i>Users
                        </h3>
                        <div class="card-actions">
                            <span class="badge bg-green">{{ $role->users->count() }} users</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($role->users->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($role->users as $user)
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $user->nama }}</div>
                                        <div class="text-muted small">{{ $user->email }}</div>
                                    </div>
                                    <div>
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="empty">
                                    <div class="empty-img">
                                        <i class="fas fa-users fa-3x text-muted"></i>
                                    </div>
                                    <p class="empty-title">No users assigned</p>
                                    <p class="empty-subtitle text-muted">
                                        No users have this role yet
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cogs me-2"></i>Actions
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>Edit Role
                            </a>
                            
                            @if($role->users->count() === 0)
                            <form method="POST" action="{{ route('roles.destroy', $role) }}" 
                                  onsubmit="return confirm('Are you sure you want to delete this role? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Delete Role
                                </button>
                            </form>
                            @else
                            <button type="button" class="btn btn-danger" disabled title="Cannot delete role with assigned users">
                                <i class="fas fa-trash me-2"></i>Delete Role
                            </button>
                            <small class="text-muted">
                                Cannot delete role with assigned users
                            </small>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar me-2"></i>Statistics
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="text-h3 text-primary">{{ $role->permissions->count() }}</div>
                                <div class="text-muted">Permissions</div>
                            </div>
                            <div class="col-6">
                                <div class="text-h3 text-success">{{ $role->users->count() }}</div>
                                <div class="text-muted">Users</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

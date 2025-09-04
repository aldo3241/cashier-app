@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-user-shield text-primary me-2"></i>
                    Create New Role
                </h2>
                <div class="text-muted mt-1">Create a new role with specific permissions</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Roles
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Role Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Role Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name') }}" placeholder="e.g., manager">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-hint">Unique identifier for the role (lowercase, no spaces)</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Display Name</label>
                                        <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                               name="display_name" value="{{ old('display_name') }}" placeholder="e.g., Manager">
                                        @error('display_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-hint">Human-readable name for the role</div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          name="description" rows="3" placeholder="Describe the role's purpose and responsibilities">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Permissions</h3>
                            <div class="card-actions">
                                <button type="button" class="btn btn-sm btn-outline-primary" id="selectAll">
                                    <i class="fas fa-check-square me-1"></i>Select All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAll">
                                    <i class="fas fa-square me-1"></i>Deselect All
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @foreach($permissions as $category => $categoryPermissions)
                            <div class="mb-4">
                                <h4 class="text-primary mb-3">
                                    <i class="fas fa-folder me-2"></i>{{ ucfirst(str_replace('_', ' ', $category)) }}
                                </h4>
                                <div class="row">
                                    @foreach($categoryPermissions as $permission)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <label class="form-check">
                                            <input type="checkbox" class="form-check-input" 
                                                   name="permissions[]" value="{{ $permission->id }}"
                                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                            <span class="form-check-label">
                                                <strong>{{ $permission->display_name }}</strong>
                                                @if($permission->description)
                                                    <br><small class="text-muted">{{ $permission->description }}</small>
                                                @endif
                                            </span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create Role
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
    const selectAllBtn = document.getElementById('selectAll');
    const deselectAllBtn = document.getElementById('deselectAll');
    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');

    selectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
    });

    deselectAllBtn.addEventListener('click', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    });
});
</script>
@endsection

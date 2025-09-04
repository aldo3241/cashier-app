@extends('layouts.app')

@section('title', 'User Management')

@push('styles')
<style>
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    color: white;
    text-transform: uppercase;
}

.user-avatar.bg-gradient-primary { background: linear-gradient(45deg, #206bc4, #4299e1); }
.user-avatar.bg-gradient-success { background: linear-gradient(45deg, #2fb344, #48c78e); }
.user-avatar.bg-gradient-warning { background: linear-gradient(45deg, #f59f00, #ffd43b); }
.user-avatar.bg-gradient-danger { background: linear-gradient(45deg, #d63384, #fd7e14); }

.search-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.search-container .form-control,
.search-container .form-select {
    border: none;
    border-radius: 10px;
    padding: 12px 16px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
}

.search-container .input-group-text {
    border: none;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 10px 0 0 10px;
}

.table-card {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
    border: none;
}

.table-enhanced th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    padding: 1rem;
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
}

.table-enhanced td {
    padding: 1rem;
    border-bottom: 1px solid #f8f9fa;
    vertical-align: middle;
}

.table-enhanced tbody tr {
    transition: all 0.3s ease;
}

.table-enhanced tbody tr:hover {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border: none;
    margin: 0 2px;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.animate-in {
    animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

</style>
@endpush

@section('content')
<!-- Page header -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-users text-primary me-2"></i>
                    User Management
                </h2>
                <div class="text-muted mt-1">Manage system users and their roles</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New User
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Statistics Cards -->
        <div class="stats-card animate-in">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div class="display-6 fw-bold mb-2" id="totalUsers">{{ $users->count() }}</div>
                        <div class="text-white-50">Total Users</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div class="display-6 fw-bold mb-2" id="adminCount">{{ $users->where('role', 'admin')->count() + $users->filter(function($u) { return is_object($u->role) && $u->role->name === 'admin'; })->count() }}</div>
                        <div class="text-white-50">Administrators</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div class="display-6 fw-bold mb-2" id="cashierCount">{{ $users->where('role', 'cashier')->count() + $users->filter(function($u) { return is_object($u->role) && $u->role->name === 'cashier'; })->count() }}</div>
                        <div class="text-white-50">Cashiers</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div class="display-6 fw-bold mb-2" id="userCount">{{ $users->where('role', 'user')->count() + $users->filter(function($u) { return is_object($u->role) && $u->role->name === 'user'; })->count() }}</div>
                        <div class="text-white-50">Regular Users</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card table-card animate-in">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="card-title">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    System Users
                                </h3>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add New User
                                </a>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Enhanced Search and Filter Section -->
                    <div class="search-container">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search text-primary"></i>
                                    </span>
                                    <input type="text" id="userSearch" class="form-control" placeholder="Search by name, username, or email..." autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select id="roleFilter" class="form-select">
                                    <option value="">🎭 All Roles</option>
                                    <option value="admin">👑 Administrator</option>
                                    <option value="cashier">💰 Cashier</option>
                                    <option value="user">👤 User</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex gap-2">
                                    <button id="clearFilters" class="btn btn-light flex-fill">
                                        <i class="fas fa-times me-2"></i>Clear
                                    </button>
                                    <button id="refreshData" class="btn btn-light" title="Refresh Data">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="text-center text-white-50 small">
                                    <i class="fas fa-keyboard me-1"></i>
                                    Tip: Press <kbd>Ctrl + K</kbd> to quick search, <kbd>Esc</kbd> to clear
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <div class="d-flex">
                                    <div>
                                        <i class="fas fa-check-circle me-2"></i>
                                        {{ session('success') }}
                                    </div>
                                </div>
                                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <div class="d-flex">
                                    <div>
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        {{ session('error') }}
                                    </div>
                                </div>
                                <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-vcenter table-enhanced">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $initials = strtoupper(substr($user->nama, 0, 1) . (strpos($user->nama, ' ') ? substr($user->nama, strpos($user->nama, ' ') + 1, 1) : ''));
                                                    $avatarColors = ['bg-gradient-primary', 'bg-gradient-success', 'bg-gradient-warning', 'bg-gradient-danger'];
                                                    $colorIndex = ord($user->nama[0]) % count($avatarColors);
                                                @endphp
                                                <div class="user-avatar {{ $avatarColors[$colorIndex] }} me-3">
                                                    {{ $initials }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $user->nama }}</div>
                                                    <div class="text-muted small">ID: {{ $user->kd }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @php
                                                $roleName = '';
                                                if (is_object($user->role) && isset($user->role->name)) {
                                                    $roleName = $user->role->name;
                                                } elseif (is_string($user->role)) {
                                                    $roleName = $user->role;
                                                }
                                                $isAdmin = $roleName === 'admin';
                                            @endphp
                                            <span class="badge {{ $isAdmin ? 'bg-red' : 'bg-blue' }}">
                                                {{ $user->getRoleDisplayName() }}
                                            </span>
                                        </td>
                                        <td>{{ $user->date_created ? $user->date_created->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('users.show', $user) }}" class="btn-action btn-outline-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('users.change-password', $user) }}" class="btn-action btn-outline-info" title="Change Password">
                                                    <i class="fas fa-key"></i>
                                                </a>
                                                <a href="{{ route('users.edit', $user) }}" class="btn-action btn-outline-warning" title="Edit User">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($user->kd !== auth()->id())
                                                <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action btn-outline-danger" 
                                                            onclick="return confirm('⚠️ Are you sure you want to delete this user?\\n\\nThis action cannot be undone!')"
                                                            title="Delete User">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('User management page initializing...');
    
    const searchInput = document.getElementById('userSearch');
    const roleFilter = document.getElementById('roleFilter');
    const clearBtn = document.getElementById('clearFilters');
    const refreshBtn = document.getElementById('refreshData');
    const tableRows = document.querySelectorAll('tbody tr');
    
    console.log('Found elements:', {
        searchInput: !!searchInput,
        roleFilter: !!roleFilter,
        clearBtn: !!clearBtn,
        refreshBtn: !!refreshBtn,
        tableRowsCount: tableRows.length
    });
    
    // Enhanced search functionality
    function filterUsers() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value.toLowerCase();
        
        let visibleCount = 0;
        
        tableRows.forEach((row, index) => {
            // Get user data from row
            const nameCell = row.cells[0].textContent.toLowerCase();
            const usernameCell = row.cells[1].textContent.toLowerCase();
            const emailCell = row.cells[2].textContent.toLowerCase();
            const roleCell = row.cells[3].textContent.toLowerCase();
            
            // Check search match
            const searchMatch = !searchTerm || 
                nameCell.includes(searchTerm) || 
                usernameCell.includes(searchTerm) || 
                emailCell.includes(searchTerm);
            
            // Check role match
            const roleMatch = !selectedRole || roleCell.includes(selectedRole);
            
            // Show/hide row with animation
            if (searchMatch && roleMatch) {
                row.style.display = '';
                row.style.animationDelay = `${index * 50}ms`;
                row.classList.add('animate-in');
                visibleCount++;
            } else {
                row.style.display = 'none';
                row.classList.remove('animate-in');
            }
        });
        
        // Update statistics
        updateResultsCount();
        updateFilterStats(visibleCount);
    }
    
    // Update results count with enhanced display
    function updateResultsCount() {
        const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
        const totalRows = tableRows.length;
        
        // Create or update count display
        let countDisplay = document.getElementById('resultsCount');
        if (!countDisplay) {
            countDisplay = document.createElement('div');
            countDisplay.id = 'resultsCount';
            countDisplay.className = 'alert alert-info mt-3 mb-3';
            const cardBody = document.querySelector('.card-body');
            cardBody.insertBefore(countDisplay, cardBody.querySelector('.table-responsive'));
        }
        
        const percentage = Math.round((visibleRows.length / totalRows) * 100);
        countDisplay.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-filter me-2"></i>
                <div>
                    <strong>Showing ${visibleRows.length} of ${totalRows} users</strong>
                    <div class="text-muted small">${percentage}% of total users displayed</div>
                </div>
            </div>
        `;
    }
    
    // Update filter statistics
    function updateFilterStats(visibleCount) {
        // Update the total users counter in stats
        const totalUsersElement = document.getElementById('totalUsers');
        if (totalUsersElement && searchInput.value || roleFilter.value) {
            totalUsersElement.innerHTML = `${visibleCount}<small class="text-white-50 d-block">filtered</small>`;
        } else {
            totalUsersElement.innerHTML = tableRows.length;
        }
    }
    
    // Clear filters with animation
    function clearFilters() {
        searchInput.value = '';
        roleFilter.value = '';
        
        // Add visual feedback
        const clearIcon = clearBtn.querySelector('i');
        clearIcon.classList.add('fa-spin');
        
        setTimeout(() => {
            filterUsers();
            clearIcon.classList.remove('fa-spin');
        }, 300);
    }
    
    // Refresh data
    function refreshData() {
        const refreshIcon = refreshBtn.querySelector('i');
        refreshIcon.classList.add('fa-spin');
        
        setTimeout(() => {
            location.reload();
        }, 500);
    }
    
    // Event listeners
    searchInput.addEventListener('input', filterUsers);
    roleFilter.addEventListener('change', filterUsers);
    clearBtn.addEventListener('click', clearFilters);
    refreshBtn.addEventListener('click', refreshData);
    
    // Initialize page
    console.log('Initializing page...');
    updateResultsCount();
    
    // Ensure all rows are visible initially
    tableRows.forEach(row => {
        row.style.display = '';
        row.classList.add('animate-in');
    });
    
    console.log('User management page fully loaded!');
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
        
        // Escape to clear search
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            clearFilters();
            searchInput.blur();
        }
    });
    
    // Add search tips
    searchInput.setAttribute('title', 'Tip: Use Ctrl+K to quickly focus search, Esc to clear');
});
</script>
@endpush

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(135deg, #8e44ad 0%, #663399 100%);
        }
        .sidebar {
            background: #f8f9fa;
            min-height: calc(100vh - 56px);
        }
        .nav-link.active {
            background-color: #8e44ad !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('super-admin.dashboard') }}">
                <i class="fas fa-crown me-2"></i>Super Admin Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <form method="POST" action="{{ route('super-admin.logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-3">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('super-admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('super-admin.manage-admins') }}">
                            <i class="fas fa-users-cog me-2"></i>Manage Admins
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('super-admin.bookings') }}">
                            <i class="fas fa-list me-2"></i>All Bookings
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manage Administrators</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAdminModal">
                        <i class="fas fa-plus me-2"></i>Create New Admin
                    </button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Admins Table -->
                <div class="card">
                    <div class="card-header">
                        <h5>Current Administrators</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Business Type</th>
                                        <th>Business</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($admins as $admin)
                                    <tr>
                                        <td>{{ $admin->id }}</td>
                                        <td>{{ $admin->name }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $admin->business_type === 'general' ? 'primary' : 'info' }}">
                                                {{ ucfirst(str_replace('_', ' ', $admin->business_type ?? 'general')) }}
                                            </span>
                                        </td>
                                        <td>{{ $admin->business_name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $admin->is_active ? 'success' : 'danger' }}">
                                                {{ $admin->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>{{ $admin->createdBy->name ?? 'Unknown' }}</td>
                                        <td>{{ $admin->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('super-admin.delete-admin', $admin) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this admin?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No administrators found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Admin Modal -->
    <div class="modal fade" id="createAdminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Administrator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('super-admin.create-admin') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            @error('name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="business_type" class="form-label">Business Type</label>
                            <select class="form-select" id="business_type" name="business_type" required onchange="toggleBusinessSelect()">
                                <option value="">Select Business Type</option>
                                <option value="general">General Admin</option>
                                <option value="hotel">Hotel Owner</option>
                                <option value="ferry">Ferry Operator</option>
                                <option value="theme_park">Theme Park Owner</option>
                                <option value="beach_event">Beach Event Organizer</option>
                            </select>
                        </div>
                        <div class="mb-3" id="business_select_container" style="display: none;">
                            <label for="business_id" class="form-label">Specific Business (Optional)</label>
                            <select class="form-select" id="business_id" name="business_id">
                                <option value="">All businesses of this type</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            @error('password')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Additional Permissions</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="cancel_bookings" id="cancel_bookings">
                                <label class="form-check-label" for="cancel_bookings">
                                    Cancel Bookings
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="mark_as_paid" id="mark_as_paid">
                                <label class="form-check-label" for="mark_as_paid">
                                    Mark Bookings as Paid
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="view_revenue" id="view_revenue">
                                <label class="form-check-label" for="view_revenue">
                                    View Revenue Reports
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleBusinessSelect() {
            const businessType = document.getElementById('business_type').value;
            const container = document.getElementById('business_select_container');
            const businessSelect = document.getElementById('business_id');
            
            if (businessType && businessType !== 'general') {
                container.style.display = 'block';
                
                // Clear existing options
                businessSelect.innerHTML = '<option value="">All businesses of this type</option>';
                
                // Add specific business options based on type
                let businesses = [];
                
                @if(isset($hotels))
                if (businessType === 'hotel') {
                    businesses = @json($hotels);
                }
                @endif
                
                @if(isset($ferries))
                if (businessType === 'ferry') {
                    businesses = @json($ferries);
                }
                @endif
                
                @if(isset($themeParks))
                if (businessType === 'theme_park') {
                    businesses = @json($themeParks);
                }
                @endif
                
                @if(isset($beachEvents))
                if (businessType === 'beach_event') {
                    businesses = @json($beachEvents);
                }
                @endif
                
                businesses.forEach(function(business) {
                    const option = document.createElement('option');
                    option.value = business.id;
                    option.textContent = business.name;
                    businessSelect.appendChild(option);
                });
            } else {
                container.style.display = 'none';
            }
        }
    </script>
</body>
</html>

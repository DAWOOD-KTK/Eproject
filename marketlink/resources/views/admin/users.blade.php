@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<div>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-people text-primary me-2"></i> Manage Farmers & Customers</h2>
            <p class="text-muted mb-0">Approve new farmer stalls, manage account statuses, and activate/deactivate accounts</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card p-3 border-0 shadow-sm rounded-3 bg-white mb-4">
        <form action="{{ route('admin.users') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search by name, email, or stall...">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="farmer" {{ request('role') == 'farmer' ? 'selected' : '' }}>Farmers / Stalls</option>
                    <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customers</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending_approval" {{ request('status') == 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill fw-bold">Filter</button>
                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary rounded-pill">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow-sm rounded-3 bg-white overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">User Details</th>
                        <th>Role</th>
                        <th>Contact / Stall Info</th>
                        <th>Status</th>
                        <th>Registered Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4">
                                <strong class="text-dark">{{ $user->name }}</strong>
                                <div class="text-muted small">{{ $user->email }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $user->role === 'farmer' ? 'bg-success' : ($user->role === 'admin' ? 'bg-danger' : 'bg-primary') }} rounded-pill px-3 py-1">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->role === 'farmer')
                                    <strong class="text-dark d-block">{{ $user->stall_name ?? 'No Stall Name' }}</strong>
                                    <small class="text-muted">{{ $user->phone }} • {{ $user->address }}</small>
                                @else
                                    <div>{{ $user->phone ?? 'No phone' }}</div>
                                    <small class="text-muted">{{ Str::limit($user->address, 30) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($user->status === 'active')
                                    <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-2 py-1">Active</span>
                                @elseif($user->status === 'pending_approval')
                                    <span class="badge bg-warning text-dark rounded-pill px-2 py-1">Pending Approval</span>
                                @else
                                    <span class="badge bg-danger text-white rounded-pill px-2 py-1">Suspended</span>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    @if($user->role === 'farmer' && $user->status === 'pending_approval')
                                        <form action="{{ route('admin.users.approve', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-2 py-0" style="font-size: 0.75rem;">
                                                <i class="bi bi-check-lg"></i> Approve
                                            </button>
                                        </form>
                                    @endif

                                    @if($user->status === 'suspended')
                                        <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-2 py-0" style="font-size: 0.75rem;">
                                                Activate
                                            </button>
                                        </form>
                                    @elseif(!$user->isAdmin())
                                        <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST" onsubmit="return confirm('Suspend user account?')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-2 py-0" style="font-size: 0.75rem;">
                                                Suspend
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

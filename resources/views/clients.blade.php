@extends('sections.layout')

@section('title', 'Clients')
@section('clients-active', 'active')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Clients Management</h1>
        <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Client
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search and Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.clients.index') }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="search" class="form-label">Search:</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Search by name, email, CIN, or license..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select name="status" id="status" class="form-control form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary ms-2">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Clients Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Clients List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="clientsTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>License #</th>
                            <th>License Date</th>
                            <th>Age</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clients as $client)
                            <tr>
                                <td>
                                    <img src="{{ asset('img/default_avatar.png') }}" 
                                         width="32" height="32" class="rounded-circle" alt="Avatar">
                                </td>
                                <td>
                                    <strong>{{ $client->full_name }}</strong><br>
                                    <small class="text-muted">CIN: {{ $client->cin }}</small>
                                </td>
                                <td>{{ $client->email ?: 'N/A' }}</td>
                                <td>{{ $client->phone ?: 'N/A' }}</td>
                                <td>{{ $client->permis }}</td>
                                <td class="{{ $client->license_age >= 10 ? 'bg-danger-soft' : '' }}">
                                    {{ $client->date_permis ? $client->date_permis->format('d/m/Y') : 'N/A' }}
                                    @if($client->license_age >= 10)
                                        <br><small class="text-danger">{{ $client->license_age }} years old</small>
                                    @endif
                                </td>
                                <td>{{ $client->age }} years</td>
                                <td>
                                    @php
                                        $statusClass = match($client->status) {
                                            'active' => 'success',
                                            'inactive' => 'secondary',
                                            'suspended' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ ucfirst($client->status ?? 'Unknown') }}</span>
                                    @if($client->hasActiveContract())
                                        <br><small class="text-success">Has Contract</small>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.clients.show', $client) }}" 
                                       class="btn btn-info btn-sm m-1" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.clients.edit', $client) }}" 
                                       class="btn btn-warning btn-sm m-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.clients.destroy', $client) }}" 
                                          method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this client?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm m-1" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No clients found matching your criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($clients->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $clients->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('css')
    @parent
    <style>
        .bg-danger-soft {
            background-color: rgba(231, 74, 59, 0.3) !important;
        }
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }
        .table .badge {
            font-size: 0.9em;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script>
        // Auto-submit search form on Enter key
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    </script>
@endsection

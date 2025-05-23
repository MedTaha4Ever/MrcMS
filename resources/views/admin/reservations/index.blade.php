{{-- resources/views/admin/reservations/index.blade.php --}}
@extends('sections.layout')

@section('title', 'Manage Reservations')
@section('reservations-active', 'active') {{-- Assuming a new active state for sidebar --}}

@section('content')
<div class="container-fluid mt-4">
    <h1 class="h3 mb-2 text-gray-800">Reservations Management</h1>

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

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reservations.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select name="status" id="status" class="form-control form-select">
                            <option value="">All Statuses</option>
                            @isset($statuses)
                                @foreach($statuses as $statusValue)
                                    <option value="{{ $statusValue }}" {{ request('status') == $statusValue ? 'selected' : '' }}>
                                        {{ ucfirst($statusValue) }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="start_date" class="form-label">Start Date (from):</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="end_date" class="form-label">End Date (to):</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="car_id" class="form-label">Car:</label>
                        <select name="car_id" id="car_id" class="form-control form-select">
                            <option value="">All Cars</option>
                            @isset($cars)
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ request('car_id') == $car->id ? 'selected' : '' }}>
                                        {{ $car->mat }} - {{ $car->modele->marque->name ?? 'N/A' }} {{ $car->modele->name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                     <div class="col-md-3 mb-3">
                        <label for="client_id" class="form-label">Client:</label>
                        <select name="client_id" id="client_id" class="form-control form-select">
                            <option value="">All Clients</option>
                             @isset($clients)
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->f_name }} {{ $client->l_name }} (ID: {{ $client->id }})
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary ms-2">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Reservations Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Reservations List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="reservationsTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Car (Matricule)</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Requested On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations as $reservation)
                            <tr>
                                <td>{{ $reservation->id }}</td>
                                <td>
                                    @if($reservation->client)
                                        {{ $reservation->client->f_name }} {{ $reservation->client->l_name }} <br><small>(ID: {{ $reservation->client_id }})</small>
                                    @else
                                        Client N/A (ID: {{ $reservation->client_id }})
                                    @endif
                                </td>
                                <td>
                                    @if($reservation->car)
                                    {{ $reservation->car->modele->marque->name ?? 'N/A' }} {{ $reservation->car->modele->name ?? 'N/A' }} <br><small>({{ $reservation->car->mat ?? 'N/A' }})</small>
                                    @else
                                    Car N/A
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($reservation->start_date)->format('d M Y, H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($reservation->end_date)->format('d M Y, H:i') }}</td>
                                <td>{{ $reservation->total_price ? number_format($reservation->total_price, 2) . ' EUR' : 'N/A' }}</td>
                                <td>
                                    @php
                                        $statusClass = 'secondary'; // Default
                                        if ($reservation->status == 'confirmed') $statusClass = 'success';
                                        elseif ($reservation->status == 'pending') $statusClass = 'warning';
                                        elseif ($reservation->status == 'active') $statusClass = 'primary';
                                        elseif ($reservation->status == 'completed') $statusClass = 'info';
                                        elseif ($reservation->status == 'cancelled') $statusClass = 'danger';
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }} text-dark p-2">{{ ucfirst($reservation->status) }}</span>
                                </td>
                                <td>{{ $reservation->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.reservations.show', $reservation) }}" class="btn btn-info btn-sm m-1" title="View Details"><i class="fas fa-eye"></i></a>
                                    @if ($reservation->status == 'pending')
                                        <form action="{{ route('admin.reservations.confirm', $reservation) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to confirm this reservation?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm m-1" title="Confirm"><i class="fas fa-check"></i></button>
                                        </form>
                                    @endif
                                    @if ($reservation->status == 'pending' || $reservation->status == 'confirmed' || $reservation->status == 'active')
                                        <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm m-1" title="Cancel"><i class="fas fa-times"></i></button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No reservations found matching your criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($reservations) && $reservations->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @parent
    {{-- Add any specific JS for this page if needed, e.g., for date pickers or advanced DataTable interaction --}}
    {{-- Example for DataTables (ensure DataTables JS is loaded in layout or here)
    <script>
        $(document).ready(function() {
            $('#reservationsTable').DataTable({
                "order": [[0, "desc"]], // Order by ID desc by default
                "pageLength": 25 
            });
        });
    </script>
    --}}
@endsection

@section('css')
    @parent
    {{-- Add any specific CSS for this page if needed --}}
    <style>
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }
        .table .badge {
            font-size: 0.9em;
        }
    </style>
@endsection

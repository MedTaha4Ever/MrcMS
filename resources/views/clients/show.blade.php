@extends('sections.layout')

@section('title', 'Client Details')
@section('clients-active', 'active')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Client Details: {{ $client->full_name }}</h1>
        <div>
            <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> Edit Client
            </a>
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Clients
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Client Information -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Personal Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Full Name:</strong></td>
                                    <td>{{ $client->full_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $client->email ?: 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $client->phone ?: 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>CIN:</strong></td>
                                    <td>{{ $client->cin }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Birth Date:</strong></td>
                                    <td>
                                        {{ $client->b_date ? $client->b_date->format('d/m/Y') : 'Not provided' }}
                                        @if($client->b_date)
                                            <small class="text-muted">({{ $client->age }} years old)</small>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>License Number:</strong></td>
                                    <td>{{ $client->permis }}</td>
                                </tr>
                                <tr>
                                    <td><strong>License Date:</strong></td>
                                    <td>
                                        {{ $client->date_permis ? $client->date_permis->format('d/m/Y') : 'Not provided' }}
                                        @if($client->date_permis)
                                            <small class="text-muted">({{ $client->license_age }} years old)</small>
                                            @if($client->license_age >= 10)
                                                <span class="badge bg-warning ms-2">Renewal Due</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
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
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Contract:</strong></td>
                                    <td>
                                        @if($client->hasActiveContract())
                                            <span class="badge bg-success">Active Contract</span>
                                        @else
                                            <span class="badge bg-danger">No Contract</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Member Since:</strong></td>
                                    <td>{{ $client->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($client->adrs)
                        <div class="mt-3">
                            <strong>Address:</strong>
                            <p class="mt-2">{{ $client->adrs }}</p>
                        </div>
                    @endif
                    
                    @if($client->notes)
                        <div class="mt-3">
                            <strong>Notes:</strong>
                            <p class="mt-2">{{ $client->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h4>{{ $client->reservations->count() }}</h4>
                                    <small>Total Reservations</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>{{ $client->reservations->where('status', 'confirmed')->count() }}</h5>
                                    <small>Confirmed</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5>{{ $client->reservations->where('status', 'pending')->count() }}</h5>
                                    <small>Pending</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5>{{ $client->reservations->where('status', 'completed')->count() }}</h5>
                                    <small>Completed</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5>{{ $client->reservations->where('status', 'cancelled')->count() }}</h5>
                                    <small>Cancelled</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservations History -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Reservations History</h6>
        </div>
        <div class="card-body">
            @if($client->reservations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Car</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($client->reservations->sortByDesc('created_at') as $reservation)
                                <tr>
                                    <td>{{ $reservation->id }}</td>
                                    <td>
                                        @if($reservation->car)
                                            {{ $reservation->car->modele->marque->name ?? 'N/A' }} 
                                            {{ $reservation->car->modele->name ?? 'N/A' }}<br>
                                            <small class="text-muted">{{ $reservation->car->mat }}</small>
                                        @else
                                            <span class="text-muted">Car N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $reservation->total_price ? number_format($reservation->total_price, 2) . ' EUR' : 'N/A' }}</td>
                                    <td>
                                        @php
                                            $statusClass = 'secondary';
                                            if ($reservation->status == 'confirmed') $statusClass = 'success';
                                            elseif ($reservation->status == 'pending') $statusClass = 'warning';
                                            elseif ($reservation->status == 'active') $statusClass = 'primary';
                                            elseif ($reservation->status == 'completed') $statusClass = 'info';
                                            elseif ($reservation->status == 'cancelled') $statusClass = 'danger';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ ucfirst($reservation->status) }}</span>
                                    </td>
                                    <td>{{ $reservation->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.reservations.show', $reservation) }}" 
                                           class="btn btn-info btn-sm" title="View Reservation">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No reservations found for this client.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
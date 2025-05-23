{{-- resources/views/admin/reservations/show.blade.php --}}
@extends('sections.layout')

@section('title', 'Reservation Details - ID: ' . $reservation->id)
@section('reservations-active', 'active') {{-- Assuming a new active state for sidebar --}}

@section('content')
<div class="container-fluid mt-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reservation Details (ID: {{ $reservation->id }})</h1>
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to List
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

    <div class="row">
        {{-- Reservation Details Card --}}
        <div class="col-xl-7 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Reservation Information</h6>
                    <div>
                        @if ($reservation->status == 'pending')
                            <form action="{{ route('admin.reservations.confirm', $reservation) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to confirm this reservation?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm" title="Confirm"><i class="fas fa-check"></i> Confirm</button>
                            </form>
                        @endif
                        @if (in_array($reservation->status, ['pending', 'confirmed', 'active']))
                            <form action="{{ route('admin.reservations.cancel', $reservation) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm" title="Cancel"><i class="fas fa-times"></i> Cancel</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Reservation ID</th>
                            <td>{{ $reservation->id }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
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
                        </tr>
                        <tr>
                            <th>Start Date</th>
                            <td>{{ \Carbon\Carbon::parse($reservation->start_date)->format('d M Y, H:i A') }}</td>
                        </tr>
                        <tr>
                            <th>End Date</th>
                            <td>{{ \Carbon\Carbon::parse($reservation->end_date)->format('d M Y, H:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Total Price</th>
                            <td>{{ $reservation->total_price ? number_format($reservation->total_price, 2) . ' EUR' : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Requested On</th>
                            <td>{{ $reservation->created_at->format('d M Y, H:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $reservation->updated_at->format('d M Y, H:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Client and Car Details Card --}}
        <div class="col-xl-5 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Client & Car Details</h6>
                </div>
                <div class="card-body">
                    <h5>Client Details</h5>
                    @if($reservation->client)
                        <table class="table table-sm table-borderless">
                            <tr><th width="30%">Name:</th><td>{{ $reservation->client->f_name }} {{ $reservation->client->l_name }}</td></tr>
                            <tr><th>Client ID:</th><td>{{ $reservation->client->id }}</td></tr>
                            <tr><th>CIN:</th><td>{{ $reservation->client->cin ?? 'N/A' }}</td></tr>
                            <tr><th>Address:</th><td>{{ $reservation->client->adrs ?? 'N/A' }}</td></tr>
                            {{-- Add other client details if needed --}}
                        </table>
                    @else
                        <p class="text-muted">Client details not available (client may have been deleted or not set).</p>
                    @endif
                    
                    <hr>
                    <h5>Car Details</h5>
                     @if($reservation->car)
                        <table class="table table-sm table-borderless">
                            <tr><th width="30%">Make:</th><td>{{ $reservation->car->modele->marque->name ?? 'N/A' }}</td></tr>
                            <tr><th>Model:</th><td>{{ $reservation->car->modele->name ?? 'N/A' }}</td></tr>
                            <tr><th>Matricule:</th><td>{{ $reservation->car->mat }}</td></tr>
                            <tr><th>KM:</th><td>{{ number_format($reservation->car->km ?? 0) }} km</td></tr>
                            {{-- Add other car details if needed --}}
                        </table>
                    @else
                         <p class="text-muted">Car details not available (car may have been deleted).</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
    @parent
    <style>
        .table-sm th, .table-sm td {
            padding: 0.4rem;
        }
        .card-header h6 {
            line-height: 1.75; /* Align title better with buttons */
        }
    </style>
@endsection

@section('scripts')
    @parent
    {{-- No specific JS for this page for now --}}
@endsection

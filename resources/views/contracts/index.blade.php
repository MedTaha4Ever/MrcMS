@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.contracts') }}</h3>
                    <a href="{{ route('admin.contracts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('messages.new_contract') }}
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('admin.contracts.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.search') }}..." 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">{{ __('messages.status') }}</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        {{ __('messages.pending') }}
                                    </option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                        {{ __('messages.confirmed') }}
                                    </option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        {{ __('messages.cancelled') }}
                                    </option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        {{ __('messages.completed') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_from" class="form-control" 
                                       placeholder="Date de début" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" class="form-control" 
                                       placeholder="Date de fin" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-search"></i> {{ __('messages.filter') }}
                                </button>
                                <a href="{{ route('admin.contracts.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Effacer
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Reservations Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{ __('messages.client_name') }}</th>
                                    <th>{{ __('messages.car_details') }}</th>
                                    <th>{{ __('messages.start_date') }}</th>
                                    <th>{{ __('messages.end_date') }}</th>
                                    <th>{{ __('messages.duration') }}</th>
                                    <th>{{ __('messages.total_price') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->id }}</td>
                                    <td>
                                        <strong>{{ $reservation->client->full_name }}</strong><br>
                                        <small class="text-muted">{{ $reservation->client->email }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $reservation->car->brand }} {{ $reservation->car->model }}</strong><br>
                                        <small class="text-muted">{{ $reservation->car->year }}</small>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($reservation->start_date)->diffInDays(\Carbon\Carbon::parse($reservation->end_date)) }} 
                                        {{ __('messages.days') }}
                                    </td>
                                    <td>{{ number_format($reservation->total_price, 2) }} {{ __('messages.dt') }}</td>
                                    <td>
                                        @switch($reservation->status)
                                            @case('pending')
                                                <span class="badge badge-warning">{{ __('messages.pending') }}</span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge badge-success">{{ __('messages.confirmed') }}</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge badge-danger">{{ __('messages.cancelled') }}</span>
                                                @break
                                            @case('completed')
                                                <span class="badge badge-info">{{ __('messages.completed') }}</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.contracts.show', $reservation) }}" 
                                               class="btn btn-sm btn-info" title="{{ __('messages.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.contracts.edit', $reservation) }}" 
                                               class="btn btn-sm btn-warning" title="{{ __('messages.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.contracts.destroy', $reservation) }}" 
                                                  method="POST" style="display: inline-block;"
                                                  onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        title="{{ __('messages.delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">{{ __('messages.no_results') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $reservations->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
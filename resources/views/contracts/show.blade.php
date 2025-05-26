@extends('sections.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.contract_details') }}</h3>
                    <div>
                        <a href="{{ route('admin.contracts.edit', $reservation) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                        </a>
                        <a href="{{ route('admin.contracts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ __('messages.client_details') }}</h5>
                            <table class="table">
                                <tr>
                                    <th>{{ __('messages.name') }}:</th>
                                    <td>{{ $reservation->client->f_name }} {{ $reservation->client->l_name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.email') }}:</th>
                                    <td>{{ $reservation->client->email }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.phone') }}:</th>
                                    <td>{{ $reservation->client->phone }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>{{ __('messages.car_details') }}</h5>
                            <table class="table">
                                <tr>
                                    <th>{{ __('messages.car') }}:</th>
                                    <td>{{ $reservation->car->modele->marque->name }} {{ $reservation->car->modele->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.matricule') }}:</th>
                                    <td>{{ $reservation->car->mat }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.price_per_day') }}:</th>
                                    <td>{{ number_format($reservation->car->price_per_day, 2) }} DT</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>{{ __('messages.contract_details') }}</h5>
                            <table class="table">
                                <tr>
                                    <th>{{ __('messages.start_date') }}:</th>
                                    <td>{{ \Carbon\Carbon::parse($reservation->start_date)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.end_date') }}:</th>
                                    <td>{{ \Carbon\Carbon::parse($reservation->end_date)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.duration') }}:</th>
                                    <td>{{ \Carbon\Carbon::parse($reservation->start_date)->diffInDays(\Carbon\Carbon::parse($reservation->end_date)) }} {{ __('messages.days') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.total_price') }}:</th>
                                    <td>{{ number_format($reservation->total_price, 2) }} DT</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.status') }}:</th>
                                    <td>
                                        @switch($reservation->status)
                                            @case('pending')
                                                <span class="badge bg-warning">{{ __('messages.pending') }}</span>
                                                @break
                                            @case('confirmed')
                                                <span class="badge bg-success">{{ __('messages.confirmed') }}</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger">{{ __('messages.cancelled') }}</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-info">{{ __('messages.completed') }}</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

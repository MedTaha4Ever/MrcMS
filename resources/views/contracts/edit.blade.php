@extends('sections.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.edit_contract') }}</h3>
                    <div>
                        <a href="{{ route('admin.contracts.show', $reservation) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('admin.contracts.update', $reservation) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Client Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client_id">{{ __('messages.client_name') }} *</label>
                                    <select name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id', $reservation->client_id) == $client->id ? 'selected' : '' }}>
                                                {{ $client->f_name }} {{ $client->l_name }} - {{ $client->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Car Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="car_id">{{ __('messages.car_details') }} *</label>
                                    <select name="car_id" id="car_id" class="form-control @error('car_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un véhicule</option>
                                        @foreach($cars as $car)
                                            <option value="{{ $car->id }}" 
                                                    data-price="{{ $car->price_per_day }}"
                                                    {{ old('car_id', $reservation->car_id) == $car->id ? 'selected' : '' }}>
                                                {{ $car->modele->marque->name }} {{ $car->modele->name }} ({{ $car->mat }}) - {{ $car->price_per_day }} DT/jour
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('car_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Start Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">{{ __('messages.start_date') }} *</label>
                                    <input type="date" name="start_date" id="start_date" 
                                           class="form-control @error('start_date') is-invalid @enderror"
                                           value="{{ old('start_date', $reservation->start_date) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">{{ __('messages.end_date') }} *</label>
                                    <input type="date" name="end_date" id="end_date" 
                                           class="form-control @error('end_date') is-invalid @enderror"
                                           value="{{ old('end_date', $reservation->end_date) }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">{{ __('messages.status') }} *</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                                        <option value="pending" {{ old('status', $reservation->status) == 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                                        <option value="confirmed" {{ old('status', $reservation->status) == 'confirmed' ? 'selected' : '' }}>{{ __('messages.confirmed') }}</option>
                                        <option value="cancelled" {{ old('status', $reservation->status) == 'cancelled' ? 'selected' : '' }}>{{ __('messages.cancelled') }}</option>
                                        <option value="completed" {{ old('status', $reservation->status) == 'completed' ? 'selected' : '' }}>{{ __('messages.completed') }}</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Price Calculation Display -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5>Calcul du prix</h5>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Prix par jour:</strong>
                                                <span id="price-per-day">0</span> DT
                                            </div>
                                            <div class="col-md-3">
                                                <strong>{{ __('messages.duration') }}:</strong>
                                                <span id="duration">0</span> {{ __('messages.days') }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>{{ __('messages.total_price') }}:</strong>
                                                <span id="total-price">0</span> DT
                                            </div>
                                            <div class="col-md-3">
                                                <div id="availability-status"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.save') }}
                        </button>
                        <a href="{{ route('admin.contracts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ __('messages.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const carSelect = document.getElementById('car_id');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const pricePerDaySpan = document.getElementById('price-per-day');
    const durationSpan = document.getElementById('duration');
    const totalPriceSpan = document.getElementById('total-price');
    const availabilityStatus = document.getElementById('availability-status');

    function updateCalculations() {
        const selectedCar = carSelect.options[carSelect.selectedIndex];
        const pricePerDay = selectedCar.dataset.price || 0;
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        pricePerDaySpan.textContent = pricePerDay;

        if (startDate && endDate && endDate > startDate) {
            const timeDiff = endDate.getTime() - startDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
            const totalPrice = daysDiff * pricePerDay;

            durationSpan.textContent = daysDiff;
            totalPriceSpan.textContent = totalPrice.toFixed(2);

            // Check availability if car is selected
            if (carSelect.value && startDateInput.value && endDateInput.value) {
                checkAvailability();
            }
        } else {
            durationSpan.textContent = '0';
            totalPriceSpan.textContent = '0';
            availabilityStatus.innerHTML = '';
        }
    }

    function checkAvailability() {
        if (!carSelect.value || !startDateInput.value || !endDateInput.value) {
            return;
        }

        availabilityStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Vérification...';        fetch('{{ route("admin.api.check.availability") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                car_id: carSelect.value,
                start_date: startDateInput.value,
                end_date: endDateInput.value,
                reservation_id: '{{ $reservation->id }}' // Include current reservation ID to exclude it from availability check
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                availabilityStatus.innerHTML = '<span class="text-success"><i class="fas fa-check"></i> Disponible</span>';
            } else {
                availabilityStatus.innerHTML = '<span class="text-danger"><i class="fas fa-times"></i> Non disponible</span>';
            }
        })
        .catch(error => {
            availabilityStatus.innerHTML = '<span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Erreur de vérification</span>';
            console.error('Error:', error);
        });
    }

    carSelect.addEventListener('change', updateCalculations);
    startDateInput.addEventListener('change', updateCalculations);
    endDateInput.addEventListener('change', updateCalculations);

    // Initial calculation
    updateCalculations();
});
</script>
@endpush

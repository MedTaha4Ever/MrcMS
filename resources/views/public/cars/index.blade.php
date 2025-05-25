<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location de Voitures - MrcMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .car-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .car-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .price-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #28a745;
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: bold;
        }
        .filter-section {
            background: #f8f9fa;
            padding: 30px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .footer {
            background: #343a40;
            color: white;
            padding: 40px 0;
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('public.cars.index') }}">
                <i class="fas fa-car"></i> MrcMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('public.cars.index') }}">
                            <i class="fas fa-car"></i> Nos Véhicules
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-user"></i> Espace Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Location de Voitures en Tunisie</h1>
            <p class="lead mb-4">Découvrez notre flotte de véhicules modernes et fiables pour tous vos déplacements</p>
            <a href="#cars-section" class="btn btn-light btn-lg">
                <i class="fas fa-search"></i> Voir les Véhicules
            </a>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section" id="cars-section">
        <div class="container">
            <form method="GET" action="{{ route('public.cars.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Marque</label>
                        <select name="brand" class="form-select">
                            <option value="">Toutes les marques</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                    {{ $brand }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Prix min (DT/jour)</label>
                        <input type="number" name="min_price" class="form-control" 
                               value="{{ request('min_price') }}" placeholder="0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Prix max (DT/jour)</label>
                        <input type="number" name="max_price" class="form-control" 
                               value="{{ request('max_price') }}" placeholder="1000">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date début</label>
                        <input type="date" name="start_date" class="form-control" 
                               value="{{ request('start_date') }}" min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date fin</label>
                        <input type="date" name="end_date" class="form-control" 
                               value="{{ request('end_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Cars Grid -->
    <section class="py-5">
        <div class="container">
            @if(request()->hasAny(['brand', 'min_price', 'max_price', 'start_date', 'end_date']))
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-filter"></i> 
                                Filtres appliqués - {{ $cars->total() }} véhicule(s) trouvé(s)
                            </span>
                            <a href="{{ route('public.cars.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times"></i> Effacer les filtres
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                @forelse($cars as $car)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card car-card h-100 position-relative">
                            <div class="price-badge">{{ number_format($car->price_per_day, 0) }} DT/jour</div>
                            
                            @if($car->image_url)
                                <img src="{{ $car->image_url }}" class="car-image" alt="{{ $car->brand }} {{ $car->model }}">
                            @else
                                <div class="car-image bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-car fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $car->brand }} {{ $car->model }}</h5>
                                <p class="card-text text-muted">Année {{ $car->year }}</p>
                                
                                <div class="row text-center mb-3">
                                    @if($car->fuel_type)
                                        <div class="col-4">
                                            <i class="fas fa-gas-pump text-primary"></i><br>
                                            <small>{{ ucfirst($car->fuel_type) }}</small>
                                        </div>
                                    @endif
                                    @if($car->transmission)
                                        <div class="col-4">
                                            <i class="fas fa-cogs text-primary"></i><br>
                                            <small>{{ ucfirst($car->transmission) }}</small>
                                        </div>
                                    @endif
                                    @if($car->seats)
                                        <div class="col-4">
                                            <i class="fas fa-users text-primary"></i><br>
                                            <small>{{ $car->seats }} places</small>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mt-auto">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('public.cars.show', ['car' => $car->id, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" 
                                           class="btn btn-primary">
                                            <i class="fas fa-eye"></i> Voir Détails
                                        </a>
                                        @if(request('start_date') && request('end_date'))
                                            <a href="{{ route('public.reservations.create', ['car' => $car->id, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" 
                                               class="btn btn-success">
                                                <i class="fas fa-calendar-check"></i> Réserver
                                            </a>
                                        @else
                                            <a href="{{ route('public.cars.show', $car->id) }}" 
                                               class="btn btn-outline-success">
                                                <i class="fas fa-calendar-check"></i> Réserver
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                            <h4>Aucun véhicule disponible</h4>
                            <p class="text-muted">Essayez de modifier vos critères de recherche.</p>
                            <a href="{{ route('public.cars.index') }}" class="btn btn-primary">
                                <i class="fas fa-refresh"></i> Voir tous les véhicules
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($cars->hasPages())
                <div class="row">
                    <div class="col-12">
                        <nav aria-label="Navigation des pages">
                            {{ $cars->appends(request()->query())->links() }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-car"></i> MrcMS</h5>
                    <p>Votre partenaire de confiance pour la location de véhicules en Tunisie.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6>Contact</h6>
                    <p>
                        <i class="fas fa-phone"></i> +216 XX XXX XXX<br>
                        <i class="fas fa-envelope"></i> contact@mrcms.tn<br>
                        <i class="fas fa-map-marker-alt"></i> Tunis, Tunisie
                    </p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12 text-center">
                    <p>&copy; {{ date('Y') }} MrcMS. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-update end date minimum when start date changes
        document.querySelector('input[name="start_date"]').addEventListener('change', function() {
            const startDate = new Date(this.value);
            const endDateInput = document.querySelector('input[name="end_date"]');
            const nextDay = new Date(startDate);
            nextDay.setDate(startDate.getDate() + 1);
            endDateInput.min = nextDay.toISOString().split('T')[0];
            
            if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
                endDateInput.value = nextDay.toISOString().split('T')[0];
            }
        });
    </script>
</body>
</html>
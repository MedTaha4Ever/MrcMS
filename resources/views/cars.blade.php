@extends('sections.layout')

@section('title', 'Voitures')
@section('cars-active', 'active')

@section('content')
    <div class="container">
        <div class="container">
            <div class="container-fluid p-0">
                <h1 class="h3 mb-3">Cars</h1>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="c_table" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>#</th>
                                            <th>Serie</th>
                                            <th>Modele</th>
                                            <th>Kilometrage</th>
                                            <th>Status</th>
                                            <th>Modofication</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cars as $car)
                                            @php
                                                // Prepare data for child row here for cleanliness
                                                $dpcDate = \Carbon\Carbon::parse($car->dpc);
                                                $age = $dpcDate->diffInYears(\Carbon\Carbon::now());
                                                $dpcFormatted = $dpcDate->format('d M Y');
                                                $childRowHtml = "<div><ul><li><b>Age</b>: {$age} ans</li><li><b>D.P.C</b>: {$dpcFormatted}</li></ul></div>";
                                            @endphp
                                            <tr data-child-value="{{ e($childRowHtml) }}">
                                                <td class="details-control"></td>
                                                <td><img src="{{ asset('img/default_car_icon.png') }}" {{-- Placeholder for local asset --}}
                                                        width="32" height="32" class="rounded-circle my-n1"
                                                        alt="Car Avatar"></td>
                                                <td>{{ $car->mat }}</td>
                                                {{-- Assuming $car->modele->name is available after controller refactor ($car->load('modele')) --}}
                                                <td>{{ $car->modele->name ?? ($car->modName ?? 'N/A') }}</td>
                                                <td>{{ $car->km }} Km</td>
                                                @if ($car->contract_id > 0 && $car->contract) {{-- Assuming $car->contract relationship exists --}}
                                                    <td><span class="badge bg-danger">Contrat {{ $car->contract->id ?? $car->contract_id }}</span></td>
                                                @else
                                                    <td><span class="badge bg-success">Pas de Contrat</span></td>
                                                @endif
                                                <td>
                                                    {{-- Assuming named routes like 'admin.cars.edit' and 'admin.cars.delete' --}}
                                                    <a href="{{ route('admin.cars.edit', $car->id) }}" {{-- Example, adjust if route name is different --}}
                                                        class="btn btn-primary btn-sm">Modifier</a>
                                                    <button type="button" value="{{ $car->id }}"
                                                        class="btn btn-danger btn-sm delete" data-toggle="modal"
                                                        data-target="#deleteModal">Supprimer</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Supprimer la voiture :
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        {{-- Ensure delete_id has data-base-url for the JS to construct the href --}}
                        <a href="#" class="btn btn-danger delete_id" data-base-url="{{ url('admin/cars/delete') }}">Supprimer</a>
                    </div>
                </div>
            </div>
        </div>
    @stop {{-- End of @section('content') --}}

    @section('css')
        @parent {{-- Include CSS from parent layout if any --}}
        <link rel="stylesheet" href="{{ asset('css/datatables_custom.css') }}">
        {{-- Add other CSS links if needed, e.g., for DataTables itself if not in main layout --}}
        {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"> --}}
    @stop

    @section('scripts')
        @parent {{-- Include JS from parent layout if any --}}
        {{-- Add other JS links if needed, e.g., for DataTables itself if not in main layout --}}
        {{-- <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> --}}
        
        {{-- Pass data to JavaScript --}}
        <script>
            // It's better to attach data to specific elements (like #c_table) than using global vars
            document.addEventListener('DOMContentLoaded', function () {
                const cTable = document.getElementById('c_table');
                if(cTable) {
                    cTable.setAttribute('data-lang-url', "{{ asset('local/fr-FR.json') }}");
                }
            });
        </script>
        <script src="{{ asset('js/cars_table.js') }}" defer></script>
    @stop

    @section('addcar')
        <div class="clearfix">
             {{-- Assuming named route 'admin.cars.add' --}}
            <a href="{{ route('admin.cars.add') }}" class="btn btn-primary btn-lg float-right mr-5" id="addcar"
                aria-label="addcar">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z" />
                </svg>
                Ajouter une voiture
            </a>
        </div>


    @stop

@extends('sections.layout')

@section('title', 'Clients')
@section('clients-active', 'active')

@section('content')
    <div class="container">
        <div class="container">
            <div class="container-fluid p-0">
                <h1 class="h3 mb-3">Clients</h1>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="c_table" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>#</th>
                                            <th>Nom et Prénom</th>
                                            <th>N° Permis</th>
                                            <th>Date permis</th>
                                            <th>Age</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clients as $client)
                                            @php
                                                $childValue = "Notes: " . e($client->notes) . "<br> C.I.N: " . e($client->cin) . "<br> Adresse: " . e($client->adrs);
                                                $permisDate = \Carbon\Carbon::parse($client->date_permis);
                                                $permisAge = $permisDate->diffInYears(\Carbon\Carbon::now());
                                                $birthDate = \Carbon\Carbon::parse($client->b_date);
                                                $clientAge = $birthDate->diffInYears(\Carbon\Carbon::now());
                                            @endphp
                                            <tr data-child-value="{{ $childValue }}">
                                                <td class="details-control"></td>
                                                <td><img src="{{ asset('img/default_avatar.png') }}" {{-- Placeholder for local asset --}}
                                                        width="32" height="32" class="rounded-circle my-n1"
                                                        alt="Avatar"></td>
                                                <td>{{ $client->f_name . ' ' . $client->l_name }}</td>
                                                <td>{{ $client->permis }}</td>
                                                <td @if ($permisAge >= 10) class="bg-danger-soft" @endif> {{-- Using a class instead of inline style --}}
                                                    {{ $permisDate->format('d/m/Y') }}
                                                </td>
                                                <td>{{ $clientAge }} ans</td>
                                                @if ($client->contract_id > 0) {{-- Assuming contract_id indicates active contract --}}
                                                    <td><span class="badge bg-success">Actif</span> {{-- Simplified status --}}
                                                    </td>
                                                @else
                                                    <td><span class="badge bg-danger">Pas de Contrat</span></td>
                                                @endif
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

        <!-- Modal Marque add-->
        <div class="modal fade" id="addMarqueModal" tabindex="-1" role="dialog" aria-labelledby="addMarqueModal"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMarqueModal">Confirmer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/admin/settings/marque/add') }}" method="post">
                            @csrf
                            <label for="name">Nom de Marque:</label>
                            <input type="text" name="name" id="name">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Ajouter">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @stop {{-- End of content --}}

    @section('css')
        @parent 
        {{-- datatables_custom.css is expected to be loaded via parent layout or globally if used by multiple pages --}}
        {{-- If not, uncomment: <link rel="stylesheet" href="{{ asset('css/datatables_custom.css') }}"> --}}
        <style>
            /* Example of a utility class that could be in a global CSS file */
            .bg-danger-soft {
                background-color: rgba(231, 74, 59, 0.3) !important; /* Soft red for highlighting */
            }
        </style>
    @stop

    @section('scripts')
        @parent
        <script>
            // Pass data to JavaScript by attaching to relevant elements
            document.addEventListener('DOMContentLoaded', function () {
                const cTable = document.getElementById('c_table');
                if(cTable) {
                    cTable.setAttribute('data-lang-url', "{{ asset('local/fr-FR.json') }}");
                }
            });
        </script>
        <script src="{{ asset('js/clients_table.js') }}" defer></script>
    @stop

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
                                            <th>Adresse</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clients as $client)
                                            <tr data-child-value="{{ $client->note }}">
                                                <td class="details-control"></td>
                                                <td><img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                        width="32" height="32" class="rounded-circle my-n1"
                                                        alt="Avatar"></td>
                                                <td>{{ $client->f_name . ' ' . $client->l_name }}</td>
                                                <td>{{ $client->permis }}</td>
                                                <td @if ($client->date_permis < now()) style="background-color:red" @endif>
                                                    {{ date('d/m/Y', strtotime($client->date_permis)) }}</td>
                                                <td>{{ $diff = Carbon\Carbon::parse($client->b_date)->diffInYears(Carbon\Carbon::now()) }}
                                                    ans
                                                </td>
                                                <td>{{ $client->adrs }}</td>
                                                @if ($client->contract_id > 0)
                                                    <td><span class="badge bg-success">{{ $client->contract_id }}</span>
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
    @stop
    @section('scripts')
        <script defer>
            function format(value) {
                return '<div>Notes: ' + value + '</div>';
            }
            $(document).ready(function() {
                let table = $('#c_table').DataTable({});

                // Add event listener for opening and closing details
                $('#c_table').on('click', 'td.details-control', function() {
                    let tr = $(this).closest('tr');
                    let row = table.row(tr);

                    if (row.child.isShown()) {
                        // This row is already open - close it
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        // Open this row
                        row.child(format(tr.data('child-value'))).show();
                        tr.addClass('shown');
                    }
                });
            });
        </script>
    @stop
    @section('css')
        <style>
            td.details-control {
                background: url('http://www.datatables.net/examples/resources/details_open.png') no-repeat center center;
                cursor: pointer;
            }

            tr.shown td.details-control {
                background: url('http://www.datatables.net/examples/resources/details_close.png') no-repeat center center;
            }
        </style>
    @stop

@extends('sections.layout')

@section('title', 'Parametre')
@section('settings-active', 'active')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Parametre de voitures</h1>

        <div class="card shadow mb-4">
            {{-- parametre de marques --}}
            <div class="card-body">
                <h5 class="card-title">Marques</h5>
                <a href="#" data-toggle="modal" data-target="#addMarqueModal" class="btn btn-primary">
                    <i class="fas fa-fw fa-plus-square"></i> {{-- Corrected icon HTML --}}
                </a>
                <br><br>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Modifier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($marques as $marque)
                            <tr>
                                <th scope="row">{{ $marque->name }}</th>
                                <td>
                                    <a class="btn btn-primary btn-sm"
                                        href="{{ url('admin/settings/marque/edit/' . $marque->id) }}"> {{-- Added btn-sm for consistency --}}
                                        <i class="fas fa-fw fa-edit"></i> {{-- Corrected icon HTML --}}
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm delete" data-toggle="modal" {{-- Added btn-sm --}}
                                        data-target="#deleteMarqueModal" value="{{ $marque->id }}">
                                        <i class="fas fa-fw fa-times"></i> {{-- Corrected icon HTML --}}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mb-4">
            {{-- parametre de modeles --}}
            <div class="card-body">
                <h5 class="card-title">Modeles</h5>
                <a href="#" data-toggle="modal" data-target="#addModelModal" class="btn btn-primary">
                    <i class="fas fa-fw fa-plus-square"></i> {{-- Corrected icon HTML --}}
                </a>
                <br><br>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Marque</th>
                            <th scope="col">Année</th>
                            <th scope="col">Modifier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($models as $model)
                            <tr>
                                <th scope="row">{{ $model->name }}</th>
                                {{-- Use the relationship to get marque name --}}
                                <td scope="row">{{ $model->marque->name ?? 'N/A' }}</td>
                                <td scope="row">{{ $model->year }}</td>
                                <td>                                    <button type="button" class="btn btn-danger btn-sm delete-model" data-toggle="modal" 
                                        data-target="#deleteModelModal" value="{{ $model->id }}">
                                        <i class="fas fa-fw fa-times"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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

    <!-- Modal Marque delete-->
    <div class="modal fade" id="deleteMarqueModal" tabindex="-1" role="dialog" aria-labelledby="deleteMarqueModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMarqueModal">Confirmer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Voulez-vous vraiment supprimer cette marque ? Cette action est irréversible.
                </div>
                <div class="modal-footer"> {{-- Corrected structure: buttons in footer --}}
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <a href="#" type="button" class="btn btn-danger delete_id_marque" data-base-url="{{ url('admin/settings/marque/delete') }}">Supprimer</a>
                    {{-- Removed orphaned </form> tag --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Model Add -->
    <div class="modal fade" id="addModelModal" tabindex="-1" role="dialog" aria-labelledby="addModelModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un Modèle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.settings.model.add') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="marque_id">Marque:</label>
                            <select name="marque_id" id="marque_id" class="form-control" required>
                                <option value="">Sélectionnez une marque</option>
                                @foreach ($marques as $marque)
                                    <option value="{{ $marque->id }}">{{ $marque->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="model_name">Nom du Modèle:</label>
                            <input type="text" name="name" id="model_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="year">Année:</label>
                            <input type="text" name="year" id="year" class="form-control" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Model delete-->
    <div class="modal fade" id="deleteModelModal" tabindex="-1" role="dialog" aria-labelledby="deleteModelModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModelModal">Confirmer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Voulez-vous vraiment supprimer ce modèle ? Cette action est irréversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <a href="#" type="button" class="btn btn-danger delete_id_model" data-base-url="{{ route('admin.settings.model.delete', '') }}">Supprimer</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent {{-- Good practice to include parent scripts --}}
    <script src="{{ asset('js/settings_page.js') }}" defer></script>
@stop

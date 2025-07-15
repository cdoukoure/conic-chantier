@extends('layouts.app')

@section('title', 'Détails du projet')

@section('content')
    <div class="container pt-4">
        <h2 class="mt-4">Détail du projet : {{ $project->name }}</h2>

        {{-- Informations du projet --}}
        <div class="card mb-4">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nom</dt>
                    <dd class="col-sm-9">{{ $project->name }}</dd>

                    <dt class="col-sm-3">Type</dt>
                    <dd class="col-sm-9">{{ ucfirst($project->type) }}</dd>

                    <dt class="col-sm-3">Client</dt>
                    <dd class="col-sm-9">{{ optional($project->client)->name ?? '-' }}</dd>

                    <dt class="col-sm-3">Budget</dt>
                    <dd class="col-sm-9">{{ number_format($project->budget, 2, ',', ' ') }} €</dd>

                    <dt class="col-sm-3">Dates</dt>
                    <dd class="col-sm-9">{{ $project->start_date }} → {{ $project->end_date ?? 'Non définie' }}</dd>

                    <dt class="col-sm-3">Description</dt>
                    <dd class="col-sm-9">{{ $project->description ?? '-' }}</dd>
                </dl>
            </div>
        </div>

        {{-- Bouton Ajouter --}}
        <div class="mb-3">
            <button class="btn btn-success" onclick="openCreateModal()">
                <i class="fas fa-plus me-2"></i>Ajouter un chantier
            </button>
        </div>

        {{-- Tableau des chantiers --}}
        <div class="card">
            <div class="card-header">
                <strong>Liste des chantiers liés</strong>
            </div>
            <div class="card-body">
                <table id="chantiersTable" class="table table-bordered w-100">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Phase</th>
                            <th>Budget</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL FORM CHANTIER --}}
    <div class="modal fade" id="chantierModal" aria-labelledby="chantierModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="chantierForm">
                @csrf
                <input type="hidden" name="id" id="chantier_id">
                <input type="hidden" name="client_id" value="{{ $project->client_id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="chantierModalLabel">Nouveau chantier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="parent_id" value="{{ $project->id }}">
                        <input type="hidden" name="type" value="chantier">

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="phase_id" class="form-label w-100">Phase
                            </label>
                            <select class="form-select" id="phase_id" name="phase_id" style="width: 100%">
                                <option value="">-- Aucun --</option>
                                @foreach($phases as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="budget" class="form-label">Budget (€)</label>
                            <input type="number" step="0.01" class="form-control" id="budget" name="budget" required>
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Date début</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">Date fin</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let modal = new bootstrap.Modal(document.getElementById('chantierModal'));
        let table;

        $(document).ready(function () {
            $('#phase_id').select2({
                theme: 'bootstrap-5',
                dropdownParent: $("#chantierModal"),
                width: 'resolve' // need to override the changed default
            });

            table = $('#chantiersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("projects.projectChantiersDatatable", $project->id) }}',
                columns: [
                    { data: 'name' },
                    { data: 'phase_name' },
                    { data: 'budget' },
                    { data: 'start_date' },
                    { data: 'end_date' },
                    {
                        data: 'id',
                        render: function (id, type, row) {
                            return `
                                <button class="btn btn-sm btn-warning me-1" onclick="editChantier(${id})">
                                    Configuration <i class="fas fa-edit"></i>
                                </button>
                                <a href="/projects/${id}" class="btn btn-sm btn-outline-primary me-2" role="button" aria-disabled="true">
                                    Gestion <i class="fas fa-bars"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="deleteChantier(${id})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
                }
            });

            $('#chantierForm').submit(function (e) {
                e.preventDefault();
                const id = $('#chantier_id').val();
                const method = id ? 'PUT' : 'POST';
                const url = id ? `/projects/${id}` : `{{ route('projects.store') }}`;
                // $('#budget').val(Number($('#budget').val()));
                $('#budget').val(parseFloat($('#budget').val()).toFixed(2));
                $.ajax({
                    url,
                    method,
                    data: $(this).serialize(),
                    success: function () {
                        modal.hide();
                        table.ajax.reload();
                    },
                    error: function () {
                        alert("Erreur lors de l'enregistrement");
                    }
                });
            });
        });

        function openCreateModal() {
            $('#chantierForm')[0].reset();
            $('#chantier_id').val('');
            $('#chantierModalLabel').text('Nouveau chantier');
            modal.show();
        }

        function editChantier(id) {
            $.getJSON(`/projects/${id}`, function (data) {
                $('#chantier_id').val(data.id);
                $('#name').val(data.name);
                $('#start_date').val(data.start_date);
                $('#end_date').val(data.end_date);
                $('#client_id').val(data.client_id);
                $('#phase_id').val(data.phase_id);
                $('#budget').val(parseFloat(data.budget).toFixed(2));
                $('#chantierModalLabel').text('Modifier chantier');
                modal.show();
            });
        }

        function deleteChantier(id) {
            if (confirm("Supprimer ce chantier ?")) {
                $.ajax({
                    url: `/projects/${id}`,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function () {
                        table.ajax.reload();
                    },
                    error: function () {
                        alert("Échec de la suppression.");
                    }
                });
            }
        }
    </script>
@endpush
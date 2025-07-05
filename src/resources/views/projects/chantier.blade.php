@extends('layouts.app')

@section('title', 'Détails du projet')

@section('content')
    <div class="container">
        <h2>Détails du chantier</h2>
        <div class="row">
            <div class="col-sm-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">Client</dt>
                            <dd class="col-sm-9">{{ $project->parent->client->name ?? '-' }}</dd>

                            <dt class="col-sm-3">Projet</dt>
                            <dd class="col-sm-9">{{ $project->parent->name }}</dd>

                            <dt class="col-sm-3">Chantier</dt>
                            <dd class="col-sm-9">{{ $project->name }}</dd>

                            <dt class="col-sm-3">Budget</dt>
                            <dd class="col-sm-9">{{ number_format($project->budget, 2) }} €</dd>

                            <dt class="col-sm-3">Début</dt>
                            <dd class="col-sm-9">{{ $project->start_date }}</dd>

                            <dt class="col-sm-3">Fin</dt>
                            <dd class="col-sm-9">{{ $project->end_date ?? 'Non définie' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">

            </div>
        </div>


        <!-- DATATABLE CONTACTS -->
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4>Contacts associés</h4>
                <button class="btn btn-primary btn-sm" id="btnAddContact">Ajouter un contact</button>
            </div>
            <table id="contactsTable" class="table table-bordered w-100">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>

        <!-- DATATABLE FINANCIAL MOVEMENTS -->
        <div>
            <h4>Mouvements financiers</h4>
            <table id="movementsTable" class="table table-striped w-100">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Contact</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modals (à implémenter selon ton UX) -->
    <div id="modalsHere"></div>
@endsection

@push('scripts')
    <script>
        const projectId = {{ $project->id }};

        $(function () {
            let movementsTable = $('#movementsTable').DataTable({
                ajax: `/projects/${projectId}/financial-movements/datatable`,
                processing: true,
                serverSide: true,
                columns: [
                    { data: 'date' },
                    { data: 'type' },
                    { data: 'amount', render: data => `${data} €` },
                    { data: 'contact.name' },
                    { data: 'description' },
                    {
                        data: 'id',
                        render: function (id) {
                            return `<button class="btn btn-sm btn-secondary" onclick="editMovement(${id})">Modifier</button>`;
                        }
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
                }
            });

            let contactsTable = $('#contactsTable').DataTable({
                ajax: `/projects/${projectId}/contacts/datatable`,
                processing: true,
                serverSide: true,
                columns: [
                    { data: 'pivot.role' },
                    { data: 'name' },
                    { data: 'phone' },
                    { data: 'email' },
                    {
                        data: 'id',
                        render: function (id) {
                            return `
                                <button class="btn btn-sm btn-outline-primary" onclick="createMovement(${id})">Créer Mouvement</button>
                                <button class="btn btn-sm btn-outline-danger" onclick="detachContact(${id})">Retirer</button>
                            `;
                        }
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json'
                }
            });

            // Simule le rechargement après modification mouvement
            window.refreshMovements = function () {
                movementsTable.ajax.reload();
            };

            // Exemple pour lancer modaux
            window.createMovement = function (contactId) {
                // Charger un formulaire dynamique ou ouvrir un modal custom
                alert('Créer mouvement pour contact #' + contactId);
            };

            window.editMovement = function (movementId) {
                // Exemple : fetch + modal + refreshMovements()
                alert('Modifier mouvement #' + movementId);
                // Après modification, rappelle : refreshMovements()
            };

            window.detachContact = function (contactId) {
                if (confirm('Retirer ce contact du projet ?')) {
                    $.ajax({
                        url: `/projects/${projectId}/contacts/${contactId}`,
                        method: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function () {
                            contactsTable.ajax.reload();
                        },
                        error: function () {
                            alert('Erreur lors de la suppression du contact');
                        }
                    });
                }
            };

            $('#btnAddContact').click(function () {
                alert('Ouverture modal d’ajout de contact');
            });
        });
    </script>
@endpush
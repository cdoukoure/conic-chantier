@extends('layouts.app')

@section('title', ' Catégorie de finance')

@section('content')
    <div class="container pt-4">

        <button class="btn btn-primary mb-3 mt-4" id="btnAddFinancialMovementCategorie">Nouvelle Catégorie de finance</button>

        <table class="table table-bordered" id="financialMovementCategorieTable">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="financialMovementCatModal" tabindex="-1" aria-labelledby="financialMovementCatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="financialMovementCatForm">
                @csrf
                <input type="hidden" name="id" id="financialMovementCategorie_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="financialMovementCatModalLabel">Nouvelle  Catégorie de finance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            let table = $('#financialMovementCategorieTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{{ route("financial-movement-categories.datatable") }}',
                columns: [
                    { data: 'name' },
                    { data: 'description' },
                    {
                        data: 'id',
                        orderable: false,
                        render: function (id) {
                            return `
                            <button class="btn btn-sm btn-outline-primary me-2" onclick="editFinancialMovementCategorie(${id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteFinancialMovementCategorie(${id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                        }
                    }
                ],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
                }
            });

            $('#btnAddFinancialMovementCategorie').click(() => {
                $('#financialMovementCatForm')[0].reset();
                $('#financialMovementCategorie_id').val('');
                $('#financialMovementCatForm').attr('action', '{{ route("financial-movement-categories.store") }}');
                $('#financialMovementCatModalLabel').text('Nouvelle Catégorie de finance');
                $('#financialMovementCatModal').modal('show');
            });

            window.editFinancialMovementCategorie = function (id) {
                $.getJSON(`/financial-movement-categories/${id}`, function (data) {
                    $('#financialMovementCategorie_id').val(data.id);
                    $('#name').val(data.name);
                    $('#description').val(data.description);
                    $('#financialMovementCatForm').attr('action', `/financial-movement-categories/${id}`);
                    $('#financialMovementCatModalLabel').text('Modifier la  Catégorie de finance');
                    $('#financialMovementCatModal').modal('show');
                });
            };

            window.deleteFinancialMovementCategorie = function (id) {
                if (confirm('Voulez-vous supprimer cette  Catégorie de finance ?')) {
                    $.ajax({
                        url: `/financial-movement-categories/${id}`,
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function () {
                            table.ajax.reload();
                        },
                        error: function (xhr) {
                            alert(xhr.responseJSON?.message || 'Erreur lors de la suppression');
                        }
                    });
                }
            };

            $('#financialMovementCatForm').submit(function (e) {
                e.preventDefault();
                const form = $(this);
                const url = form.attr('action');
                const method = $('#financialMovementCategorie_id').val() ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    method: method,
                    data: form.serialize(),
                    success: function () {
                        $('#financialMovementCatModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON?.message || 'Erreur lors de l’enregistrement');
                    }
                });
            });
        });
    </script>
@endpush
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des contacts</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .badge-client {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-fournisseur {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-prestataire {
            background-color: #ede9fe;
            color: #5b21b6;
        }

        .badge-ouvrier {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-autre {
            background-color: #e5e7eb;
            color: #4b5563;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Notification -->
        <div id="notification" class="toast position-fixed top-0 end-0 mt-3 me-3" style="z-index: 9999">
            <div class="toast-header">
                <strong class="me-auto" id="toast-title">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toast-message"></div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Gestion des contacts</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contactModal"
                    onclick="resetForm()">
                    <i class="fas fa-plus me-2"></i>Ajouter un contact
                </button>
            </div>

            <div class="card-body">
                <table id="contactsTable" class="table table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nouveau contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="contactForm">
                    <div class="modal-body">
                        <input type="hidden" id="contactId" name="id">
                        @csrf
                        <div id="methodField"></div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" id="type" class="form-select" required>
                                @foreach(['client', 'fournisseur', 'prestataire', 'ouvrier', 'autre'] as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="address" name="address">
                        </div>

                        <div class="mb-3">
                            <label for="siret" class="form-label">SIRET</label>
                            <input type="text" class="form-control" id="siret" name="siret">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery, Bootstrap JS, DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialisation de DataTable
            var table = $('#contactsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('contacts.datatable') }}",
                columns: [
                    {
                        data: 'type',
                        render: function (data, type, row) {
                            return '<span class="badge badge-' + data + '">' +
                                data.charAt(0).toUpperCase() + data.slice(1) + '</span>';
                        }
                    },
                    { data: 'name' },
                    {
                        data: 'email',
                        render: function (data) {
                            return data || '-';
                        }
                    },
                    { data: 'phone' },
                    {
                        data: 'id',
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                        <button class="btn btn-sm btn-outline-primary me-2" onclick="editContact(${data})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteContact(${data})">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                        }
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
                }
            });

            // Initialisation des toast
            var toastEl = document.getElementById('notification');
            var toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 3000 });

            // Fonction pour afficher les notifications
            function showNotification(title, message, type = 'success') {
                $('#toast-title').text(title);
                $('#toast-message').text(message);
                $('.toast-header').removeClass('bg-success bg-danger bg-warning')
                    .addClass('bg-' + type);
                toast.show();
            }

            // Réinitialiser le formulaire
            window.resetForm = function () {
                $('#contactForm')[0].reset();
                $('#contactId').val('');
                $('#modalTitle').text('Nouveau contact');
                $('#methodField').html('');
                $('#contactForm').attr('action', "{{ route('contacts.store') }}");
            };

            // Éditer un contact
            window.editContact = function (id) {
                $.get("/contacts/" + id + "/edit", function (data) {
                    $('#contactId').val(data.id);
                    $('#type').val(data.type);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    $('#address').val(data.address);
                    $('#siret').val(data.siret);

                    $('#modalTitle').text('Modifier contact');
                    $('#methodField').html('<input type="hidden" name="_method" value="PUT">');
                    $('#contactForm').attr('action', '/contacts/' + id);

                    var modal = new bootstrap.Modal(document.getElementById('contactModal'));
                    modal.show();
                }).fail(function () {
                    showNotification('Erreur', 'Impossible de charger le contact', 'danger');
                });
            };

            // Supprimer un contact
            window.deleteContact = function (id) {
                if (confirm('Êtes-vous sûr de vouloir supprimer ce contact ?')) {
                    $.ajax({
                        url: '/contacts/' + id,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function () {
                            table.ajax.reload();
                            showNotification('Succès', 'Contact supprimé avec succès');
                        },
                        error: function (xhr) {
                            let message = 'Une erreur est survenue';

                            if (xhr.status === 404) {
                                message = 'Le contact n’existe plus.';
                            } else if (xhr.status === 403) {
                                message = 'Vous n’êtes pas autorisé à effectuer cette action.';
                            } else if (xhr.status === 500) {
                                message = 'Erreur serveur : ' + xhr.responseJSON?.message;
                            } else if (xhr.responseJSON?.message) {
                                message = xhr.responseJSON.message;
                            }

                            showNotification('Erreur', message, 'danger');
                        }
                    });
                }
            };

            // Gestion du formulaire
            $('#contactForm').submit(function (e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var method = form.find('input[name="_method"]').val() || 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: form.serialize(),
                    success: function (response) {
                        table.ajax.reload();
                        $('#contactModal').modal('hide');
                        showNotification('Succès', response.message);
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = '';
                            $.each(errors, function (key, value) {
                                errorMessage += value[0] + '\n';
                            });
                            showNotification('Erreur', errorMessage, 'danger');
                        } else {
                            showNotification('Erreur', 'Une erreur est survenue', 'danger');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>
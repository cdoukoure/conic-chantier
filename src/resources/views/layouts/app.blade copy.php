<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Conic Chantier')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap / DataTables / FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @stack('styles')
    @yield(section: 'styles')
</head>

<body>
    <!-- Notification -->
    <div id="notification" class="toast position-fixed top-0 end-0 mt-3 me-3" style="z-index: 9999">
        <div class="toast-header">
            <strong class="me-auto" id="toast-title">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message"></div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="{{ url('/') }}">Conic Chantier</a>
    </nav>

    <main class="container mt-4">
        @yield('content')
    </main>

    <footer class="text-center py-3 text-muted border-top mt-5">
        &copy; {{ date('Y') }} - Tous droits réservés.
    </footer>

    <!-- Scripts communs -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    @stack('scripts')
    @yield('scripts') {{-- Permet de rester compatible avec @section('scripts') --}}
</body>

</html>
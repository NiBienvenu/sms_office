<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
        <!-- Option 1: Include in HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    <style>
        .sidebar .nav-link span {
        font-size: 20px !important;
        }

        .sidebar .sidebar-brand-text {
            font-size: 20px !important;
        }
        .sidebar .nav-link i {
            font-size: 20px !important;
        }
        .nav-item .active {
            background-color: #a5adc5 !important;
            color: white !important;
        }

        .nav-item .active .nav-link {
            color: white !important;
        }
        #item-value {
            transition: opacity 0.3s ease, visibility 0s 0.3s !important;
        }

        .sidebar-collapsed #item-value {
            opacity: 0 !important;
            visibility: hidden !important;
        }

        /* Résultats de la recherche */
        #student_search_result {
            max-height: 300px;
            overflow-y: auto;
            position: absolute;
            width: 100%;
            z-index: 1000;
        }

        /* Lorsque l'utilisateur survole un élément */
        .search-item:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        /* Lorsque l'input est en erreur */
        .invalid-feedback {
            display: block;
        }




    </style>
</head>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        @include('layouts.sidebar')
        {{-- @dump(app()->getLocale()) --}}
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                @include('layouts.header')

                @yield('content')

            </div>
            <!-- End of Main Content -->

           @include('layouts.footer')

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    @include('components.logout-model')

    <script src="{{ asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js')}}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js')}}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>
</html>

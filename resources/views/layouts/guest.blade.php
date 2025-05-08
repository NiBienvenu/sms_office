<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Custom font for clock -->
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom fonts for this template-->
        <link href="{{ asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!-- Bootstrap Icons for the input fields -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="{{ asset('css/sb-admin-2.min.css')}}" rel="stylesheet">

        <style>
            body {
                background: linear-gradient(to right, #6a11cb, #2575fc) !important;
                position: relative;
                overflow: hidden;
            }

            /* Clock and Date Styling */
            .clock-container {
                position: fixed;
                /* top: 0%; */
                bottom: 0%;
                left: 87%;
                transform: translate(-50%, -50%);
                font-family: 'Orbitron', sans-serif;
                font-size: 80px;
                font-weight: bold;
                color: #fff; /* Semi-transparent for background effect */
                text-align: center;
                white-space: nowrap;
                z-index: 0;
                user-select: none;
                animation: fadeIn 2s ease-in-out;
            }

            .date {
                font-size: 40px;
                margin-top: -10px;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translate(-50%, -60%);
                }
                to {
                    opacity: 1;
                    transform: translate(-50%, -50%);
                }
            }

            .form-container {
                position: relative;
                z-index: 1; /* Ensure form is above the clock */
            }
        </style>
    </head>
    <body class="bg-gradient-primary">

        <!-- Clock & Date in Background -->
        <div class="clock-container">
            <div id="clock">00:00:00</div>
            <div id="date" class="date">Monday, 1 January 2024</div>
        </div>

        <!-- Page Content -->
        <div class="form-container">
            {{ $slot }}
        </div>

        <!-- Bootstrap core JavaScript -->
        <script src="{{ asset('vendor/jquery/jquery.min.js')}}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

        <!-- Core plugin JavaScript -->
        <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

        <!-- Custom scripts for all pages -->
        <script src="{{ asset('js/sb-admin-2.min.js')}}"></script>

        <!-- JavaScript to Update Time & Date -->
        <script>
            function updateClock() {
                const now = new Date();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const seconds = now.getSeconds().toString().padStart(2, '0');
                document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;

                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                document.getElementById('date').textContent = now.toLocaleDateString('en-US', options);
            }

            setInterval(updateClock, 1000);
            updateClock();
        </script>

    </body>
</html>

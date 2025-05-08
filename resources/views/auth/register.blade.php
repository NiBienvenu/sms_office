@extends('layouts.app')

@section('content')
<div class="bg-white shadow-sm">
    <form method="POST" action="{{ route('register') }}" class="p-6" enctype="multipart/form-data">
        @csrf

        <!-- Informations de base -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm text-gray-700 mb-2">Prénom: *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                       class="w-full h-10 px-3 border border-gray-300 focus:outline-none focus:border-blue-500">
                @error('first_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-2">Nom de famille: *</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                       class="w-full h-10 px-3 border border-gray-300 focus:outline-none focus:border-blue-500">
                @error('last_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-2">Téléphone:</label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                       class="w-full h-10 px-3 border border-gray-300 focus:outline-none focus:border-blue-500">
                @error('phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm text-gray-700 mb-2">Email: *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full h-10 px-3 border border-gray-300 focus:outline-none focus:border-blue-500">
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-2">Genre:</label>
                <select name="gender" class="w-full h-10 px-3 border border-gray-300 focus:outline-none focus:border-blue-500">
                    <option value="">Sélectionner</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculin</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Féminin</option>
                </select>
                @error('gender')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-2">Date de naissance:</label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                       class="w-full h-10 px-3 border border-gray-300 focus:outline-none focus:border-blue-500">
                @error('birth_date')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm text-gray-700 mb-2">Pays:</label>
                <input type="text" name="country" value="{{ old('country') }}"
                       class="w-full h-10 px-3 border border-gray-300 focus:outline-none focus:border-blue-500">
                @error('country')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-2">Ville:</label>
                <input type="text" name="city" value="{{ old('city') }}"
                       class="w-full h-10 px-3 border border-gray-300 focus:outline-none focus:border-blue-500">
                @error('city')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-2">Adresse:</label>
                <input type="text" name="address" value="{{ old('address') }}"
                       class="w-full h-10 px-3 border border-gray-300 focus:outline-none focus:border-blue-500">
                @error('address')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Photo et PIN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm text-gray-700 mb-2">Photo de profil:</label>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded cursor-pointer hover:bg-blue-100">
                        <span>Choose File</span>
                        <input type="file" name="photo" class="hidden" accept="image/*"
                               onchange="document.getElementById('preview').src = window.URL.createObjectURL(this.files[0])">
                    </label>
                    <img id="preview" src="/placeholder.jpg" alt="Preview" class="w-16 h-16 object-cover">
                </div>
                @error('photo')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-2">Code PIN:</label>
                <div class="flex items-center space-x-4">
                    <input type="text" name="pin_code" id="pin_code" value="{{ old('pin_code') }}" disabled
                           class="w-full h-10 px-3 border border-gray-300 focus:outline-none focus:border-blue-500">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="activate_pin" name="activate_pin" {{ old('activate_pin') ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300">
                        <span class="ml-2 text-sm text-gray-600">Activer le code PIN</span>
                    </label>
                </div>
                @error('pin_code')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Mot de passe -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm text-gray-700 mb-2">Mot de passe: *</label>
                <input type="password" name="password" value="{{ old('password') }}" required
                       class="w-full h-10 px-3 border border-gray-300 focus:outline-none focus:border-blue-500">
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-2">Confirmez le mot de passe: *</label>
                <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" required
                       class="w-full h-10 px-3 border border-gray-300 focus:outline-none focus:border-blue-500">
                @error('password_confirmation')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white hover:bg-blue-700 focus:outline-none">
                Enregistrer
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pinCheckbox = document.getElementById('activate_pin');
    const pinInput = document.getElementById('pin_code');

    pinCheckbox.addEventListener('change', function() {
        pinInput.disabled = !this.checked;
        if (this.checked) {
            pinInput.focus();
        }
    });
});
</script>

<style>
input, select, button {
    border-radius: 0 !important;
}

.h-10 {
    height: 2.5rem;
}
</style>
@endsection

{{-- <!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                            placeholder="First Name">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" id="exampleLastName"
                                            placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Email Address">
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            id="exampleRepeatPassword" placeholder="Repeat Password">
                                    </div>
                                </div>
                                <a href="{{ route('login')}}" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </a>
                                <hr>
                                {{-- <a href="{}" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                    <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                </a> --}}
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ route('forgot-password')}}">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="{{ route('login')}}">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html> --}}


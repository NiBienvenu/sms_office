<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden" style="max-width: 900px; width: 100%;">
            <div class="row g-0">

                <!-- Image Section -->
                <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center bg-light">
                    <img class="img-fluid p-4" src="{{ asset('logo/logo.png') }}" alt="Logo" style="max-width: 70%;">
                </div>

                <!-- Login Form Section -->
                <div class="col-lg-6 p-5 bg-white">
                    <div class="text-center mb-4">
                        <h1 class="h4 fw-bold text-primary">Welcome Back!</h1>
                        <p class="text-muted">Login to your account</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope-fill text-primary"></i> Email Address
                            </label>
                            <x-text-input id="email" class="form-control rounded-md" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="text-danger small mt-1" />
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock-fill text-primary"></i> Password
                            </label>
                            <x-text-input id="password" class="form-control rounded-md" type="password" name="password" required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="text-danger small mt-1" />
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex justify-content-between align-items-center m-3">
                            <div class="ml-2">
                                <input id="remember_me" type="checkbox" class="form-check-input me-2" name="remember">
                                <label for="remember_me" class="form-check-label text-muted">Remember me</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-decoration-none text-primary small">Forgot Password?</a>
                        </div>

                        <!-- Login Button -->
                        <div class="d-grid">
                            <button class="btn btn-primary rounded-md fw-bold w-100">Log in</button>
                        </div>
                    </form>

                    {{-- <!-- Register Link -->
                    <div class="text-center mt-4">
                        <p class="small text-muted">Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-bold">Sign up</a></p>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->


</x-guest-layout>

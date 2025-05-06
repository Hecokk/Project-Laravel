<x-guest-layout>
    <!-- Login Section -->
    <section class="py-5">
        <div class="container login-container" style="max-width: 450px; margin: 0 auto;">
            <div class="card login-card" style="border: none; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); border-radius: 12px; overflow: hidden;">
                <div class="login-header text-white" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); padding: 30px; text-align: center;">
                    <h3 class="mb-1">Selamat Datang Kembali</h3>
                    <p class="mb-0 opacity-75">Masuk ke akun Anda untuk melanjutkan</p>
                </div>
                <div class="login-form" style="padding: 40px;">

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4 alert alert-success" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-floating" style="margin-bottom: 20px;">
                            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@contoh.com">
                            <label for="email">Alamat Email</label>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 invalid-feedback" />
                        </div>

                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" placeholder="Kata Sandi">
                            <label for="password">Kata Sandi</label>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 invalid-feedback" />
                        </div>

                        <!-- Remember Me -->
                        <div class="d-flex justify-content-between mb-4">
                            <div class="form-check">
                                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                <label for="remember_me" class="form-check-label">
                                    {{ __('Ingat saya') }}
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                            <a class="text-decoration-none" href="{{ route('password.request') }}">
                                {{ __('Lupa kata sandi?') }}
                            </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mb-4">
                            {{ __('Masuk') }}
                        </button>

                        {{-- Optional: Social Login Divider and Buttons (Keep or remove) --}}
                        {{-- <div class="divider" style="display: flex; align-items: center; margin: 30px 0;">
                            <span style="padding: 0 10px; color: var(--text-muted); font-size: 0.9rem;">atau masuk dengan</span>
                        </div>
                        <div class="social-login">
                            <button class="btn btn-outline-dark w-100" style="border-radius: 50px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; padding: 12px; font-weight: 500;">
                                <i class="fab fa-google" style="margin-right: 10px; font-size: 1.1rem;"></i> Google
                            </button>
                        </div> --}}

                        <div class="text-center mt-4">
                            <p class="mb-0">Belum memiliki akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
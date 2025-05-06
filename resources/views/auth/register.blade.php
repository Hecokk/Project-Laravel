<x-guest-layout>
    <!-- Register Section -->
    <section class="py-5">
        <div class="container register-container" style="max-width: 550px; margin: 0 auto;">
            <div class="card register-card"
                style="border: none; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); border-radius: 12px; overflow: hidden;">
                <div class="register-header text-white"
                    style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); padding: 30px; text-align: center;">
                    <h3 class="mb-1">Buat Akun Baru</h3>
                    <p class="mb-0 opacity-75">Temukan dan beri penilaian untuk buku favorit Anda</p>
                </div>
                <div class="register-form" style="padding: 40px;">
                    {{-- Optional: Social Register --}}

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- First Name -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating" style="margin-bottom: 20px;">
                                    <input id="first_name" class="form-control @error('first_name') is-invalid @enderror" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus autocomplete="given-name" placeholder="Nama Depan">
                                    <label for="first_name">Nama Depan</label>
                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2 invalid-feedback" />
                                </div>
                            </div>
                            <!-- Last Name -->
                            <div class="col-md-6">
                                <div class="form-floating" style="margin-bottom: 20px;">
                                    <input id="last_name" class="form-control @error('last_name') is-invalid @enderror" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name" placeholder="Nama Belakang">
                                    <label for="last_name">Nama Belakang</label>
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2 invalid-feedback" />
                                </div>
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="form-floating" style="margin-bottom: 20px;">
                            <input id="email" class="form-control @error('email') is-invalid @enderror" type="email"
                                name="email" value="{{ old('email') }}" required autocomplete="username"
                                placeholder="nama@contoh.com">
                            <label for="email">Alamat Email</label>
                            <x-input-error :messages="$errors->get('email')" class="mt-2 invalid-feedback" />
                        </div>

                        <!-- Password -->
                        <div class="form-floating" style="margin-bottom: 20px;">
                            <input id="password" class="form-control @error('password') is-invalid @enderror"
                                type="password" name="password" required autocomplete="new-password"
                                placeholder="Kata Sandi">
                            <label for="password">Kata Sandi</label>
                            <x-input-error :messages="$errors->get('password')" class="mt-2 invalid-feedback" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-floating mb-3">
                            <input id="password_confirmation" class="form-control" type="password"
                                name="password_confirmation" required autocomplete="new-password"
                                placeholder="Konfirmasi Kata Sandi">
                            <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                            <x-input-error :messages="$errors->get('password_confirmation')"
                                class="mt-2 invalid-feedback" />
                        </div>

                        {{-- Optional: Terms Checkbox --}}

                        <div class="d-flex align-items-center justify-content-end mt-4">
                            <a class="text-decoration-none me-3" href="{{ route('login') }}">
                                {{ __('Sudah terdaftar?') }}
                            </a>

                            <button type="submit" class="btn btn-primary py-2">
                                {{ __('Daftar') }}
                            </button>
                        </div>
                    </form>
                </div> {{-- Close register-form --}}
            </div> {{-- Close register-card --}}
        </div> {{-- Close container --}}
    </section>
</x-guest-layout>
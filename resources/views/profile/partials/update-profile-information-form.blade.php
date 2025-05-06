<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <p class="text-muted mb-4">
        {{ __("Perbarui informasi profil dan alamat email akun Anda.") }}
    </p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="row mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
                <label for="first_name" class="form-label">{{ __('Nama Depan') }}</label>
                <input id="first_name" name="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name) }}" required autofocus>
                @error('first_name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="last_name" class="form-label">{{ __('Nama Belakang') }}</label>
                <input id="last_name" name="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name) }}" required>
                @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="alert alert-warning mt-2">
                <p class="mb-1">
                    {{ __('Alamat email Anda belum diverifikasi.') }}
                </p>
                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link p-0">
                        {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                    </button>
                </form>

                @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success mt-2">
                    {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                </div>
                @endif
            </div>
            @endif
        </div>

        <div class="d-flex align-items-center mt-4">
            <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>

            @if (session('status') === 'profile-updated')
            <div class="alert alert-success d-inline-block ms-3 mb-0 py-2">
                {{ __('Tersimpan.') }}
            </div>
            @endif
        </div>
    </form>
</section>
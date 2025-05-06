@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-placeholder bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                        </div>
                        <h5 class="mb-0">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
                        <p class="text-muted small">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-user me-2"></i> Profil Saya
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-book me-2"></i> Koleksi Buku
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-star me-2"></i> Ulasan Saya
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-list me-2"></i> Daftar Bacaan
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-sign-out-alt me-2"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Informasi Profil -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Informasi Profil</h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Perbarui Password -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Perbarui Password</h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Hapus Akun -->
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-white text-danger">
                    <h5 class="card-title mb-0">Hapus Akun</h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-placeholder {
        font-weight: bold;
    }
</style>
@endpush
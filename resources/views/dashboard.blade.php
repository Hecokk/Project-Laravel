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
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
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
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0">Selamat Datang di BookTrackr!</h4>
                </div>
                <div class="card-body">
                    <p class="card-text">Anda berhasil login ke platform BookTrackr. Mulai lacak buku bacaan Anda, buat daftar bacaan, dan bagikan ulasan dengan komunitas pecinta buku.</p>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> Anda telah berhasil login ke akun Anda.
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-book fa-3x text-primary"></i>
                            </div>
                            <h5 class="fw-bold">0</h5>
                            <p class="text-muted mb-0">Buku Dibaca</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-star fa-3x text-warning"></i>
                            </div>
                            <h5 class="fw-bold">0</h5>
                            <p class="text-muted mb-0">Ulasan Ditulis</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-list fa-3x text-success"></i>
                            </div>
                            <h5 class="fw-bold">0</h5>
                            <p class="text-muted mb-0">Daftar Bacaan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <p class="mb-0">Anda belum memiliki aktivitas terbaru.</p>
                        <a href="#" class="btn btn-primary mt-3">Jelajahi Buku</a>
                    </div>
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
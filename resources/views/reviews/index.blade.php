@extends('layouts.guest') {{-- Atau layout lain jika ini hanya untuk user login --}}

@section('content') {{-- Atau nama section lain di layout Anda --}}
<!-- Main Content -->
<main class="container py-5">
    <h2 class="text-center mb-4 section-title">Ulasan Buku</h2>

    <div class="row g-4">
        {{-- Data ulasan statis dari reviews.html sebagai placeholder --}}
        <!-- Review Card 1 (Using existing style within Bootstrap grid) -->
        <div class="col-lg-6">
            <div class="review-card h-100">
                <div class="book-info">
                    <img src="https://via.placeholder.com/150x200" alt="Book Cover" class="mb-3">
                    <h3>Judul Buku 1</h3>
                    <p class="author">Penulis: John Doe</p>
                </div>
                <div class="review-content">
                    <div class="rating mb-2">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p class="review-text">Ulasan yang sangat bagus tentang buku ini. Sangat direkomendasikan untuk dibaca!</p>
                    <p class="reviewer">- Reviewer Name</p>
                </div>
            </div>
        </div>

        <!-- Review Card 2 (Using existing style within Bootstrap grid) -->
        <div class="col-lg-6">
            <div class="review-card h-100">
                <div class="book-info">
                    <img src="https://via.placeholder.com/150x200" alt="Book Cover" class="mb-3">
                    <h3>Judul Buku 2</h3>
                    <p class="author">Penulis: Jane Smith</p>
                </div>
                <div class="review-content">
                    <div class="rating mb-2">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                    <p class="review-text">Buku yang menarik dengan plot yang menegangkan. Karakter-karakternya sangat hidup.</p>
                    <p class="reviewer">- Reviewer Name</p>
                </div>
            </div>
        </div>

        {{-- Nanti akan diganti dengan loop @foreach data dinamis --}}

    </div>
</main>
@endsection
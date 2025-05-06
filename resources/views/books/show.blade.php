@extends('layouts.guest') {{-- Atau layout lain jika diperlukan --}}

@section('content')
<!-- Book Detail -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Book Cover & Info (Kolom Kiri) -->
            <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
                {{-- Menggunakan contain, width auto, dan bg-light --}}
                <div class="position-relative bg-light text-center rounded shadow-sm mb-4"> {{-- Pindahkan class ke div --}}
                    <img src="{{ $book->cover_image_path ?? 'https://placehold.co/600x900/3d405b/FFFFFF?text=No+Cover' }}"
                        class="book-cover img-fluid" {{-- Hapus class styling dari img --}}
                        style="height: 350px; width: auto; object-fit: contain;" {{-- Ganti cover -> contain, width auto --}}
                        alt="{{ $book->title }} Book Cover">
                </div>

                {{-- Tombol Aksi (Tambahkan logika @auth jika perlu) --}}
                <div class="d-grid gap-2 mb-4">
                    <button class="btn btn-primary status-btn"><i class="fas fa-book-open me-2"></i> Want to Read</button>
                    <button class="btn btn-outline-primary status-btn"><i class="fas fa-star me-2"></i> Rate This Book</button>
                    <button class="btn btn-outline-secondary status-btn"><i class="fas fa-list me-2"></i> Add to List</button>
                </div>

                {{-- Statistik (Nanti bisa diisi data agregat) --}}
                <div class="book-stats mb-4">
                    <div class="row text-center">
                        <div class="col-4">
                            <h3 class="mb-0">{{-- {{ $book->average_rating ?? 'N/A' }} --}} 4.8</h3>
                            <p class="small mb-0 text-muted">Ratings</p>
                        </div>
                        <div class="col-4">
                            <h3 class="mb-0">{{-- {{ $book->reviews_count ?? 0 }} --}} 842</h3>
                            <p class="small mb-0 text-muted">Reviews</p>
                        </div>
                        <div class="col-4">
                            <h3 class="mb-0">{{-- {{ $book->reading_count ?? 0 }} --}} 25K</h3>
                            <p class="small mb-0 text-muted">Reading</p>
                        </div>
                    </div>
                </div>

                {{-- Peringkat Lengkap (Nanti bisa diisi data agregat) --}}
                {{-- <div class="mb-4">
                    <h5 class="mb-3">Full Ratings</h5>
                    {{-- Tampilkan detail rating jika ada datanya --}}
                {{-- </div> --}}
            </div>

            <!-- Book Details (Kolom Kanan) -->
            <div class="col-lg-9 col-md-8">
                {{-- Breadcrumb (Sesuaikan dengan struktur kategori/genre Anda) --}}
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"
                                class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Books</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
                    </ol>
                </nav>

                {{-- Judul dan Penulis --}}
                <h1 class="book-title mb-2">{{ $book->title }}</h1>
                <p class="book-author mb-3">by <a href="#"
                        class="text-decoration-none">{{ $book->author->name ?? 'Unknown Author' }}</a></p>

                {{-- Tampilkan pesan sukses jika ada --}}
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- Rating Singkat --}}
                <div class="d-flex align-items-center mb-4">
                    <span class="book-rating me-2">{{-- {{ number_format($book->average_rating, 1) ?? 'N/A' }} --}}
                        4.8</span>
                    {{-- Tampilkan bintang rating --}}
                    <span class="text-muted ms-2">({{-- {{ $book->ratings_count ?? 0 }} --}} ratings)</span>
                </div>

                {{-- Deskripsi Buku --}}
                <div class="book-description mb-4">
                    <p>{!! clean($book->description ?? 'No description available.') !!}</p>
                </div>

                {{-- Meta Buku --}}
                <div class="book-meta text-muted mb-4">
                    <p class="mb-1">Genre: <a href="#"
                            class="text-decoration-none">{{ $book->genre->name ?? 'N/A' }}</a></p>
                    <p class="mb-1">Publication Year: {{ $book->publication_year ?? 'N/A' }}</p>
                    {{-- Tambahkan meta lain jika ada (ISBN, Halaman, dll.) --}}
                </div>

                {{-- Tag/Genre (Jika ada relasi ManyToMany) --}}
                {{-- <div class="mb-4">
                    {{-- @foreach ($book->tags as $tag)
                             <a href="#" class="book-tag">{{ $tag->name }}</a>
                @endforeach --}}
                {{-- </div> --}}

                <hr class="my-4">

                {{-- Bagian Ulasan --}}
                <h4 class="mb-4">Community Reviews</h4>

                {{-- Form Tambah Ulasan (Hanya untuk user login) --}}
                @auth
                <div class="card review-card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Write Your Review</h5>
                        <form method="POST" action="{{ route('reviews.store', $book) }}">
                            @csrf
                            {{-- Rating Input --}}
                            <div class="mb-3">
                                <label for="rating" class="form-label">Your Rating:</label>
                                <div>
                                    {{-- Implementasi input rating bintang (misal: radio button atau JS library) --}}
                                    @for ($i = 1; $i <= 5; $i++) <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="rating" id="rating{{ $i }}"
                                            value="{{ $i }}" required>
                                        <label class="form-check-label" for="rating{{ $i }}">{{ $i }}</label>
                                </div>
                                @endfor
                            </div>
                            <x-input-error :messages="$errors->get('rating')" class="mt-2 invalid-feedback d-block" />
                            {{-- d-block agar tampil --}}
                    </div>

                    {{-- Trix Editor untuk Konten --}}
                    <div class="mb-3">
                        <label for="content" class="form-label">Your Review:</label>
                        <input id="content" type="hidden" name="content" value="{{ old('content') }}">
                        <trix-editor input="content" class="form-control @error('content') is-invalid @enderror"
                            style="min-height: 150px;"></trix-editor>
                        <x-input-error :messages="$errors->get('content')" class="mt-2 invalid-feedback d-block" />
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
            </div>
            @else
            <p><a href="{{ route('login') }}">Login</a> or <a href="{{ route('register') }}">register</a> to write
                a review.</p>
            @endauth

            {{-- Daftar Ulasan Dinamis --}}
            @forelse ($book->reviews as $review)
            <div class="card review-card mb-3"> {{-- Tambah margin bawah --}}
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        {{-- Ganti dengan avatar user jika ada --}}
                        <img src="https://placehold.co/48x48/ced4da/6c757d?text={{ strtoupper(substr($review->user->first_name, 0, 1)) }}"
                            class="review-avatar me-3" alt="{{ $review->user->first_name }}">
                        <div>
                            <h6 class="mb-0">{{ $review->user->first_name }} {{ $review->user->last_name }}</h6>
                            <small class="review-date text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="ms-auto">
                            {{-- Tampilkan bintang rating ulasan --}}
                            @for ($i = 0; $i < 5; $i++) <i
                                class="fas fa-star {{ $i < $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-1">{{ $review->rating }}</span>
                        </div>
                    </div>
                    <div class="review-text">
                        {{-- Tampilkan konten setelah disanitasi --}}
                        {!! clean($review->content) !!}
                    </div>
                </div>
            </div>
            @empty
            <p>No reviews for this book yet.</p>
            @endforelse

            {{-- TODO: Tambahkan pagination links jika menggunakan paginate() di controller --}}
            {{-- {{ $reviews->links() }} --}}

        </div>
    </div>
    </div>
</section>
@endsection

@push('styles')
{{-- Trix Editor CSS --}}
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
    /* Tambahkan CSS spesifik book-detail jika ada yang belum tercover style.css */
    .book-cover {
        /* ... */
    }

    /* ... */
    trix-toolbar [data-trix-button-group="file-tools"] {
        display: none;
        /* Sembunyikan tombol upload file Trix */
    }
</style>
@endpush

@push('scripts')
{{-- Trix Editor JS --}}
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpush
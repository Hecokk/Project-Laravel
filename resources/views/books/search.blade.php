@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Filter Pencarian</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('books.search') }}" method="GET">
                        <div class="mb-3">
                            <label for="query" class="form-label">Kata Kunci</label>
                            <input type="text" class="form-control" id="query" name="query" value="{{ $query ?? '' }}" placeholder="Judul atau penulis...">
                        </div>

                        <div class="mb-3">
                            <label for="genre_id" class="form-label">Genre</label>
                            <select class="form-select" id="genre_id" name="genre_id">
                                <option value="">Semua Genre</option>
                                @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}" {{ (isset($genre_id) && $genre_id == $genre->id) ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="include_google" value="1">

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    @if (!empty($query) || !empty($genre_id))
                    Hasil Pencarian
                    @if (!empty($query))
                    untuk "{{ $query }}"
                    @endif
                    @if (!empty($genre_id))
                    @if (!empty($query))
                    di
                    @else
                    untuk
                    @endif
                    genre {{ $genres->firstWhere('id', $genre_id)->name ?? '' }}
                    @endif
                    @else
                    Rekomendasi Buku
                    @endif
                </h2>
                <div class="text-muted">
                    @if (!empty($query) || !empty($genre_id))
                    {{ $books->total() }} hasil ditemukan
                    @else
                    Temukan buku favorit Anda
                    @endif
                </div>
            </div>

            @if ($books->isEmpty() && empty($googleBooks))
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Tidak ada buku yang ditemukan untuk pencarian ini. Coba kata kunci lain atau ubah filter Anda.
            </div>
            @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                {{-- Hasil dari database lokal --}}
                @foreach ($books as $book)
                <div class="col">
                    <div class="card h-100 book-card shadow-sm">
                        <div class="position-relative bg-light text-center">
                            <img src="{{ $book->cover_image_path ?? 'https://placehold.co/600x900/3d405b/FFFFFF?text=No+Cover' }}"
                                class="card-img-top" alt="{{ $book->title }} Cover"
                                style="height: 220px; width: auto; object-fit: contain;">

                            @if ($book->publication_year >= date('Y'))
                            <span class="badge bg-warning position-absolute top-0 end-0 mt-2 me-2">
                                Akan Terbit
                            </span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title book-title">{{ $book->title }}</h5>
                            <p class="card-text book-author text-muted mb-1">oleh {{ $book->author->name ?? 'Unknown' }}</p>
                            <p class="card-text mb-2"><small class="text-muted">{{ $book->genre->name ?? 'Uncategorized' }}</small></p>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-star text-warning"></i>
                                    <span>4.5</span> {{-- Nanti bisa diganti dengan rata-rata rating asli --}}
                                </div>
                                <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Hasil dari Google Books API --}}
                @foreach ($googleBooks as $book)
                @php
                $volumeInfo = $book['volumeInfo'] ?? [];
                $id = $book['id'] ?? '';
                $title = $volumeInfo['title'] ?? 'Untitled';
                $authors = isset($volumeInfo['authors']) ? implode(', ', $volumeInfo['authors']) : 'Unknown';
                $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? null;
                $description = $volumeInfo['description'] ?? 'Tidak ada deskripsi.';
                if (strlen($description) > 150) {
                $description = substr($description, 0, 150) . '...';
                }
                @endphp
                <div class="col">
                    <div class="card h-100 book-card shadow-sm d-flex flex-column">
                        <div class="position-relative bg-light text-center">
                            <img src="{{ $thumbnail ?? 'https://placehold.co/600x900/3d405b/FFFFFF?text=No+Cover' }}"
                                class="card-img-top" alt="{{ $title }} Cover"
                                style="height: 220px; width: auto; object-fit: contain;">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div>
                                <h5 class="card-title book-title">{{ $title }}</h5>
                                <p class="card-text book-author text-muted mb-1">oleh {{ $authors }}</p>
                                <p class="card-text small text-muted mt-2">{{ $description }}</p>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <a href="{{ route('google-books.show', $id) }}" class="btn btn-sm btn-primary">Detail</a>

                                @auth
                                <form action="{{ route('google-books.import', $id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-plus-circle me-1"></i> Impor
                                    </button>
                                </form>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                @if (!$books->isEmpty())
                {{ $books->links() }}
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .book-card {
        transition: transform 0.2s;
    }

    .book-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
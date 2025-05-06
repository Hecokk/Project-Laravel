@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <!-- Tabs for switching between local and Google Books search -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('books.search', ['query' => $query ?? '']) }}">Pencarian Database Lokal</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('google-books.search', request()->query()) }}">Pencarian Google Books</a>
        </li>
    </ul>

    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">Pencarian Google Books</h2>
            <p class="text-muted">Temukan buku dari seluruh dunia menggunakan Google Books API</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('google-books.search') }}" method="GET" class="d-flex">
                        <input type="text" name="query" class="form-control form-control-lg me-2"
                            placeholder="Cari judul buku, penulis, ISBN..."
                            value="{{ $query ?? '' }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                    </form>

                    <div class="mt-3">
                        <div class="accordion" id="advancedSearchAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#advancedSearch" aria-expanded="false" aria-controls="advancedSearch">
                                        Pencarian Lanjutan
                                    </button>
                                </h2>
                                <div id="advancedSearch" class="accordion-collapse collapse" data-bs-parent="#advancedSearchAccordion">
                                    <div class="accordion-body">
                                        <form action="{{ route('google-books.search') }}" method="GET">
                                            <input type="hidden" name="query" value="{{ $query ?? '' }}">

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="max_results" class="form-label">Jumlah Hasil</label>
                                                    <select name="max_results" id="max_results" class="form-select">
                                                        <option value="10" {{ request('max_results') == 10 ? 'selected' : '' }}>10</option>
                                                        <option value="20" {{ request('max_results') == 20 ? 'selected' : '' }}>20</option>
                                                        <option value="30" {{ request('max_results') == 30 ? 'selected' : '' }}>30</option>
                                                        <option value="40" {{ request('max_results') == 40 ? 'selected' : '' }}>40</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="order_by" class="form-label">Urutkan Berdasarkan</label>
                                                    <select name="order_by" id="order_by" class="form-select">
                                                        <option value="relevance" {{ request('order_by') == 'relevance' ? 'selected' : '' }}>Relevansi</option>
                                                        <option value="newest" {{ request('order_by') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="lang" class="form-label">Bahasa</label>
                                                    <select name="lang" id="lang" class="form-select">
                                                        <option value="">Semua Bahasa</option>
                                                        <option value="id" {{ request('lang') == 'id' ? 'selected' : '' }}>Indonesia</option>
                                                        <option value="en" {{ request('lang') == 'en' ? 'selected' : '' }}>Inggris</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-12 mt-3">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-filter me-1"></i> Terapkan Filter
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($query && empty($books))
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i> Tidak ada hasil yang ditemukan untuk pencarian "{{ $query }}".
        <hr>
        <p>Coba cari di <a href="{{ route('books.search', ['query' => $query ?? '']) }}" class="alert-link">Database Lokal</a> untuk melihat koleksi buku kami.</p>
    </div>
    @endif

    @if (!empty($books))
    <div class="mb-3">
        <p>{{ $totalItems }} hasil ditemukan untuk pencarian "{{ $query }}"</p>
    </div>

    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($books as $book)
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
            <div class="card h-100 book-card shadow-sm">
                <div class="position-relative">
                    <img src="{{ $thumbnail ?? 'https://placehold.co/600x900/3d405b/FFFFFF?text=No+Cover' }}"
                        class="card-img-top" alt="{{ $title }} Cover"
                        style="height: 220px; object-fit: cover;">
                </div>
                <div class="card-body">
                    <h5 class="card-title book-title">{{ $title }}</h5>
                    <p class="card-text book-author text-muted mb-1">oleh {{ $authors }}</p>

                    <p class="card-text small text-muted mt-2">{{ $description }}</p>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="{{ route('google-books.show', $id) }}" class="btn btn-sm btn-primary">Detail</a>

                        @auth
                        <form action="{{ route('google-books.import', $id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-plus-circle me-1"></i> Tambahkan
                            </button>
                        </form>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-between align-items-center">
        @if (request('start_index', 0) > 0)
        <a href="{{ route('google-books.search', [
                    'query' => $query,
                    'start_index' => max(0, request('start_index', 0) - request('max_results', 10)),
                    'max_results' => request('max_results', 10),
                    'order_by' => request('order_by', 'relevance'),
                    'lang' => request('lang'),
                ]) }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i> Sebelumnya
        </a>
        @else
        <div></div>
        @endif

        @if (count($books) == request('max_results', 10) && $totalItems > (request('start_index', 0) + request('max_results', 10)))
        <a href="{{ route('google-books.search', [
                    'query' => $query,
                    'start_index' => request('start_index', 0) + request('max_results', 10),
                    'max_results' => request('max_results', 10),
                    'order_by' => request('order_by', 'relevance'),
                    'lang' => request('lang'),
                ]) }}" class="btn btn-outline-primary">
            Selanjutnya <i class="fas fa-arrow-right ms-1"></i>
        </a>
        @else
        <div></div>
        @endif
    </div>
    @endif
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

    .book-title {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush
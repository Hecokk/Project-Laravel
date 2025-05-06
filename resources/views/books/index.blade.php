@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">Browse Books</h2>
            <p class="text-muted">Explore popular and trending books</p>
        </div>
        <div class="col-md-4">
            <form action="{{ route('books.search') }}" method="GET" class="d-flex">
                <input type="text" name="query" class="form-control me-2" placeholder="Search books...">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    @if (empty($googleBooks))
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i> Could not retrieve books at this time.
    </div>
    @else
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-4">
        @foreach ($googleBooks as $googleBook)
        @php
        $volumeInfo = $googleBook['volumeInfo'] ?? [];
        $id = $googleBook['id'] ?? '';
        $title = Str::limit($volumeInfo['title'] ?? 'Untitled', 50);
        $authors = isset($volumeInfo['authors']) ? implode(', ', $volumeInfo['authors']) : 'Unknown';
        $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? 'https://placehold.co/600x900/3d405b/FFFFFF?text=No+Cover';
        @endphp
        <div class="col">
            <div class="card h-100 book-card shadow-sm">
                <div class="position-relative bg-light text-center">
                    <a href="{{ route('google-books.show', $id) }}">
                        <img src="{{ $thumbnail }}"
                            class="card-img-top" alt="{{ $title }} Cover"
                            style="height: 220px; width: auto; object-fit: contain;">
                    </a>
                </div>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title book-title mt-2 flex-grow-1">
                        <a href="{{ route('google-books.show', $id) }}" class="text-dark text-decoration-none">{{ $title }}</a>
                    </h6>
                    <p class="card-text book-author small text-muted mb-2">by {{ Str::limit($authors, 30) }}</p>

                    <div class="mt-auto">
                        <a href="{{ route('google-books.show', $id) }}" class="btn btn-sm btn-outline-primary w-100">Details</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
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
        /* Hapus style ini jika ingin judul wrap */
        /* overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap; */
    }
</style>
@endpush
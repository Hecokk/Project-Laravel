@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">My Books</h2>
            <p class="text-muted">Manage your book collection and reading status.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="#" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Add Book
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">All Books</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Read</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Currently Reading</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Want to Read</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if ($books->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i> You haven't added any books to your collection yet.
    </div>
    @else
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($books as $book)
        <div class="col">
            <div class="card h-100 book-card shadow-sm">
                <div class="position-relative">
                    <div class="position-relative bg-light text-center">
                        <img src="{{ $book->cover_image_path ?? 'https://placehold.co/600x900/3d405b/FFFFFF?text=No+Cover' }}"
                            class="card-img-top" alt="{{ $book->title }} Cover"
                            style="height: 220px; width: auto; object-fit: contain;">
                    </div>

                    <div class="position-absolute top-0 end-0 mt-2 me-2">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-check me-2"></i> Mark as Read</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-book-open me-2"></i> Mark as Reading</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-list me-2"></i> Add to List</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i> Remove from Collection</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title book-title">{{ $book->title }}</h5>
                    <p class="card-text book-author text-muted mb-1">by {{ $book->author->name ?? 'Unknown' }}</p>
                    <p class="card-text mb-2"><small class="text-muted">{{ $book->genre->name ?? 'Uncategorized' }}</small></p>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <i class="fas fa-star text-warning"></i>
                            <span>4.5</span> {{-- Nanti bisa diganti dengan rata-rata rating asli --}}
                        </div>
                        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary">Details</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $books->links() }}
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
</style>
@endpush
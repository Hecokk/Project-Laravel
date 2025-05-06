@extends('layouts.guest') {{-- Ganti ke layout lain jika perlu, misal layouts.app --}}

@section('content')
<div class="container py-5">

    {{-- Bagian Atas: Statistik & Sambutan (jika perlu) --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-3">Welcome back, {{ $user->first_name ?? 'User' }}!</h2>
            {{-- Bisa tambahkan elemen lain di sini --}}
        </div>
        <div class="col-md-4">
            {{-- Profile Stats --}}
            <div class="card bg-light shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center mb-3">Profile Stats</h5>
                    <div class="row text-center">
                        <div class="col-6 mb-2">
                            <span class="fs-4 fw-bold d-block">{{ $userStats['read'] }}</span>
                            <span class="text-muted small">Books Read</span>
                        </div>
                        <div class="col-6 mb-2">
                            <span class="fs-4 fw-bold d-block">{{ $userStats['reading'] }}</span>
                            <span class="text-muted small">Currently Reading</span>
                        </div>
                        <div class="col-6 mb-2">
                            <span class="fs-4 fw-bold d-block">{{ $userStats['reviews'] }}</span>
                            <span class="text-muted small">Reviews Written</span>
                        </div>
                        <div class="col-6 mb-2">
                            <span class="fs-4 fw-bold d-block">{{ $userStats['want_to_read'] }}</span>
                            <span class="text-muted small">Want to Read</span>
                        </div>
                    </div>
                    {{-- <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-secondary w-100 mt-2">View Profile</a> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Recently Trending Books --}}
    @if ($trendingBooks->isNotEmpty())
    <div class="mb-5">
        <h4 class="mb-3">Recently Trending</h4>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
            @foreach ($trendingBooks as $book)
            <div class="col">
                <div class="book-card-sm">
                    <a href="{{ route('books.show', $book) }}">
                        <div class="position-relative bg-light text-center rounded shadow-sm">
                            <img src="{{ $book->cover_image_path ?? 'https://placehold.co/200x300/3d405b/FFFFFF?text=No+Cover' }}"
                                class="img-fluid"
                                style="height: 150px; width: auto; object-fit: contain;"
                                alt="{{ $book->title }} Cover">
                        </div>
                    </a>
                    {{-- <h6 class="mt-2 small book-title">{{ $book->title }}</h6> --}}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Popular Reviews --}}
    @if ($popularReviews->isNotEmpty())
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Popular Reviews</h4>
            <a href="{{ route('reviews.index') }}" class="btn btn-sm btn-outline-secondary">See More</a>
        </div>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($popularReviews as $review)
            <div class="col">
                <div class="card review-card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <img src="https://placehold.co/40x40/ced4da/6c757d?text={{ strtoupper(substr($review->user->first_name, 0, 1)) }}"
                                class="rounded-circle me-3" alt="{{ $review->user->first_name }}">
                            <div>
                                <a href="#" class="text-dark fw-bold text-decoration-none">{{ $review->user->name }}</a>
                                reviewed
                                <a href="{{ route('books.show', $review->book) }}" class="text-decoration-none">{{ $review->book->title }}</a>
                                <div class="rating-stars text-warning">
                                    @for ($i = 0; $i < 5; $i++) <i
                                        class="fas fa-star {{ $i < $review->rating ? '' : 'far' }}"></i>
                                        @endfor
                                </div>
                                <small class="text-muted"> - {{ $review->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <p class="review-excerpt mb-0">{!! Str::limit(strip_tags(clean($review->content)), 150) !!}</p>
                        <div class="mt-2 text-muted small d-flex justify-content-between align-items-center">
                            <div>
                                <form action="{{ route('reviews.like.toggle', $review) }}" method="POST" class="d-inline-block me-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm p-0 border-0 text-muted">
                                        @if (Auth::check() && Auth::user()->likedReviews->contains($review))
                                        <i class="fas fa-thumbs-up text-primary"></i>
                                        @else
                                        <i class="far fa-thumbs-up"></i>
                                        @endif
                                        {{ $review->likers_count }} {{ Str::plural('Like', $review->likers_count) }}
                                    </button>
                                </form>
                                <span class="ms-2">
                                    <i class="far fa-comment"></i>
                                    {{ $review->comments_count }} {{ Str::plural('Comment', $review->comments_count) }}
                                </span>
                            </div>
                            <a href="{{ route('books.show', $review->book) }}#review-{{ $review->id }}" class="text-decoration-none text-muted">Expand <i class="fas fa-chevron-down"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Recently Added Books / News --}}
    @if ($recentlyAddedBooks->isNotEmpty())
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Recently Added Books</h4>
            {{-- <a href="#" class="btn btn-sm btn-outline-secondary">See More</a> --}}
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
            @foreach ($recentlyAddedBooks as $book)
            <div class="col">
                <div class="book-card-sm">
                    <a href="{{ route('books.show', $book) }}">
                        <div class="position-relative bg-light text-center rounded shadow-sm">
                            <img src="{{ $book->cover_image_path ?? 'https://placehold.co/200x300/3d405b/FFFFFF?text=No+Cover' }}"
                                class="img-fluid"
                                style="height: 150px; width: auto; object-fit: contain;"
                                alt="{{ $book->title }} Cover">
                        </div>
                    </a>
                    <h6 class="mt-2 small book-title">{{ $book->title }}</h6>
                    <p class="small text-muted mb-0">by {{ $book->author->name ?? 'Unknown' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

@push('styles')
<style>
    /* Tambahan style jika diperlukan */
    .rating-stars .far {
        color: #e0e0e0;
        /* Warna bintang kosong */
    }

    .book-card-sm a {
        display: block;
        transition: transform 0.2s ease-in-out;
    }

    .book-card-sm a:hover {
        transform: scale(1.03);
    }

    .book-title {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endpush
<x-guest-layout>
    {{-- Konten welcome.blade.php --}}

    <!-- Hero Section -->
    <header class="hero-section py-5 text-center text-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">Welcome to BookTrackr</h1>
                    <div class="row justify-content-center text-center mb-5">
                        <div class="col-md-3 mb-3">
                            <div class="counter-box">
                                <h2 class="counter-number">12.5M</h2>
                                <p class="counter-label">Books Read</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="counter-box">
                                <h2 class="counter-number">1.2M</h2>
                                <p class="counter-label">Books Cataloged</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="counter-box">
                                <h2 class="counter-number">8.7M</h2>
                                <p class="counter-label">Ratings Submitted</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="counter-box">
                                <h2 class="counter-number">1.6M</h2>
                                <p class="counter-label">Reviews Written</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-lg btn-primary px-4 py-2 mb-2">Create Your Account</a>
                    <p class="mt-2">Already have an account? <a href="{{ route('login') }}" class="text-white">Log in</a></p>
                </div>
            </div>
        </div>
    </header>

    <!-- Trending Books Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title mb-4">Trending Books</h2>
            <div class="row">
                @forelse ($trendingBooks as $book)
                <div class="col-md-2 col-6 mb-4">
                    <div class="book-card">
                        @if (isset($book['google_books_id']))
                        {{-- Buku dari Google Books API --}}
                        <a href="{{ route('google-books.show', $book['google_books_id']) }}" class="text-decoration-none">
                            <div class="position-relative bg-light text-center rounded shadow-sm">
                                <img src="{{ $book['thumbnail'] ?? 'https://placehold.co/200x300/3d405b/FFFFFF?text=No+Cover' }}"
                                    class="img-fluid"
                                    style="height: 180px; width: auto; object-fit: contain;"
                                    alt="{{ $book['title'] }} Book Cover">
                            </div>
                            <h5 class="book-title mt-2">{{ $book['title'] }}</h5>
                            <p class="book-author small text-muted">{{ $book['author_name'] }}</p>
                        </a>

                        <form action="{{ route('google-books.import', $book['google_books_id']) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary w-100">
                                <i class="fas fa-plus-circle"></i> Import
                            </button>
                        </form>
                        @else
                        {{-- Buku dari database lokal --}}
                        <a href="{{ route('books.show', $book) }}" class="text-decoration-none">
                            <div class="position-relative bg-light text-center rounded shadow-sm">
                                <img src="{{ $book->cover_image_path ?? 'https://placehold.co/200x300/3d405b/FFFFFF?text=No+Cover' }}"
                                    class="img-fluid"
                                    style="height: 180px; width: auto; object-fit: contain;"
                                    alt="{{ $book->title }} Book Cover">
                            </div>
                            <h5 class="book-title mt-2">{{ $book->title }}</h5>
                            <p class="book-author small text-muted">{{ $book->author->name ?? 'Unknown Author' }}</p>
                        </a>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-center">No trending books found.</p>
                @endforelse
            </div>
            {{-- <div class="text-center mt-4">
                <a href="{{ route('google-books.search') }}" class="btn btn-primary">Explore More Books</a>
        </div> --}}
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title mb-3">About BookTrackr</h2>
                    <p class="lead mb-4">BookTrackr helps you catalog your reading life, discover new books, and share your thoughts with friends.</p>
                </div>
                <div class="col-lg-6">
                    <img src="https://placehold.co/600x400/3d405b/FFFFFF?text=BookTrackr+Profile"
                        class="img-fluid rounded shadow" alt="BookTrackr Profile Preview">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <!-- Feature 1 -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 order-lg-2">
                    <h2 class="section-title mb-3">Track Your Reading</h2>
                    <p class="lead">Easily catalog the books you've read, are currently reading, or want to read.</p>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <img src="https://placehold.co/600x400/3d405b/FFFFFF?text=Book+Collection"
                        class="img-fluid rounded shadow" alt="Book Collection Feature">
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <h2 class="section-title mb-3">Write Reviews</h2>
                    <p class="lead">Share your opinions and rate the books you've finished.</p>
                </div>
                <div class="col-lg-6">
                    <img src="https://placehold.co/600x400/3d405b/FFFFFF?text=Book+Review"
                        class="img-fluid rounded shadow" alt="Book Review Feature">
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 order-lg-2">
                    <h2 class="section-title mb-3">Connect with Friends</h2>
                    <p class="lead">See what your friends are reading and discover books together.</p>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <img src="https://placehold.co/600x400/3d405b/FFFFFF?text=Friend+Activity"
                        class="img-fluid rounded shadow" alt="Friend Activity Feature">
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title mb-3">Create Book Lists</h2>
                    <p class="lead">Organize your books into custom lists for any occasion.</p>
                </div>
                <div class="col-lg-6">
                    <img src="https://placehold.co/600x400/3d405b/FFFFFF?text=Book+Lists"
                        class="img-fluid rounded shadow" alt="Book Lists Feature">
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Reviews Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0">Popular Reviews</h2>
                <a href="{{-- route('reviews.index') --}}" class="btn btn-link">View All</a>
                {{-- Aktifkan route jika sudah ada --}}
            </div>

            <div class="row">
                {{-- Nanti akan diganti dengan data dinamis dari controller --}}
                <!-- Review 1 -->
                <div class="col-lg-6 mb-4">
                    <div class="card review-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://placehold.co/50x50/ced4da/6c757d?text=User" alt="User"
                                    class="rounded-circle me-3">
                                <div>
                                    <h6 class="mb-0">Andi Wijaya</h6>
                                    <small class="text-muted">Laskar Pelangi</small>
                                </div>
                                <div class="ms-auto rating">
                                    <i class="fas fa-star text-warning"></i> 4.5
                                </div>
                            </div>
                            <p class="card-text">This was a fantastic read! Highly recommended...</p> {{-- Contoh teks --}}
                            <a href="{{-- route('reviews.show', 1) --}}" class="card-link">Read More</a>
                            {{-- Contoh route --}}
                        </div>
                    </div>
                </div>

                <!-- Review 2 -->
                <div class="col-lg-6 mb-4">
                    <div class="card review-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://placehold.co/50x50/ced4da/6c757d?text=User" alt="User"
                                    class="rounded-circle me-3">
                                <div>
                                    <h6 class="mb-0">Maya Putri</h6>
                                    <small class="text-muted">Pulang</small>
                                </div>
                                <div class="ms-auto rating">
                                    <i class="fas fa-star text-warning"></i> 5.0
                                </div>
                            </div>
                            <p class="card-text">An amazing story, couldn't put it down!</p> {{-- Contoh teks pendek --}}
                            <a href="#" class="card-link">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-5 bg-primary text-white text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="mb-4">Ready to Start Tracking?</h2>
                    <p class="lead mb-4">Join thousands of readers and manage your literary journey with BookTrackr.</p>
                    <a href="{{ route('register') }}" class="btn btn-lg btn-light">Register Now</a>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
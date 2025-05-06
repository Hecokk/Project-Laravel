@extends('layouts.guest')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('google-books.search') }}">Pencarian Google Books</a></li>
            <li class="breadcrumb-item active">Detail Buku</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Cover dan informasi buku -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="text-center pt-4">
                    <img src="{{ $thumbnail ?? 'https://placehold.co/600x900/3d405b/FFFFFF?text=No+Cover' }}"
                        alt="{{ $title }} Cover" class="img-fluid rounded book-cover"
                        style="max-height: 350px; width: auto;">
                </div>
                <div class="card-body">
                    @if (!empty($categories))
                    <div class="mb-3">
                        @foreach($categories as $category)
                        <span class="badge bg-secondary me-1">{{ $category }}</span>
                        @endforeach
                    </div>
                    @endif

                    <div class="book-meta mb-2">
                        <i class="fas fa-calendar-alt text-muted me-1"></i>
                        <span>Publikasi: {{ $publishedDate ?? 'Tidak diketahui' }}</span>
                    </div>

                    @if(!empty($pageCount))
                    <div class="book-meta mb-2">
                        <i class="fas fa-file-alt text-muted me-1"></i>
                        <span>{{ $pageCount }} halaman</span>
                    </div>
                    @endif

                    @if(!empty($isbn))
                    <div class="book-meta mb-2">
                        <i class="fas fa-barcode text-muted me-1"></i>
                        <span>ISBN: {{ $isbn }}</span>
                    </div>
                    @endif

                    @if(!empty($publisher))
                    <div class="book-meta mb-2">
                        <i class="fas fa-building text-muted me-1"></i>
                        <span>Penerbit: {{ $publisher }}</span>
                    </div>
                    @endif

                    @if(!empty($language))
                    <div class="book-meta mb-2">
                        <i class="fas fa-globe text-muted me-1"></i>
                        <span>Bahasa: {{ $language == 'id' ? 'Indonesia' : ($language == 'en' ? 'Inggris' : $language) }}</span>
                    </div>
                    @endif

                    @auth
                    <div class="mt-4">
                        <form action="{{ route('google-books.import', $id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-plus-circle me-1"></i> Tambahkan ke Koleksi
                            </button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Detail informasi buku -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="card-title fs-2 mb-1">{{ $title }}</h1>
                    <h2 class="card-subtitle mb-3 text-muted fs-5">oleh {{ $authors }}</h2>

                    @if(!empty($rating))
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <div class="stars me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=$rating)
                                    <i class="fas fa-star text-warning"></i>
                                    @elseif($i - 0.5 <= $rating)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                        <i class="far fa-star text-warning"></i>
                                        @endif
                                        @endfor
                            </div>
                            <span class="rating-text">
                                {{ $rating }}/5 ({{ $ratingsCount }} ulasan)
                            </span>
                        </div>
                    </div>
                    @endif

                    @if(!empty($previewLink))
                    <div class="mb-4">
                        <a href="{{ $previewLink }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Pratinjau di Google Books
                        </a>

                        @if(!empty($buyLink))
                        <a href="{{ $buyLink }}" target="_blank" class="btn btn-outline-success ms-2">
                            <i class="fas fa-shopping-cart me-1"></i> Beli Buku
                        </a>
                        @endif
                    </div>
                    @endif

                    <!-- Tabs untuk informasi detail -->
                    <div class="book-details-tabs mt-4">
                        <ul class="nav nav-tabs" id="bookDetailsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                    data-bs-target="#description" type="button" role="tab"
                                    aria-controls="description" aria-selected="true">
                                    Deskripsi
                                </button>
                            </li>
                            @if(!empty($contents))
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contents-tab" data-bs-toggle="tab"
                                    data-bs-target="#contents" type="button" role="tab"
                                    aria-controls="contents" aria-selected="false">
                                    Daftar Isi
                                </button>
                            </li>
                            @endif
                        </ul>
                        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="bookDetailsTabsContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                @if(!empty($description))
                                {!! $description !!}
                                @else
                                <p>Tidak ada deskripsi untuk buku ini.</p>
                                @endif
                            </div>
                            @if(!empty($contents))
                            <div class="tab-pane fade" id="contents" role="tabpanel" aria-labelledby="contents-tab">
                                <ol>
                                    @foreach($contents as $content)
                                    <li>{{ $content }}</li>
                                    @endforeach
                                </ol>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .book-cover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
    }

    .book-meta {
        font-size: 0.9rem;
    }
</style>
@endpush
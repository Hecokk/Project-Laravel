<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home', '/') }}">BookTrackr</a>
        {{-- Ganti 'home' jika nama route berbeda --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                {{-- Tautan Navigasi Umum --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    @auth
                    <a class="nav-link {{ request()->routeIs('books.my-books') ? 'active' : '' }}"
                        href="{{ route('books.my-books') }}">My Books</a>
                    @else
                    <a class="nav-link {{ request()->routeIs('books.index') ? 'active' : '' }}"
                        href="{{ route('books.index') }}">Books</a>
                    @endauth
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reviews.index') ? 'active' : '' }}"
                        href="{{ route('reviews.index') }}">Reviews</a>
                </li>
                {{-- Mengubah Lists menjadi Browse Dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="browseDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Browse
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="browseDropdown">
                        <li><a class="dropdown-item" href="#">Categories</a></li> {{-- TODO: Arahkan ke route kategori --}}
                        <li><a class="dropdown-item" href="#">News</a></li> {{-- TODO: Arahkan ke route artikel/news --}}
                    </ul>
                </li>
            </ul>

            {{-- Form Pencarian --}}
            <form class="d-flex mx-auto" action="{{ route('books.search') }}" method="GET" id="searchForm">
                <div class="input-group">
                    <input class="form-control" type="search" name="query" placeholder="Search books..."
                        aria-label="Search" value="{{ request('query') ?? '' }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav ms-auto">
                {{-- Language Switcher Dihapus --}}
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-globe me-1"></i> {{ strtoupper(app()->getLocale()) }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                    <li>
                        <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active fw-bold' : '' }}" href="{{ url('/en' . substr(request()->getRequestUri(), 3)) }}">
                            <span class="flag-icon me-2">🇺🇸</span> English
                            @if(app()->getLocale() == 'en')<i class="fas fa-check ms-2 text-success"></i>@endif
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ app()->getLocale() == 'id' ? 'active fw-bold' : '' }}" href="{{ url('/id' . substr(request()->getRequestUri(), 3)) }}">
                            <span class="flag-icon me-2">🇮🇩</span> Indonesia
                            @if(app()->getLocale() == 'id')<i class="fas fa-check ms-2 text-success"></i>@endif
                        </a>
                    </li>
                </ul>
                </li> --}}

                @guest
                {{-- Tautan untuk Tamu (belum login) --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                        href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary text-white px-3 mx-2 {{ request()->routeIs('register') ? 'active' : '' }}"
                        href="{{ route('register') }}">Register</a>
                </li>
                @else
                {{-- Tautan untuk Pengguna yang Sudah Login --}}
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}">Dashboard</a>
                </li> --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->first_name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
@props(['theme'])

<nav class="navbar fixed-top navbar-expand-lg bg-secondary-subtle">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    @if (auth()->user()->settings->type == 'admin')
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('admin.index') }}">Admin</a>
                        </li>
                    @endif


                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Quadros
                        </a>
                        <ul class="dropdown-menu">
                            @if (empty(auth()->user()->settings->boards))
                                <li><a class="dropdown-item">Ask Admin ;)</a></li>
                            @else
                                @foreach (auth()->user()->settings->boards as $board)
                                    <li><a class="dropdown-item" href="#">{{ $board }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-3">
                <li class="nav-item">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off" {{ $theme == 'dark' ? 'checked' : '' }}>
                        <label class="btn btn-outline-info themeBtn" id="dark" for="btnradio2"><i
                                class="bi bi-moon-stars-fill"></i></label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off" {{ $theme == 'light' ? 'checked' : '' }}>
                        <label class="btn btn-outline-info themeBtn" id="light" for="btnradio3"><i
                                class="bi bi-brightness-high-fill"></i></label>
                    </div>
                </li>
                @auth
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-danger" type="submit">Logout</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
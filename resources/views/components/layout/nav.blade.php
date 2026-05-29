@props(['theme', 'logo', 'user' => auth()->user()])

<nav class="navbar rounded-bottom-4 navbar-expand-lg bg-secondary-subtle">
    <div class="container-fluid">
        {{-- Home "btn" --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <img src="{{ $logo }}" alt="Logo" width="30" height="30">

            <div class="d-flex flex-column lh-1">
                <span>Home</span>

                @auth
                    <small class="text-body-secondary">
                        {{ $user->name }}
                    </small>
                @endauth
            </div>
        </a>

        {{-- Hamburgor btn on mobyle --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    {{-- Admin Tab --}}
                    @if ($user->state == 'admin')
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('admin.index') }}">Admin</a>
                        </li>
                    @endif

                    {{-- Lists dropdown --}}
                    <li class="nav-item dropdown-center">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Lists
                        </a>
                        <ul class="dropdown-menu">
                            @if (empty($user->boards))
                                <li><a class="dropdown-item">Ask Admin ;)</a></li>
                            @else
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                @foreach ($user->boards() as $board)
                                    <li>
                                        <p class="dropdown-item disabled mb-0" aria-disabled="true">{{ $board->name }}</p>
                                    </li>
                                    @foreach ($user->lists() as $list)
                                        @if ($list->board_id == $board->id)
                                            <li><a class="dropdown-item"
                                                    href="{{ route('list.index', ['id' => $list]) }}">↳{{ $list->name }}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 gap-3">
                {{-- Ligth/Dark toggle --}}
                <li class="nav-item">
                    <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"
                            {{ $theme == 'dark' ? 'checked' : '' }}>
                        <label class="btn btn-outline-info themeBtn" id="dark" for="btnradio2"><i
                                class="bi bi-moon-stars-fill"></i></label>

                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off"
                            {{ $theme == 'light' ? 'checked' : '' }}>
                        <label class="btn btn-outline-info themeBtn" id="light" for="btnradio3"><i
                                class="bi bi-brightness-high-fill"></i></label>
                    </div>
                </li>

                {{-- Logout btn --}}
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

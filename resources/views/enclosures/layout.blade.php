<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Állatkert | @yield('title', 'Default title')</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
            crossorigin="anonymous"
        />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid mx-5">
                <a class="navbar-brand" href="{{ route('home') }}">Állatkert</a>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#main-navbar"
                    aria-controls="main-navbar"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="main-navbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('enclosures.index') }}">Kifutók</a>
                        </li>
                        @auth
                            @if (Auth::user()->admin)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('enclosures.create') }}">Új kifutó</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('animals.create') }}">Új állat</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('animals.index') }}">Archivált állatok</a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    <div class="d-flex">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            @auth
                            <form action="{{ route('logout') }}" method="post">
                                @csrf
                                <button class="nav-link btn">Kijelentkezés</button>
                            </form>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Bejelentkezés</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Regisztráció</a>
                                </li>
                            @endauth

                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container-fluid px-5 my-3">
            @yield('content')
        </div>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"
        ></script>
    </body>
</html>

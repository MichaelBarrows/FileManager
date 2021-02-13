<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="{{ asset('css/custom.css')}}">
    <title>@yield('page_title') - File Manager</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">File Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="nav justify-content-end" >
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/upload">Upload</a>
                    </li>
                    @if(Auth::user()->is_admin)
                        <li class="nav-item">
                            <a class="nav-link" href="/admin">Admin</a>
                        </li>
                    @endif
                    <form class="nav-item" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="nav-link" href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Logout
                        </a>
                    </form>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">{{Auth::user()->name}}</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container">
        <div class="row">
            <h2>@yield('page_title')</h2>
        </div>
        @foreach (['success', 'danger'] as $msg)
            @if(Session::has($msg))
                <div class="row flash-messages">
                    <div class="alert alert-{{ $msg }}" role="alert">
                        {{ Session::get($msg) }}
                    </div>
                </div>
            @endif
        @endforeach

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="row flash-messages">
                    <div class="alert alert-danger" role="alert">
                        {{ $error }}
                    </div>
                </div>
            @endforeach
        @endif

        <div class="row">
            @yield('content')
        </div>
    </div>
</body>
</html>

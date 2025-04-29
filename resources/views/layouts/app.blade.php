<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" type='text/css' href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css" />
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #0A0A0A;
            color: #212529;
        }
        .navbar {
            background-color: #1e1e1e;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: #ffffff;
        }
        .navbar-nav .nav-link:hover {
            color: rgba(255, 255, 255, 0.8);
        }
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            padding: 0.25rem 0.5rem;
            border-radius: 50%;
            background-color: #ff6b6b;
            color: white;
            font-size: 0.75rem;
        }
        .card {
            border-radius: 10px;
            /*width: 700px;
            /*margin: auto;
            /*background-color: #1e1e1e;
            /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); */
            border: none;
        }
        .card-header {
            background-color: #1e1e1e;
            border-bottom: 1px solid #eaeaea;
            padding: 1rem;
        }

        .card-title{
            color: #ececec;
            }
        .btn-primary {
            background-color: #0a66c2;
            border-color: #0a66c2;
        }
        .btn-primary:hover {
            background-color: #004182;
            border-color: #004182;
        }
        .btn-outline-primary {
            color: #0a66c2;
            border-color: #0a66c2;
        }
        .btn-outline-primary:hover {
            background-color: #0a66c2;
            border-color: #0a66c2;
        }
        footer {
            background-color: #ffffff;
            padding: 1.5rem 0;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.05);
        }
    </style>

    @stack('styles')
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-md navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    Campus Connect
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.search') }}">Search</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('societies') }}">Societies</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('resources') }}">Resources</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('events') }}">Events</a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link position-relative" href="#">
                                    <i class="bi bi-bell fs-5"></i>
                                    <span class="notification-badge">1</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link position-relative" href="#">
                                    <i class="bi bi-chat fs-5"></i>
                                    <span class="notification-badge">1</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown mt-1">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.show', ['id' => Auth::user()->id]) }}">
                                        {{ __('Profile') }}
                                    </a>
                                    
                                    @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                        <a class="dropdown-item" href="#">
                                            {{ __('Admin Panel') }}
                                        </a>
                                    @endif
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="py-4">
        <div class="container">
            @if(session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="mt-5" style="background-color:#1e1e1e;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0" style="color:white;">&copy; {{ date('Y') }} {{ config('app.name', 'Educational Social Platform') }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-decoration-none me-3" style="color:white;">Terms of Service</a>
                    <a href="#" class="text-decoration-none me-3" style="color:white;">Privacy Policy</a>
                    <a href="#" class="text-decoration-none" style="color:white;">Contact Us</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
    @stack('scripts')
</body>
</html>
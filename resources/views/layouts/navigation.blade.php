<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="bi bi-mortarboard-fill text-primary me-2"></i>
            {{ config('app.name', 'EdConnect') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            @auth
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door"></i> {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                        <i class="bi bi-collection"></i> {{ __('Feed') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('societies.*') ? 'active' : '' }}" href="{{ route('societies.index') }}">
                        <i class="bi bi-people"></i> {{ __('Societies') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}" href="{{ route('announcements.index') }}">
                        <i class="bi bi-megaphone"></i> {{ __('Announcements') }}
                    </a>
                </li>
                @if(auth()->user()->can('access_resources'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('resources.*') ? 'active' : '' }}" href="#" id="resourcesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-mortarboard"></i> {{ __('Resources') }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="resourcesDropdown">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('resources.results') ? 'active' : '' }}" href="{{ route('resources.results') }}">
                                <i class="bi bi-file-earmark-text"></i> {{ __('Results') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('resources.attendance') ? 'active' : '' }}" href="{{ route('resources.attendance') }}">
                                <i class="bi bi-calendar-check"></i> {{ __('Attendance') }}
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
            @endauth

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
                        <a class="nav-link {{ request()->routeIs('chats.*') ? 'active' : '' }}" href="{{ route('chats.index') }}">
                            <i class="bi bi-chat-dots"></i>
                            @if(App\Models\Chat::where('receiver_id', auth()->id())->where('status', 'requested')->count() > 0)
                                <span class="notification-badge">
                                    {{ App\Models\Chat::where('receiver_id', auth()->id())->where('status', 'requested')->count() }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    <li class="nav-item notification-dropdown dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            @php
                                $unreadCount = App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span class="notification-badge">{{ $unreadCount }}</span>
                            @endif
                        </a>
                        
                        @include('components.notification-dropdown')
                    </li>

                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isSubAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-gear"></i> {{ __('Admin') }}
                        </a>
                    </li>
                    @endif
                    
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            @if(auth()->user()->profile_picture)
                                <img src="{{ Storage::url(auth()->user()->profile_picture) }}" class="avatar avatar-sm" alt="{{ auth()->user()->name }}">
                            @else
                                <i class="bi bi-person-circle"></i>
                            @endif
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profile.show', auth()->id()) }}">
                                <i class="bi bi-person"></i> {{ __('View Profile') }}
                            </a>
                            
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="bi bi-pencil-square"></i> {{ __('Edit Profile') }}
                            </a>
                            
                            <div class="dropdown-divider"></div>
                            
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right text-danger"></i> {{ __('Logout') }}
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

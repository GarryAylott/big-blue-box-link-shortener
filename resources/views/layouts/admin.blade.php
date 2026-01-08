<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - BigBluBox</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>
    <div class="site">
        <header class="site-header">
            <div class="wrapper">
                <div class="header-inner">
                    <h1 class="site-title">
                        <a href="{{ route('admin.dashboard') }}">BigBluBox</a>
                    </h1>
                    <nav>
                        <ul class="nav-list">
                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.links.create') }}" class="nav-link {{ request()->routeIs('admin.links.create') ? 'active' : '' }}">
                                    New Link
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.links.index') }}" class="nav-link {{ request()->routeIs('admin.links.index') ? 'active' : '' }}">
                                    All Links
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.analytics.clicks') }}" class="nav-link {{ request()->routeIs('admin.analytics.clicks') ? 'active' : '' }}">
                                    Redirect Logs
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.analytics.summary') }}" class="nav-link {{ request()->routeIs('admin.analytics.summary') ? 'active' : '' }}">
                                    Analytics
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="nav-link nav-link--logout">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <main>
            <div class="wrapper">
                @if(session('success'))
                    <div class="alert alert--success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert--error">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert--error">
                        <ul style="margin: 0; padding-left: 1.25rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>

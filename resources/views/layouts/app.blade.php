<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @isset($title)
            {{ $title }} | Smart Fertilizer Planner
        @else
            @yield('title', 'Smart Fertilizer Planner')
        @endisset
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect width='100' height='100' rx='22' fill='%232D6A4F'/%3E%3Ctext x='50' y='62' text-anchor='middle' font-size='42' font-family='Arial' fill='white'%3EFP%3C/text%3E%3C/svg%3E">
    @stack('styles')
</head>
<body class="sfp-body">
    @auth
        <nav class="sfp-navbar">
            <a class="sfp-logo" href="{{ route('dashboard') }}" aria-label="FertiPlan dashboard">
                <span class="sfp-logo-icon" aria-hidden="true">FP</span>
                <span>
                    <span class="sfp-logo-name">FertiPlan</span>
                    <span class="sfp-logo-tagline">Smart Farming Assistant</span>
                </span>
            </a>

            <ul class="sfp-nav-links">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                </li>
                @if (auth()->user()->role === 'farmer')
                    <li>
                        <a href="{{ route('parcels.index') }}" class="{{ request()->routeIs('parcels.*') ? 'active' : '' }}">My Land</a>
                    </li>
                    <li>
                        <a href="{{ route('plans.index') }}" class="{{ request()->routeIs('plans.*') ? 'active' : '' }}">Plans</a>
                    </li>
                    <li>
                        <a href="{{ route('history.index') }}" class="{{ request()->routeIs('history.*') ? 'active' : '' }}">History</a>
                    </li>
                @endif
                @if (auth()->user()->role === 'admin')
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Admin</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.crops.index') }}" class="{{ request()->routeIs('admin.crops.*') ? 'active' : '' }}">Crops</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.fertilizers.index') }}" class="{{ request()->routeIs('admin.fertilizers.*') ? 'active' : '' }}">Fertilizers</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
                    </li>
                @endif
            </ul>

            <div class="d-flex align-items-center">
                <div class="sfp-lang-switch">
                    <a href="{{ route('locale.switch', 'en') }}" class="sfp-lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('locale.switch', 'hi') }}" class="sfp-lang-btn {{ app()->getLocale() === 'hi' ? 'active' : '' }}">HI</a>
                </div>
            </div>

            <div class="sfp-user-menu dropdown">
                <button class="sfp-user-trigger" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="sfp-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="sfp-username d-none d-md-inline">{{ auth()->user()->name }}</span>
                    <span class="sfp-menu-caret">v</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end sfp-dropdown">
                    <li>
                        <span class="dropdown-item-text">
                            <strong>{{ auth()->user()->name }}</strong>
                            <small>{{ ucfirst(auth()->user()->role) }}</small>
                        </span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('app.profile') }}</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">{{ __('app.logout') }}</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="sfp-mobile-nav">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            @if (auth()->user()->role === 'farmer')
                <a href="{{ route('parcels.index') }}" class="{{ request()->routeIs('parcels.*') ? 'active' : '' }}">Land</a>
                <a href="{{ route('plans.index') }}" class="{{ request()->routeIs('plans.*') ? 'active' : '' }}">Plans</a>
                <a href="{{ route('history.index') }}" class="{{ request()->routeIs('history.*') ? 'active' : '' }}">History</a>
            @else
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Admin</a>
                <a href="{{ route('admin.crops.index') }}" class="{{ request()->routeIs('admin.crops.*') ? 'active' : '' }}">Crops</a>
                <a href="{{ route('admin.fertilizers.index') }}" class="{{ request()->routeIs('admin.fertilizers.*') ? 'active' : '' }}">Fertilizers</a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
            @endif
        </div>
    @endauth

    @if (session('success'))
        <div class="sfp-alert sfp-alert-success">
            <span>OK</span> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="sfp-alert sfp-alert-danger">
            <span>!</span> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="sfp-alert sfp-alert-danger">
            <span>!</span> {{ $errors->first() }}
        </div>
    @endif

    <main class="sfp-main">
        <div class="sfp-container">
            @if (isset($header))
                <div class="sfp-page-header">
                    <div class="sfp-page-header-content">
                        {{ $header }}
                    </div>
                </div>
            @endif

            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </div>
    </main>

    <footer class="sfp-footer">
        <p>FertiPlan (c) {{ date('Y') }} - {{ __('app.app_name') }} | Powered by Agronomic Science and ICAR Data</p>
        <p class="sfp-footer-tagline">Helping farmers grow smarter, not harder.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.sfp-delete-btn').forEach((btn) => {
                btn.addEventListener('click', (event) => {
                    if (!confirm('Are you sure? This action cannot be undone.')) {
                        event.preventDefault();
                    }
                });
            });

            setTimeout(() => {
                document.querySelectorAll('.sfp-alert').forEach((alert) => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 4000);
        });

        window.addEventListener('beforeprint', () => {
            document.querySelectorAll('.sfp-navbar, .sfp-mobile-nav, .sfp-print-hide, .sfp-chart-print-hide, .sfp-chart-shell').forEach((element) => {
                element.dataset.printDisplay = element.style.display || '';
                element.style.display = 'none';
            });
        });

        window.addEventListener('afterprint', () => {
            document.querySelectorAll('.sfp-navbar, .sfp-mobile-nav, .sfp-print-hide, .sfp-chart-print-hide, .sfp-chart-shell').forEach((element) => {
                element.style.display = element.dataset.printDisplay || '';
                delete element.dataset.printDisplay;
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

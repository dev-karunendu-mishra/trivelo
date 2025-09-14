<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ app(\App\Services\ThemeService::class)->getThemeClass() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ app(\App\Services\ThemeService::class)->getActiveTheme()?->color_scheme['primary'] ?? '#007bff' }}">

    <title>@yield('title', config('app.name', 'Trivelo'))</title>
    <meta name="description" content="@yield('meta_description', 'Premium hotel booking system with multiple themes and advanced features.')">
    <meta name="keywords" content="@yield('meta_keywords', 'hotel booking, accommodation, travel, themes')">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    @php
        $themeFonts = app(\App\Services\ThemeService::class)->getThemeFonts();
    @endphp
    @if(!empty($themeFonts))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?{{ implode('&', array_map(fn($font) => 'family=' . $font . ':wght@300;400;500;600;700', $themeFonts)) }}&display=swap" rel="stylesheet">
    @endif

    <!-- Theme CSS Variables -->
    <style>
        {!! app(\App\Services\ThemeService::class)->getInlineCss() !!}
    </style>

    <!-- Additional CSS -->
    @stack('css')

    <!-- Theme Configuration for JavaScript -->
    <script>
        window.Theme = @json(app(\App\Services\ThemeService::class)->getThemeConfig());
    </script>
</head>
<body class="{{ app(\App\Services\ThemeService::class)->getThemeClass() }}" data-bs-theme="{{ app(\App\Services\ThemeService::class)->isDarkTheme() ? 'dark' : 'light' }}">
    
    <!-- Theme Switching Button (for development/demo) -->
    @if(config('app.debug'))
        <div class="theme-switcher position-fixed top-0 end-0 m-3" style="z-index: 1050;">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-palette"></i> Theme
                </button>
                <ul class="dropdown-menu">
                    @foreach(app(\App\Services\ThemeService::class)->getAvailableThemes() as $theme)
                        <li>
                            <a class="dropdown-item {{ app(\App\Services\ThemeService::class)->getActiveTheme()?->name === $theme->name ? 'active' : '' }}" 
                               href="#" onclick="switchTheme('{{ $theme->name }}')">
                                <i class="bi bi-circle-fill me-2" style="color: {{ $theme->color_scheme['primary'] }};"></i>
                                {{ $theme->display_name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Navigation -->
    @include('layouts.navigation')

    <!-- Page Header -->
    @hasSection('header')
        <section class="page-header">
            @yield('header')
        </section>
    @endif

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Alpine.js -->
    @vite(['resources/js/app.js'])

    <!-- Additional JavaScript -->
    @stack('js')

    <!-- Theme Switching JavaScript -->
    @if(config('app.debug'))
        <script>
            function switchTheme(themeName) {
                fetch('{{ route("theme.switch") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ theme: themeName })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Failed to switch theme');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

            // Theme initialization
            document.addEventListener('DOMContentLoaded', function() {
                // Apply theme class to body if not already present
                if (window.Theme && window.Theme.cssClass) {
                    document.body.classList.add(window.Theme.cssClass);
                }

                // Initialize theme-specific JavaScript
                if (typeof initTheme === 'function') {
                    initTheme(window.Theme);
                }
            });
        </script>
    @endif

    <!-- Page-specific scripts -->
    @stack('scripts')
</body>
</html>
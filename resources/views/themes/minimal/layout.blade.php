<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Trivelo') }} - Minimal Simplicity</title>
        
        <!-- Minimal Theme CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Minimal Typography -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
        
        <style>
            /* Minimal Theme - Clean & Simple */
            :root {
                --minimal-black: #000000;
                --minimal-white: #FFFFFF;
                --minimal-gray-50: #FAFAFA;
                --minimal-gray-100: #F5F5F5;
                --minimal-gray-200: #E5E5E5;
                --minimal-gray-400: #A3A3A3;
                --minimal-gray-600: #525252;
                --minimal-gray-900: #171717;
                --minimal-accent: #3B82F6;
            }

            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                font-weight: 300;
                background: var(--minimal-white);
                color: var(--minimal-gray-900);
                line-height: 1.6;
            }

            .minimal-nav {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid var(--minimal-gray-200);
            }

            .minimal-logo {
                font-weight: 600;
                color: var(--minimal-black);
                text-decoration: none;
                font-size: 1.5rem;
            }

            .minimal-nav-link {
                color: var(--minimal-gray-600);
                text-decoration: none;
                font-weight: 400;
                padding: 0.75rem 1.5rem;
                transition: color 0.2s ease;
                border-bottom: 2px solid transparent;
            }

            .minimal-nav-link:hover {
                color: var(--minimal-black);
                border-bottom-color: var(--minimal-accent);
            }

            .btn-minimal-primary {
                background: var(--minimal-black);
                color: var(--minimal-white);
                border: none;
                padding: 0.75rem 2rem;
                font-weight: 500;
                transition: all 0.2s ease;
                border-radius: 0;
            }

            .btn-minimal-primary:hover {
                background: var(--minimal-gray-900);
                color: var(--minimal-white);
                transform: translateY(-1px);
            }

            .btn-minimal-secondary {
                background: transparent;
                color: var(--minimal-black);
                border: 1px solid var(--minimal-gray-200);
                padding: 0.75rem 2rem;
                font-weight: 500;
                transition: all 0.2s ease;
                border-radius: 0;
            }

            .btn-minimal-secondary:hover {
                background: var(--minimal-gray-50);
                border-color: var(--minimal-black);
                color: var(--minimal-black);
            }

            .minimal-hero {
                min-height: 100vh;
                background: var(--minimal-white);
                display: flex;
                align-items: center;
            }

            .minimal-section {
                padding: 5rem 0;
            }

            .minimal-card {
                background: var(--minimal-white);
                border: 1px solid var(--minimal-gray-200);
                transition: all 0.2s ease;
            }

            .minimal-card:hover {
                border-color: var(--minimal-gray-400);
                transform: translateY(-2px);
            }

            .minimal-divider {
                width: 4rem;
                height: 1px;
                background: var(--minimal-black);
                margin: 2rem auto;
            }

            .minimal-mono {
                font-family: 'JetBrains Mono', monospace;
                font-size: 0.875rem;
                color: var(--minimal-gray-600);
            }

            .text-minimal-accent {
                color: var(--minimal-accent);
            }

            .bg-minimal-gray {
                background: var(--minimal-gray-50);
            }

            .minimal-footer {
                background: var(--minimal-white);
                border-top: 1px solid var(--minimal-gray-200);
            }
        </style>
    </head>
    <body>
        <!-- Minimal Navigation -->
        @include('themes.minimal.navigation')
        
        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
        
        <!-- Minimal Footer -->
        @include('themes.minimal.footer')
        
        <!-- Minimal Theme JavaScript -->
        <script>
            // Minimal theme interactions
            document.addEventListener('DOMContentLoaded', function() {
                // Simple smooth scrolling
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    });
                });
                
                // Simple scroll effect for navigation
                let lastScrollTop = 0;
                window.addEventListener('scroll', function() {
                    const nav = document.querySelector('.minimal-nav');
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    
                    if (scrollTop > 100) {
                        nav.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
                    } else {
                        nav.style.boxShadow = 'none';
                    }
                    
                    lastScrollTop = scrollTop;
                });
            });
            
            // Theme switching function
            function switchTheme(themeName) {
                fetch('/themes/switch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ theme: themeName })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        console.error('Failed to switch theme:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error switching theme:', error);
                });
            }
        </script>
    </body>
</html>
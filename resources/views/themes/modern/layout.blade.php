<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Trivelo') }} - Modern Experience</title>
        
        <!-- Modern Theme CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Custom Modern Theme Styles -->
        <style>
            /* Modern Theme - Glassmorphism & Contemporary UI */
            .glass-effect {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            .glass-card {
                background: rgba(255, 255, 255, 0.15);
                backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            }
            
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            
            .gradient-text {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .btn-modern {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                padding: 12px 32px;
                border-radius: 50px;
                color: white;
                font-weight: 600;
                transition: all 0.3s ease;
                box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            }
            
            .btn-modern:hover {
                transform: translateY(-2px);
                box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
                color: white;
            }
            
            .modern-nav {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            }
            
            .hero-modern {
                min-height: 100vh;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                position: relative;
                overflow: hidden;
            }
            
            .hero-modern::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            }
            
            .floating-animation {
                animation: floating 6s ease-in-out infinite;
            }
            
            @keyframes floating {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            
            .pulse-animation {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: .5; }
            }
        </style>
        
        <!-- Additional fonts for modern look -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    </head>
    <body class="font-sans antialiased bg-gray-50" style="font-family: 'Inter', sans-serif;">
        <!-- Modern Navigation -->
        @include('themes.modern.navigation')
        
        <!-- Page Content -->
        <main class="relative z-10">
            @yield('content')
        </main>
        
        <!-- Modern Footer -->
        @include('themes.modern.footer')
        
        <!-- Theme switching JS -->
        <script>
            // Modern theme interactions
            document.addEventListener('DOMContentLoaded', function() {
                // Add smooth scrolling to anchor links
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
                
                // Add glass effect on scroll
                window.addEventListener('scroll', function() {
                    const nav = document.querySelector('.modern-nav');
                    if (window.scrollY > 50) {
                        nav.style.background = 'rgba(255, 255, 255, 0.9)';
                    } else {
                        nav.style.background = 'rgba(255, 255, 255, 0.95)';
                    }
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
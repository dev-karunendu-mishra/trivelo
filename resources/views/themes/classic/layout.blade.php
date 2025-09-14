<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Trivelo') }} - Classic Elegance</title>
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        
        <!-- Google Fonts - Classic Typography -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Crimson+Text:wght@400;600&family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
        
        <!-- Classic Theme CSS -->
        @vite(['resources/js/app.js'])
        
        <style>
            /* Classic Theme - Traditional Elegance */
            :root {
                --classic-gold: #C9A961;
                --classic-dark-gold: #B8941C;
                --classic-navy: #1B365D;
                --classic-light-navy: #2C5F8A;
                --classic-cream: #F8F6F0;
                --classic-beige: #E8DCC7;
                --classic-brown: #8B4513;
                --classic-text: #2C2C2C;
                --classic-light-text: #666666;
            }

            body {
                font-family: 'Crimson Text', serif;
                background: var(--classic-cream);
                color: var(--classic-text);
                line-height: 1.7;
            }

            .classic-header {
                font-family: 'Playfair Display', serif;
            }

            .classic-logo {
                font-family: 'Libre Baskerville', serif;
                font-weight: 700;
                color: var(--classic-gold);
                text-decoration: none;
            }

            .classic-nav {
                background: rgba(27, 54, 93, 0.95);
                backdrop-filter: blur(10px);
                border-bottom: 3px solid var(--classic-gold);
                box-shadow: 0 4px 20px rgba(27, 54, 93, 0.3);
            }

            .classic-nav .nav-link {
                color: #ffffff;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                font-size: 0.9rem;
                padding: 15px 20px;
                border-bottom: 3px solid transparent;
                transition: all 0.3s ease;
            }

            .classic-nav .nav-link:hover {
                color: var(--classic-gold);
                border-bottom-color: var(--classic-gold);
            }

            .btn-classic-primary {
                background: linear-gradient(135deg, var(--classic-gold) 0%, var(--classic-dark-gold) 100%);
                border: 2px solid var(--classic-gold);
                color: white;
                font-family: 'Playfair Display', serif;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                padding: 12px 30px;
                border-radius: 0;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .btn-classic-primary::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                transition: left 0.5s;
            }

            .btn-classic-primary:hover::before {
                left: 100%;
            }

            .btn-classic-primary:hover {
                background: var(--classic-navy);
                border-color: var(--classic-navy);
                color: var(--classic-gold);
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(27, 54, 93, 0.3);
            }

            .btn-classic-secondary {
                background: transparent;
                border: 2px solid var(--classic-gold);
                color: var(--classic-gold);
                font-family: 'Playfair Display', serif;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 1px;
                padding: 12px 30px;
                border-radius: 0;
                transition: all 0.3s ease;
            }

            .btn-classic-secondary:hover {
                background: var(--classic-gold);
                color: white;
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(201, 169, 97, 0.3);
            }

            .classic-hero {
                background: linear-gradient(rgba(27, 54, 93, 0.7), rgba(27, 54, 93, 0.7)), url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="classic-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="2" fill="%23C9A961" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23classic-pattern)"/></svg>');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
                color: white;
                position: relative;
            }

            .classic-hero::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23C9A961' fill-opacity='0.05'%3E%3Cpath d='M20 20c0 4.4-3.6 8-8 8s-8-3.6-8-8 3.6-8 8-8 8 3.6 8 8zm0-20c0 4.4-3.6 8-8 8s-8-3.6-8-8 3.6-8 8-8 8 3.6 8 8z'/%3E%3C/g%3E%3C/svg%3E");
            }

            .classic-card {
                background: white;
                border: 1px solid var(--classic-beige);
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                border-radius: 0;
                position: relative;
            }

            .classic-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, var(--classic-gold) 0%, var(--classic-navy) 100%);
            }

            .ornament {
                width: 60px;
                height: 3px;
                background: var(--classic-gold);
                margin: 20px auto;
                position: relative;
            }

            .ornament::before,
            .ornament::after {
                content: '';
                position: absolute;
                width: 8px;
                height: 8px;
                background: var(--classic-gold);
                border-radius: 50%;
                top: -2.5px;
            }

            .ornament::before {
                left: -15px;
            }

            .ornament::after {
                right: -15px;
            }

            .classic-quote {
                font-family: 'Playfair Display', serif;
                font-style: italic;
                font-size: 1.4rem;
                color: var(--classic-light-text);
                text-align: center;
                position: relative;
                padding: 30px;
            }

            .classic-quote::before {
                content: '"';
                font-size: 4rem;
                position: absolute;
                top: -10px;
                left: 0;
                color: var(--classic-gold);
                line-height: 1;
            }

            .classic-quote::after {
                content: '"';
                font-size: 4rem;
                position: absolute;
                bottom: -30px;
                right: 0;
                color: var(--classic-gold);
                line-height: 1;
            }

            .classic-section-title {
                font-family: 'Playfair Display', serif;
                font-weight: 600;
                color: var(--classic-navy);
                text-align: center;
                margin-bottom: 2rem;
                position: relative;
            }

            .texture-overlay {
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f8f6f0' fill-opacity='0.8'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
        </style>
    </head>
    <body class="texture-overlay">
        <!-- Classic Navigation -->
        @include('themes.classic.navigation')
        
        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
        
        <!-- Classic Footer -->
        @include('themes.classic.footer')
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        
        <!-- Classic Theme JavaScript -->
        <script>
            // Classic theme interactions
            document.addEventListener('DOMContentLoaded', function() {
                // Smooth scrolling
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
                
                // Add parallax effect to hero section
                window.addEventListener('scroll', function() {
                    const scrolled = window.pageYOffset;
                    const hero = document.querySelector('.classic-hero');
                    if (hero) {
                        const speed = scrolled * 0.5;
                        hero.style.transform = `translateY(${speed}px)`;
                    }
                });
                
                // Add elegant fade-in animations
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };
                
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, observerOptions);
                
                // Observe elements for animation
                document.querySelectorAll('.classic-card, .classic-quote').forEach(el => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(30px)';
                    el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                    observer.observe(el);
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
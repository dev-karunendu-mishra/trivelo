<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>
    
    <!-- Admin Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js for analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('styles')
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        
        .admin-layout {
            min-height: 100vh;
        }
        
        .admin-wrapper {
            display: flex;
            min-height: calc(100vh - 60px);
        }
        
        .admin-sidebar {
            width: 250px;
            background: #343a40;
            transition: all 0.3s ease;
        }
        
        .admin-main {
            flex: 1;
            background: #f8f9fa;
            padding: 0;
        }
        
        .page-header {
            background: white;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 2rem;
        }
        
        .content {
            padding: 0 2rem 2rem;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                position: fixed;
                top: 60px;
                left: -100%;
                height: calc(100vh - 60px);
                z-index: 1000;
            }
            
            .admin-sidebar.show {
                left: 0;
            }
            
            .admin-main {
                width: 100%;
            }
            
            .content {
                padding: 0 1rem 2rem;
            }
        }
    </style>
</head>
<body class="admin-layout">
    <!-- Top Navigation -->
    @include('admin.layouts.header')
    
    <div class="admin-wrapper">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Page Header -->
            @hasSection('page-header')
                <div class="page-header">
                    @yield('page-header')
                </div>
            @endif
            
            <!-- Alerts -->
            @include('admin.partials.alerts')
            
            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Admin Scripts -->
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.querySelector('.admin-sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
                
                // Close sidebar on outside click (mobile)
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                            sidebar.classList.remove('show');
                        }
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
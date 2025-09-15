<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Hotel Manager') - {{ config('app.name', 'Trivelo') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Styles -->
    <style>
        :root {
            --bs-body-font-family: 'Inter', sans-serif;
            --sidebar-width: 280px;
            --header-height: 70px;
        }
        
        body {
            font-family: var(--bs-body-font-family);
            background-color: #f8f9fa;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease-in-out;
        }
        
        .main-content.expanded {
            margin-left: 0;
        }
        
        .header {
            height: var(--header-height);
            background: #ffffff;
            border-bottom: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .content-area {
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            margin: 0.25rem 1rem;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover,
        .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        
        .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 0.75rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: box-shadow 0.15s ease-in-out;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .stats-card {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            border-radius: 1rem;
        }
        
        .stats-card.primary {
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
        }
        
        .stats-card.success {
            --gradient-start: #56ab2f;
            --gradient-end: #a8e6cf;
        }
        
        .stats-card.warning {
            --gradient-start: #f093fb;
            --gradient-end: #f5576c;
        }
        
        .stats-card.info {
            --gradient-start: #4facfe;
            --gradient-end: #00f2fe;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
            transform: translateY(-1px);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-area {
                padding: 1rem;
            }
        }
        
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin: -2rem -2rem 2rem -2rem;
            border-radius: 0 0 1rem 1rem;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
        }
        
        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        
        .breadcrumb-item.active {
            color: white;
        }
        
        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @include('hotel-manager.layouts.sidebar')
        
        <!-- Main Content -->
        <div class="main-content" id="main-content">
            <!-- Header -->
            @include('hotel-manager.layouts.header')
            
            <!-- Content Area -->
            <div class="content-area">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
        
        // Mobile sidebar toggle
        function toggleMobileSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('show');
        }
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
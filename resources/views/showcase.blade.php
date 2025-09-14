@extends(app('App\Services\ThemeService')->getThemeLayout())

@section('content')
<div class="container my-5">
    <!-- Theme Showcase Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 mb-3" style="font-family: var(--classic-header, inherit);">Theme Showcase</h1>
        <p class="lead">Experience our three distinct design themes</p>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Currently viewing: <strong>{{ app('App\Services\ThemeService')->getActiveTheme()->display_name ?? 'Default' }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Theme Cards -->
    <div class="row g-4">
        @foreach(app('App\Services\ThemeService')->getAvailableThemes() as $theme)
        <div class="col-lg-4">
            <div class="card h-100 theme-card {{ $theme->is_active ? 'border-primary shadow-lg' : '' }}" 
                 style="border-width: {{ $theme->is_active ? '3px' : '1px' }};">
                <div class="card-header bg-{{ $theme->is_active ? 'primary text-white' : 'light' }}">
                    <h5 class="card-title mb-0">
                        @if($theme->is_active)
                            <i class="bi bi-check-circle-fill me-2"></i>
                        @endif
                        {{ $theme->display_name }}
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $theme->description }}</p>
                    
                    <!-- Theme Preview -->
                    <div class="mb-3 p-3 rounded" style="background: {{ $theme->color_scheme['background'] ?? '#ffffff' }}; border: 1px solid {{ $theme->color_scheme['primary'] ?? '#007bff' }};">
                        <div class="d-flex align-items-center mb-2">
                            <div class="rounded-circle me-2" 
                                 style="width: 20px; height: 20px; background: {{ $theme->color_scheme['primary'] ?? '#007bff' }};"></div>
                            <small style="color: {{ $theme->color_scheme['text'] ?? '#000000' }};">Primary Color</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle me-2" 
                                 style="width: 20px; height: 20px; background: {{ $theme->color_scheme['secondary'] ?? '#6c757d' }};"></div>
                            <small style="color: {{ $theme->color_scheme['text'] ?? '#000000' }};">Secondary Color</small>
                        </div>
                    </div>

                    <!-- Theme Features -->
                    <div class="mb-3">
                        <strong>Features:</strong>
                        <ul class="list-unstyled mt-2">
                            @if($theme->name === 'modern')
                                <li><i class="bi bi-check text-success me-1"></i> Glassmorphism Effects</li>
                                <li><i class="bi bi-check text-success me-1"></i> Modern Animations</li>
                                <li><i class="bi bi-check text-success me-1"></i> Gradient Buttons</li>
                                <li><i class="bi bi-check text-success me-1"></i> Card-based Layout</li>
                            @elseif($theme->name === 'classic')
                                <li><i class="bi bi-check text-success me-1"></i> Elegant Typography</li>
                                <li><i class="bi bi-check text-success me-1"></i> Traditional Colors</li>
                                <li><i class="bi bi-check text-success me-1"></i> Formal Layout</li>
                                <li><i class="bi bi-check text-success me-1"></i> Rich Textures</li>
                            @elseif($theme->name === 'minimal')
                                <li><i class="bi bi-check text-success me-1"></i> Clean Typography</li>
                                <li><i class="bi bi-check text-success me-1"></i> Lots of Whitespace</li>
                                <li><i class="bi bi-check text-success me-1"></i> Simple Navigation</li>
                                <li><i class="bi bi-check text-success me-1"></i> Monochrome Design</li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card-footer">
                    @if($theme->is_active)
                        <button class="btn btn-success w-100" disabled>
                            <i class="bi bi-check-lg me-2"></i>
                            Currently Active
                        </button>
                    @else
                        <button class="btn btn-outline-primary w-100" onclick="switchTheme('{{ $theme->name }}')">
                            <i class="bi bi-palette me-2"></i>
                            Switch to {{ $theme->display_name }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Theme Demo Sections -->
    <div class="mt-5">
        <h2 class="text-center mb-4">Current Theme Demo</h2>
        
        <!-- Sample Content -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sample Hotel Room</h5>
                        <p class="card-text">Experience luxury and comfort in our beautifully designed accommodations.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-success">Available</span>
                            <strong class="text-primary">$199/night</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Premium Suite</h5>
                        <p class="card-text">Indulge in our premium suite with breathtaking city views and world-class amenities.</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-warning">Limited</span>
                            <strong class="text-primary">$349/night</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Test -->
    <div class="mt-5 text-center">
        <h3 class="mb-3">Navigation</h3>
        <div class="btn-group" role="group">
            <a href="{{ route('home') }}" class="btn btn-outline-primary">
                <i class="bi bi-house me-1"></i> Home
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
            </a>
            <a href="{{ route('showcase') }}" class="btn btn-primary">
                <i class="bi bi-palette me-1"></i> Theme Showcase
            </a>
        </div>
    </div>
</div>

<!-- Theme Switch JavaScript -->
<script>
function switchTheme(themeName) {
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Switching...';
    
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
            // Show success message
            button.innerHTML = '<i class="bi bi-check-lg me-2"></i>Success! Reloading...';
            button.className = 'btn btn-success w-100';
            
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            // Show error
            button.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>Error: ' + (data.message || 'Unknown error');
            button.className = 'btn btn-danger w-100';
            button.disabled = false;
            
            // Restore original state after 3 seconds
            setTimeout(() => {
                button.innerHTML = originalText;
                button.className = 'btn btn-outline-primary w-100';
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error switching theme:', error);
        button.innerHTML = '<i class="bi bi-exclamation-triangle me-2"></i>Network Error';
        button.className = 'btn btn-danger w-100';
        button.disabled = false;
        
        // Restore original state after 3 seconds
        setTimeout(() => {
            button.innerHTML = originalText;
            button.className = 'btn btn-outline-primary w-100';
        }, 3000);
    });
}
</script>

<style>
.theme-card {
    transition: all 0.3s ease;
}

.theme-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}
</style>
@endsection
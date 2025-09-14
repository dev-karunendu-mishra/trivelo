<!-- Theme Selector Component -->
<div class="theme-selector-container">
    <!-- Theme Loader -->
    <div class="theme-loader d-none">
        <div class="spinner-border spinner-border-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        Switching theme...
    </div>

    <!-- Current Theme Display -->
    <div class="mb-3">
        <h6>Current Theme: <span class="current-theme-name badge bg-primary">{{ ucfirst(app('theme')->getCurrentTheme()) }}</span></h6>
    </div>

    <!-- Theme Options -->
    <div class="row g-3">
        @forelse($themes as $theme)
        <div class="col-md-6 col-lg-4">
            <div class="card theme-option {{ app('theme')->getCurrentTheme() === $theme->slug ? 'border-primary' : '' }}" 
                 data-theme="{{ $theme->slug }}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">{{ $theme->name }}</h6>
                    @if(app('theme')->getCurrentTheme() === $theme->slug)
                        <span class="badge bg-success">Active</span>
                    @endif
                </div>
                
                <div class="card-body">
                    <p class="card-text text-muted">{{ $theme->description }}</p>
                    
                    <!-- Theme Preview Colors -->
                    <div class="theme-colors d-flex mb-3">
                        <div class="color-swatch" style="background-color: {{ $theme->settings['primary_color'] ?? '#007bff' }};" 
                             title="Primary Color"></div>
                        <div class="color-swatch" style="background-color: {{ $theme->settings['secondary_color'] ?? '#6c757d' }};" 
                             title="Secondary Color"></div>
                        <div class="color-swatch" style="background-color: {{ $theme->settings['success_color'] ?? '#28a745' }};" 
                             title="Success Color"></div>
                        <div class="color-swatch" style="background-color: {{ $theme->settings['info_color'] ?? '#17a2b8' }};" 
                             title="Info Color"></div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary theme-preview-btn" 
                                data-theme="{{ $theme->slug }}">
                            Preview
                        </button>
                        
                        @if(app('theme')->getCurrentTheme() !== $theme->slug)
                        <button class="btn btn-sm btn-primary theme-switch-btn" 
                                data-theme="{{ $theme->slug }}">
                            Select
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                No themes available. Please run the theme seeder.
            </div>
        </div>
        @endforelse
    </div>
</div>

<style>
.theme-selector-container {
    padding: 1rem;
}

.theme-option {
    transition: all 0.3s ease;
    cursor: pointer;
}

.theme-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.theme-option.active,
.theme-option.border-primary {
    border-color: var(--bs-primary) !important;
    box-shadow: 0 0 0 1px var(--bs-primary);
}

.color-swatch {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin-right: 5px;
    border: 2px solid #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}

.theme-loader {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    background: rgba(255,255,255,0.9);
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .theme-selector-container {
        padding: 0.5rem;
    }
    
    .theme-option {
        margin-bottom: 1rem;
    }
}
</style>
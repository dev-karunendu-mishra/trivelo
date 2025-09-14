/**
 * Theme Management JavaScript
 */

class ThemeManager {
    constructor() {
        this.currentTheme = this.getCurrentTheme();
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadThemeAssets();
    }

    bindEvents() {
        // Theme switch buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('theme-switch-btn')) {
                e.preventDefault();
                const theme = e.target.dataset.theme;
                this.switchTheme(theme);
            }
        });

        // Theme preview functionality
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('theme-preview-btn')) {
                e.preventDefault();
                const theme = e.target.dataset.theme;
                this.previewTheme(theme);
            }
        });
    }

    async switchTheme(themeName) {
        try {
            // Show loading state
            this.showLoading();

            // Make API call to switch theme
            const response = await fetch('/themes/switch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ theme: themeName })
            });

            const data = await response.json();

            if (data.success) {
                this.currentTheme = themeName;
                await this.loadThemeAssets();
                this.showSuccess('Theme switched successfully!');
                
                // Update UI elements
                this.updateThemeUI();
            } else {
                this.showError(data.message || 'Failed to switch theme');
            }
        } catch (error) {
            console.error('Theme switch error:', error);
            this.showError('Failed to switch theme');
        } finally {
            this.hideLoading();
        }
    }

    async previewTheme(themeName) {
        try {
            // Temporarily load theme assets for preview
            const tempTheme = this.currentTheme;
            this.currentTheme = themeName;
            
            await this.loadThemeAssets();
            
            // Show preview modal or notification
            this.showPreviewNotification(themeName);
            
            // Revert after 5 seconds
            setTimeout(async () => {
                this.currentTheme = tempTheme;
                await this.loadThemeAssets();
                this.hidePreviewNotification();
            }, 5000);
        } catch (error) {
            console.error('Theme preview error:', error);
            this.showError('Failed to preview theme');
        }
    }

    async loadThemeAssets() {
        // Remove existing theme stylesheets
        document.querySelectorAll('link[data-theme]').forEach(link => link.remove());

        // Load new theme stylesheet
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = `/build/assets/themes/${this.currentTheme}.css?v=${Date.now()}`;
        link.setAttribute('data-theme', this.currentTheme);
        
        document.head.appendChild(link);

        // Update data-theme attribute on html element
        document.documentElement.setAttribute('data-theme', this.currentTheme);
        
        // Store in localStorage
        localStorage.setItem('selected-theme', this.currentTheme);
    }

    getCurrentTheme() {
        // Get from localStorage, session, or default
        return localStorage.getItem('selected-theme') || 
               document.documentElement.getAttribute('data-theme') || 
               'default';
    }

    updateThemeUI() {
        // Update active state in theme selector
        document.querySelectorAll('.theme-option').forEach(option => {
            option.classList.remove('active');
            if (option.dataset.theme === this.currentTheme) {
                option.classList.add('active');
            }
        });

        // Update theme name display
        const themeDisplay = document.querySelector('.current-theme-name');
        if (themeDisplay) {
            themeDisplay.textContent = this.currentTheme.charAt(0).toUpperCase() + this.currentTheme.slice(1);
        }
    }

    showLoading() {
        const loader = document.querySelector('.theme-loader');
        if (loader) {
            loader.classList.remove('d-none');
        }
    }

    hideLoading() {
        const loader = document.querySelector('.theme-loader');
        if (loader) {
            loader.classList.add('d-none');
        }
    }

    showSuccess(message) {
        this.showToast(message, 'success');
    }

    showError(message) {
        this.showToast(message, 'error');
    }

    showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 5000);
    }

    showPreviewNotification(themeName) {
        const notification = document.createElement('div');
        notification.className = 'alert alert-info position-fixed';
        notification.id = 'theme-preview-notification';
        notification.style.cssText = 'top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;';
        notification.innerHTML = `
            <strong>Preview Mode:</strong> ${themeName.charAt(0).toUpperCase() + themeName.slice(1)} theme
            <div class="mt-2">
                <button class="btn btn-sm btn-success me-2" onclick="themeManager.confirmPreview()">Keep This Theme</button>
                <button class="btn btn-sm btn-secondary" onclick="themeManager.cancelPreview()">Cancel</button>
            </div>
        `;

        document.body.appendChild(notification);
    }

    hidePreviewNotification() {
        const notification = document.getElementById('theme-preview-notification');
        if (notification) {
            notification.remove();
        }
    }

    confirmPreview() {
        const notification = document.getElementById('theme-preview-notification');
        if (notification) {
            const themeName = this.currentTheme;
            this.switchTheme(themeName);
            notification.remove();
        }
    }

    cancelPreview() {
        // This will be handled by the timeout in previewTheme
        this.hidePreviewNotification();
    }
}

// Initialize theme manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.themeManager = new ThemeManager();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ThemeManager;
}
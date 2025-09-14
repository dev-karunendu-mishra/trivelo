# 🎨 Theme Specifications & Design Guide
## Trivelo Hotel Booking System Themes

---

## 📋 **Theme Overview**

This document provides detailed specifications for each theme available in the Trivelo hotel booking system, including color schemes, typography, layouts, and component designs.

---

## 🏨 **Default Theme**

### **Brand Identity**
- **Theme Name**: Default
- **Style**: Classic Hotel Booking
- **Target Audience**: General users, wide appeal
- **Personality**: Trustworthy, Professional, Clean

### **Color Palette**
```scss
// Primary Colors
$primary: #007bff;           // Bootstrap Blue
$secondary: #6c757d;         // Bootstrap Gray
$success: #28a745;           // Green for confirmations
$info: #17a2b8;             // Cyan for information
$warning: #ffc107;          // Yellow for warnings
$danger: #dc3545;           // Red for errors

// Hotel-Specific Colors
$hotel-accent: #ff6b35;     // Orange accent for CTAs
$hotel-gold: #ffd700;       // Gold for ratings/premium
$booking-success: #28a745;  // Green for successful bookings
$rating-gold: #ffb400;      // Gold for star ratings

// Neutral Colors
$white: #ffffff;
$light-gray: #f8f9fa;
$medium-gray: #e9ecef;
$dark-gray: #343a40;
$black: #000000;

// Background Colors
$body-bg: #ffffff;
$section-bg: #f8f9fa;
$card-bg: #ffffff;
```

### **Typography**
```scss
// Font Families
$font-family-sans-serif: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
$font-family-serif: 'Georgia', serif;

// Font Sizes
$font-size-xs: 0.75rem;     // 12px
$font-size-sm: 0.875rem;    // 14px
$font-size-base: 1rem;      // 16px
$font-size-lg: 1.125rem;    // 18px
$font-size-xl: 1.25rem;     // 20px
$font-size-2xl: 1.5rem;     // 24px
$font-size-3xl: 1.875rem;   // 30px
$font-size-4xl: 2.25rem;    // 36px

// Font Weights
$font-weight-light: 300;
$font-weight-normal: 400;
$font-weight-medium: 500;
$font-weight-semibold: 600;
$font-weight-bold: 700;

// Line Heights
$line-height-tight: 1.25;
$line-height-base: 1.6;
$line-height-loose: 1.8;
```

### **Component Styles**
```scss
// Buttons
.btn-primary {
    background: linear-gradient(45deg, $primary, lighten($primary, 10%));
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: $font-weight-medium;
    transition: all 0.3s ease;
    
    &:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba($primary, 0.3);
    }
}

// Cards
.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    
    &:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
}

// Hotel Cards
.hotel-card {
    .hotel-image {
        border-radius: 12px 12px 0 0;
        overflow: hidden;
        height: 200px;
        
        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        &:hover img {
            transform: scale(1.05);
        }
    }
    
    .hotel-rating {
        .rating-stars {
            color: $rating-gold;
            font-size: 1.1rem;
        }
    }
}
```

---

## 🌟 **Modern Theme**

### **Brand Identity**
- **Theme Name**: Modern
- **Style**: Contemporary & Tech-Forward
- **Target Audience**: Young professionals, tech-savvy users
- **Personality**: Innovative, Sleek, Dynamic

### **Color Palette**
```scss
// Primary Colors - Indigo & Purple Gradient
$primary: #6366f1;           // Indigo
$primary-light: #818cf8;     // Light Indigo
$primary-dark: #4f46e5;      // Dark Indigo
$secondary: #64748b;         // Slate Gray

// Gradient Colors
$gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
$gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
$gradient-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);

// Dark Mode Colors
$dark-bg: #0f172a;          // Dark Navy
$dark-surface: #1e293b;     // Dark Surface
$dark-border: #334155;      // Dark Border
$dark-text: #f1f5f9;       // Light Text

// Accent Colors
$accent-cyan: #06b6d4;      // Cyan
$accent-emerald: #10b981;   // Emerald
$accent-pink: #ec4899;      // Pink
$accent-yellow: #f59e0b;    // Amber
```

### **Typography**
```scss
// Modern Font Stack
$font-family-sans-serif: 'Inter', system-ui, -apple-system, sans-serif;
$font-family-display: 'Poppins', sans-serif;

// Modern Font Sizes (Larger scale)
$font-size-xs: 0.8rem;      // 12.8px
$font-size-sm: 0.9rem;      // 14.4px
$font-size-base: 1rem;      // 16px
$font-size-lg: 1.125rem;    // 18px
$font-size-xl: 1.3rem;      // 20.8px
$font-size-2xl: 1.6rem;     // 25.6px
$font-size-3xl: 2rem;       // 32px
$font-size-4xl: 2.5rem;     // 40px
$font-size-5xl: 3rem;       // 48px

// Modern Spacing
$spacing-unit: 0.25rem;     // 4px base unit
$spacing: (
    0: 0,
    1: $spacing-unit,       // 4px
    2: $spacing-unit * 2,   // 8px
    3: $spacing-unit * 3,   // 12px
    4: $spacing-unit * 4,   // 16px
    5: $spacing-unit * 5,   // 20px
    6: $spacing-unit * 6,   // 24px
    8: $spacing-unit * 8,   // 32px
    10: $spacing-unit * 10, // 40px
    12: $spacing-unit * 12, // 48px
    16: $spacing-unit * 16, // 64px
    20: $spacing-unit * 20, // 80px
);
```

### **Component Styles**
```scss
// Modern Buttons with Gradients
.btn-modern {
    background: $gradient-primary;
    border: none;
    border-radius: 10px;
    padding: 14px 28px;
    font-weight: 600;
    font-size: 0.95rem;
    letter-spacing: 0.025em;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    
    &:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px rgba($primary, 0.4);
    }
    
    &:active {
        transform: translateY(0);
    }
}

// Glass Morphism Cards
.card-glass {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

// Modern Hotel Cards
.hotel-card-modern {
    border-radius: 20px;
    overflow: hidden;
    background: linear-gradient(145deg, #ffffff, #f8fafc);
    box-shadow: 
        0 4px 6px rgba(0, 0, 0, 0.05),
        0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    
    &:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 
            0 20px 40px rgba(0, 0, 0, 0.1),
            0 6px 20px rgba(0, 0, 0, 0.05);
    }
    
    .hotel-image {
        position: relative;
        overflow: hidden;
        
        &::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.1) 100%);
        }
    }
}

// Animated Elements
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}
```

### **Dark Mode Support**
```scss
// Dark mode variants
@media (prefers-color-scheme: dark) {
    :root {
        --bs-body-bg: #{$dark-bg};
        --bs-body-color: #{$dark-text};
        --bs-card-bg: #{$dark-surface};
        --bs-border-color: #{$dark-border};
    }
    
    .card-glass {
        background: rgba(30, 41, 59, 0.8);
        border-color: rgba(255, 255, 255, 0.1);
    }
}

// Dark mode toggle
.dark-mode-toggle {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    
    .toggle-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: $gradient-primary;
        border: none;
        color: white;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        
        &:hover {
            transform: rotate(180deg);
        }
    }
}
```

---

## 💎 **Luxury Theme**

### **Brand Identity**
- **Theme Name**: Luxury
- **Style**: Premium & Elegant
- **Target Audience**: High-end customers, luxury travelers
- **Personality**: Sophisticated, Exclusive, Opulent

### **Color Palette**
```scss
// Luxury Color Scheme - Gold & Dark
$luxury-gold: #d4af37;      // Antique Gold
$luxury-gold-light: #f7e98e; // Light Gold
$luxury-gold-dark: #b8941f;  // Dark Gold

$luxury-black: #0d0d0d;     // Deep Black
$luxury-charcoal: #1a1a1a;  // Charcoal
$luxury-gray: #2c2c2c;      // Dark Gray
$luxury-silver: #c0c0c0;    // Silver
$luxury-platinum: #e5e4e2;  // Platinum

// Accent Colors
$luxury-burgundy: #800020;   // Deep Burgundy
$luxury-navy: #000080;       // Navy Blue
$luxury-emerald: #50c878;    // Emerald Green
$luxury-purple: #6a0dad;     // Deep Purple

// Text Colors
$luxury-text-primary: $luxury-platinum;
$luxury-text-secondary: $luxury-silver;
$luxury-text-muted: lighten($luxury-gray, 20%);
```

### **Typography**
```scss
// Luxury Font Stack
$font-family-serif: 'Playfair Display', Georgia, serif;
$font-family-sans-serif: 'Montserrat', 'Helvetica Neue', sans-serif;
$font-family-script: 'Dancing Script', cursive;

// Elegant Font Sizes
$font-size-xs: 0.75rem;
$font-size-sm: 0.875rem;
$font-size-base: 1rem;
$font-size-lg: 1.125rem;
$font-size-xl: 1.375rem;    // Slightly larger for luxury feel
$font-size-2xl: 1.75rem;
$font-size-3xl: 2.25rem;
$font-size-4xl: 3rem;
$font-size-5xl: 4rem;       // Large luxury headings

// Luxury Letter Spacing
$letter-spacing-tight: -0.025em;
$letter-spacing-normal: 0;
$letter-spacing-wide: 0.025em;
$letter-spacing-wider: 0.05em;
$letter-spacing-widest: 0.1em;
```

### **Patterns & Textures**
```scss
// Luxury Patterns
$luxury-pattern-dots: radial-gradient(circle at 1px 1px, rgba($luxury-gold, 0.15) 1px, transparent 0);
$luxury-pattern-lines: repeating-linear-gradient(
    45deg,
    transparent,
    transparent 2px,
    rgba($luxury-gold, 0.1) 2px,
    rgba($luxury-gold, 0.1) 4px
);

// Gold Gradients
$gradient-gold: linear-gradient(135deg, $luxury-gold-light 0%, $luxury-gold 50%, $luxury-gold-dark 100%);
$gradient-gold-text: linear-gradient(135deg, $luxury-gold 0%, $luxury-gold-light 50%, $luxury-gold 100%);
```

### **Component Styles**
```scss
// Luxury Buttons
.btn-luxury {
    background: $gradient-gold;
    border: 2px solid $luxury-gold;
    border-radius: 0; // Sharp edges for luxury feel
    padding: 16px 32px;
    font-family: $font-family-sans-serif;
    font-weight: 600;
    font-size: 0.95rem;
    letter-spacing: $letter-spacing-wide;
    text-transform: uppercase;
    color: $luxury-black;
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
    
    &::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(white, 0.2), transparent);
        transition: left 0.5s;
    }
    
    &:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba($luxury-gold, 0.4);
        
        &::before {
            left: 100%;
        }
    }
}

// Luxury Cards
.card-luxury {
    background: linear-gradient(145deg, $luxury-charcoal, $luxury-black);
    border: 1px solid $luxury-gold;
    border-radius: 0;
    color: $luxury-text-primary;
    position: relative;
    overflow: hidden;
    
    &::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: $gradient-gold;
    }
    
    .card-header {
        background: transparent;
        border-bottom: 1px solid rgba($luxury-gold, 0.3);
        
        .card-title {
            font-family: $font-family-serif;
            color: $luxury-gold;
            font-weight: 400;
            letter-spacing: $letter-spacing-wide;
        }
    }
}

// Luxury Hotel Cards
.hotel-card-luxury {
    background: $luxury-black;
    border: 1px solid $luxury-gold;
    color: $luxury-text-primary;
    transition: all 0.4s ease;
    position: relative;
    
    &::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: $gradient-gold;
        z-index: -1;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    &:hover {
        transform: translateY(-5px);
        
        &::before {
            opacity: 1;
        }
    }
    
    .hotel-name {
        font-family: $font-family-serif;
        color: $luxury-gold;
        font-size: $font-size-xl;
        font-weight: 400;
        letter-spacing: $letter-spacing-wide;
    }
    
    .hotel-rating {
        .rating-stars {
            color: $luxury-gold;
            filter: drop-shadow(0 0 2px rgba($luxury-gold, 0.5));
        }
    }
    
    .price-amount {
        font-family: $font-family-serif;
        color: $luxury-gold;
        font-size: $font-size-2xl;
        font-weight: 400;
    }
}

// Luxury Typography Classes
.text-luxury-gold {
    background: $gradient-gold-text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 600;
}

.heading-luxury {
    font-family: $font-family-serif;
    font-weight: 400;
    letter-spacing: $letter-spacing-wide;
    color: $luxury-gold;
    
    &.heading-script {
        font-family: $font-family-script;
        font-weight: 400;
        letter-spacing: normal;
    }
}
```

---

## 🎯 **Minimal Theme**

### **Brand Identity**
- **Theme Name**: Minimal
- **Style**: Clean & Simplified
- **Target Audience**: Design-conscious users, minimalists
- **Personality**: Clean, Focused, Zen-like

### **Color Palette**
```scss
// Minimal Monochrome
$minimal-black: #000000;
$minimal-white: #ffffff;
$minimal-gray-50: #fafafa;
$minimal-gray-100: #f5f5f5;
$minimal-gray-200: #eeeeee;
$minimal-gray-300: #e0e0e0;
$minimal-gray-400: #bdbdbd;
$minimal-gray-500: #9e9e9e;
$minimal-gray-600: #757575;
$minimal-gray-700: #616161;
$minimal-gray-800: #424242;
$minimal-gray-900: #212121;

// Accent Colors (Used sparingly)
$minimal-accent: #2196f3;   // Clean blue for links
$minimal-success: #4caf50;  // Green for success states
$minimal-error: #f44336;    // Red for errors
```

### **Typography**
```scss
// Minimal Font Stack
$font-family-sans-serif: 'Inter', 'Helvetica Neue', Arial, sans-serif;
$font-family-mono: 'SF Mono', Monaco, 'Cascadia Code', monospace;

// Minimal Font Scale
$font-size-xs: 0.75rem;     // 12px
$font-size-sm: 0.875rem;    // 14px
$font-size-base: 1rem;      // 16px
$font-size-lg: 1.125rem;    // 18px
$font-size-xl: 1.25rem;     // 20px
$font-size-2xl: 1.5rem;     // 24px
$font-size-3xl: 1.875rem;   // 30px
$font-size-4xl: 2.25rem;    // 36px

// Minimal Font Weights
$font-weight-light: 300;
$font-weight-normal: 400;
$font-weight-medium: 500;
$font-weight-semibold: 600;

// Generous Line Heights
$line-height-tight: 1.4;
$line-height-base: 1.8;     // More space for readability
$line-height-loose: 2;
```

### **Spacing & Layout**
```scss
// Minimal Grid System
$minimal-max-width: 1200px;
$minimal-container-padding: 24px;

// Generous Spacing Scale
$spacing-xs: 8px;
$spacing-sm: 16px;
$spacing-md: 24px;
$spacing-lg: 32px;
$spacing-xl: 48px;
$spacing-2xl: 64px;
$spacing-3xl: 96px;

// Minimal Borders
$border-width-thin: 1px;
$border-width-medium: 2px;
$border-radius: 0;          // No rounded corners
$border-color: $minimal-gray-200;
```

### **Component Styles**
```scss
// Minimal Buttons
.btn-minimal {
    background: transparent;
    border: 1px solid $minimal-black;
    border-radius: 0;
    padding: 12px 24px;
    font-weight: $font-weight-medium;
    color: $minimal-black;
    transition: all 0.2s ease;
    
    &:hover {
        background: $minimal-black;
        color: $minimal-white;
    }
    
    &.btn-minimal-primary {
        background: $minimal-black;
        color: $minimal-white;
        
        &:hover {
            background: transparent;
            color: $minimal-black;
        }
    }
}

// Minimal Cards
.card-minimal {
    background: $minimal-white;
    border: 1px solid $minimal-gray-200;
    border-radius: 0;
    padding: $spacing-lg;
    
    .card-title {
        font-size: $font-size-lg;
        font-weight: $font-weight-medium;
        color: $minimal-black;
        margin-bottom: $spacing-md;
        line-height: $line-height-tight;
    }
    
    .card-text {
        color: $minimal-gray-600;
        line-height: $line-height-base;
    }
}

// Minimal Hotel Cards
.hotel-card-minimal {
    background: $minimal-white;
    border: 1px solid $minimal-gray-200;
    transition: border-color 0.2s ease;
    
    &:hover {
        border-color: $minimal-black;
    }
    
    .hotel-image {
        border-bottom: 1px solid $minimal-gray-200;
        
        img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
    }
    
    .hotel-content {
        padding: $spacing-lg;
    }
    
    .hotel-name {
        font-size: $font-size-lg;
        font-weight: $font-weight-medium;
        color: $minimal-black;
        margin-bottom: $spacing-sm;
        line-height: $line-height-tight;
    }
    
    .hotel-location {
        font-size: $font-size-sm;
        color: $minimal-gray-600;
        margin-bottom: $spacing-md;
    }
    
    .hotel-rating {
        margin-bottom: $spacing-md;
        
        .rating-stars {
            color: $minimal-black;
            font-size: $font-size-sm;
        }
        
        .rating-text {
            font-size: $font-size-sm;
            color: $minimal-gray-600;
            margin-left: $spacing-sm;
        }
    }
    
    .hotel-price {
        border-top: 1px solid $minimal-gray-200;
        padding-top: $spacing-md;
        
        .price-amount {
            font-size: $font-size-xl;
            font-weight: $font-weight-medium;
            color: $minimal-black;
        }
        
        .price-period {
            font-size: $font-size-sm;
            color: $minimal-gray-600;
        }
    }
}

// Minimal Forms
.form-minimal {
    .form-group {
        margin-bottom: $spacing-lg;
    }
    
    .form-label {
        font-size: $font-size-sm;
        font-weight: $font-weight-medium;
        color: $minimal-black;
        margin-bottom: $spacing-sm;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    
    .form-control {
        border: none;
        border-bottom: 1px solid $minimal-gray-300;
        border-radius: 0;
        padding: 12px 0;
        font-size: $font-size-base;
        background: transparent;
        transition: border-color 0.2s ease;
        
        &:focus {
            border-color: $minimal-black;
            box-shadow: none;
            background: transparent;
        }
    }
}

// Minimal Navigation
.navbar-minimal {
    background: $minimal-white;
    border-bottom: 1px solid $minimal-gray-200;
    padding: $spacing-md 0;
    
    .navbar-brand {
        font-size: $font-size-lg;
        font-weight: $font-weight-medium;
        color: $minimal-black;
        text-decoration: none;
    }
    
    .navbar-nav {
        .nav-link {
            color: $minimal-gray-600;
            font-size: $font-size-sm;
            font-weight: $font-weight-medium;
            padding: 8px 16px;
            transition: color 0.2s ease;
            
            &:hover,
            &.active {
                color: $minimal-black;
            }
        }
    }
}

// Minimal Layout Classes
.section-minimal {
    padding: $spacing-3xl 0;
    
    .section-title {
        font-size: $font-size-3xl;
        font-weight: $font-weight-medium;
        color: $minimal-black;
        text-align: center;
        margin-bottom: $spacing-2xl;
        line-height: $line-height-tight;
    }
}

.container-minimal {
    max-width: $minimal-max-width;
    margin: 0 auto;
    padding: 0 $minimal-container-padding;
}

// Minimal Utilities
.text-minimal-black { color: $minimal-black; }
.text-minimal-gray { color: $minimal-gray-600; }
.text-minimal-light { color: $minimal-gray-400; }

.border-minimal { border: 1px solid $minimal-gray-200; }
.border-minimal-dark { border: 1px solid $minimal-black; }

.bg-minimal-light { background-color: $minimal-gray-50; }
.bg-minimal-white { background-color: $minimal-white; }
```

---

## 📱 **Responsive Design Standards**

### **Breakpoint System**
```scss
// Consistent across all themes
$breakpoints: (
    xs: 0,
    sm: 576px,
    md: 768px,
    lg: 992px,
    xl: 1200px,
    xxl: 1400px
);

// Mobile-first media queries
@mixin media-up($size) {
    @media (min-width: map-get($breakpoints, $size)) {
        @content;
    }
}

@mixin media-down($size) {
    @media (max-width: map-get($breakpoints, $size) - 1px) {
        @content;
    }
}

@mixin media-between($min, $max) {
    @media (min-width: map-get($breakpoints, $min)) and (max-width: map-get($breakpoints, $max) - 1px) {
        @content;
    }
}
```

### **Mobile Optimizations**
```scss
// Mobile-specific adjustments for all themes
@include media-down(md) {
    // Typography scaling
    .display-1 { font-size: 2.5rem; }
    .display-2 { font-size: 2rem; }
    .h1, h1 { font-size: 1.75rem; }
    .h2, h2 { font-size: 1.5rem; }
    
    // Spacing adjustments
    .section { padding: 2rem 0; }
    .container { padding: 0 1rem; }
    
    // Component adjustments
    .hotel-card {
        margin-bottom: 1.5rem;
        
        .hotel-image {
            height: 180px;
        }
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
        
        &:last-child {
            margin-bottom: 0;
        }
    }
    
    // Navigation adjustments
    .navbar-nav {
        .nav-link {
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
    }
}
```

---

## 🎨 **Theme Implementation Guidelines**

### **CSS Custom Properties Setup**
```scss
// Each theme should define these custom properties
:root {
    // Colors
    --theme-primary: #{$primary};
    --theme-secondary: #{$secondary};
    --theme-accent: #{$accent};
    --theme-text: #{$text-color};
    --theme-background: #{$bg-color};
    
    // Typography
    --theme-font-family: #{$font-family};
    --theme-font-size-base: #{$font-size-base};
    --theme-line-height: #{$line-height-base};
    
    // Spacing
    --theme-spacing-unit: #{$spacing-unit};
    --theme-border-radius: #{$border-radius};
    --theme-border-width: #{$border-width};
    
    // Shadows
    --theme-shadow-sm: #{$shadow-sm};
    --theme-shadow-md: #{$shadow-md};
    --theme-shadow-lg: #{$shadow-lg};
}
```

### **Component Override System**
```scss
// Base component styles
.btn {
    // Base styles that work across all themes
    display: inline-block;
    padding: 0.5rem 1rem;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    
    // Theme-specific styles
    background-color: var(--theme-primary);
    color: var(--theme-text-on-primary);
    border-radius: var(--theme-border-radius);
    font-family: var(--theme-font-family);
}

// Theme-specific overrides
.theme-luxury {
    .btn {
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 0;
    }
}

.theme-minimal {
    .btn {
        border: 1px solid var(--theme-primary);
        background: transparent;
        color: var(--theme-primary);
    }
}
```

This comprehensive theme specification provides all the design details needed for implementing the four distinct themes in the Trivelo hotel booking system.
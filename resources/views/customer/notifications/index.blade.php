@extends('layouts.app')

@section('title', 'Notifications - Trivelo')

@section('content')
<div class="container-fluid px-4">
    @php
        $themeConfig = config('theme.' . $theme ?? 'classic', config('theme.classic'));
    @endphp

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1 fw-bold" style="color: {{ $themeConfig['colors']['primary'] }};">
                        <i class="bi bi-bell me-2"></i>Notifications
                    </h2>
                    <p class="text-muted mb-0">
                        {{ $stats['unread'] }} unread of {{ $stats['total'] }} total notifications
                    </p>
                </div>
                <div class="d-flex gap-2">
                    @if($stats['unread'] > 0)
                        <form method="POST" action="{{ route('customer.notifications.read-all') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-check-all me-1"></i>Mark All Read
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid {{ $themeConfig['colors']['primary'] }} !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-primary fw-bold h4">{{ $stats['total'] }}</div>
                            <div class="text-muted small">Total Notifications</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-bell-fill text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #dc3545 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-danger fw-bold h4">{{ $stats['unread'] }}</div>
                            <div class="text-muted small">Unread</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-exclamation-circle-fill text-danger" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #198754 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-success fw-bold h4">{{ $stats['read'] }}</div>
                            <div class="text-muted small">Read</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #fd7e14 !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-warning fw-bold h4">{{ $stats['today'] }}</div>
                            <div class="text-muted small">Today</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-calendar-day-fill text-warning" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('customer.notifications') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="type" class="form-label">Notification Type</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">All Types</option>
                                @foreach($types as $value => $label)
                                    <option value="{{ $value }}" {{ request('type') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check mt-4 pt-2">
                                <input class="form-check-input" type="checkbox" name="show_expired" id="show_expired" value="1" 
                                       {{ request('show_expired') ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_expired">
                                    Show Expired
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mt-4 pt-1">
                                <button type="submit" class="btn text-white me-2" style="background: {{ $themeConfig['colors']['primary'] }};">
                                    <i class="bi bi-funnel me-1"></i>Filter
                                </button>
                                <a href="{{ route('customer.notifications') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    @if($notifications->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        @foreach($notifications as $notification)
                            <div class="notification-item border-bottom p-4 {{ $notification->isRead() ? 'read' : 'unread' }}" 
                                 data-notification-id="{{ $notification->id }}">
                                <div class="row align-items-center">
                                    <div class="col-1">
                                        <div class="notification-icon">
                                            <i class="bi {{ $notification->icon }} text-primary" style="font-size: 1.5rem;"></i>
                                        </div>
                                    </div>

                                    <div class="col-8">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="mb-0 me-2 {{ $notification->isRead() ? '' : 'fw-bold' }}">
                                                {{ $notification->title }}
                                            </h6>
                                            @if(!$notification->isRead())
                                                <span class="badge bg-primary">New</span>
                                            @endif
                                            <span class="badge {{ $notification->priority_class }} ms-2">
                                                {{ ucfirst($notification->priority) }}
                                            </span>
                                            @if($notification->isExpired())
                                                <span class="badge bg-secondary ms-2">Expired</span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-muted mb-2">{{ $notification->message }}</p>
                                        
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $notification->time_ago }}
                                            @if($notification->expires_at)
                                                • Expires {{ $notification->expires_at->diffForHumans() }}
                                            @endif
                                        </small>
                                    </div>

                                    <div class="col-3 text-end">
                                        <div class="btn-group" role="group">
                                            @if(!$notification->isRead())
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary mark-read-btn"
                                                        data-notification-id="{{ $notification->id }}"
                                                        title="Mark as read">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            @endif

                                            @if($notification->action_url)
                                                <a href="#" 
                                                   class="btn btn-sm btn-primary notification-action-btn"
                                                   data-notification-id="{{ $notification->id }}"
                                                   data-action-url="{{ $notification->action_url }}">
                                                    <i class="bi bi-arrow-right me-1"></i>View
                                                </a>
                                            @endif

                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger delete-notification-btn"
                                                    data-notification-id="{{ $notification->id }}"
                                                    title="Delete notification">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    <nav aria-label="Notifications pagination">
                        {{ $notifications->appends(request()->query())->links() }}
                    </nav>
                </div>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="py-5">
                    <i class="bi bi-bell-slash display-1 text-muted mb-3"></i>
                    <h3 class="mb-3">No Notifications</h3>
                    <p class="text-muted mb-4">
                        @if(request()->hasAny(['type', 'status']))
                            No notifications match your current filters.
                        @else
                            You don't have any notifications yet. They'll appear here when you receive them.
                        @endif
                    </p>
                    @if(request()->hasAny(['type', 'status']))
                        <a href="{{ route('customer.notifications') }}" class="btn text-white" style="background: {{ $themeConfig['colors']['primary'] }};">
                            <i class="bi bi-arrow-left me-2"></i>Show All Notifications
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .notification-item.unread {
        background-color: #f8f9fa;
        border-left: 4px solid {{ $themeConfig['colors']['primary'] }} !important;
    }

    .notification-item.read {
        background-color: #ffffff;
    }

    .notification-item:hover {
        background-color: #e9ecef !important;
    }

    .notification-item:last-child {
        border-bottom: none !important;
    }

    .notification-icon {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .badge-secondary {
        background-color: #6c757d !important;
    }
    
    .badge-primary {
        background-color: {{ $themeConfig['colors']['primary'] }} !important;
    }
    
    .badge-warning {
        background-color: #ffc107 !important;
        color: #000 !important;
    }
    
    .badge-danger {
        background-color: #dc3545 !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark notification as read
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.notificationId;
            markNotificationAsRead(notificationId);
        });
    });

    // Handle notification action (view)
    document.querySelectorAll('.notification-action-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationId = this.dataset.notificationId;
            const actionUrl = this.dataset.actionUrl;
            
            // Mark as read and then redirect
            markNotificationAsRead(notificationId, () => {
                window.location.href = actionUrl;
            });
        });
    });

    // Delete notification
    document.querySelectorAll('.delete-notification-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.dataset.notificationId;
            
            if (confirm('Are you sure you want to delete this notification?')) {
                deleteNotification(notificationId);
            }
        });
    });
});

function markNotificationAsRead(notificationId, callback = null) {
    fetch(`/customer/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('unread');
                notificationItem.classList.add('read');
                
                // Remove "New" badge
                const newBadge = notificationItem.querySelector('.badge.bg-primary');
                if (newBadge && newBadge.textContent === 'New') {
                    newBadge.remove();
                }
                
                // Remove mark as read button
                const markReadBtn = notificationItem.querySelector('.mark-read-btn');
                if (markReadBtn) {
                    markReadBtn.remove();
                }
            }
            
            // Update unread count (would need to implement counter in layout)
            updateNotificationCount();
            
            if (callback) callback();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to mark notification as read. Please try again.');
    });
}

function deleteNotification(notificationId) {
    fetch(`/customer/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || response.status === 302) {
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.style.transition = 'all 0.3s ease';
                notificationItem.style.opacity = '0';
                notificationItem.style.transform = 'translateX(-100%)';
                setTimeout(() => {
                    notificationItem.remove();
                    
                    // Check if no notifications left
                    if (document.querySelectorAll('.notification-item').length === 0) {
                        location.reload(); // Reload to show empty state
                    }
                }, 300);
            }
            
            updateNotificationCount();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete notification. Please try again.');
    });
}

function updateNotificationCount() {
    // This would update a notification counter in the header/navigation
    // Implementation would depend on your layout structure
    fetch('/customer/notifications/count')
    .then(response => response.json())
    .then(data => {
        // Update notification badge if exists
        const badge = document.querySelector('.notification-count-badge');
        if (badge) {
            badge.textContent = data.count;
            badge.style.display = data.count > 0 ? 'inline' : 'none';
        }
    });
}
</script>
@endpush
@endsection
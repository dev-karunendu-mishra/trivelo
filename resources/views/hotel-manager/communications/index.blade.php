@extends('hotel-manager.layouts.app')

@section('title', 'Communications')
@section('page-title', 'Guest Communications')
@section('page-subtitle', 'Send messages and notifications to your guests')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Guest Communications</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sendMessageModal">
                        <i class="fas fa-plus"></i> Send Message
                    </button>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="communicationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab">
                                Messages <span class="badge bg-primary">{{ $messageCount ?? 0 }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="emails-tab" data-bs-toggle="tab" data-bs-target="#emails" type="button" role="tab">
                                Emails <span class="badge bg-success">{{ $emailCount ?? 0 }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                                Notifications <span class="badge bg-warning">{{ $notificationCount ?? 0 }}</span>
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="communicationTabsContent">
                        <!-- Messages Tab -->
                        <div class="tab-pane fade show active" id="messages" role="tabpanel">
                            <div class="mt-3">
                                @forelse($messages ?? [] as $message)
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="card-title">{{ $message['subject'] ?? 'No Subject' }}</h6>
                                                <p class="card-text">{{ $message['content'] ?? 'No content' }}</p>
                                                <small class="text-muted">
                                                    To: {{ $message['recipient'] ?? 'Unknown' }} | 
                                                    {{ $message['created_at'] ?? 'Unknown date' }}
                                                </small>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-reply"></i> Reply</a></li>
                                                    <li><a class="dropdown-item" href="#"><i class="fas fa-trash"></i> Delete</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No messages found</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        
                        <!-- Emails Tab -->
                        <div class="tab-pane fade" id="emails" role="tabpanel">
                            <div class="mt-3">
                                @forelse($emails ?? [] as $email)
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $email['subject'] ?? 'No Subject' }}</h6>
                                        <p class="card-text">{{ $email['content'] ?? 'No content' }}</p>
                                        <small class="text-muted">
                                            To: {{ $email['recipient'] ?? 'Unknown' }} | 
                                            {{ $email['sent_at'] ?? 'Unknown date' }}
                                        </small>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No emails found</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        
                        <!-- Notifications Tab -->
                        <div class="tab-pane fade" id="notifications" role="tabpanel">
                            <div class="mt-3">
                                @forelse($notifications ?? [] as $notification)
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h6 class="card-title">{{ $notification['title'] ?? 'No Title' }}</h6>
                                                <p class="card-text">{{ $notification['message'] ?? 'No message' }}</p>
                                                <small class="text-muted">{{ $notification['created_at'] ?? 'Unknown date' }}</small>
                                            </div>
                                            <span class="badge bg-{{ $notification['type'] ?? 'secondary' }}">
                                                {{ $notification['type'] ?? 'info' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-4">
                                    <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No notifications found</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="sendMessageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('hotel-manager.communications.send') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Send Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="messageType" class="form-label">Message Type</label>
                                <select class="form-select" id="messageType" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="message">Direct Message</option>
                                    <option value="email">Email</option>
                                    <option value="notification">Push Notification</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="recipient" class="form-label">Recipient</label>
                                <select class="form-select" id="recipient" name="recipient" required>
                                    <option value="">Select Recipient</option>
                                    @foreach($guests ?? [] as $guest)
                                    <option value="{{ $guest['id'] }}">{{ $guest['name'] ?? 'Unknown' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Message Content</label>
                        <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
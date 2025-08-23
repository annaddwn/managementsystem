@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
    <div class="card stats-card-minimal h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Total Progress</div>
                    <div class="text-dark display-6">{{ $stats['total_progress'] }}</div>
                </div>
                <div class="align-self-center icon-wrapper">
                    <i class="bi bi-list-task"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card stats-card-minimal h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Progress Saya</div>
                    <div class="text-dark display-6">{{ $stats['my_progress'] }}</div>
                </div>
                <div class="align-self-center icon-wrapper">
                    <i class="bi bi-person-check"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card stats-card-minimal h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Total Dokumen</div>
                    <div class="text-dark display-6">{{ $stats['total_documents'] }}</div>
                </div>
                <div class="align-self-center icon-wrapper">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card stats-card-minimal h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Notifikasi Baru</div>
                    <div class="text-dark display-6">{{ $stats['unread_notifications'] }}</div>
                </div>
                <div class="align-self-center icon-wrapper">
                    <i class="bi bi-bell"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="card-title mb-3 fw-semibold">Aksi Cepat</h5>
                <div class="row g-3">
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <div class="col-md-4">
                        <a href="{{ route('progress.create') }}" class="btn btn-custom-primary w-100 py-3">
                            <i class="bi bi-plus-circle me-2"></i>
                            Buat Progress Baru
                        </a>
                    </div>
                    @endif
                    
                    <div class="col-md-4">
                        <a href="{{ route('documents.create') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="bi bi-cloud-upload me-2"></i>
                            Upload Dokumen
                        </a>
                    </div>
                    
                    <div class="col-md-4">
                        <a href="{{ route('progress.index') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="bi bi-list-check me-2"></i>
                            Lihat Progress
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Content -->
<div class="row">
    <!-- Recent Progress -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="bi bi-clock-history me-2 text-primary"></i>Progress Terbaru
                    </h5>
                    <a href="{{ route('progress.index') }}" class="btn btn-outline-primary btn-sm">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($recentProgress->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-inbox display-6 d-block mb-2 opacity-50"></i>
                        <p class="mb-0">Belum ada progress</p>
                    </div>
                @else
                    @foreach($recentProgress as $progress)
                    <div class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm bg-primary rounded d-flex align-items-center justify-content-center">
                                    <i class="bi bi-list-task text-white small"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">
                                    <a href="{{ route('progress.show', $progress) }}" class="text-decoration-none text-dark fw-medium">
                                        {{ $progress->title }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-2">{{ Str::limit($progress->keterangan, 60) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-person me-1"></i>{{ $progress->uploader->name }}
                                    </small>
                                    <small class="text-muted">
                                        {{ $progress->due_date->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Documents -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-semibold">
                        <i class="bi bi-file-earmark-text me-2 text-success"></i>Dokumen Terbaru
                    </h5>
                    <a href="{{ route('documents.index') }}" class="btn btn-outline-primary btn-sm">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                @if($recentDocuments->isEmpty())
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-file-earmark display-6 d-block mb-2 opacity-50"></i>
                        <p class="mb-0">Belum ada dokumen</p>
                    </div>
                @else
                    @foreach($recentDocuments as $document)
                    <div class="p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm bg-success rounded d-flex align-items-center justify-content-center">
                                    <i class="bi bi-file-earmark text-white small"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">
                                    <a href="#" class="text-decoration-none text-dark fw-medium">
                                        {{ $document->judul }}
                                    </a>
                                </h6>
                                <p class="text-muted small mb-2">{{ Str::limit($document->keterangan, 60) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-person me-1"></i>{{ $document->uploader->name }}
                                    </small>
                                    <span class="badge bg-light text-dark border">{{ $document->jenis }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Notifications -->
@if($notifications->isNotEmpty())
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="card-title mb-0 fw-semibold">
                    <i class="bi bi-bell me-2 text-warning"></i>Notifikasi Terbaru
                </h5>
            </div>
            <div class="card-body">
                @foreach($notifications->take(3) as $notification)
                <div class="alert alert-light border-start border-4 border-primary d-flex align-items-start" role="alert">
                    <div class="flex-shrink-0 me-3">
                        <i class="bi bi-info-circle text-primary"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading mb-1">{{ $notification->title }}</h6>
                        <p class="mb-1">{{ $notification->message }}</p>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <button type="button" class="btn-close" onclick="markAsRead({{ $notification->id }})"></button>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<style>
/* Custom Variables */
:root {
    --primary-color: #051650;
    --primary-light: #073a7a;
}

/* Stats Cards */
.stats-card {
    transition: all 0.3s ease;
    background: white;
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content-center;
    font-size: 20px;
    color: white;
}

.bg-primary { background-color: var(--primary-color) !important; }

/* Avatar */
.avatar-sm {
    width: 36px;
    height: 36px;
    font-size: 14px;
}

/* Custom Buttons */
.btn-custom-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-custom-primary:hover {
    background-color: var(--primary-light);
    border-color: var(--primary-light);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(5, 22, 80, 0.3);
}

/* Outline buttons */
.btn-outline-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
}

.btn-outline-info:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13, 202, 240, 0.3);
}

.btn-outline-primary:hover {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

/* Cards */
.card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

/* Clean list items */
.border-bottom:last-child {
    border-bottom: none !important;
}

/* Alerts */
.alert {
    border-radius: 8px;
    border: none;
}

/* Typography */
.fw-semibold {
    font-weight: 600 !important;
}

/* Badge */
.badge {
    font-weight: 500;
}

/* Smooth animations */
* {
    transition: all 0.2s ease;
}
</style>

<script>
function markAsRead(notificationId) {
    // Add AJAX call to mark notification as read
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    });
}
</script>

@endsection
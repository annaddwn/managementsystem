@extends('layouts.app')

@section('title', 'Detail Progress')
@section('page-title', 'Detail Progress')

@section('content')
<!-- Success/Error Alerts -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <!-- Progress Details -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <h4 class="text-primary fw-bold mb-0">{{ $progress->title }}</h4>
                    @php
                        $statusConfig = [
                            'pending' => ['class' => 'status-pending', 'text' => 'Menunggu'],
                            'in_progress' => ['class' => 'status-progress', 'text' => 'Dikerjakan'],
                            'completed' => ['class' => 'status-completed', 'text' => 'Selesai'],
                        ];
                        $config = $statusConfig[$progress->status] ?? ['class' => 'status-pending', 'text' => 'Unknown'];
                    @endphp
                    <span class="badge {{ $config['class'] }} px-3 py-2">{{ $config['text'] }}</span>
                </div>

                <!-- Creator Info -->
                <div class="info-row">
                    <div class="info-label">Dibuat oleh</div>
                    <div class="info-content">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-3">
                                {{ strtoupper(substr($progress->uploader->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-semibold">{{ $progress->uploader->name }}</div>
                                <small class="text-muted">{{ ucfirst($progress->uploader->role) }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deadline Info -->
                <div class="info-row">
                    <div class="info-label">Deadline</div>
                    <div class="info-content">
                        <div class="fw-semibold">{{ $progress->due_date->format('d F Y') }}</div>
                        <div class="deadline-status">
                            @if($progress->due_date->isPast())
                                <span class="text-danger">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Terlambat {{ $progress->due_date->diffForHumans() }}
                                </span>
                            @elseif($progress->due_date->isToday())
                                <span class="text-warning">
                                    <i class="bi bi-clock me-1"></i>
                                    Deadline hari ini
                                </span>
                            @else
                                <span class="text-muted">{{ $progress->due_date->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Created Date -->
                <div class="info-row">
                    <div class="info-label">Dibuat pada</div>
                    <div class="info-content">{{ $progress->created_at->format('d F Y, H:i') }}</div>
                </div>

                <!-- Description -->
                <div class="info-row">
                    <div class="info-label">Keterangan</div>
                    <div class="info-content">
                        <div class="description-box">
                            {!! nl2br(e($progress->keterangan)) !!}
                        </div>
                    </div>
                </div>

                <!-- File Download -->
                @if($progress->file_path)
                <div class="info-row">
                    <div class="info-label">File</div>
                    <div class="info-content">
                        <a href="{{ route('progress.download', $progress) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-2"></i>Download File
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Submissions -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="text-primary fw-bold mb-4">
                    <i class="bi bi-file-earmark-check me-2"></i>
                    Submissions ({{ $progress->submissions->count() }})
                </h5>

                @if($progress->submissions->isEmpty())
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h6>Belum Ada Submission</h6>
                        <p>Belum ada yang mengsubmit progress ini.</p>
                    </div>
                @else
                    @foreach($progress->submissions as $submission)
                    <div class="submission-card">
                        <div class="submission-header">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle avatar-success me-3">
                                    {{ strtoupper(substr($submission->submitter->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $submission->submitter->name }}</div>
                                    <small class="text-muted">{{ $submission->created_at->format('d F Y, H:i') }}</small>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $submissionConfig = [
                                        'submitted' => ['class' => 'status-submitted', 'text' => 'Disubmit'],
                                        'approved' => ['class' => 'status-approved', 'text' => 'Disetujui'],
                                        'rejected' => ['class' => 'status-rejected', 'text' => 'Ditolak'],
                                    ];
                                    $sConfig = $submissionConfig[$submission->status] ?? ['class' => 'status-submitted', 'text' => 'Unknown'];
                                @endphp
                                <span class="badge {{ $sConfig['class'] }}">{{ $sConfig['text'] }}</span>
                                
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if($submission->status !== 'approved')
                                        <li>
                                            <button type="button" class="dropdown-item text-success" onclick="showApprovalModal('{{ $submission->id }}')">
                                                <i class="bi bi-check-circle me-2"></i>Setujui
                                            </button>
                                        </li>
                                        @endif
                                        @if($submission->status !== 'rejected')
                                        <li>
                                            <button type="button" class="dropdown-item text-danger" onclick="showRejectionModal('{{ $submission->id }}')">
                                                <i class="bi bi-x-circle me-2"></i>Tolak
                                            </button>
                                        </li>
                                        @endif
                                        @if($submission->status !== 'submitted')
                                        <li>
                                            <form method="POST" action="{{ route('progress.update-submission-status', $submission) }}" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="submitted">
                                                <button type="submit" class="dropdown-item text-info">
                                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset ke Submitted
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="submission-content">
                            <div class="fw-semibold mb-2">Keterangan:</div>
                            <div class="description-box">
                                {!! nl2br(e($submission->keterangan)) !!}
                            </div>
                        </div>

                        @if($submission->file_path)
                        <div class="submission-file">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="bi bi-paperclip me-1"></i>File dilampirkan
                                </span>
                                <a href="{{ route('progress.download-submission', $submission) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-download me-2"></i>Download
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Actions -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h6 class="text-primary fw-bold mb-3">Aksi</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('progress.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                    
                    @if($progress->file_path)
                    <a href="{{ route('progress.download', $progress) }}" class="btn btn-outline-primary">
                        <i class="bi bi-download me-2"></i>Download File
                    </a>
                    @endif

                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <div class="mt-3 pt-3 border-top">
                        <div class="text-center mb-3">
                            <small class="text-muted fw-semibold">Update Status Progress</small>
                        </div>
                        
                        @if($progress->status !== 'pending')
                        <form method="POST" action="{{ route('progress.update-status', $progress) }}" class="mb-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="pending">
                            <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="bi bi-clock me-2"></i>Set Menunggu
                            </button>
                        </form>
                        @endif

                        @if($progress->status !== 'in_progress')
                        <form method="POST" action="{{ route('progress.update-status', $progress) }}" class="mb-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="btn btn-outline-warning btn-sm w-100">
                                <i class="bi bi-play-circle me-2"></i>Set Dikerjakan
                            </button>
                        </form>
                        @endif

                        @if($progress->status !== 'completed')
                        <form method="POST" action="{{ route('progress.update-status', $progress) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                <i class="bi bi-check-circle me-2"></i>Set Selesai
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Submit Progress (For Employees) -->
        @if(auth()->user()->isPegawai())
        @php
            $mySubmission = $progress->submissions->where('submitted_by', auth()->id())->first();
        @endphp
        
        @if(!$mySubmission)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <h6 class="text-primary fw-bold mb-3">Submit Progress</h6>
                <form action="{{ route('progress.submit', $progress) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-semibold">
                            Keterangan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="4" 
                                  placeholder="Jelaskan progress yang sudah dikerjakan" required>{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="file" class="form-label fw-semibold">
                            File <small class="text-muted">(opsional)</small>
                        </label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" 
                               id="file" name="file" 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        <div class="form-text">
                            Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (Max: 10MB)
                        </div>
                        @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-2"></i>Submit Progress
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4 text-center">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                <h6 class="mt-3 fw-bold">Progress Sudah Disubmit</h6>
                <p class="text-muted mb-0">Disubmit pada {{ $mySubmission->created_at->format('d F Y, H:i') }}</p>
            </div>
        </div>
        @endif
        @endif

        <!-- Statistics -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h6 class="text-primary fw-bold mb-3">Statistik</h6>
                
                <div class="text-center mb-4">
                    <div class="stat-number text-info">{{ $progress->submissions->where('status', 'submitted')->count() }}</div>
                    <small class="text-muted">Menunggu Review</small>
                </div>
                
                <div class="row text-center">
                    <div class="col-4">
                        <div class="stat-number text-primary">{{ $progress->submissions->count() }}</div>
                        <small class="text-muted">Total</small>
                    </div>
                    <div class="col-4">
                        <div class="stat-number text-success">{{ $progress->submissions->where('status', 'approved')->count() }}</div>
                        <small class="text-muted">Disetujui</small>
                    </div>
                    <div class="col-4">
                        <div class="stat-number text-danger">{{ $progress->submissions->where('status', 'rejected')->count() }}</div>
                        <small class="text-muted">Ditolak</small>
                    </div>
                </div>

                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                <div class="text-center mt-3 pt-3 border-top">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Kelola submission dengan dropdown di setiap item
                    </small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-success">
                    <i class="bi bi-check-circle me-2"></i>Setujui Submission
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin menyetujui submission ini?</p>
                <small class="text-muted">Tindakan ini akan mengubah status submission menjadi "Disetujui".</small>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <form id="approvalForm" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Ya, Setujui
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-x-circle me-2"></i>Tolak Submission
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin menolak submission ini?</p>
                <small class="text-muted">Tindakan ini akan mengubah status submission menjadi "Ditolak".</small>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <form id="rejectionForm" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-2"></i>Ya, Tolak
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Hidden forms for each submission (for modal actions) -->
@if(auth()->user()->isAdmin() || auth()->user()->isManager())
@foreach($progress->submissions as $submission)
<form id="approval-form-{{ $submission->id }}" method="POST" action="{{ route('progress.update-submission-status', $submission) }}" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" value="approved">
</form>
<form id="rejection-form-{{ $submission->id }}" method="POST" action="{{ route('progress.update-submission-status', $submission) }}" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" value="rejected">
</form>
@endforeach
@endif
@endsection

@section('scripts')
<style>
    :root {
        --primary-color: #051650;
        --primary-light: #073a7a;
        --success-color: #22c55e;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
        --light-bg: #f8fafc;
        --border-color: #e2e8f0;
    }

    /* Custom Button Styles */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover, .btn-primary:focus {
        background-color: var(--primary-light);
        border-color: var(--primary-light);
        box-shadow: 0 0 0 0.2rem rgba(5, 22, 80, 0.25);
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    /* Card Styles */
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(5, 22, 80, 0.1) !important;
    }

    /* Status Badges */
    .status-pending {
        background-color: #6b7280;
        color: white;
    }

    .status-progress {
        background-color: var(--warning-color);
        color: white;
    }

    .status-completed {
        background-color: var(--success-color);
        color: white;
    }

    .status-submitted {
        background-color: var(--info-color);
        color: white;
    }

    .status-approved {
        background-color: var(--success-color);
        color: white;
    }

    .status-rejected {
        background-color: var(--danger-color);
        color: white;
    }

    /* Info Rows */
    .info-row {
        display: flex;
        margin-bottom: 1.5rem;
        align-items: flex-start;
    }

    .info-row:last-child {
        margin-bottom: 0;
    }

    .info-label {
        width: 150px;
        flex-shrink: 0;
        font-weight: 600;
        color: var(--primary-color);
        padding-top: 2px;
    }

    .info-content {
        flex: 1;
        padding-left: 1rem;
    }

    /* Avatar Circles */
    .avatar-circle {
        width: 40px;
        height: 40px;
        background-color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    .avatar-success {
        background-color: var(--success-color);
    }

    /* Description Box */
    .description-box {
        background-color: var(--light-bg);
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid var(--primary-color);
        line-height: 1.6;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6b7280;
        margin-bottom: 0;
    }

    /* Submission Cards */
    .submission-card {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background-color: white;
        transition: all 0.3s ease;
    }

    .submission-card:hover {
        box-shadow: 0 4px 15px rgba(5, 22, 80, 0.08);
        border-color: var(--primary-color);
    }

    .submission-card:last-child {
        margin-bottom: 0;
    }

    .submission-header {
        display: flex;
        justify-content: between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .submission-content {
        margin-bottom: 1rem;
    }

    .submission-file {
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    /* Statistics */
    .stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    /* Deadline Status */
    .deadline-status {
        margin-top: 0.25rem;
        font-size: 0.875rem;
    }

    /* Form Controls */
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(5, 22, 80, 0.25);
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 20px 60px rgba(5, 22, 80, 0.15);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-row {
            flex-direction: column;
        }
        
        .info-label {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .info-content {
            padding-left: 0;
        }
        
        .submission-header {
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

<script>
    function showApprovalModal(submissionId) {
        if (confirm('Apakah Anda yakin ingin menyetujui submission ini?')) {
            document.getElementById('approval-form-' + submissionId).submit();
        }
    }

    function showRejectionModal(submissionId) {
        if (confirm('Apakah Anda yakin ingin menolak submission ini?')) {
            document.getElementById('rejection-form-' + submissionId).submit();
        }
    }

    document.querySelectorAll('form[action*="update-status"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const statusInput = this.querySelector('input[name="status"]');
            const statusValue = statusInput.value;
            
            let message = '';
            
            switch(statusValue) {
                case 'pending':
                    message = 'Apakah Anda yakin ingin mengubah status progress ke "Menunggu"?';
                    break;
                case 'in_progress':
                    message = 'Apakah Anda yakin ingin mengubah status progress ke "Sedang Dikerjakan"?';
                    break;
                case 'completed':
                    message = 'Apakah Anda yakin ingin menandai progress sebagai "Selesai"?';
                    break;
            }
            
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });


    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endsection
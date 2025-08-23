@extends('layouts.app')

@section('title', 'Progress')
@section('page-title', 'Progress')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Progress</h4>
                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                    <a href="{{ route('progress.create') }}" class="btn btn-custom-primary">
                        <i class="bi bi-plus-circle me-2"></i>Buat Progress
                    </a>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-custom-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Judul Progress</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Submissions</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($progress as $index => $item)
                                    <tr>
                                        <td>{{ $progress->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <span class="text-white fw-bold">{{ substr($item->uploader->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <div>{{ $item->uploader->name }}</div>
                                                    <small class="text-muted">{{ ucfirst($item->uploader->role) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-info rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-list-task text-white"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $item->title }}</div>
                                                    @if($item->keterangan)
                                                        <small class="text-muted">{{ Str::limit($item->keterangan, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div>
                                                <div>{{ $item->due_date->format('d/m/Y') }}</div>
                                                <small class="text-muted">
                                                    @if($item->due_date->isPast())
                                                        <span class="text-danger">
                                                            <i class="bi bi-exclamation-triangle"></i>
                                                            Terlambat
                                                        </span>
                                                    @elseif($item->due_date->isToday())
                                                        <span class="text-warning">
                                                            <i class="bi bi-clock"></i>
                                                            Hari ini
                                                        </span>
                                                    @else
                                                        {{ $item->due_date->diffForHumans() }}
                                                    @endif
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($item->status) {
                                                    'pending' => 'bg-secondary',
                                                    'in_progress' => 'bg-warning',
                                                    'completed' => 'bg-success',
                                                    default => 'bg-secondary'
                                                };
                                                $statusText = match($item->status) {
                                                    'pending' => 'Menunggu',
                                                    'in_progress' => 'Dikerjakan',
                                                    'completed' => 'Selesai',
                                                    default => 'Unknown'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-info me-1">{{ $item->submissions->count() }}</span>
                                                @if($item->submissions->count() > 0)
                                                    <small class="text-muted">submissions</small>
                                                @else
                                                    <small class="text-muted">Belum ada</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('progress.show', $item) }}" 
                                                   class="btn btn-outline-info" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($item->file_path)
                                                <a href="{{ route('progress.download', $item) }}" 
                                                   class="btn btn-outline-success" title="Download File">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                @endif
                                                
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-list-task display-4 d-block mb-3"></i>
                                                Belum ada progress
                                                <br>
                                                <small>
                                                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                                        Klik tombol "Buat Progress" untuk membuat progress baru.
                                                    @else
                                                        Belum ada progress yang perlu dikerjakan.
                                                    @endif
                                                </small>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($progress->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $progress->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}

/* Custom table header dengan warna #051650 */
.table-custom-dark {
    background-color: #051650 !important;
    color: white;
}

.table-custom-dark th {
    background-color: #051650 !important;
    color: white;
    border-color: rgba(255, 255, 255, 0.2);
}

/* Custom button dengan warna #051650 */
.btn-custom-primary {
    background-color: #051650;
    border-color: #051650;
    color: white;
}

.btn-custom-primary:hover {
    background-color: #073a7a;
    border-color: #073a7a;
    color: white;
}

.btn-custom-primary:focus,
.btn-custom-primary.focus {
    background-color: #073a7a;
    border-color: #073a7a;
    box-shadow: 0 0 0 0.2rem rgba(5, 22, 80, 0.5);
}

.btn-custom-primary:active,
.btn-custom-primary.active,
.show > .btn-custom-primary.dropdown-toggle {
    background-color: #073a7a;
    border-color: #073a7a;
}
</style>

@endsection
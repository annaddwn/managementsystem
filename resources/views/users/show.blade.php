@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Detail User</h4>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="avatar-lg bg-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3">
                                <span class="text-white fw-bold display-6">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger fs-6">Admin</span>
                            @elseif($user->role === 'manager')
                                <span class="badge bg-warning fs-6">Manager</span>
                            @else
                                <span class="badge bg-info fs-6">Pegawai</span>
                            @endif
                        </div>

                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold" width="30%">
                                                <i class="bi bi-person me-2 text-primary"></i>Nama Lengkap
                                            </td>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">
                                                <i class="bi bi-envelope me-2 text-primary"></i>Email
                                            </td>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">
                                                <i class="bi bi-shield-check me-2 text-primary"></i>Role
                                            </td>
                                            <td>
                                                @if($user->role === 'admin')
                                                    <span class="badge bg-danger">Admin</span>
                                                @elseif($user->role === 'manager')
                                                    <span class="badge bg-warning">Manager</span>
                                                @else
                                                    <span class="badge bg-info">Pegawai</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">
                                                <i class="bi bi-calendar-plus me-2 text-primary"></i>Tanggal Dibuat
                                            </td>
                                            <td>{{ $user->created_at->format('d F Y, H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">
                                                <i class="bi bi-calendar-check me-2 text-primary"></i>Terakhir Diupdate
                                            </td>
                                            <td>{{ $user->updated_at->format('d F Y, H:i') }}</td>
                                        </tr>
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-graph-up text-primary display-6 mb-2"></i>
                                    <h5 class="mb-1">{{ $user->progress()->count() }}</h5>
                                    <small class="text-muted">Progress Dibuat</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-file-earmark-text text-info display-6 mb-2"></i>
                                    <h5 class="mb-1">{{ $user->progressSubmissions()->count() }}</h5>
                                    <small class="text-muted">Submission</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-file-earmark text-warning display-6 mb-2"></i>
                                    <h5 class="mb-1">{{ $user->documents()->count() }}</h5>
                                    <small class="text-muted">Dokumen</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-bell text-success display-6 mb-2"></i>
                                    <h5 class="mb-1">{{ $user->notifications()->count() }}</h5>
                                    <small class="text-muted">Notifikasi</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-4">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <div>
                                @if(!$user->isAdmin() || auth()->user()->isAdmin())
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil me-2"></i>Edit User
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
}
</style>
@endsection
@extends('layouts.app')

@section('title', 'Dokumen')
@section('page-title', 'Dokumen')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Dokumen</h4>
                    <a href="{{ route('documents.create') }}" class="btn btn-custom-primary">
                        <i class="bi bi-cloud-upload me-2"></i>Upload Dokumen
                    </a>
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
                                    <th>Jenis</th>
                                    <th>Nama</th>
                                    <th>Judul Dokumen</th>
                                    <th>Keterangan</th>                                
                                    <th>Tanggal Upload</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $index => $document)
                                    <tr>
                                        <td>{{ $documents->firstItem() + $index }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $document->jenis }}</span>
                                        </td>
                                        <td>{{ $document->uploader->name }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-success rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-file-earmark text-white"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $document->judul }}</div>
                                                    
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $document->keterangan }}</td>
                                        <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('documents.download', $document) }}"
                                                   class="btn btn-outline-success" title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                               
                                                @if(auth()->user()->isAdmin())
                                                    <button type="button"
                                                            class="btn btn-outline-danger"
                                                            title="Hapus"
                                                            onclick="confirmDelete('{{ $document->judul }}', '{{ route('documents.destroy', $document) }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-file-earmark-text display-4 d-block mb-3"></i>
                                                Belum ada dokumen
                                                <br>
                                                <small>Klik tombol "Upload Dokumen" untuk menambah dokumen baru.</small>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($documents->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $documents->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus dokumen <strong id="documentName"></strong>?</p>
                <p class="text-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Tindakan ini tidak dapat dibatalkan!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
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

<script>
function confirmDelete(documentName, deleteUrl) {
    document.getElementById('documentName').textContent = documentName;
    document.getElementById('deleteForm').action = deleteUrl;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

@endsection
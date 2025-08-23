@extends('layouts.app')

@section('title', 'Upload Dokumen')
@section('page-title', 'Upload Dokumen Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-cloud-upload me-2"></i>Form Upload Dokumen
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="jenis" class="form-label">
                            Jenis Dokumen <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('jenis') is-invalid @enderror" id="jenis" name="jenis" required>
                            <option value="">Pilih jenis dokumen</option>
                            <option value="Laporan" {{ old('jenis') == 'Laporan' ? 'selected' : '' }}>Laporan</option>
                            <option value="Proposal" {{ old('jenis') == 'Proposal' ? 'selected' : '' }}>Proposal</option>
                            <option value="Kontrak" {{ old('jenis') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                            <option value="Surat" {{ old('jenis') == 'Surat' ? 'selected' : '' }}>Surat</option>
                            <option value="Presentasi" {{ old('jenis') == 'Presentasi' ? 'selected' : '' }}>Presentasi</option>
                            <option value="Panduan" {{ old('jenis') == 'Panduan' ? 'selected' : '' }}>Panduan</option>
                            <option value="SOP" {{ old('jenis') == 'SOP' ? 'selected' : '' }}>SOP</option>
                            <option value="Lainnya" {{ old('jenis') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="judul" class="form-label">
                            Judul Dokumen <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                               id="judul" name="judul" value="{{ old('judul') }}" 
                               placeholder="Masukkan judul dokumen" required>
                        @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">
                            Keterangan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="4" 
                                  placeholder="Jelaskan tentang dokumen ini" required>{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="file" class="form-label">
                            File Dokumen <span class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" 
                               id="file" name="file" required
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                        <div class="form-text">
                            Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, JPEG, PNG (Max: 10MB)
                        </div>
                        @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- File Preview -->
                    <div id="filePreview" class="mb-4" style="display: none;">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Preview File:</h6>
                                <div id="fileInfo" class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark display-6 text-primary me-3"></i>
                                    <div>
                                        <div id="fileName" class="fw-bold"></div>
                                        <div id="fileSize" class="text-muted small"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-custom-primary">
                            <i class="bi bi-cloud-upload me-2"></i>Upload Dokumen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
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
    // File input handling
    document.getElementById('file').addEventListener('change', function() {
        const file = this.files[0];
        const preview = document.getElementById('filePreview');
        
        if (file) {
            // Check file size
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                alert('File terlalu besar! Maksimum 10MB.');
                this.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Show preview
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);
            preview.style.display = 'block';
            
            // Update icon based on file type
            const icon = document.querySelector('#fileInfo i');
            icon.className = getFileIcon(file.name);
        } else {
            preview.style.display = 'none';
        }
    });
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        const icons = {
            'pdf': 'bi bi-file-earmark-pdf display-6 text-danger me-3',
            'doc': 'bi bi-file-earmark-word display-6 text-primary me-3',
            'docx': 'bi bi-file-earmark-word display-6 text-primary me-3',
            'xls': 'bi bi-file-earmark-excel display-6 text-success me-3',
            'xlsx': 'bi bi-file-earmark-excel display-6 text-success me-3',
            'ppt': 'bi bi-file-earmark-ppt display-6 text-warning me-3',
            'pptx': 'bi bi-file-earmark-ppt display-6 text-warning me-3',
            'jpg': 'bi bi-file-earmark-image display-6 text-info me-3',
            'jpeg': 'bi bi-file-earmark-image display-6 text-info me-3',
            'png': 'bi bi-file-earmark-image display-6 text-info me-3'
        };
        return icons[ext] || 'bi bi-file-earmark display-6 text-muted me-3';
    }
</script>
@endsection
@extends('layouts.app')

@section('title', 'Buat Progress')
@section('page-title', 'Buat Progress Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Form Buat Progress
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('progress.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">
                            Judul Progress <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" 
                               placeholder="Masukkan judul progress" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">
                            Keterangan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="4" 
                                  placeholder="Jelaskan detail progress yang harus dikerjakan" required>{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label">
                            Tanggal Deadline <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                               id="due_date" name="due_date" value="{{ old('due_date') }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                        @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="file" class="form-label">
                            File Pendukung <small class="text-muted">(opsional)</small>
                        </label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" 
                               id="file" name="file" 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        <div class="form-text">
                            Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX (Max: 10MB)
                        </div>
                        @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('progress.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Buat Progress
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Set minimum date to tomorrow
    document.getElementById('due_date').min = new Date(Date.now() + 86400000).toISOString().split('T')[0];
    
    // File input validation
    document.getElementById('file').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                alert('File terlalu besar! Maksimum 10MB.');
                this.value = '';
            }
        }
    });
</script>
@endsection
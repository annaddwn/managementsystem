<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Company System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0" style="box-shadow: 0 10px 40px rgba(5, 22, 80, 0.1); border-radius: 16px;">
                    <div class="card-body p-5 py-6">
                        <!-- Header -->
                        <div class="text-center mb-5">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px; background-color: #051650; border-radius: 12px; overflow: hidden;">
                                    <img src="{{ asset('images/logo.png') }}" 
                                         style="width: 40px; height: 40px; object-fit: contain;">
                                </div>
                            </div>
                            <h4 class="fw-bold mb-2" style="color: #051650;">Login</h4>
                        </div>
                        
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-medium" style="color: #051650; font-size: 14px;">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autofocus
                                       placeholder="Masukkan email Anda"
                                       style="border: 1.5px solid #e9ecef; border-radius: 8px; padding: 12px 16px; font-size: 14px; transition: all 0.3s ease;"
                                       onfocus="this.style.borderColor='#051650'; this.style.boxShadow='0 0 0 3px rgba(5, 22, 80, 0.1)'"
                                       onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                                @error('email')
                                    <div class="invalid-feedback" style="font-size: 12px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-medium" style="color: #051650; font-size: 14px;">Password</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required
                                       placeholder="Masukkan password Anda"
                                       style="border: 1.5px solid #e9ecef; border-radius: 8px; padding: 12px 16px; font-size: 14px; transition: all 0.3s ease;"
                                       onfocus="this.style.borderColor='#051650'; this.style.boxShadow='0 0 0 3px rgba(5, 22, 80, 0.1)'"
                                       onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                                @error('password')
                                    <div class="invalid-feedback" style="font-size: 12px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           class="form-check-input" 
                                           id="remember" 
                                           name="remember"
                                           style="border-color: #e9ecef;">
                                    <label class="form-check-label text-muted" for="remember" style="font-size: 14px;">
                                        Ingat saya
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" 
                                        class="btn fw-medium" 
                                        style="background-color: #051650; color: white; border: none; border-radius: 8px; padding: 12px; font-size: 14px; transition: all 0.3s ease;"
                                        onmouseover="this.style.backgroundColor='#0a2570'"
                                        onmouseout="this.style.backgroundColor='#051650'">
                                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                                </button>
                            </div>
                        </form>
                        
                        <!-- Demo Accounts -->
                        <div class="text-center">
                            <button class="btn btn-link p-0 text-muted" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    data-bs-target="#demoAccounts" 
                                    aria-expanded="false" 
                                    aria-controls="demoAccounts"
                                    style="font-size: 12px; text-decoration: none;">
                                <i class="fas fa-chevron-down me-1"></i>Lihat Akun Demo
                            </button>
                        </div>
                        
                        <div class="collapse mt-3" id="demoAccounts">
                            <div class="bg-light p-3" style="border-radius: 8px; border-left: 3px solid #051650;">
                                <div class="row g-3 text-center">
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <small class="fw-bold" style="color: #051650;">Admin</small><br>
                                            <small class="text-muted" style="font-size: 11px;">
                                                admin@example.com<br>
                                                <span class="text-dark">password123</span>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <small class="fw-bold" style="color: #051650;">Manager</small><br>
                                            <small class="text-muted" style="font-size: 11px;">
                                                manager@example.com<br>
                                                <span class="text-dark">password123</span>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-0">
                                            <small class="fw-bold" style="color: #051650;">Pegawai</small><br>
                                            <small class="text-muted" style="font-size: 11px;">
                                                pegawai@example.com<br>
                                                <span class="text-dark">password123</span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="text-center mt-4">
                    <small class="text-muted" style="font-size: 12px;">
                        © 2024 Company System. All rights reserved.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for cleaner look */
.form-control:focus {
    border-color: #051650 !important;
    box-shadow: 0 0 0 3px rgba(5, 22, 80, 0.1) !important;
}

.form-check-input:checked {
    background-color: #051650 !important;
    border-color: #051650 !important;
}

.btn-link:hover {
    color: #051650 !important;
}

.collapse .bg-light {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Remove default form validation styles */
.was-validated .form-control:valid,
.form-control.is-valid {
    border-color: #051650;
    background-image: none;
}

.was-validated .form-control:valid:focus,
.form-control.is-valid:focus {
    border-color: #051650;
    box-shadow: 0 0 0 3px rgba(5, 22, 80, 0.1);
}
</style>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
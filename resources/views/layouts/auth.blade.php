<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') - Medical E-Commerce</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom Auth CSS -->
    <style>
        :root {
            --primary-orange: #FF8B3D;
            --primary-cyan: #4ECDC4;
            --light-gray: #F8F9FA;
            --dark-text: #2C3E50;
        }

        body {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-cyan) 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }

        .auth-header {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-cyan) 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .logo {
            height: 60px;
            width: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .auth-body {
            padding: 40px 30px;
        }

        .form-control {
            border: 2px solid #E9ECEF;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 0.2rem rgba(255, 139, 61, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-cyan) 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 139, 61, 0.3);
        }

        .btn-link {
            color: var(--primary-orange);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .btn-link:hover {
            color: var(--primary-cyan);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .alert-danger {
            background-color: #FEF2F2;
            color: #DC2626;
        }

        .alert-success {
            background-color: #F0FDF4;
            color: #16A34A;
        }

        .form-check-input:checked {
            background-color: var(--primary-orange);
            border-color: var(--primary-orange);
        }

        .invalid-feedback {
            font-size: 14px;
        }

        .auth-footer {
            text-align: center;
            padding: 20px 30px;
            background-color: #F8F9FA;
            border-top: 1px solid #E9ECEF;
        }

        /* Mobile Optimization */
        @media (max-width: 480px) {
            .auth-container {
                padding: 10px;
            }
            
            .auth-header {
                padding: 30px 20px;
            }
            
            .auth-body {
                padding: 30px 20px;
            }
            
            .logo {
                height: 50px;
                margin-bottom: 15px;
            }
        }

        /* Loading state */
        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <img src="{{ asset('images/logo.png') }}" alt="Medical E-Commerce" class="logo">
                <h1 class="h3 mb-0">@yield('header', 'Medical E-Commerce')</h1>
                <p class="mb-0 opacity-75">@yield('subtitle', 'Professional Healthcare Solutions')</p>
            </div>

            <!-- Body -->
            <div class="auth-body">
                <!-- Flash Messages -->
                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        @if($errors->count() == 1)
                            {{ $errors->first() }}
                        @else
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Main Content -->
                @yield('content')
            </div>

            <!-- Footer -->
            @hasSection('footer')
                <div class="auth-footer">
                    @yield('footer')
                </div>
            @endif
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Auth JS -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Form submission loading state
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Please wait...';
                    }
                });
            });
        });

        // Input validation styling
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(function(input) {
                input.addEventListener('blur', function() {
                    if (this.value.trim() !== '') {
                        this.classList.add('is-valid');
                        this.classList.remove('is-invalid');
                    }
                });
                
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid', 'is-valid');
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>

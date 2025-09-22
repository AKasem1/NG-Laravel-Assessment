@extends('layouts.app')

@section('title', 'Login - MediCare')

@push('styles')
<style>
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: var(--gradient-soft);
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .auth-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 30%, rgba(255, 139, 61, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(78, 205, 196, 0.1) 0%, transparent 50%);
        animation: float 8s ease-in-out infinite;
    }
    
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius-large);
        box-shadow: var(--shadow-strong);
        padding: 3rem;
        max-width: 480px;
        width: 100%;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }
    
    .auth-title {
        font-size: 2.2rem;
        font-weight: 800;
        text-align: center;
        margin-bottom: 0.5rem;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .auth-subtitle {
        text-align: center;
        color: #64748b;
        margin-bottom: 2.5rem;
        font-weight: 500;
    }
    
    .btn-auth {
        background: var(--gradient-primary);
        border: none;
        color: white;
        padding: 14px 24px;
        border-radius: 12px;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }
    
    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
        color: white;
    }

    .form-floating {
        margin-bottom: 1.5rem;
    }

    .form-floating .form-control {
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 1rem 1rem;
        min-height: calc(3.5rem + 2px);
    }

    .form-floating .form-control:focus {
        background: rgba(255, 255, 255, 0.95);
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 0.2rem rgba(255, 139, 61, 0.25);
        transform: translateY(-2px);
    }

    .auth-links {
        text-align: center;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.3);
        margin-top: 1.5rem;
    }

    .auth-links a {
        color: var(--primary-orange);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .auth-links a:hover {
        color: var(--primary-cyan);
        text-decoration: underline;
    }

    .login-type-info {
        background: linear-gradient(135deg, rgba(78, 205, 196, 0.1), rgba(255, 139, 61, 0.1));
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        color: #475569;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="auth-card">
                    <h2 class="auth-title">Welcome Back!</h2>
                    <p class="auth-subtitle">Sign in to your account to continue</p>

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            @foreach($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('unified.login') }}">
                        @csrf
                        
                        <div class="form-floating">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            <label for="email">Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            <label for="password">Password</label>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Keep me signed in</label>
                        </div>

                        <button type="submit" class="btn btn-auth mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </button>
                    </form>
                    
                    <div class="auth-links">
                        <p>Don't have an account? 
                            <a href="{{ route('register') }}">Create Account</a>
                        </p>
                        <p>
                            <a href="{{ route('password.request') }}">Forgot your password?</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Create Account - MediCare')

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
        max-width: 520px;
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

    .register-info {
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
            <div class="col-md-8">
                <div class="auth-card">
                    <h2 class="auth-title">Create Account</h2>
                    <p class="auth-subtitle">Join MediCare for premium medical supplies</p>
                    
                    <div class="register-info">
                        <i class="bi bi-person-plus me-2"></i>
                        Creating a customer account for shopping and checkout
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('unified.register') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required autofocus>
                                    <label for="name">Full Name</label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            <label for="email">Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    <label for="password">Password</label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                    <label for="password_confirmation">Confirm Password</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            <label for="phone">Phone Number (Optional)</label>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating">
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" style="height: 80px;">{{ old('address') }}</textarea>
                            <label for="address">Address (Optional)</label>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-auth mb-3">
                            <i class="bi bi-person-plus me-2"></i>Create Account & Continue
                        </button>
                    </form>
                    
                    <div class="auth-links">
                        <p>Already have an account? 
                            <a href="{{ route('login') }}">Sign In</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

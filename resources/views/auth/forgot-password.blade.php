@extends('layouts.auth')

@section('title', 'Reset Password')

@section('header', 'Forgot Password?')
@section('subtitle', 'Reset your admin account password')

@section('content')
<div class="mb-4 text-center">
    <div class="mb-3">
        <i class="bi bi-key" style="font-size: 3rem; color: var(--primary-orange);"></i>
    </div>
    <p class="text-muted">
        No problem! Enter your email address and we'll send you a password reset link.
    </p>
</div>

<form method="POST" action="{{ route('password.email') }}" novalidate>
    @csrf
    
    <!-- Email Address -->
    <div class="mb-4">
        <label for="email" class="form-label">
            <i class="bi bi-envelope me-2"></i>Email Address
        </label>
        <input type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               id="email" 
               name="email" 
               value="{{ old('email') }}" 
               placeholder="Enter your registered email"
               required 
               autofocus>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Submit Button -->
    <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-send me-2"></i>
            Send Password Reset Link
        </button>
    </div>

    <!-- Help Text -->
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        <small>
            <strong>Having trouble?</strong><br>
            The reset link will be valid for 60 minutes. Check your spam folder if you don't see the email.
        </small>
    </div>
</form>
@endsection

@section('footer')
    <div class="text-center">
        <span class="text-muted">Remember your password?</span>
        <a href="{{ route('login') }}" class="btn-link ms-1">
            <i class="bi bi-arrow-left me-1"></i>Back to Login
        </a>
    </div>
    
    <div class="text-center mt-3">
        <small class="text-muted">
            <i class="bi bi-shield-lock me-1"></i>
            Secure password recovery
        </small>
    </div>
@endsection

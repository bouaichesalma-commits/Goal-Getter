@extends('layouts.guest')

@section('content')
<div class="auth-container">
    <h2 class="title">Create Account</h2>

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <div class="input-group">
            <label>Full Name</label>
            <input type="text" name="name" required>
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="input-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn-primary">Register</button>

        <p class="link">
            Already have an account?
            <a href="{{ route('login') }}">Login</a>
        </p>
    </form>
</div>
@endsection
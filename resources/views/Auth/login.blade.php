@extends('layouts.guest')

@section('content')
<div class="auth-container">
    <h2 class="title">Login</h2>

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required autofocus>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" class="btn-primary">Login</button>

        <p class="link">
            Don’t have an account?
            <a href="{{ route('register') }}">Register</a>
        </p>
    </form>
</div>
@endsection
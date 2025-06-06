<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mb-4 text-center">Login</h2>
                <form action="{{ route('login') }}" method="POST" class="border p-4 rounded bg-light">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    @if($errors->has('error'))  
                        <div class="text-danger mb-2">{{ $errors->first('error') }}</div>
                    @endif
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <p class="mt-3 text-center">Don’t have an account? <a href="{{ route('register') }}">Register</a></p>
            </div>
        </div>
    </div>
@endsection

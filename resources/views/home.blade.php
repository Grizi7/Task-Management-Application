@extends('layouts.app')

@section('content')

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Task Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @method('DELETE')
                            @csrf
                        </form>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <header class="bg-primary text-white text-center py-5 mb-4">
        <div class="container">
            <h1 class="display-4 fw-bold">Welcome to Task Manager</h1>
            <p class="lead">Stay organized and on top of your tasks with ease. Start managing your tasks today!</p>
        </div>
    </header>

    <section class="text-center py-5">
        <div class="container">
            <h2 class="mb-4">Get Started with Task Manager</h2>
            <p>Organize your daily tasks and stay focused on what's important. It's that simple!</p>
            @guest
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-3">Log in to Start</a>
            @else
                <a href="{{ route('tasks.index') }}" class="btn btn-primary btn-lg mt-3">Start Managing Tasks</a>
            @endguest
        </div>
    </section>

@endsection

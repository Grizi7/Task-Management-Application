@extends('layouts.app')

@section('content')
    @include('layouts.navbar')
    
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

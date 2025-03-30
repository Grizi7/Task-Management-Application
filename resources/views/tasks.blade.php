@extends('layouts.app')

@section('content')
    @include('layouts.navbar')

    <section>
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-9 col-xl-7">
                    <div class="card rounded-3">
                        <div class="card-body p-4">
                            @if($do == 'manage')
                            <h4 class="text-center my-3 pb-3">Tasks</h4>
                                @if($tasks->IsEmpty())
                                    <div class="alert alert-warning" role="alert">
                                        No tasks found. Please create a new task.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table mb-4">
                                            <thead>
                                                <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Title</th>
                                                    <th scope="col">Description</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col" class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tasks as $task)
                                                    <tr>
                                                        <th scope="row">{{ $task->id }}</th>
                                                        <td>{{ $task->title }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#taskModal{{ $task->id }}">
                                                                View Description
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <span class="badge {{ $task->completed ? 'bg-success' : 'bg-warning' }}">
                                                                {{ $task->completed ? 'Completed' : 'Pending' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{route('tasks.edit', $task->id)}}" class="btn btn-primary">Edit</a>
                                                            <a href="{{ route('tasks.destroy', $task->id) }}" class="btn btn-danger ms-1" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $task->id }}').submit();">Delete</a>
                                                            <form id="delete-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: none;">
                                                                @method('DELETE')
                                                                @csrf
                                                            </form>
                                                            @if (!$task->completed)
                                                                
                                                                <a href="{{ route('tasks.finish', $task->id) }}" class="btn btn-success ms-1" onclick="event.preventDefault(); document.getElementById('complete-form-{{ $task->id }}').submit();">finish</a>
                                                                <form id="complete-form-{{ $task->id }}" action="{{ route('tasks.finish', $task->id) }}" method="POST" style="display: none;">
                                                                    @method('PUT')
                                                                    @csrf
                                                                </form>

                                                            @endif
                                                        </td>
                                                    </tr>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="taskModal{{ $task->id }}" tabindex="-1" aria-labelledby="taskModalLabel{{ $task->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="taskModalLabel{{ $task->id }}">Task Description</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    {{ $task->description }}
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <!-- Display pagination links -->
                                        {{ $tasks->links('vendor.pagination.bootstrap-5') }}
                                    </div>
                                @endif    
                                <a href="{{route('tasks.create')}}" class="btn btn-primary mb-3 float-end">Create New Task</a>

                            @elseif($do == 'create')

                                <h4 class="text-center my-3 pb-3">Create New Task</h4>

                                <form action="{{route('tasks.store')}}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                        @error('title')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                                        @error('description')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary float-end">Create Task</button>
                                </form>
                            
                            @elseif($do == 'edit')

                                <h4 class="text-center my-3 pb-3">Edit Task</h4>
                                {{-- {{dd($task)}} --}}
                                <form action="{{route('tasks.update', $task->id)}}" method="POST">
                                    @method('PUT')
                                    @csrf
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{ $task->title }}" required>
                                        @error('title')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ $task->description }}</textarea>
                                        @error('description')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary float-end">Update Task</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

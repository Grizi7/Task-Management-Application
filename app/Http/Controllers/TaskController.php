<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth()->user()->tasks;
        return view('tasks', [
            'title' => 'Tasks',
            'do' => 'manage',
            'tasks' => $tasks,
        ]);
    }

    public function create()
    {
        return view('tasks', [
            'title' => 'Create Task',
            'do' => 'create',
        ]);
    }

    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        auth()->user()->tasks()->create($data);
        return redirect()->route('tasks.index');
    }


    public function edit(Task $task)
    {

        return view('tasks', [
            'title' => 'Edit Task',
            'do' => 'edit',
            'task' => $task,
        ]);
    }

    public function update(TaskRequest $request, Task $task) 
    {
        $data = $request->validated();
        $task->update($data);
        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index');
    }

    public function finish(Task $task)
    {
        $task->update(['completed' => true]);
        return redirect()->route('tasks.index');
    }
}

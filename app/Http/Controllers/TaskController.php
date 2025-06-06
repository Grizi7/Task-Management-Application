<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use App\Notifications\TaskReminderNotification;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = auth()->user()->tasks()->paginate(10);
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


    public function unfinish(Task $task)
    {
        $task->update(['completed' => false]);
        return redirect()->route('tasks.index');
    }
    public function show(Task $task)
    {
        return view('tasks', [
            'title' => 'Task Details',
            'do' => 'show',
            'task' => $task,
        ]);
    }

}

<?php

use App\Models\Task;
use App\Events\TaskReminder;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Auth\AuthController;

Route::get('/',[HomeController::class, 'index'])->name('home');
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'store']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/verify-email', [AuthController::class, 'showVerifyEmailForm'])->name('verify-email');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
});
Route::delete('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('tasks')->group(function(){
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/{task:id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/finish/{task:id}', [TaskController::class, 'finish'])->name('tasks.finish');
    Route::get('/edit/{task:id}', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/update/{task:id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/delete/{task:id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
})->middleware('auth');

Route::get('/test-reminder', function () {
    $task = Task::whereNotNull('user_id')->first();

    if ($task) {
        broadcast(new TaskReminder($task));
        return 'Reminder broadcasted for task: ' . $task->title;
    }

    return 'No task found with user_id';
});

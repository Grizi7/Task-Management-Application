<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/',[HomeController::class, 'index'])->name('home');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::delete('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('tasks')->group(function(){
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/finish/{task:id}', [TaskController::class, 'finish'])->name('tasks.finish');
    Route::get('/edit/{task:id}', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/update/{task:id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/delete/{task:id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
})->middleware('auth');

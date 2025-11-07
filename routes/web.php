<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('dashboard');
});

route::get('/tasks', [TaskController::class, 'index'])->name('task.index');

Route::get('/task/create', [TaskController::class, 'create'])->name('task.create');

Route::post('/tasks', [TaskController::class, 'store'])->name('task.store');
Route::post('/task/{task}/update', [TaskController::class, 'update'])->name('task.update');
Route::delete('/task/{task}', [TaskController::class, 'destroy'])->name('task.destroy');

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TaskController;

// Route::get('/', function () {
    
//     return view('dashboard');
// });
Route::get('/', function () {
    return view('dashboard');
})->middleware('jwt');


Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');


Route::get('/register', function () {
    return view('auth.register');  
})->name('register');



route::get('/tasks', [TaskController::class, 'index'])->name('task.index');

Route::get('/task/create', [TaskController::class, 'create'])->name('task.create');

Route::post('/tasks', [TaskController::class, 'store'])->name('task.store');
Route::post('/task/{task}/update', [TaskController::class, 'update'])->name('task.update');
Route::delete('/task/{task}', [TaskController::class, 'destroy'])->name('task.destroy');

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Models\Task;
use Tymon\JWTAuth\Facades\JWTAuth;

// Route::get('/', function () {

//     return view('dashboard');
// });
Route::get('/', function () {
    $user = JWTAuth::parseToken()->authenticate();
    $tasks = Task::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(4);
    return view('dashboard', ['tasks' => $tasks]);
})->name('home')->middleware('jwt');


Route::get('/login', function () {
    return view('auth.login');
})->name('login');


Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::middleware(['jwt'])->group(function () {

    route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    route::get('/tasks/pending', [TaskController::class, 'pending'])->name('tasks.pending');
    route::get('/tasks/completed', [TaskController::class, 'completed'])->name('tasks.completed');

    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');

    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}/update', [TaskController::class, 'update'])->name('tasks.update');

    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

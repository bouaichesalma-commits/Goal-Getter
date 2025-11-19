<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\View\Components\taskcard;

Route::get('/', function () {
   return response()->json(['message' => 'Hello world!']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt')->group(function () {
   Route::get('/user', [AuthController::class, 'getUser']);
   //   Route::put('/user', [AuthController::class, 'updateUser']);
   Route::post('/logout', [AuthController::class, 'logout']);

   Route::post('/tasks/filter', [TaskController::class, 'filter'])->name('tasks.filter');

   /********************************Tasks with APi****************************************/
});

/********************************Tasks with APi****************************************/

Route::prefix('Tasks')->group(function () {


   Route::controller(TaskController::class)->group(function () {

      Route::get('/', 'indexApi');

      Route::post('/add', ' storeApi');

      Route::put('/update/{id}', 'updateApi');

      Route::delete('/delete/{id}', 'destroyApi');

      
   });

});

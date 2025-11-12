<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Task;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    //         View::composer('*', function ($view) {
    //     if ($user = JWTAuth::parseToken()->authenticate()) {
    //         $tasks = Task::where('user_id', $user->id)->paginate(2);
    //         $view->with('tasks', $tasks);
    //     }
    // });
    }
}

<?php

namespace App\View\Components;

use Closure;
use App\Models\Task as TaskModel;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class task extends Component
{
    public $tasks;
    public $total;
    public $low;
    public $medium;
    public $high;
    
    /**
     * Create a new component instance.
     */

    public function __construct()
    {
        
         $user = JWTAuth::user();
        $this->tasks = TaskModel::where('user_id', $user->id)->paginate(4);
        $this->total = TaskModel::where('user_id', $user->id)->count();
        $this->low = TaskModel::where('user_id', $user->id)->where('priority', 'low')->count();
        $this->medium = TaskModel::where('user_id', $user->id)->where('priority', 'medium')->count();
        $this->high = TaskModel::where('user_id', $user->id)->where('priority', 'high')->count();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.task');
    }
}

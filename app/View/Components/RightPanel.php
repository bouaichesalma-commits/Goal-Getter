<?php

namespace App\View\Components;


use Illuminate\View\Component;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class RightPanel extends Component
{
     public int $tasksCount = 0;
    public int $completedCount = 0;
    public int $pendingCount = 0;
    public int $completionPercent = 0;
    public string $completionRate = '0%';
    public $recent; // Collection
    public $tasks;  // LengthAwarePaginator

    public function __construct()
    {
        $user = JWTAuth::user();
        

        $this->tasksCount = Task::where('user_id', $user->id)->count();
        $this->completedCount = Task::where('user_id', $user->id)
                                    ->where('is_completed', 1)->count();
        $this->pendingCount = $this->tasksCount - $this->completedCount;
        $this->completionPercent = $this->tasksCount ? round($this->completedCount * 100 / $this->tasksCount) : 0;
        $this->completionRate = $this->completionPercent . '%';
        $this->recent = Task::where('user_id', $user->id)->latest()->take(4)->get();
    }

    public function render()
    {
      
        return view('components.right-panel', [
            'tasksCount' => $this->tasksCount,
            'completedCount' => $this->completedCount,
            'pendingCount' => $this->pendingCount,
            'completionPercent' => $this->completionPercent,
            'completionRate' => $this->completionRate,
            'recent' => $this->recent,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Exception;

class TaskController extends Controller
{
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $tasks = Task::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(4);
    
        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $task = new Task();
            $task->title = $request->input('title');
            $task->description = $request->input('description');
            $task->is_completed = $request->input('is_completed', false);
            $task->priority = $request->input('priority', 'medium'); // Add priority with default
            $task->user_id = $user->id;
            $task->save();

            $tasks = Task::where('user_id', $user->id)->get();
            return redirect()->route('tasks.index')->with('success', 'Task created successfully');

        } catch (Exception $e) {
            Log::error('Error creating task: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function edit($id)
    {
        $task = Task::findOrFail($id);

        return view('tasks.edit', compact('task')); 
    }

    public function update(Request $request, Task $task)
    {
        try {

            $user = JWTAuth::parseToken()->authenticate();

            if ($task->user_id !== $user->id) {
                
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $task->title = $request->input('title', $task->title);
            $task->description = $request->input('description', $task->description);
            $task->is_completed = $request->input('is_completed', $task->is_completed);
            $task->priority = $request->input('priority', $task->priority); // Add priority update
            $task->save();

              return redirect()->route('tasks.index')->with('success', 'Task updated successfully');

        } catch (Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
             return redirect()->route('tasks.index')->with('error', 'Could Modify task');
        }
    }

    public function destroy(Task $task)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($task->user_id !== $user->id) {
                
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $task->delete();


            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');


        } catch (Exception $e) {
          
            return redirect()->route('tasks.index')->with('error', 'Could not delete task');
            
        }
    }


    public function pending()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $tasks = Task::where('user_id', $user->id)
                      ->where('is_completed', false)
                      ->paginate(4);

        return view('tasks.pending', ['tasks' => $tasks]);
    }

    public function completed()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $tasks = Task::where('user_id', $user->id)
                      ->where('is_completed', true)
                      ->paginate(4);

        return view('tasks.completed', ['tasks' => $tasks]);
    }   



}
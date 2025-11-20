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
            return redirect()->route('tasks.index')->with('success', 'task updated successfuly');
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


    public function toggle(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $task = Task::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $task->is_completed = !$task->is_completed;
        $task->save();

        return response()->json([
            'success' => true,
            'is_completed' => $task->is_completed
        ]);
    }


    // Enhanced filter method with validation
    public function filter(Request $request)
    {

        try {
            $validated = $request->validate([
                'filter' => 'sometimes|string|in:all,today,week,high,medium,low'
            ]);

            $filter = $validated['filter'] ?? 'all';
            $query = Task::query();

            // Apply filters (same switch case as above)
            switch ($filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfDay(), now()->addDays(7)->endOfDay()]);
                    break;
                case 'high':
                    $query->where('priority', 'high');
                    break;
                case 'medium':
                    $query->where('priority', 'medium');
                    break;
                case 'low':
                    $query->where('priority', 'low');
                    break;
            }

            $tasks = $query->paginate(4);

            return response()->json([

                'tasks' => $tasks

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error filtering tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }





    /********************************************API Functions************************************* */


    public function indexApi()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $tasks = Task::where('user_id', $user->id)->get();


        return response()->json(['Tasks' => $tasks, 'user' => $user], 200);
    }

    public function storeApi(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_completed' => 'nullable|boolean',
                'priority' => 'nullable|in:low,medium,high',
            ]);

            $task = new Task();
            $task->title = $request->title;
            $task->description = $request->description;
            $task->is_completed = $request->is_completed;
            $task->priority = $request->priority; // Add priority with default
            $task->user_id = $user->id;
            $task->save();

            // $tasks = Task::where('user_id', $user->id)->get();
            return response()->json([
                'success' => true,
                'message' => 'Task created successfully',
                'task' => $task
            ], 201);
        } catch (Exception $e) {

            Log::error('Error creating task: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // PUT/PATCH /api/tasks/{task}
    public function updateApi(Request $request, Task $task)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();


            if ($task->user_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'is_completed' => 'nullable|boolean',
                'priority' => 'nullable|in:low,medium,high',
            ]);

            $task->title = $request->title;
            $task->description = $request->input('description', $task->description);
            if ($request->has('is_completed')) {
                $task->is_completed = $request->input('is_completed');
            }
            if ($request->has('priority')) {
                $task->priority = $request->input('priority', $task->priority);
            }
            $task->save();

            return response()->json([
                'success' => true,
                'message' => 'Task updated successfully',
                'task' => $task
            ], 200);
        } catch (Exception $e) {
            Log::error('Error updating task: ' . $e->getMessage());
            return response()->json(['error' => 'Could not update task'], 500);
        }
    }



    public function destroyApi(Task $task)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($task->user_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $task->delete(); // soft delete

            return response()->json([
                'success' => true, 
                'message' => 'Task deleted successfully'],
                200);
        } catch (Exception $e) {
            Log::error('Error deleting task: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete task'], 500);
        }
    }

    
    // GET /api/tasks/pending
    public function pendingApi()
    {
        $user = JWTAuth::parseToken()->authenticate();


        $tasks = Task::where('user_id', $user->id)
            ->where('is_completed', false)
            ->orderBy('created_at', 'desc')->get();

        return response()->json(['tasks' => $tasks], 200);
    }

    // GET /api/tasks/completed
    public function completedApi()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $tasks = Task::where('user_id', $user->id)
            ->where('is_completed', true)
            ->get();

        return response()->json(['tasks' => $tasks], 200);
    }




    // Enhanced filter method with validation
    public function filterApi(Request $request)
    {

        try {


            $user = JWTAuth::parseToken()->authenticate();


            $validated = $request->validate([
                'filter' => 'sometimes|string|in:all,today,week,high,medium,low'
            ]);

            $filter = $validated['filter'] ?? 'all';
            $query = Task::query()->where('user_id',$user->id);

            // Apply filters (same switch case as above)
            switch ($filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->subDays(7)->startOfDay(),  now()->endOfDay()]);
                    break;
                case 'high':
                    $query->where('priority', 'high');
                    break;
                case 'medium':
                    $query->where('priority', 'medium');
                    break;
                case 'low':
                    $query->where('priority', 'low');
                    break;
            }

            $tasks = $query->get();



            return response()->json([
                'filter' => $filter,
                'tasks' => $tasks 
    

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error filtering tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

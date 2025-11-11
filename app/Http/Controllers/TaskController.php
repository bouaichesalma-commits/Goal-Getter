<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Exception;

class TaskController extends Controller
{

    public function create()
    {

        return view('tasks.create');
    }



    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            // Get authenticated user from JWT token
            $user = JWTAuth::user();
            
            if (!$user) {
                Log::warning('Task creation attempted without authenticated user');
                return response()->json([
                    'error' => 'User not authenticated'
                ], 401);
            }

            Log::info('Creating task for user', ['user_id' => $user->id]);

            // Create the task
            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'is_completed' => false,
                'user_id' => $user->id,
            ]);

            Log::info('Task created successfully', [
                'task_id' => $task->id,
                'user_id' => $user->id
            ]);

            // If it's an API request, return JSON response
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Task created successfully',
                    'task' => $task
                ], 201);
            }

            // If it's a web request, redirect with success message
            return redirect()->route('tasks.index')->with('success', 'Task created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed during task creation', [
                'errors' => $e->errors(),
                'user_id' => JWTAuth::user()?->id
            ]);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'error' => 'Validation failed',
                    'details' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            Log::error('JWT Token expired during task creation');
            
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Token expired'], 401);
            }

            return redirect()->route('login')->with('error', 'Session expired. Please login again.');

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            Log::error('JWT Token invalid during task creation');
            
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Token invalid'], 401);
            }

            return redirect()->route('login')->with('error', 'Invalid session. Please login again.');

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            Log::error('JWT Exception during task creation', [
                'message' => $e->getMessage()
            ]);
            
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Authentication failed'], 401);
            }

            return redirect()->route('login')->with('error', 'Authentication failed. Please login again.');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error during task creation', [
                'error' => $e->getMessage(),
                'user_id' => JWTAuth::user()?->id
            ]);

            $errorMessage = 'Database error occurred. Please try again.';

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => $errorMessage], 500);
            }

            return back()->with('error', $errorMessage)->withInput();

        } catch (Exception $e) {
            Log::error('Unexpected error during task creation', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => JWTAuth::user()?->id
            ]);

            $errorMessage = 'An unexpected error occurred. Please try again.';

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => $errorMessage], 500);
            }

            return back()->with('error', $errorMessage)->withInput();
        }
    }

    public function index(Request $request)
    {
        try {
            $user = JWTAuth::user();
            
            if (!$user) {
                Log::warning('Task index accessed without authenticated user');
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            Log::info('Fetching tasks for user', ['user_id' => $user->id]);

            $tasks = Task::where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'tasks' => $tasks,
                    'count' => $tasks->count()
                ]);
            }

            return view('tasks.index', compact('tasks'));

        } catch (Exception $e) {
            Log::error('Error fetching tasks', [
                'error' => $e->getMessage(),
                'user_id' => JWTAuth::user()?->id
            ]);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Failed to fetch tasks'], 500);
            }

            return back()->with('error', 'Failed to load tasks. Please try again.');
        }
    }

    public function update(Request $request, Task $task)
    {
        try {
            $user = JWTAuth::user();
            
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Check if task belongs to user
            if ($task->user_id !== $user->id) {
                Log::warning('Unauthorized task update attempt', [
                    'task_user_id' => $task->user_id,
                    'current_user_id' => $user->id
                ]);
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'is_completed' => 'sometimes|boolean',
            ]);

            $task->update($validated);

            Log::info('Task updated successfully', [
                'task_id' => $task->id,
                'user_id' => $user->id
            ]);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Task updated successfully',
                    'task' => $task
                ]);
            }

            return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');

        } catch (Exception $e) {
            Log::error('Error updating task', [
                'error' => $e->getMessage(),
                'task_id' => $task->id,
                'user_id' => JWTAuth::user()?->id
            ]);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Failed to update task'], 500);
            }

            return back()->with('error', 'Failed to update task. Please try again.')->withInput();
        }
    }

    public function destroy(Request $request, Task $task)
    {
        try {
            $user = JWTAuth::user();
            
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Check if task belongs to user
            if ($task->user_id !== $user->id) {
                Log::warning('Unauthorized task deletion attempt', [
                    'task_user_id' => $task->user_id,
                    'current_user_id' => $user->id
                ]);
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $task->delete();

            Log::info('Task deleted successfully', [
                'task_id' => $task->id,
                'user_id' => $user->id
            ]);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Task deleted successfully'
                ]);
            }

            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');

        } catch (Exception $e) {
            Log::error('Error deleting task', [
                'error' => $e->getMessage(),
                'task_id' => $task->id,
                'user_id' => JWTAuth::user()?->id
            ]);

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Failed to delete task'], 500);
            }

            return back()->with('error', 'Failed to delete task. Please try again.');
        }
    }
}
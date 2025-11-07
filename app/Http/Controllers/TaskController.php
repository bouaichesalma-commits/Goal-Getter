<?php

namespace App\Http\Controllers;
use App\Models\Task;    

use Illuminate\Http\Request;

class TaskController extends Controller

{

    public function index()
    {
        // Retrieve all tasks from the database
        $tasks = Task::all();

        
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        // Return a view to create a new task
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        // Validate and store the new task
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
           
        ]); 

        // Assuming Task is a model that represents tasks in the database
        $task = Task::create($validated);
        

        return redirect()->back()->with('success', 'Task created successfully!');
    }

    public function update(Request $request, Task $task)
    {
        // Validate and update the existing task
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()->back()->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        // Delete the specified task
        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully!');
    }
}

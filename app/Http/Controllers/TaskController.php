<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * GET /api/tasks
     * List all tasks (optional).
     */
    public function index()
    {
        // List all tasks
        $tasks = Task::all();
        return response()->json($tasks, Response::HTTP_OK);
    }

    /**
     * GET /api/projects/{project_id}/tasks
     * List all tasks for a specific project.
     */
    public function tasksByProject($projectId)
    {
        $project = Project::find($projectId);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        $tasks = $project->tasks; // Using the relationship
        return response()->json($tasks, Response::HTTP_OK);
    }

    /**
     * POST /api/projects/{project_id}/tasks
     * Create a new task under a project.
     */
    public function store(Request $request, $projectId)
    {
        $project = Project::find($projectId);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'status' => ['required', Rule::in(['to_do', 'in_progress', 'done'])]
        ]);

        $validatedData['project_id'] = $projectId;
        $task = Task::create($validatedData);

        return response()->json($task, Response::HTTP_CREATED);
    }

    /**
     * GET /api/tasks/{id}
     * Show details of a single task.
     */
    public function show($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($task, Response::HTTP_OK);
    }

    /**
     * PUT /api/tasks/{id}
     * Update an existing task.
     */
    public function update(Request $request, $id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'status' => ['sometimes', 'required', Rule::in(['to_do', 'in_progress', 'done'])]
        ]);

        $task->update($validatedData);
        return response()->json($task, Response::HTTP_OK);
    }

    /**
     * DELETE /api/tasks/{id}
     * Delete a task.
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $task->delete();
        return response()->json(['message' => 'Task deleted successfully'], Response::HTTP_OK);
    }
}

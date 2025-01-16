<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * GET /api/projects
     * List all projects.
     */
    public function index()
    {
        $projects = Project::all();
        return response()->json($projects, Response::HTTP_OK);
    }

    /**
     * POST /api/projects
     * Create a new project.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['open', 'in_progress', 'completed'])],
        ]);

        $project = Project::create($validatedData);
        return response()->json($project, Response::HTTP_CREATED);
    }

    /**
     * GET /api/projects/{id}
     * Show details of a single project.
     */
    public function show($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($project, Response::HTTP_OK);
    }

    /**
     * PUT /api/projects/{id}
     * Update an existing project.
     */
    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['sometimes', 'required', Rule::in(['open', 'in_progress', 'completed'])],
        ]);

        $project->update($validatedData);
        return response()->json($project, Response::HTTP_OK);
    }

    /**
     * DELETE /api/projects/{id}
     * Delete a project.
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        if (!$project) {
            return response()->json(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        $project->delete();
        return response()->json(['message' => 'Project deleted successfully'], Response::HTTP_OK);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        Project::create($validated);
        return back();
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        $project->update($validated);
        return back();
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('tasks.index');
    }
}
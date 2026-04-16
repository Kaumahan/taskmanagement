<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::all();
        $selectedProjectId = $request->query('project_id', $projects->first()?->id);

        $tasks = Task::where('project_id', $selectedProjectId)
            ->orderBy('priority', 'asc')
            ->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'projects' => $projects,
            'selectedProjectId' => (int) $selectedProjectId
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'required|exists:projects,id',
        ]);

        $maxPriority = Task::where('project_id', $request->project_id)->max('priority') ?? 0;

        Task::create([
            'name' => $request->name,
            'project_id' => $request->project_id,
            'priority' => $maxPriority + 1,
        ]);

        return back();
    }

    public function reorder(Request $request)
    {
        $taskIds = $request->tasks; 

        foreach ($taskIds as $index => $id) {
            Task::where('id', $id)->update(['priority' => $index + 1]);
        }

        return back();
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back();
    }
}
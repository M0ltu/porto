<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function show(Project $project): View
    {
        $related = Project::query()
            ->where('slug', '!==', $project->slug)
            ->get()
            ->sortByDesc(fn (Project $candidate) => $candidate->category === $project->category)
            ->take(3)
            ->values();

        return view('pages.projects.show', [
            'project' => $project,
            'related' => $related,
        ]);
    }
}

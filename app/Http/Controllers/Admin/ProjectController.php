<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Support\Uploads;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index');
    }

    public function create(): View
    {
        return view('admin.projects.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $data['slug'] = $this->uniqueSlug($request, $data);
        $data['sort_order'] = (Project::max('sort_order') ?? 0) + 1;

        if ($request->hasFile('cover')) {
            $data['cover_path'] = Uploads::store($request->file('cover'), 'projects');
        }

        $project = Project::create($data);
        $this->syncGallery($request, $project);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.form', ['project' => $project]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->validated($request);

        $data['slug'] = $this->uniqueSlug($request, $data, $project);

        if ($request->hasFile('cover')) {
            $data['cover_path'] = Uploads::replace($project->cover_path, $request->file('cover'), 'projects');
        }

        $project->update($data);
        $this->syncGallery($request, $project);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Perubahan proyek tersimpan.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        Uploads::delete($project->cover_path);

        foreach ($project->images as $image) {
            Uploads::delete($image->path);
        }

        $project->images()->delete();
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Proyek dihapus.');
    }

    public function destroyImage(Project $project, ProjectImage $image): RedirectResponse
    {
        abort_if($image->project_id !== $project->id, Response::HTTP_NOT_FOUND);

        Uploads::delete($image->path);
        $image->delete();

        return redirect()
            ->back()
            ->with('success', 'Foto galeri dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/'],
            'category' => ['required', 'string', 'max:255'],
            'year' => ['required', 'digits:4'],
            'role' => ['nullable', 'string', 'max:255'],
            'summary' => ['required', 'string'],
            'tech' => ['nullable', 'string', 'max:500'],
            'problem' => ['nullable', 'string'],
            'goal' => ['nullable', 'string'],
            'process' => ['nullable', 'string'],
            'feedback' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],
            'demo_url' => ['nullable', 'url', 'max:255'],
            'repo_url' => ['nullable', 'url', 'max:255'],
            'featured' => ['nullable', 'boolean'],
            'cover' => ['nullable', 'image', 'max:2048'],
            'gallery.*' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['featured'] = $request->boolean('featured');
        $data['tech'] = collect(explode(',', (string) $data['tech']))->map(fn (string $item) => trim($item))->filter()->values()->all();
        $data['features'] = collect(explode("\n", (string) $data['features']))->map(fn (string $item) => trim($item))->filter()->values()->all();

        unset($data['cover']);

        return $data;
    }

    private function uniqueSlug(Request $request, array $data, ?Project $project = null): string
    {
        $base = Str::slug($data['slug'] ?: $data['title']) ?: 'proyek';
        $slug = $base;
        $counter = 1;

        while (Project::query()->where('slug', $slug)->when($project, fn ($query) => $query->whereKeyNot($project->getKey()))->exists()) {
            $slug = $base.'-'.(++$counter);
        }

        return $slug;
    }

    private function syncGallery(Request $request, Project $project): void
    {
        if (! $request->hasFile('gallery')) {
            return;
        }

        $nextOrder = (int) $project->images()->max('sort_order') + 1;

        foreach ($request->file('gallery') as $file) {
            ProjectImage::create([
                'project_id' => $project->getKey(),
                'path' => Uploads::store($file, 'projects/gallery'),
                'sort_order' => $nextOrder++,
            ]);
        }
    }
}

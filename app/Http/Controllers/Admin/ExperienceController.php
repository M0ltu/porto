<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index(): View
    {
        return view('admin.experiences.index');
    }

    public function create(): View
    {
        return view('admin.experiences.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['sort_order'] = (Experience::max('sort_order') ?? 0) + 1;

        Experience::create($data);

        return redirect()
            ->route('admin.experiences.index')
            ->with('success', 'Pengalaman berhasil ditambahkan.');
    }

    public function edit(Experience $experience): View
    {
        return view('admin.experiences.form', ['experience' => $experience]);
    }

    public function update(Request $request, Experience $experience): RedirectResponse
    {
        $experience->update($this->validated($request));

        return redirect()
            ->route('admin.experiences.index')
            ->with('success', 'Perubahan pengalaman tersimpan.');
    }

    public function destroy(Experience $experience): RedirectResponse
    {
        $experience->delete();

        return redirect()
            ->route('admin.experiences.index')
            ->with('success', 'Pengalaman dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'period' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'organization' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'tags' => ['nullable', 'string', 'max:500'],
            'current' => ['nullable', 'boolean'],
        ]);

        $data['current'] = $request->boolean('current');
        $data['tags'] = collect(explode(',', (string) $data['tags']))->map(fn (string $item) => trim($item))->filter()->values()->all();

        return $data;
    }
}

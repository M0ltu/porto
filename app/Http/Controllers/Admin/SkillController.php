<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(): View
    {
        return view('admin.skills.index');
    }

    public function create(): View
    {
        return view('admin.skills.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['sort_order'] = (Skill::max('sort_order') ?? 0) + 1;

        Skill::create($data);

        return redirect()
            ->route('admin.skills.index')
            ->with('success', 'Keahlian berhasil ditambahkan.');
    }

    public function edit(Skill $skill): View
    {
        return view('admin.skills.form', ['skill' => $skill]);
    }

    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $skill->update($this->validated($request));

        return redirect()
            ->route('admin.skills.index')
            ->with('success', 'Perubahan keahlian tersimpan.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $skill->delete();

        return redirect()
            ->route('admin.skills.index')
            ->with('success', 'Keahlian dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:hard,soft'],
            'value' => ['required', 'integer', 'min:1', 'max:100'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
    }
}

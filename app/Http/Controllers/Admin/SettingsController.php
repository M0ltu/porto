<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Social;
use App\Support\Uploads;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.index');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'headline' => ['required', 'string', 'max:255'],
            'bio' => ['required', 'string'],
            'availability' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'cv' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'remove_cv' => ['nullable', 'boolean'],
        ]);

        $profile = Profile::query()->firstOrFail();

        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = Uploads::replace($profile->avatar_path, $request->file('avatar'), 'profile');
        }

        if ($request->boolean('remove_cv')) {
            Uploads::delete($profile->cv_path);
            $data['cv_path'] = null;
        } elseif ($request->hasFile('cv')) {
            $data['cv_path'] = Uploads::replace($profile->cv_path, $request->file('cv'), 'cv');
        }

        unset($data['avatar'], $data['cv'], $data['remove_cv']);

        $profile->update($data);

        foreach ($request->input('socials', []) as $id => $href) {
            $social = Social::query()->find($id);

            if ($social !== null) {
                $social->update(['href' => trim((string) $href)]);
            }
        }

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Pengaturan tersimpan.');
    }
}

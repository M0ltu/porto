<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Support\Uploads;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(): View
    {
        return view('admin.certificates.index');
    }

    public function create(): View
    {
        return view('admin.certificates.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image_path'] = Uploads::store($request->file('image'), 'certificates');
        }

        $data['sort_order'] = (Certificate::max('sort_order') ?? 0) + 1;

        Certificate::create($data);

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil ditambahkan.');
    }

    public function edit(Certificate $certificate): View
    {
        return view('admin.certificates.form', ['certificate' => $certificate]);
    }

    public function update(Request $request, Certificate $certificate): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image_path'] = Uploads::replace($certificate->image_path, $request->file('image'), 'certificates');
        }

        $certificate->update($data);

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', 'Perubahan sertifikat tersimpan.');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        Uploads::delete($certificate->image_path);
        $certificate->delete();

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', 'Sertifikat dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'issuer' => ['required', 'string', 'max:255'],
            'year' => ['required', 'digits:4'],
            'type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'credential_url' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        unset($data['image']);

        return $data;
    }
}

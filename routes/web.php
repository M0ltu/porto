<?php

use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman publik
|--------------------------------------------------------------------------
| Konten berasal dari database lewat App\Support\Portfolio (view composer).
*/

Route::view('/', 'pages.home')->name('home');
Route::view('/tentang', 'pages.about')->name('about');
Route::view('/keahlian', 'pages.skills')->name('skills.index');
Route::view('/proyek', 'pages.projects.index')->name('projects.index');

Route::get('/proyek/{project}', [ProjectController::class, 'show'])->name('projects.show');

Route::view('/sertifikat', 'pages.certificates')->name('certificates.index');
Route::view('/pengalaman', 'pages.experience')->name('experience.index');
Route::view('/cv', 'pages.cv')->name('cv');
Route::view('/kontak', 'pages.contact')->name('contact');

Route::view('/kesan-pesan', 'pages.messages')->name('messages.create');
Route::post('/kesan-pesan', [MessageController::class, 'store'])
    ->middleware('throttle:5,60')
    ->name('messages.store');

/*
|--------------------------------------------------------------------------
| Dashboard admin (UI)
|--------------------------------------------------------------------------
*/

Route::redirect('/dashboard', '/admin')->middleware('auth')->name('dashboard');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('proyek', AdminProjectController::class)
        ->parameters(['proyek' => 'project'])
        ->names('projects')
        ->except(['show']);
    Route::delete('/proyek/{project}/gambar/{image}', [AdminProjectController::class, 'destroyImage'])
        ->name('projects.images.destroy');

    Route::resource('sertifikat', CertificateController::class)
        ->parameters(['sertifikat' => 'certificate'])
        ->names('certificates')
        ->except(['show']);

    Route::resource('keahlian', SkillController::class)
        ->parameters(['keahlian' => 'skill'])
        ->names('skills')
        ->except(['show']);

    Route::resource('pengalaman', ExperienceController::class)
        ->parameters(['pengalaman' => 'experience'])
        ->names('experiences')
        ->except(['show']);

    Route::view('/testimoni', 'admin.testimonials.index')->name('testimonials.index');

    Route::post('/testimoni/{message}/setujui', [AdminMessageController::class, 'toggleApprove'])->name('messages.approve');
    Route::delete('/testimoni/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

    Route::get('/pengaturan', [SettingsController::class, 'edit'])->name('settings');
    Route::put('/pengaturan', [SettingsController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

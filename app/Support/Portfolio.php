<?php

namespace App\Support;

use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Social;
use Illuminate\Http\Request;

class Portfolio
{
    /** @var array<string, mixed>|null */
    private static ?array $data = null;

    private static ?Request $request = null;

    /**
     * @return array<string, mixed>
     */
    public static function data(): array
    {
        $request = app()->bound('request') ? app('request') : null;

        if (self::$request !== $request) {
            self::$request = $request;
            self::$data = null;
        }

        return self::$data ??= self::load();
    }

    /**
     * @return array<string, mixed>
     */
    private static function load(): array
    {
        $profile = Profile::query()->first();
        $skills = self::skills();
        $projects = Project::query()->with('images')->orderBy('sort_order')->get();
        $experiences = Experience::query()->orderBy('sort_order')->get();

        $hardCount = count($skills['hard']);
        $softCount = count($skills['soft']);
        $techCount = $projects
            ->flatMap(fn (Project $project) => $project->tech ?? [])
            ->filter()
            ->unique()
            ->count();
        $experienceTypes = $experiences->pluck('type')->filter()->unique()->implode(', ');

        return [
            'profile' => $profile?->toArray() ?? [],
            'socials' => Social::query()->orderBy('sort_order')->get()->toArray(),
            'stats' => [
                [
                    'label' => 'Total proyek',
                    'value' => (string) $projects->count(),
                    'hint' => $projects->count() === 1 ? 'Proyek di portfolio' : 'Proyek di portfolio',
                ],
                [
                    'label' => 'Teknologi',
                    'value' => (string) $techCount,
                    'hint' => 'Teknologi unik di proyek',
                ],
                [
                    'label' => 'Keahlian',
                    'value' => (string) ($hardCount + $softCount),
                    'hint' => $hardCount.' teknis, '.$softCount.' interpersonal',
                ],
                [
                    'label' => 'Pengalaman',
                    'value' => (string) $experiences->count(),
                    'hint' => $experienceTypes !== '' ? $experienceTypes : 'Catatan pengalaman',
                ],
            ],
            'skills' => $skills,
            'projects' => $projects->toArray(),
            'certificates' => Certificate::query()->orderBy('sort_order')->get()->toArray(),
            'experiences' => $experiences->toArray(),
            'feedback_aspects' => Setting::get('feedback_aspects', self::defaultFeedbackAspects()),
            'messages' => Message::query()->orderByDesc('created_at')->get()->toArray(),
        ];
    }

    public static function skills(): array
    {
        $skills = Skill::query()->orderBy('sort_order')->get();

        return [
            'hard' => $skills->where('type', 'hard')->values()->toArray(),
            'soft' => $skills->where('type', 'soft')->values()->toArray(),
        ];
    }

    public static function defaultFeedbackAspects(): array
    {
        return [
            'design' => 'Desain & tampilan',
            'navigation' => 'Kemudahan navigasi',
            'content' => 'Kejelasan isi',
            'comfort' => 'Kenyamanan',
        ];
    }
}

<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Uploads
{
    public static function store(mixed $file, string $directory): string
    {
        $path = $file->store("uploads/{$directory}", 'public');

        return Storage::url($path);
    }

    public static function replace(?string $current, mixed $file, string $directory): string
    {
        self::delete($current);

        return self::store($file, $directory);
    }

    public static function delete(?string $publicPath): void
    {
        if (! $publicPath) {
            return;
        }

        $path = parse_url($publicPath, PHP_URL_PATH) ?: $publicPath;

        if (! str_starts_with($path, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete(Str::replaceFirst('/storage/', '', $path));
    }
}

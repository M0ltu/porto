<?php

namespace App\Support;

use Illuminate\Filesystem\Filesystem as BaseFilesystem;

class Filesystem extends BaseFilesystem
{
    /**
     * Write the contents of a file, retrying the atomic rename on Windows.
     *
     * Windows can briefly lock the freshly written temp file (antivirus/indexing)
     * or the destination while another process compiles the same view, which
     * makes rename() fail with "Access is denied". A short retry prevents the
     * 500 error on the first request after a Blade template changes.
     */
    public function replace($path, $content, $mode = null)
    {
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        $tempPath = tempnam(dirname($path), basename($path));

        if (! is_null($mode)) {
            @chmod($tempPath, $mode);
        } else {
            @chmod($tempPath, 0777 - umask());
        }

        file_put_contents($tempPath, $content);

        for ($attempt = 0; $attempt < 10; $attempt++) {
            clearstatcache(true, $path);

            if (@rename($tempPath, $path)) {
                return;
            }

            usleep(50000);
        }

        @unlink($tempPath);

        parent::replace($path, $content, $mode);
    }
}

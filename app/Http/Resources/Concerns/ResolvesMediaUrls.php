<?php

namespace App\Http\Resources\Concerns;

use Illuminate\Support\Facades\Storage;

trait ResolvesMediaUrls
{
    /**
     * Convert a path on the public disk into an absolute URL.
     */
    protected function mediaUrl(?string $path): ?string
    {
        return filled($path) ? Storage::disk('public')->url($path) : null;
    }
}

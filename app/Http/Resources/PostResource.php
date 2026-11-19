<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\ResolvesMediaUrls;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Post
 */
class PostResource extends JsonResource
{
    use ResolvesMediaUrls;

    /**
     * Include the full HTML content (only needed on the post page).
     */
    private bool $withContent = false;

    public function withContent(): static
    {
        $this->withContent = true;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->translate('title'),
            'excerpt' => $this->translate('excerpt'),
            'category' => $this->category->value,
            'cover_image' => $this->mediaUrl($this->cover_image),
            'published_at' => $this->published_at?->toIso8601String(),
            'product' => $this->whenLoaded('product', fn (): ?array => $this->product ? [
                'slug' => $this->product->slug,
                'name' => $this->product->translate('name'),
            ] : null),
            'content' => $this->when($this->withContent, fn (): ?string => $this->translate('content')),
        ];
    }
}

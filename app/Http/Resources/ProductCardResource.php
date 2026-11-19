<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\ResolvesMediaUrls;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A lightweight product representation for cards and listings.
 *
 * @mixin Product
 */
class ProductCardResource extends JsonResource
{
    use ResolvesMediaUrls;

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
            'name' => $this->translate('name'),
            'tagline' => $this->translate('tagline'),
            'short_description' => $this->translate('short_description'),
            'icon' => $this->icon,
            'logo' => $this->mediaUrl($this->logo),
            'cover_image' => $this->mediaUrl($this->cover_image),
            'status' => $this->status->value,
            'has_demo' => filled($this->demo_url),
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\ResolvesMediaUrls;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Testimonial
 */
class TestimonialResource extends JsonResource
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
            'client_name' => $this->translate('client_name'),
            'client_designation' => $this->translate('client_designation'),
            'organization' => $this->translate('organization'),
            'photo' => $this->mediaUrl($this->photo),
            'content' => $this->translate('content'),
            'rating' => $this->rating,
            'product' => $this->whenLoaded('product', fn (): ?array => $this->product ? [
                'slug' => $this->product->slug,
                'name' => $this->product->translate('name'),
            ] : null),
        ];
    }
}

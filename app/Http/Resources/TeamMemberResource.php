<?php

namespace App\Http\Resources;

use App\Http\Resources\Concerns\ResolvesMediaUrls;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin TeamMember
 */
class TeamMemberResource extends JsonResource
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
            'name' => $this->translate('name'),
            'designation' => $this->translate('designation'),
            'bio' => $this->translate('bio'),
            'photo' => $this->mediaUrl($this->photo),
            'email' => $this->email,
            'facebook_url' => $this->facebook_url,
            'linkedin_url' => $this->linkedin_url,
            'github_url' => $this->github_url,
        ];
    }
}

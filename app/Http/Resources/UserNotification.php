<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserNotification extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $resource = $this->resource;
        return [
            'id' => $resource->id,
            'subject' => $resource->subject,
            'content' => $resource->content,
            'is_read' => $resource->is_read,
            'user_avatar' => $resource->user?->userDetail->profile_image ?? uploadedAsset('default', 'profile'),
            'related_user_avatar' => $resource->relatedUser?->userDetail->profile_image ?? uploadedAsset('default', 'profile'),
            'time' => $resource->created_at->diffForHumans(),
        ];
    }
}

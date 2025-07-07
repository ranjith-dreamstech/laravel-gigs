<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::guard('web')->user();
        $authUserId = $authUser->id;

        $resource = $this->resource;
        return [
            'id' => $resource->id,
            'message_type' => $resource->type,
            'file_path' => uploadedAsset($resource->file),
            'message' => $resource->message,
            'created_at' => $resource->created_at->format('Y-m-d H:i:s'),
            'time' => $resource->created_at->format('h:i A'),
            'alignment' => $resource->sender_id === $authUserId ? 'right' : 'left',
            'is_sender' => $resource->sender_id === $authUserId,
            'sender_id' => $resource->sender_id,
            'receiver_id' => $resource->receiver_id,
            'sender_username' => $resource->sender->name ?? '',
            'sender_avatar' => $this->getAvatar($resource->sender_id),
            'receiver_username' => $resource->receiver->name ?? '',
            'receiver_avatar' => $this->getAvatar($resource->receiver_id),
            'admin_avatar' => $this->getAdminAvatar(),
        ];
    }

    /** @return string */
    public function getAvatar(int $userId): string
    {
        /** @var \App\Models\User $user */
        $user = User::find($userId);
        return $user->userDetail ? $user->userDetail->profile_image : uploadedAsset('default', 'profile');
    }

    /** @return string */
    public function getAdminAvatar(): string
    {
        /** @var \App\Models\User $user */
        $user = User::where('user_type', 1)->first();
        return $user->userDetail ? $user->userDetail->profile_image : uploadedAsset('default', 'profile');
    }
}

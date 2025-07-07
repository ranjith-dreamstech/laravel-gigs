<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\CustomException;
use App\Http\Resources\MessageResource;
use App\Models\Gigs;
use App\Models\Message;
use App\Models\User;
use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Services\MqttService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Models\Booking;

class MessageRepository implements MessageRepositoryInterface
{
    /**
     * @return array <string, mixed>
     */
    public function sellerMessages(): array
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::guard('web')->user();
        $ownServices = Gigs::where('user_id', $authUser->id)->pluck('id')->toArray();
        $buyerIds = Booking::whereIn('gigs_id', $ownServices)
            ->whereNotNull('customer_id')
            ->pluck('customer_id')
            ->toArray();
        $bookedServiceIds = Booking::where('customer_id', $authUser->id)->pluck('gigs_id')->toArray();
        $sellerIds = Gigs::whereIn('id', $bookedServiceIds)
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->toArray();
        $relatedUserIds = array_unique(array_merge($buyerIds, $sellerIds));
        /** @var \App\Models\User $adminUser */
        $adminUser = User::where('user_type', 1)->first();
        $relatedUserIds[] = $adminUser->id;
        $relatedUserIds = array_diff($relatedUserIds, [$authUser->id]);
        $relatedUsers = User::whereIn('id', $relatedUserIds)->get()->map(function ($user) use ($authUser) {
            $lastMessage = Message::where(function ($query) use ($user, $authUser) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $authUser->id);
            })
                ->orWhere(function ($query) use ($user, $authUser) {
                    $query->where('sender_id', $authUser->id)
                        ->where('receiver_id', $user->id);
                })
                ->orderBy('id', 'desc')
                ->first();
            $user->lastMessage = $lastMessage;
            return $user;
        });

        return [
            'relatedUsers' => $relatedUsers,
            'authUser' => $authUser,
        ];
    }

    /**
     * @return array <string , mixed>
     */
    public function sellerFetchMessages(Request $request): array
    {
        /**
         * @var \App\Models\User $authUser
         */
        $authUser = Auth::guard('web')->user();
        $last_offset = $request->last_offset ?? '';
        $perPage = $last_offset ? intval($last_offset) : 10;
        if ($perPage > 10) {
            $perPage = 10;
        }
        $authUserId = $authUser->id;
        $messagePartnerId = $request->user_id;
        $totalMessages = Message::where(function ($query) use ($authUserId, $messagePartnerId) {
            $query->where('sender_id', $authUserId)
                ->where('receiver_id', $messagePartnerId);
        })
            ->orWhere(function ($query) use ($authUserId, $messagePartnerId) {
                $query->where('sender_id', $messagePartnerId)
                    ->where('receiver_id', $authUserId);
            })
            ->count();
        if (! $request->has('offset') || $request->offset === '') {
            $offset = max(0, $totalMessages - $perPage + 1);
        } else {
            $offset = max(0, (int) $request->offset);
        }
        $messages = Message::where(function ($query) use ($authUserId, $messagePartnerId) {
            $query->where('sender_id', $authUserId)
                ->where('receiver_id', $messagePartnerId);
        })
            ->orWhere(function ($query) use ($authUserId, $messagePartnerId) {
                $query->where('sender_id', $messagePartnerId)
                    ->where('receiver_id', $authUserId);
            })
            ->orderBy('id', 'asc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        if ($offset === 0) {
            $nextOffset = null;
        } else {
            $nextOffset = max(0, $offset - $perPage);
        }
        $lastMessage = Message::where(function ($query) use ($authUserId, $messagePartnerId) {
            $query->where('sender_id', $authUserId)
                ->where('receiver_id', $messagePartnerId);
        })
            ->orWhere(function ($query) use ($authUserId, $messagePartnerId) {
                $query->where('sender_id', $messagePartnerId)
                    ->where('receiver_id', $authUserId);
            })
            ->orderBy('id', 'desc')
            ->first();
        $lastMessageResp = null;
        if ($lastMessage) {
            $messageText = strlen($lastMessage->message) > 20 ? substr($lastMessage->message, 0, 20) . '...' : $lastMessage->message;
            $lastMessageCreatedAt = $lastMessage->created_at ? $lastMessage->created_at->diffForHumans() : '';
            $lastMessageResp = [
                'id' => $lastMessage->id,
                'message' => $messageText,
                'created_at' => $lastMessageCreatedAt,
                'partner_id' => $request->user_id,
            ];
        }
        $partnerProfile = User::where('id', $messagePartnerId)->with('userDetail')->first();
        return [
            'status' => true,
            'code' => 200,
            'messages' => MessageResource::collection($messages),
            'profile' => $partnerProfile,
            'next_offset' => $nextOffset,
            'last_offset' => $offset,
            'last_message' => $lastMessageResp,
        ];
    }

    /**
     * @return array <string , mixed>
     */
    public function sellerSendMessage(Request $request): array
    {
        try {
            if ($request->messageType === 'file' && $request->hasFile('file')) {
                $foldername = 'chat_attachments';
                $file = $request->file('file');
                $filename = $file->getClientOriginalName();
                $mime_type = $file->getClientMimeType();
                $size = $file->getSize();
                $path = uploadFile($file, $foldername, $filename);
                $_message = new Message();
                $_message->sender_id = $request->sender_id;
                $_message->receiver_id = $request->receiver_id;
                $_message->type = 'file';
                $_message->file = $path;
                $_message->mime_type = $mime_type;
                $_message->size = $size;
                $_message->message = $filename;
                $_message->save();
            }
            if (! empty($request->message)) {
                $message = new Message();
                $message->sender_id = $request->sender_id;
                $message->receiver_id = $request->receiver_id;
                $message->message = $request->message;
                $message->save();
            }
            $publishMessage = [
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
            ];
            $payload = json_encode($publishMessage);
            if ($payload === false) {
                throw new CustomException('Failed to encode message');
            }
            $mqtt = new MqttService();
            $mqtt->publish($request->topic, $payload);
            $response = [
                'success' => true,
                'message' => 'Message sent successfully',
            ];
        } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
        }
        return $response;
    }

    /**
     * @return array <string , mixed>
     */
    public function buyerMessages(): array
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::guard('web')->user();
        $ownServices = Gigs::where('user_id', $authUser->id)->pluck('id')->toArray();
        $buyerIds = Booking::whereIn('gigs_id', $ownServices)
            ->whereNotNull('customer_id')
            ->pluck('customer_id')
            ->toArray();
        $bookedServiceIds = Booking::where('customer_id', $authUser->id)->pluck('gigs_id')->toArray();
        $sellerIds = Gigs::whereIn('id', $bookedServiceIds)
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->toArray();
        $relatedUserIds = array_unique(array_merge($buyerIds, $sellerIds));
        /** @var \App\Models\User $adminUser */
        $adminUser = User::where('user_type', 1)->first();
        $relatedUserIds[] = $adminUser->id;
        $relatedUserIds = array_diff($relatedUserIds, [$authUser->id]);
        $relatedUsers = User::whereIn('id', $relatedUserIds)->get()->map(function ($user) use ($authUser) {
            $lastMessage = Message::where(function ($query) use ($user, $authUser) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $authUser->id);
            })
                ->orWhere(function ($query) use ($user, $authUser) {
                    $query->where('sender_id', $authUser->id)
                        ->where('receiver_id', $user->id);
                })
                ->orderBy('id', 'desc')
                ->first();
            $user->lastMessage = $lastMessage;
            return $user;
        });
        return [
            'relatedUsers' => $relatedUsers,
            'authUser' => $authUser,
        ];
    }

    /**
     * @return array <string , mixed>
     */
    public function buyerFetchMessages(Request $request): array
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::guard('web')->user();
        $last_offset = $request->last_offset ?? '';
        $perPage = $last_offset ? intval($last_offset) : 10;
        if ($perPage > 10) {
            $perPage = 10;
        }
        $authUserId = $authUser->id;
        $messagePartnerId = $request->user_id;
        $totalMessages = Message::where(function ($query) use ($authUserId, $messagePartnerId) {
            $query->where('sender_id', $authUserId)
                ->where('receiver_id', $messagePartnerId);
        })
            ->orWhere(function ($query) use ($authUserId, $messagePartnerId) {
                $query->where('sender_id', $messagePartnerId)
                    ->where('receiver_id', $authUserId);
            })
            ->count();
        if (! $request->has('offset') || $request->offset === '') {
            $offset = max(0, $totalMessages - $perPage + 1);
        } else {
            $offset = max(0, (int) $request->offset);
        }
        $messages = Message::where(function ($query) use ($authUserId, $messagePartnerId) {
            $query->where('sender_id', $authUserId)
                ->where('receiver_id', $messagePartnerId);
        })
            ->orWhere(function ($query) use ($authUserId, $messagePartnerId) {
                $query->where('sender_id', $messagePartnerId)
                    ->where('receiver_id', $authUserId);
            })
            ->orderBy('id', 'asc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        if ($offset === 0) {
            $nextOffset = null;
        } else {
            $nextOffset = max(0, $offset - $perPage);
        }
        $lastMessage = Message::where(function ($query) use ($authUserId, $messagePartnerId) {
            $query->where('sender_id', $authUserId)
                ->where('receiver_id', $messagePartnerId);
        })
            ->orWhere(function ($query) use ($authUserId, $messagePartnerId) {
                $query->where('sender_id', $messagePartnerId)
                    ->where('receiver_id', $authUserId);
            })
            ->orderBy('id', 'desc')
            ->first();
        $lastMessageResp = null;
        if ($lastMessage) {
            $messageText = strlen($lastMessage->message) > 20 ? substr($lastMessage->message, 0, 20) . '...' : $lastMessage->message;
            $lastMessageResp = [
                'id' => $lastMessage->id,
                'message' => $messageText,
                'created_at' => $lastMessage->created_at ? $lastMessage->created_at->diffForHumans() : '',
                'partner_id' => $request->user_id,
            ];
        }
        $partnerProfile = User::where('id', $messagePartnerId)->with('userDetail')->first();
        return [
            'status' => true,
            'code' => 200,
            'messages' => MessageResource::collection($messages),
            'profile' => $partnerProfile,
            'next_offset' => $nextOffset,
            'last_offset' => $offset,
            'last_message' => $lastMessageResp,
        ];
    }

    /**
     * @return array <string , mixed>
     */
    public function buyerSendMessage(Request $request): array
    {
        try {
            if ($request->messageType === 'file' && $request->hasFile('file')) {
                $foldername = 'chat_attachments';
                $file = $request->file('file');
                $filename = $file->getClientOriginalName();
                $mime_type = $file->getClientMimeType();
                $size = $file->getSize();
                $path = uploadFile($file, $foldername, $filename);
                $_message = new Message();
                $_message->sender_id = $request->sender_id;
                $_message->receiver_id = $request->receiver_id;
                $_message->type = 'file';
                $_message->file = $path;
                $_message->mime_type = $mime_type;
                $_message->size = $size;
                $_message->message = $filename;
                $_message->save();
            }
            if (! empty($request->message)) {
                $message = new Message();
                $message->sender_id = $request->sender_id;
                $message->receiver_id = $request->receiver_id;
                $message->message = $request->message;
                $message->save();
            }
            $publishMessage = [
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
            ];
            $payload = json_encode($publishMessage);
            if ($payload === false) {
                throw new CustomException('Failed to encode message');
            }
            $mqtt = new MqttService();

            $mqtt->publish($request->topic, $payload);
            $response = [
                'success' => true,
                'message' => 'Message sent successfully',
            ];
        } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
        }
        return $response;
    }

    /**
     * @return array <string , mixed>
     */
    public function searchUsers(Request $request): array
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::guard('web')->user();
        $ownServices = Gigs::where('user_id', $authUser->id)->pluck('id')->toArray();
        $buyerIds = Booking::whereIn('gigs_id', $ownServices)
            ->whereNotNull('customer_id')
            ->pluck('customer_id')
            ->toArray();
        $bookedServiceIds = Booking::where('customer_id', $authUser->id)->pluck('gigs_id')->toArray();
        $sellerIds = Gigs::whereIn('id', $bookedServiceIds)
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->toArray();
        $relatedUserIds = array_unique(array_merge($buyerIds, $sellerIds));
        /** @var \App\Models\User $adminUser */
        $adminUser = User::where('user_type', 1)->first();
        $relatedUserIds[] = $adminUser->id;
        $relatedUserIds = array_diff($relatedUserIds, [$authUser->id]);

        $users = User::where(function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->q . '%')
                ->orWhereHas('userDetail', function ($subQuery) use ($request) {
                    $subQuery->where('first_name', 'like', '%' . $request->q . '%')
                        ->orWhere('last_name', 'like', '%' . $request->q . '%');
                });
        })
            ->whereIn('id', $relatedUserIds)
            ->with('userDetail')
            ->get()
            ->map(function ($user) use ($authUser) {
                $authUserId = $authUser->id;

                $lastMessage = Message::where(function ($query) use ($user, $authUserId) {
                    $query->where('sender_id', $user->id)
                        ->where('receiver_id', $authUserId);
                })
                    ->orWhere(function ($query) use ($user, $authUserId) {
                        $query->where('sender_id', $authUserId)
                            ->where('receiver_id', $user->id);
                    })
                    ->orderBy('id', 'desc')
                    ->first();
                /** @var \App\Models\Message|null $lastMessage */
                $user->lastMessage = $lastMessage;
                $user->lastMessageTime = $lastMessage?->created_at?->diffForHumans() ?? '';
                $user->avatar = $user->userDetail->profile_image ?? '';

                return $user;
            });
        return [
            'status' => true,
            'code' => 200,
            'users' => $users,
        ];
    }

    /**
     * @return array <string , mixed>
     */
    public function sendMessage(Request $request): array
    {
        DB::beginTransaction();
        try {
            if ($request->messageType === 'file' && $request->hasFile('file')) {
                $foldername = 'chat_attachments';
                $file = $request->file('file');
                $filename = $file->getClientOriginalName();
                $mime_type = $file->getClientMimeType();
                $size = $file->getSize();
                $path = uploadFile($file, $foldername, $filename);
                $_message = new Message();
                $_message->sender_id = $request->sender_id;
                $_message->receiver_id = $request->receiver_id;
                $_message->type = 'file';
                $_message->file = $path;
                $_message->mime_type = $mime_type;
                $_message->size = $size;
                $_message->message = $filename;
                $_message->save();
            }
            if (! empty($request->message)) {
                $message = new Message();
                $message->sender_id = $request->sender_id;
                $message->receiver_id = $request->receiver_id;
                $message->message = $request->message;
                $message->save();
            }
            $publishMessage = [
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'message' => $request->message,
            ];
            $payload = json_encode($publishMessage);
            if ($payload === false) {
                throw new CustomException('Failed to encode message');
            }
            $mqtt = new MqttService();
            $mqtt->publish($request->topic, $payload);
            $response = [
                'success' => true,
                'message' => 'Message sent successfully',
            ];

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
        }

        return $response;
    }

    /**
     * @return array <string , mixed>
     */
    public function fetchMessages(Request $request): array
    {
        $last_offset = $request->last_offset ?? '';
        $perPage = $last_offset ? intval($last_offset) : 10;
        if ($perPage > 10) {
            $perPage = 10;
        }
        /** @var \App\Models\User $authUser */
        $authUser = current_user();
        $authUserId = $authUser->id;
        $messagePartnerId = $request->user_id;
        $totalMessages = Message::where(function ($query) use ($authUserId, $messagePartnerId) {
            $query->where('sender_id', $authUserId)
                ->where('receiver_id', $messagePartnerId);
        })
            ->orWhere(function ($query) use ($authUserId, $messagePartnerId) {
                $query->where('sender_id', $messagePartnerId)
                    ->where('receiver_id', $authUserId);
            })
            ->count();
        if (! $request->has('offset') || $request->offset === '') {
            $offset = max(0, $totalMessages - $perPage + 1);
        } else {
            $offset = max(0, (int) $request->offset);
        }
        $messages = Message::where(function ($query) use ($authUserId, $messagePartnerId) {
            $query->where('sender_id', $authUserId)
                ->where('receiver_id', $messagePartnerId);
        })
            ->orWhere(function ($query) use ($authUserId, $messagePartnerId) {
                $query->where('sender_id', $messagePartnerId)
                    ->where('receiver_id', $authUserId);
            })
            ->orderBy('id', 'asc')
            ->offset($offset)
            ->limit($perPage)
            ->get();

        if ($offset === 0) {
            $nextOffset = null;
        } else {
            $nextOffset = max(0, $offset - $perPage);
        }
        $lastMessage = Message::where(function ($query) use ($authUserId, $messagePartnerId) {
            $query->where('sender_id', $authUserId)
                ->where('receiver_id', $messagePartnerId);
        })
            ->orWhere(function ($query) use ($authUserId, $messagePartnerId) {
                $query->where('sender_id', $messagePartnerId)
                    ->where('receiver_id', $authUserId);
            })
            ->orderBy('id', 'desc')
            ->first();
        $lastMessageResp = null;
        if ($lastMessage) {
            $messageText = strlen($lastMessage->message) > 20 ? substr($lastMessage->message, 0, 20) . '...' : $lastMessage->message;
            $lastMessageResp = [
                'id' => $lastMessage->id,
                'message' => $lastMessage->type === 'text' ? $messageText : '<i class="fa fa-link"></i> ' . $messageText,
                'created_at' => $lastMessage->created_at?->diffForHumans(),
            ];
        }
        return [
            'status' => true,
            'code' => 200,
            'messages' => MessageResource::collection($messages),
            'next_offset' => $nextOffset,
            'last_offset' => $offset,
            'last_message' => $lastMessageResp,
        ];
    }
}

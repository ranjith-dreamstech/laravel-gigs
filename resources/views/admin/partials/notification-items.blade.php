@if (!empty($notifications) && $notifications->count() > 0)
    @foreach ($notifications as $k => $notification)
        <div class="notification-list">
            <div class="d-flex">
                <a href="#" class="avatar avatar-lg me-2 flex-shrink-0">
                    <img src="{{ getUserProfileImage($notification->related_user_id) }}" alt="Profile" class="rounded-circle">
                </a>
                <div class="flex-grow-1">
                    <p class="mb-1"><a href="#"><span class="text-gray-9">{{ getCurrentUserFullname($notification->related_user_id) }}</span> {{ $notification->subject ?? "" }}</a></p>
                    <span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>{{ $notification->created_at ? $notification->created_at->diffForHumans() : "" }}</span>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="notification-list">
        <div class="d-flex">
            <div class="flex-grow-1">
                <p class="mb-1"><span class="text-gray-9">{{ __('web.user.no_notifications_found') }}</span></p>
            </div>
        </div>
    </div>
@endif

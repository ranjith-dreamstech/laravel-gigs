@if(!empty($notifications) && count($notifications) > 0)
    @foreach($notifications as $notification)
        @php
            $notificationContent = $notification['subject'];
            if(strlen($notificationContent) > 75) {
                $notificationContent = substr($notificationContent, 0, 80) . '...';
            }
        @endphp

<ul>
    <li class="notification-message">
        <a href="#">
            <div class="media d-flex">
                <span class="avatar avatar-lg flex-shrink-0">
                    <img class="avatar-img rounded-circle" alt="user" src="{{ $notification['related_user_avatar'] }}">
                </span>
                <div class="media-body flex-grow-1">
                    <p class="noti-details"><span class="noti-title">{{ $notificationContent }}</span></p>
                    <p class="noti-time"><span class="notification-time">{{ $notification['time'] }}</span></p>
                </div>
            </div>
        </a>
    </li>
</ul>
@endforeach
@else
<ul>
<li class="notification-message">
    <a href="#">
        <div class="media d-flex">
            <div class="media-body flex-grow-1">
                <p class="noti-details text-center"><span class="noti-title">{{ __('web.user.no_new_notifications') }}</span></p>
            </div>
        </div>
    </a>
</li>
</ul>
@endif

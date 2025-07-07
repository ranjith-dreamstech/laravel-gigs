@foreach ($notifications as $notification)
<div class="notication-item bg-white">
    <div class="row align-items-center">
        <div class="col-lg-9 position-relative @if($notification->is_read == 0) notificationitem @endif" data-id="{{ $notification->id }}">
            <div class="notication-content">
                <span>
                    <img src="{{ $notification->relatedUser ? $notification->relatedUser->userDetail->profile_image : "" }}" class="img-fluid" alt="img">
                    @if($notification->is_read == 0)
                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-primary border border-white rounded-circle" style="z-index: 1;">
                            <span class="visually-hidden">New</span>
                        </span>
                    @endif
                </span>
                <div class="notication-info">
                    <div>
                        <p class="text-dark me-0">{{ $notification->subject ?? "" }}</p>
                        <p class="notify-time">{{ $notification->content ?? "" }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="d-lg-flex align-items-center justify-content-between">
                <div class="noti-btn me-2">
                    <a href="javascript:void(0);" class="btn btn-danger del_notification" data-id="{{ $notification->id }}">{{ __('web.common.delete') }}</a>
                </div>
                <p class="m-0">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }} </p>
            </div>
        </div>
    </div>
</div>
@endforeach

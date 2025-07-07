@extends('frontend.buyer.partials.app')
@section('content')
<div class="page-wrapper">
    <div class="page-content content bg-light notify-info">
        <div class="main-title mb-4">
            <h4>{{__('web.common.notifications')}}</h4>
        </div>
        <div class="row">

            <!-- Notifications -->
            <div class="col-xl-12">
                <div class="notification-header">
                    <div class="form-sort form-wrap">
                    </div>
                    <ul>
                        <li>
                            <button type="button" id="markAllAsRead" class="btn btn-white"><i class="feather-check"></i>
                                {{__('web.common.maer_all_as_read')}}</button>
                        </li>
                        <li>
                            <button type="button" class="btn btn-delete" id="deleteAll"><i class="feather-trash-2"></i>
                                {{__('web.common.delete_all')}}</button>
                        </li>
                    </ul>
                </div>
                <div class="notication-list" id="notification-list">

                </div>
            </div>
            <!-- /Notifications -->
        </div>
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="float-end" id="pagination-container">

                </div>
            </div>
        </div>
    </div>
</div>
{{-- Delete Modal --}}
<div class="modal fade" id="delete_notification" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="delete-action">
                    <div class="delete-header">
                        <h4>{{__('web.user.delete_notification')}}</h4>
                        <p>{{__('web.user.are_you_sure')}}</p>
                    </div>
                    <div class="modal-btn">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-secondary w-100 deletebtn">
                                    {{__('web.common.delete')}}
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" data-bs-dismiss="modal" class="btn btn-primary w-100">
                                    {{__('web.common.cancel')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- deleteAllNotifications --}}
<div class="modal fade" id="deleteAllNotifications" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="delete-action">
                    <div class="delete-header">
                        <h4>{{__('web.user.delete_all_notifications')}}</h4>
                        <p>{{__('web.user.are_you_sure_delete_all_notifications')}}</p>
                    </div>
                    <div class="modal-btn">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-secondary w-100 deleteAllNotifications">
                                    {{__('web.common.delete')}}
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" data-bs-dismiss="modal" class="btn btn-primary w-100">
                                    {{__('web.common.cancel')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="{{ asset('frontend/custom/js/user/notifications.js') }}"></script>
@endpush

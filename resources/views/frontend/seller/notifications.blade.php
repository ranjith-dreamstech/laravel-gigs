@extends('frontend.seller.partials.app')
@section('content')
<div class="page-wrapper">
    <div class="page-content content bg-light notify-info">
        <div class="main-title mb-4">
            <h4>{{__('web.common.notifications')}}</h4>
        </div>
        <div class="row">

            <!-- Notifications -->
            <div class="col-xl-12">
                <div class="notification-header d-none">
                    <div class="form-sort form-wrap">
                        {{-- <span class="form-icon">
                            <i class="ti ti-calendar-event"></i>
                        </span>
                        <select class="select">
                            <option>Jan 2024</option>
                            <option>Feb 2024</option>
                            <option>Mar 2024</option>
                        </select> --}}
                    </div>
                    <ul>
                        <li>
                            <a href="javascript:void(0);" id="markAllAsRead" class="btn btn-white"><i
                                    class="feather-check"></i> {{__('web.common.maer_all_as_read')}}</a>
                        </li>
                        <li>
                            <a href="#" class="btn btn-delete" id="deleteAll"><i class="feather-trash-2"></i>
                                {{__('web.common.delete_all')}}</a>
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
                                <a href="javascript:void(0);" class="btn btn-secondary w-100 deletebtn">
                                    {{__('web.common.delete')}}
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary w-100">
                                    {{__('web.common.cancel')}}
                                </a>
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
                                <a href="javascript:void(0);" class="btn btn-secondary w-100 deleteAllNotifications">
                                    {{__('web.common.delete')}}
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-bs-dismiss="modal" class="btn btn-primary w-100">
                                    {{__('web.common.cancel')}}
                                </a>
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

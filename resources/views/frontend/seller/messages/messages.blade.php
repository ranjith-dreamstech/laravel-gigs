@extends('frontend.seller.partials.app')
@section('content')
<div class="page-wrapper">
    <!-- Content -->
    <div class="chat-page-wrapper">
        <div class="container px-0">
            <div class="page-back-btn mx-4 mb-4">
                <h4>{{__('web.user.messages')}}</h4>
            </div>
            <div class="content mx-4">
                <!-- sidebar group -->
                <div class="sidebar-group left-sidebar chat_sidebar">

                    <!-- Chats sidebar -->
                    <div id="chats" class="left-sidebar-wrap sidebar active slimscroll">

                        <div class="mb-0">
                            <!-- Left Chat Title -->
                            <div class="left-chat-title all-chats">
                                <div class="select-group-chat mb-2">
                                    <a href="javascript:void(0);">
                                        {{__('web.user.all_chats')}}
                                    </a>
                                </div>
                                <div class="add-section">
                                    <!-- Chat Search -->
                                    <div class="input-icon">
                                        <span class="input-icon-addon">
                                            <i class="feather-search fs-14"></i>
                                        </span>
                                        <input type="email" class="form-control form-control-md" id="chatSearch" placeholder="{{__('web.blog.search')}}">
                                    </div>
                                    <!-- /Chat Search -->
                                </div>
                            </div>
                            <!-- /Left Chat Title -->

                            <div class="sidebar-body chat-body" id="chatsidebar">
                                <ul class="user-list">
                                    @if(!empty($relatedUsers) && count($relatedUsers) > 0)
                                    @foreach($relatedUsers as $user)
                                    <li class="user-list-item chat-user-list" data-userid="{{ $user->id }}">
                                        <a href="javascript:void(0);">
                                            <div class="avatar avatar-online">
                                                <img src="{{ $user->userDetail ? $user->userDetail->profile_image : uploadedAsset('default','profile')}}" class="rounded-circle" alt="profile">
                                            </div>
                                            @php
                                                $message_text = $user->lastMessage ? $user->lastMessage->message : '';
                                                if(strlen($message_text) > 15){
                                                    $message_text = substr($message_text, 0, 15) . '...';
                                                }
                                            @endphp
                                            <div class="users-list-body">
                                                <div>
                                                    <h5>{{ getCurrentUserFullname($user->id) }}</h5>
                                                    <p class="last-message">{{ $message_text }}</p>
                                                </div>
                                                <div class="last-chat-time">
                                                    <small class="text-muted last_message_time">{{ $user->lastMessage ? $user->lastMessage->created_at->diffForHumans() : '' }}</small>
                                                   
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- / Chats sidebar -->
                </div>
                <!-- /Sidebar group -->

                <!-- Chat -->
                <div class="chat chat-messages" id="middle">
                    <div class="h-100">
                        <div class="chat-header">
                            <div class="user-details mb-0">
                                <figure class="avatar mb-0">
                                    <img src="{{ uploadedAsset('default','profile')}} " id="chat_avatar" data-userid="" class="rounded-circle" alt="profile">
                                </figure>
                                <div class="mt-1">
                                    <h5 class="chat-username" aria-label="{{ __('web.user.chat_with') }}">{{ __('web.user.chat_with') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="chat-body chat-page-group" id="messagebody" style="max-height: calc(100vh - 300px);">
                            <div class="messages" id="messageArea">
                                
                            </div>
                        </div>
                    </div>
                    <div class="chat-footer">
                        <form>
                            <div class="smile-foot">                         
                                <div class="chat-action-btns">
                                    <div class="chat-action-col">
                                        <a class="action-circle" href="javascript:void(0);">
                                            <i class="fas fa-link" id="openFile"></i>
                                        </a>
                                        <div class="file-section">
                                            <input type="file" id="fileupload" name="file" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="select-section d-none mt-2 mb-2 me-2">
                                <span class="selected-file badge bg-success p-2"></span>
                            </div>
                    
                            <div class="replay-forms">
                                <input type="text" class="form-control chat_form" id="messageinput" data-senderid="{{ $authUser->id }}" placeholder="{{__('web.user.type_your_message')}}">
                            </div>
                            <div class="form-buttons">
                                <button class="btn send-btn" type="button" id="sendmsg">
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                </div>
                <!-- /Chat -->

            </div>
        </div>
    </div>
    <!-- /Content -->
</div>
@endsection
@push('scripts')
<script src="{{ asset('frontend/custom/js/mqtt.min.js') }}"></script>
<script src="{{ asset('frontend/custom/js/seller/messages.js') }}"></script>
@endpush

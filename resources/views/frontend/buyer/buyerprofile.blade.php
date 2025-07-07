@extends($userlayout)
@section('content')
<!-- Page Content -->
<div class="page-wrapper">
    <div class="page-content content">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="main-title mb-4">
                    <h4>{{ __('web.user.my_profile') }}</h4>
                </div>
                <div class="card profile-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 justify-content-between flex-wrap">
                            <div class="d-flex align-items-center flex-shrink-0">

                                <span class="avatar avatar-lg"><img class="rounded-2"
                                        src="{{ $user->userDetail->profile_image ?? asset('backend/assets/img/default-profile.png') }}"
                                        alt="img"></span>
                                <div class="ms-3">

                                    <h6 class="mb-1 d-inline-flex flex-wrap align-items-center"> {{ optional($user->userDetail)->first_name && optional($user->userDetail)->last_name 
                                                ? optional($user->userDetail)->first_name . ' ' . optional($user->userDetail)->last_name 
                                                : $user->name }} <span
                                            class="badge badge-success-transparent ms-3 rounded-pill">
                                            {{ $gigCount }} {{ Str::plural('Gig', $gigCount) }}
                                        </span></h6>
                                    <p class="mb-2">{{ $user->userDetail->city->name ?? '-' }},
                                        {{ $user->userDetail->country->name ?? '-' }}</p>

                                </div>
                            </div>
                            <div class="gap-2 d-flex">
                                <a href="{{ route('buyer.settings') }}" class="btn btn-md btn-dark"><i
                                        class="ti ti-user-edit me-1"></i>Edit Profile</a>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card profile-details profile-card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('web.user.personal_details') }}</h5>
                    </div>
                    <div class="card-body personal-card">
                        <div class="row row-gap-3">
                            <div class="col-md-4 col-sm-6">
                                <h6 class="mb-1">{{ __('web.user.name') }}</h6>
                                <p class="mb-0">
                                    {{ optional($user->userDetail)->first_name && optional($user->userDetail)->last_name 
                                                ? optional($user->userDetail)->first_name . ' ' . optional($user->userDetail)->last_name 
                                                : $user->name }}
                                </p>

                            </div>
                            <div class="col-md-4 col-sm-6">
                                <h6 class="mb-1">{{ __('web.user.email') }}</h6>
                                <p class="mb-0">{{ $user->email }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <h6 class="mb-1">{{ __('web.user.phone') }}</h6>
                                <p class="mb-0">{{ $user->phone_number ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <h6 class="mb-1">{{ __('web.user.date') }}</h6>
                                <p class="mb-0">{{ $user->userDetail->dob ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <h6 class="mb-1">{{ __('web.user.speaks') }}</h6>
                                <p class="mb-0">{{ $user->userDetail->language_known ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <h6 class="mb-1">{{ __('web.user.member_since') }}</h6>
                                <p class="mb-0">{{ $user->created_date }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card profile-details profile-card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('web.user.address_line') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row row-gap-3">
                            <div class="col-md-4 col-sm-6">
                                <h6 class="mb-1">{{ __('web.user.country') }}</h6>
                                <p class="mb-0">{{ $user->userDetail->country->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <h6 class="mb-1">{{ __('web.user.city') }}</h6>
                                <p class="mb-0">{{ $user->userDetail->city->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <h6 class="mb-1">{{ __('web.user.state') }}</h6>
                                <p class="mb-0">{{ $user->userDetail->state->name ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <h6 class="mb-1">{{ __('web.user.address_line') }}</h6>
                                <p class="mb-0">{{ $user->userDetail->address ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <h6 class="mb-1">{{ __('web.user.postal_code') }}</h6>
                                <p class="mb-0">{{ $user->userDetail->postal_code ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card profile-card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('web.user.about_me') }}</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $user->userDetail->about ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Page Content -->
@endsection

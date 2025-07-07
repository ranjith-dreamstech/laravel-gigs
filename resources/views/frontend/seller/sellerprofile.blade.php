
@extends($userlayout)
@section('content')
          <!-- Page Content -->
          <div class="page-wrapper">
            <div class="page-content content bg-light">
                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="main-title mb-4">
                            <h4>{{ __('web.user.my_profile') }}</h4>
                        </div>
                        <div class="card profile-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 justify-content-between flex-wrap">
                                    <div class="d-flex align-items-center flex-shrink-0">
                                        <span class="avatar avatar-lg"><img class="rounded-2" src="{{ $user->userDetail->profile_image ?? asset('backend/assets/img/default-profile.png') }}" alt="img"></span>
                                        <div class="ms-3">
                                            <h6 class="mb-1 d-inline-flex flex-wrap align-items-center"> {{ $user->name }}  <span class="badge badge-success-transparent ms-3 rounded-pill">
                                                {{ $gigCount }} {{ Str::plural('Gig', $gigCount) }}</span></h6>
                                            <p class="mb-2">{{ $user->userDetail->city->name ?? '-' }}, {{ $user->userDetail->country->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="gap-2 d-flex">
                                        <a href="{{ route('seller.settings') }}" class="btn btn-md btn-dark"><i class="ti ti-user-edit me-1"></i>Edit Profile</a>
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
                        <div class="card personal-card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('web.user.skills') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center flex-wrap-wrap gap-2 ">
                                    
                                    @php
                                    $skills = isset($user->userDetail->skills) ? explode(',', $user->userDetail->skills) : [];
                                @endphp
                                
                                @forelse($skills as $skill)
                                <div class="badge bg-light fw-medium fs-13 text-dark me-1 mb-1">
                                    <i class="ti ti-point-filled me-1"></i> {{ trim($skill) }}
                                </div>
                                @empty
                                    <div class="badge bg-light fw-medium fs-13 text-dark me-1 mb-1">
                                        - {{-- no icon here --}}
                                    </div>
                                @endforelse
                            
                                
                                
                                    {{-- <div class="badge bg-light fw-medium fs-13 text-dark">
                                        <i class="ti ti-point-filled me-1"></i> Graphics Design
                                    </div>
                                    <div class="badge bg-light fw-medium fs-13 text-dark">
                                        <i class="ti ti-point-filled me-1"></i> Adobe Illustrator
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card profile-details">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('web.user.checkout_my_recent_portfolio') }}</h5>
                            </div>
                            <div class="card-body Recent-card">
                                <div class="gigs-card-slider owl-carousel">
                                    @forelse($user->gigs as $gig)
                                        @php
                                            $imageJson = $gig->meta->where('key', 'gigs_image')->pluck('value')->first();
                                            $images = [];
                        
                                            if (!empty($imageJson)) {
                                                $decoded = json_decode($imageJson, true);
                                                $images = is_array($decoded) ? $decoded : [$imageJson];
                                            }
                        
                                            // Get the first image only
                                            $firstImage = $images[0] ?? null;
                                        @endphp
                        
                                        <div class="gigs-grid m-0">
                                            <div class="gigs-img">
                                                <div class="slide-images">
                                                    <a href="service-details.html">
                                                        @if($firstImage)
                                                            <img src="{{ uploadedAsset($firstImage, 'default2') }}" class="img-fluid portfolio-img" alt="profile">
                                                        @else
                                                            <img src="{{ asset('backend/assets/img/no-image.png') }}" class="img-fluid" alt="noavailable">
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p>No recent Portfolio found</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        

                        <div class="card profile-details mb-0 Recent-card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('web.user.recent_work') }}</h5>
                            </div>
                            <div class="card-body pb-0">
                                <div class="gigs-card-slider owl-carousel">
                                    @forelse($user->gigs as $gig)
                                        <div class="gigs-grid">
                                            <div class="gigs-img">
                                                
                                                @php
                                           
                                                $imageJson = $gig->meta->where('key', 'gigs_image')->pluck('value')->first();
                                            
                                               
                                                $images = [];
                                                if (!empty($imageJson)) {
                                                    $decoded = json_decode($imageJson, true);
                                                    $images = is_array($decoded) ? $decoded : [$imageJson];
                                                }
                                               
                                               @endphp
                                            
                                         
                                            
                                            <div class="img-slider owl-carousel">
                                                @forelse($images as $img)
                                                     
                                                    <div class="slide-images">
                                                        <a href="{{ uploadedAsset($img, 'default2') }}">
                                                            <img src="{{ uploadedAsset($img, 'default2') }}" class="img-fluid" alt="gig">
                                                        </a>
                                                    </div>
                                                @empty
                                                    <div class="slide-images">
                                                        <img src="{{ asset('backend/assets/img/no-image.png') }}" class="img-fluid" alt="noavailable">
                                                    </div>
                                                @endforelse
                                            </div>
                                            
                                                
                                            
                                                <div class="fav-selection">
                                                    <a href="javascript:void(0);"><i class="ti ti-heart-filled"></i></a>
                                                </div>
                                            </div>
                                            <div class="gigs-content">
                                                <div class="gigs-info">
                                                    <a href="javascript:void(0);" class="badge bg-primary-light">
                                                        {{ $gig->category->name ?? 'Uncategorized' }}
                                                    </a>
                                                </div>
                                                <div class="gigs-title">
                                                    <h3>
                                                        <a href="">{{ $gig->title }}</a>
                                                    </h3>
                                                </div>
                                                <div class="gigs-card-footer">
                                                    <h5>${{ $gig->general_price }}</h5>
                                                    <span class="badge">Delivery in {{ $gig->days }} day{{ $gig->days > 1 ? 's' : '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p>No recent work found.</p>
                                    @endforelse
                                </div>
                                
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
@endsection
@push('plugins')

<script src="{{ asset('frontend/assets/plugins/owl_carousel/js/owl.carousel.min.js') }}"></script>

@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/assets/plugins/owl_carousel/css/owl.carousel.min.css') }}">
@endpush




@extends('admin.admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content me-4">

        <!-- Breadcrumb -->
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Gig Details</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">{{ __('admin.common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.gigs') }}">Gigs</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
                <a href="/admin/gigs-list" class="btn btn-sm btn-primary ms-2">
                    <i class="ti ti-arrow-left me-1"></i> Back to Gigs
                </a>
            </div>
        </div>
        <!-- /Breadcrumb -->

        <!-- Gig Details -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title">{{ $gigs->title }}</h4>
                <span class="badge bg-{{ $gigs->status == 1 ? 'success' : 'danger' }} text-white">
                    {{ $gigs->status == 1 ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Title:</div>
                                    <div class="col-md-8">{{ $gigs->title }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Slug:</div>
                                    <div class="col-md-8">{{ $gigs->slug }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Category:</div>
                                    <div class="col-md-8">{{ $category ? $category->name : 'N/A' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Subcategory:</div>
                                    <div class="col-md-8">{{ $subCategory ? $subCategory->name : 'N/A' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Price:</div>
                                    <div class="col-md-8">${{ $gigs->general_price }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Delivery Days:</div>
                                    <div class="col-md-8">{{ $gigs->days }} days</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Revisions:</div>
                                    <div class="col-md-8">{{ $gigs->no_revisions }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Tags:</div>
                                    <div class="col-md-8">
                                        @if(is_array($tags))
                                        {{ implode(', ', $tags) }}
                                        @else
                                        {{ $tags }}
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Featured:</div>
                                    <div class="col-md-8">
                                        <span class="badge bg-{{ $gigs->is_feature == 1 ? 'info' : 'secondary' }}">
                                            {{ $gigs->is_feature == 1 ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Recommended:</div>
                                    <div class="col-md-8">
                                        <span class="badge bg-{{ $gigs->is_recommend == 1 ? 'info' : 'secondary' }}">
                                            {{ $gigs->is_recommend == 1 ? 'Yes' : 'No' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seller Information -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Seller Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="flex-shrink-0">
                                        @if($userDetails && $userDetails->profile_image)
                                        <img src="{{ $userDetails->profile_image }}" alt="Profile" class="rounded-circle" width="80" height="80">
                                        @else
                                        <img src="{{ asset('backend/assets/img/user-default.png') }}" alt="Profile" class="rounded-circle" width="80" height="80">
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-0">
                                            @if($userDetails)
                                            {{ $userDetails->first_name }} {{ $userDetails->last_name }}
                                            @else
                                            {{ $user ? $user->name : 'N/A' }}
                                            @endif
                                        </h5>
                                        <p class="text-muted mb-0">{{ $user ? $user->email : 'N/A' }}</p>
                                    </div>
                                </div>

                                @if($user)
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Phone:</div>
                                    <div class="col-md-8">{{ $user->phone_number ?? 'N/A' }}</div>
                                </div>
                                @endif

                                @if($userDetails)
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">First Name:</div>
                                    <div class="col-md-8">{{ $userDetails->first_name ?? 'N/A' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Last Name:</div>
                                    <div class="col-md-8">{{ $userDetails->last_name ?? 'N/A' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Address:</div>
                                    <div class="col-md-8">{{ $userDetails->address }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Description</h5>
                            </div>
                            <div class="card-body">
                                {!! $gigs->description !!}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fast Service + Extra Info -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Fast Service</h5>
                            </div>
                            <div class="card-body">
                                @if($gigs->fast_service_tile)
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Title:</div>
                                    <div class="col-md-8">{{ $gigs->fast_service_tile }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Price:</div>
                                    <div class="col-md-8">${{ $gigs->fast_service_price }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Days:</div>
                                    <div class="col-md-8">{{ $gigs->fast_service_days }} days</div>
                                </div>
                                @else
                                <p class="text-muted">No fast service available for this gig.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Extra Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Video Platform:</div>
                                    <div class="col-md-8">{{ $gigs->video_platform ?? 'N/A' }}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Video Link:</div>
                                    <div class="col-md-8">
                                        @if($gigs->video_link)
                                        <a href="{{ $gigs->video_link }}" target="_blank">{{ $gigs->video_link }}</a>
                                        @else
                                        N/A
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 fw-bold">Extra Service:</div>
                                    <div class="col-md-8">
                                        @if(!empty($extraServices))
                                        @foreach($extraServices as $service)
                                        <div class="mb-2">
                                            <strong>{{ $service['title'] ?? 'N/A' }}</strong>: ${{ $service['price'] ?? '0' }}
                                        </div>
                                        @endforeach
                                        @else
                                        N/A
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQs -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>FAQs</h5>
                            </div>
                            <div class="card-body">
                                @if(!empty($faqs))
                                <div class="accordion" id="faqAccordion">
                                    @foreach($faqs as $index => $faq)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $index }}">
                                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                                {{ $faq['question'] ?? 'Question ' . ($index + 1) }}
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#faqAccordion">
                                            <div class="accordion-body">
                                                {{ $faq['answer'] ?? 'No answer provided.' }}
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <p class="text-muted">No FAQs available for this gig.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Gig Details -->


    </div>
    @include('admin.partials.footer')
</div>
<!-- /Page Wrapper -->

@endsection

@push('scripts')
@endpush

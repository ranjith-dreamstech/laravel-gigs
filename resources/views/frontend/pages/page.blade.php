@extends($layout)
@section('content')
<!-- Breadscrumb Section -->
<div class="breadcrumb-bar">
    <div class="container">
        <div class="row align-items-center text-center">
            <div class="col-md-12 col-12">
                <h2 class="breadcrumb-title">{{ $page->page_title ?? "" }}</h2>
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">{{ __('web.home.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('web.home.pages') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $page->page_title ?? "" }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- /Breadscrumb Section -->
<div class="section privacy-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="">
                    {!! $sectionContent !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

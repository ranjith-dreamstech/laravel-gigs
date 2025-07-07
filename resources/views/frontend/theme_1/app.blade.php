<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- SEO Meta -->
	<title>{{ isset($seo_title) ? $seo_title : config('app.name') }}</title>
	<meta name="description" content="{{ isset($seo_description) ? $seo_description : config('app.name') }}">
	<meta name="keywords" content="{{ isset($meta_keywords) ? $meta_keywords : '' }}">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<!-- Open Graph for Social Sharing -->
	<meta property="og:title" content="{{ isset($og_title) ? $og_title : config('app.name') }}">
	<meta property="og:description" content="{{ isset($og_description) ? $og_description : '' }}">
	<meta property="og:image" content="{{ isset($og_image) ? asset($og_image) : asset('frontend/assets/img/logo.svg') }}">
	<meta property="og:url" content="{{ url()->current() }}">
	<meta property="og:type" content="website">

	<!-- Favicon -->
	<link rel="icon" type="image/svg+xml" href="{{ isset($favicon) ? asset($favicon) : asset('frontend/assets/img/favicon.png') }}">
	@php 
		 $isRTL = isRTL(app()->getLocale());
	@endphp

	<!-- Bootstrap CSS -->
	@if($isRTL)
	<link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.rtl.min.css') }}">
	@else
	<link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
	@endif
	<!-- Theme Settings Js -->
	<script src="{{ asset('frontend/assets/js/theme-script.js') }}"></script>

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="{{ asset('frontend/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/assets/plugins/fontawesome/css/all.min.css') }}">

	<!-- Fearther CSS -->
	<link rel="stylesheet" href="{{ asset('frontend/assets/css/feather.css') }}">

	<!-- summernote CSS -->
	<link rel="stylesheet" href="{{ asset('backend/assets/plugins/summernote/summernote-bs5.min.css') }}">

	<!-- Tabler Icon CSS -->
	<link rel="stylesheet" href="{{ asset('frontend/assets/plugins/tabler-icons/tabler-icons.min.css') }}">

	<!-- Aos CSS -->
	<link rel="stylesheet" href="{{ asset('frontend/assets/plugins/aos/aos.css') }}">

	<!-- Owl carousel CSS -->
	<link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.carousel.min.css') }}">

	<!-- Select CSS -->
	<link rel="stylesheet" href="{{ asset('frontend/assets/plugins/select2/css/select2.min.css') }}">

	<!-- Main CSS -->
	@if($isRTL)
	<link rel="stylesheet" href="{{ asset('frontend/assets/css/style-rtl.css') }}">
	@else
	<link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
	@endif
	<!-- {{-- Custom CSS --}} -->
	<link rel="stylesheet" href="{{ asset('frontend/custom/css/custom-styles.css') }}">

	@stack('styles')

</head>

<body data-theme="{{ $theme ?? 1}}" data-dir="{{ $isRTL ? 'rtl' : 'ltr' }}">



	<!-- Main Wrapper -->
	<div class="main-wrapper">
		<div class="top-header">
			{{__('web.home.home_header_desc')}}
			<button 
				type="button" 
				class="close-btn" 
				aria-label="Close" 
				tabindex="0"
				onclick="this.parentElement.style.display='none'"
				onkeydown="if(event.key==='Enter'||event.key===' '){this.click();}"
				ontouchstart="this.click();"
			>
				<i class="ti ti-xbox-x" aria-hidden="true"></i>
			</button>
		</div>
		<!-- Header -->
		@include('frontend.theme_1.header')
		<!-- /Header -->
		<main id="main-content" role="main">
			@yield('content')
			@include('frontend.toast')
		</main>
		<!-- Footer -->
		@include('frontend.theme_1.footer')
		<!-- /Footer -->

		<!-- Mouse Cursor -->
		<div class="mouse-cursor cursor-outer"></div>
		<div class="mouse-cursor cursor-inner"></div>
		<!-- /Mouse Cursor -->

		<!-- Top Scroll -->
		<div class="back-to-top">
			<a class="back-to-top-icon align-items-center justify-content-center d-flex" href="#top" aria-label="Back to top">
				<i class="ti ti-arrow-badge-up" aria-hidden="true"></i>
			</a>
		</div>
		<!-- /Top Scroll -->

	</div>
	<!-- /Main Wrapper -->

	<!-- jQuery -->
	<script src="{{ asset('frontend/assets/js/jquery-3.7.1.min.js') }}"></script>

	<script src="{{ asset('frontend/custom/js/jquery/jquery-validation.min.js') }}"></script>
	<script src="{{ asset('frontend/custom/js/jquery/jquery-validation-additional-methods.min.js') }}"></script>

	<!-- jQuery validation -->
	<script src="{{ asset('backend/assets/js/jquery/jquery-validation.min.js') }}"></script>
	<script src="{{ asset('backend/assets/js/jquery/jquery-validation-additional-methods.min.js') }}"></script>

	<!-- Bootstrap Core JS -->
	<script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('frontend/assets/js/bootstrap-scrollspy.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/purify.min.js') }}"></script>

	<!-- summernote JS -->
	<script src="{{ asset('backend/assets/plugins/summernote/summernote-bs5.min.js') }}"></script>
	<!-- jQuery (needed for Summernote) -->

	<!-- Summernote JS -->
	{{-- <script src="{{ asset('backend/assets/plugins/summernote/summernote-lite.min.js') }}"></script> --}}

	<!-- Feather JS -->
	<script src="{{ asset('frontend/assets/js/feather.min.js') }}"></script>

	<!-- Aos -->
	<script src="{{ asset('frontend/assets/plugins/aos/aos.js') }}"></script>

	<!-- counterup JS -->
	<script src="{{ asset('frontend/assets/js/jquery.waypoints.js') }}"></script>
	<script src="{{ asset('frontend/assets/js/jquery.counterup.min.js') }}"></script>

	<!-- Owl Carousel JS -->
	<script src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>

	<!-- Select JS -->
	<script src="{{ asset('frontend/assets/plugins/select2/js/select2.min.js') }}"></script>

	@stack('plugins')

	<!-- Custom JS -->
	@if($isRTL)
	<script src="{{ asset('frontend/assets/js/script-rtl.js') }}"></script>
	@else
	<script src="{{ asset('frontend/assets/js/script.js') }}"></script>
	@endif
	<script src="{{ asset('frontend/custom/js/custom-script.js') }}"></script>
	<script src="{{ asset('frontend/custom/js/lang_script.js') }}"></script>

	@stack('scripts')


</body>

</html>

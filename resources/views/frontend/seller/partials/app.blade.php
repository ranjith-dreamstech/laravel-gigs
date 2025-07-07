<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('meta_title', $companyName)</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('frontend/assets/img/favicon.png') }}">
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

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/plugins/tabler-icons/tabler-icons.min.css') }}">

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap-datetimepicker.min.css') }}">

    <!-- Daterangepicker CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/plugins/daterangepicker/daterangepicker.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Fearther CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/feather.css') }}">
    

    <!-- Select CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/plugins/select2/css/select2.min.css') }}">

    <!-- Main CSS -->
    @if($isRTL)
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style-rtl.css') }}">
    @else
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('frontend/custom/css/custom-styles.css') }}">

    @stack('styles')

</head>

<body data-theme={{ $theme ?? 1}} data-dir="{{ $isRTL ? 'rtl' : 'ltr' }}" @if(request()->routeIs('seller.messages')) class="chat-page main-chat-blk" @endif>
    <!-- Main Wrapper -->
    <div class="main-wrapper @if(request()->routeIs('seller.messages')) chat-wrapper @endif">
        @include('frontend.seller.partials.header')
        @include('frontend.seller.partials.sidebar')
        @yield('content')
        @include('frontend.toast')

        <!-- Mouse Cursor -->
        <div class="mouse-cursor cursor-outer"></div>
        <div class="mouse-cursor cursor-inner"></div>
        <!-- /Mouse Cursor -->

        <!-- Top Scroll -->
        <div class="back-to-top">
            <a class="back-to-top-icon align-items-center justify-content-center d-flex" href="#top">
                <img src="/frontend/assets/img/icons/arrow-badge-up.svg" alt="img">
            </a>
        </div>
        <!-- /Top Scroll -->

    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('frontend/assets/js/jquery-3.7.1.min.js') }}"></script>

    <script src="{{ asset('frontend/custom/js/jquery/jquery-validation.min.js') }}"></script>
    <script src="{{ asset('frontend/custom/js/jquery/jquery-validation-additional-methods.min.js') }}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('frontend/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/bootstrap-scrollspy.js') }}"></script>

    <!-- Datatable JS -->
    <script src="{{ asset('backend/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Slimscroll JS -->
    <script src="{{ asset('frontend/assets/js/jquery.slimscroll.min.js') }}"></script>

    <!-- Feather JS -->
    <script src="{{ asset('frontend/assets/js/feather.min.js') }}"></script>

    <!-- date range -->
    <script src="{{ asset('frontend/assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/bootstrap-datetimepicker.min.js') }}"></script>

    <!-- Sticky Sidebar JS -->
    <script src="{{ asset('frontend/assets/plugins/theia-sticky-sidebar/ResizeSensor.js') }}"></script>
    <script src="{{ asset('frontend/assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}"></script>

    <!-- Select JS -->
    <script src="{{ asset('frontend/assets/plugins/select2/js/select2.min.js') }}"></script>

    <script src="{{ asset('frontend/assets/js/purify.min.js') }}"></script>

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

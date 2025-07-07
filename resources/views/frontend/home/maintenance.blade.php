<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>{{$response['title']}}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('frontend/assets/img/favicon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/custom/css/custom-styles.css?v='.time()) }}') }}">

</head>
<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Construction Content -->
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <div class="error-wrapper maintanence-sec">
                    <!-- Under Construction -->
                    <div class="error-item p-0">
                        <div class="coming-soon text-center">
                            <div class="header-logo">
                                <img src="{{ $logo }}" class="img-fluid" alt="img">
                            </div>
                            <div class="coming-content">
                                <div class="row justify-content-center gx-0">
                                    <div class="col-lg-5 col-md-6">
                                        <h2>Website is Under Maintenance</h2>
                                        {!! $response['description'] ?? '' !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Error Img -->
                <div class="error-imgs count-imgs">
                    <img src="{{ asset('frontend/assets/img/bg/error-01.png') }}" alt="img" class="error-01 error-bg">
                    <img src="{{ asset('frontend/assets/img/bg/error-01.png') }}" alt="img" class="error-05 error-bg">
                    <img src="{{ asset('frontend/assets/img/bg/error-02.png') }}" alt="img" class="error-02 error-bg">
                    <img src="{{ asset('frontend/assets/img/bg/error-04.png') }}" alt="img" class="error-04 error-bg">
                </div>
                <!-- /Error Img -->
            </div>
        </div>
        <!-- /Construction Content -->

        <!-- Mouse Cursor -->
        <div class="mouse-cursor cursor-outer"></div>
        <div class="mouse-cursor cursor-inner"></div>
        <!-- /Mouse Cursor -->

    </div>
    <!-- /Main Wrapper -->
</body>
</html>

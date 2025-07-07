<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>{{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="/frontend/assets/img/favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/frontend/assets/css/bootstrap.min.css">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="/frontend/assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="/frontend/assets/plugins/fontawesome/css/all.min.css">

    <!-- Fearther CSS -->
    <link rel="stylesheet" href="/frontend/assets/css/feather.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="/frontend/assets/css/style.css">

</head>

<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Error -->
        <div class="error-wrapper">
            <div class="error-item">
                <div class="row w-100">

                    <!-- 404 Content -->
                    <div class="col-md-6 col-sm-8 mx-auto">
                        <div class="error-content text-center">
                            <div class="error-img">
                                <img src="/frontend/assets/img/error/error-404.png" class="img-fluid" alt="img">
                            </div>
                            <div class="error-info">
                                <h2>{{__('web.home.you_are_lost')}}</h2>
                                <p>{{__('web.home.page_not_found')}}</p>
                                <a href="/" class="btn btn-primary"><i class="feather feather-chevron-left me-2"></i>{{__('web.home.go_back')}}</a>
                            </div>
                        </div>
                    </div>
                    <!-- /404 Content -->

                </div>

                <!-- Error Img -->
                <div class="error-imgs">
                    <img src="/frontend/assets/img/bg/error-01.png" alt="img" class="error-01 error-bg">
                    <img src="/frontend/assets/img/bg/error-01.png" alt="img" class="error-05 error-bg">
                    <img src="/frontend/assets/img/bg/error-02.png" alt="img" class="error-02 error-bg">
                    <img src="/frontend/assets/img/bg/error-03.png" alt="img" class="error-03 error-bg">
                    <img src="/frontend/assets/img/bg/error-04.png" alt="img" class="error-04 error-bg">
                </div>
                <!-- /Error Img -->

            </div>
        </div>
        <!-- /Error -->

        <!-- Mouse Cursor -->
        <div class="mouse-cursor cursor-outer"></div>
        <div class="mouse-cursor cursor-inner"></div>
        <!-- /Mouse Cursor -->

    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="/frontend/assets/js/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="/frontend/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/frontend/assets/js/bootstrap-scrollspy.js"></script>

    <!-- Feather JS -->
    <script src="/frontend/assets/js/feather.min.js"></script>

    <!-- Custom JS -->
    <script src="/frontend/assets/js/script.js"></script>

</body>

</html>

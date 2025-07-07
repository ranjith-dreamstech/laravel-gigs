<!-- Page Wrapper -->
@extends('admin.admin')
@section('content')
<!-- Page Wrapper -->
<div class="page-wrapper">
    <div class="content me-4">

        <!-- Breadcrumb -->
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h4 class="mb-1">Calendar</h4>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="index.html">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Calendar</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex my-xl-auto right-content align-items-center flex-wrap">
                <div class="mb-2">
                    <a href="javascript:void(0);" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#add_booking"><i class="ti ti-plus me-2"></i>Add New Booking</a>
                </div>
            </div>
        </div>
        <!-- /Breadcrumb -->

        <div class="row">
            <div class="col-md-10">
                <ul class="nav nav-tabs nav-tabs-solid custom-nav-tabs bg-transparent mb-3" id="bookingStatusFilter">
                    <li class="nav-item" role="presentation"><a class="nav-link active">All Bookings</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link">In Progress</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link">Confirmed</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link">Completed</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link">Rejected</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <div class="text-end mb-3">
                    <a href="#filtercollapse" class="filtercollapse coloumn d-inline-flex align-items-center" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="filtercollapse">
                        <i class="ti ti-filter me-1"></i> Filter<span class="badge badge-xs rounded-pill bg-danger ms-2">0</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="collapse" id="filtercollapse">
            <div class="filterbox mb-3 d-flex align-items-center">
                <h6 class="me-3">Filters</h6>
                <div class="dropdown me-2">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        Cars
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg p-2">
                        <li>
                            <div class="top-search m-2">
                                <div class="top-search-group">
                                    <span class="input-icon">
                                        <i class="ti ti-search"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Search">
                                </div>
                            </div>
                        </li>
                        @foreach ($Vehicles as $Vehicle)
                        <li>
                            <label class="dropdown-item d-flex align-items-center rounded-1">
                                <input class="form-check-input m-0 me-2" value="{{ $Vehicle->id }}" id="selectedVehcile" type="checkbox">{{ $Vehicle->name }}
                            </label>
                        </li>
                        @endForeach
                    </ul>
                </div>
                <div class="dropdown me-2">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        Customer
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg p-2">
                        <li>
                            <div class="top-search m-2">
                                <div class="top-search-group">
                                    <span class="input-icon">
                                        <i class="ti ti-search"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Search">
                                </div>
                            </div>
                        </li>
                        @foreach ($customerss as $customer)
                        <li>
                            <label class="dropdown-item d-flex align-items-center rounded-1">
                                <input class="form-check-input m-0 me-2" value="{{ $customer->id }}" id="selectedCustomer" type="checkbox">{{ $customer->name }}
                            </label>
                        </li>
                        @endForeach
                    </ul>
                </div>
                <div class="dropdown me-2">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        Driver
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg p-2">
                        <li>
                            <div class="top-search m-2">
                                <div class="top-search-group">
                                    <span class="input-icon">
                                        <i class="ti ti-search"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Search">
                                </div>
                            </div>
                        </li>
                        @foreach ($drivers as $driver)
                        <li>
                            <label class="dropdown-item d-flex align-items-center rounded-1">
                                <input class="form-check-input m-0 me-2" value="{{ $driver->id }}" id="selectedDriver" type="checkbox">{{ $driver->driver_name }}
                            </label>
                        </li>
                        @endForeach
                    </ul>
                </div>
                <div class="dropdown me-2">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        Car Type
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg p-2">
                        <li>
                            <div class="top-search m-2">
                                <div class="top-search-group">
                                    <span class="input-icon">
                                        <i class="ti ti-search"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Search">
                                </div>
                            </div>
                        </li>
                        @foreach ($cartypes as $cartype)
                        <li>
                            <label class="dropdown-item d-flex align-items-center rounded-1">
                                <input class="form-check-input m-0 me-2" value="{{ $cartype->id }}" id="selectedCartype" type="checkbox">{{ $cartype->name }}
                            </label>
                        </li>
                        @endForeach
                    </ul>
                </div>
                <a href="javascript:void(0);" class="me-2 text-purple links" id="applyFilter">Apply</a>
                <a href="javascript:void(0);" class="text-danger links" id="clearFilter">Clear All</a>
            </div>
        </div>
        <div>
            <div class="card mb-0">
                <div class="card-body">
                    <div class="adminCalendar"></div>
                </div>
            </div>
        </div>

    </div>
    <!-- Footer-->
    <div class="footer d-sm-flex align-items-center justify-content-between bg-white p-3">
        <p class="mb-0">
            <a href="javascript:void(0);">Privacy Policy</a>
            <a href="javascript:void(0);" class="ms-4">Terms of Use</a>
        </p>
        <p>&copy; 2025 Dreamsrent, Made with <span class="text-danger">❤</span> by <a href="javascript:void(0);" class="text-secondary">Dreams</a></p>
    </div>
    <!-- /Footer-->
</div>
<!-- /Page Wrapper -->

<!-- Event -->
<div class="modal fade" id="event_modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="d-inline-flex align-items-center">Booking Details<a href="javascript:void(0);" class="ms-2"></h4>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="border-bottom mb-3">
                    <div class="border rounded p-3 bg-light mb-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <span class="avatar flex-shrink-0 me-2">
                                        <img id="car_img" src="/backend/assets/img/car/car-01.jpg" alt="">
                                    </span>
                                    <div>
                                        <h6 id="car_title" class="fs-14 mb-1" aria-label="Car Title">
                                            <span id="car_title_text">Car Title</span>
                                        </h6>
                                        <p id="car_type"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div>
                                    <h6 class="fs-14 mb-1">Price</h6>
                                    <p class="fs-14 text-gray-9" id="car_price">$1200 <span class="text-gray-5 fw-normal">day/</span></p>
                                </div>
                            </div>
                            <div class="col-3">
                                <div>
                                    <h6 class="fs-14 mb-1">Status</h6>
                                    <span class="badge badge-soft-success d-inline-flex align-items-center badge-sm">
                                        <i class="ti ti-point-filled me-1"></i>Completed
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-bottom mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-medium fs-14">Start Date</h6>
                        <p id="start_date_time"></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-medium fs-14">End Date</h6>
                        <p id="end_date_time"></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-medium fs-14">Rental Period</h6>
                        <p id="rent_period"></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-medium fs-14">Driving Type</h6>
                        <p id="drive_type"></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-medium fs-14">Pickup Location</h6>
                        <p id="pickLan"></p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-medium fs-14">Return Location</h6>
                        <p id="retLan"></p>
                    </div>
                </div>
                <div class="border-bottom mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="mb-3">
                            <h6 class="d-inline-flex align-items-center fs-14 fw-medium ">Customer</h6>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-rounded flex-shrink-0 me-2">
                                <img src="/backend/assets/img/customer/customer-02.jpg" alt="">
                            </span>
                            <div>
                                <h6 class="fs-14 fw-medium mb-1" id="customer_name">Andrew Simons</h6>
                                <p id="customer_num">+1 56598 98956</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between driverInfo">
                        <div class="mb-3">
                            <h6 class="d-inline-flex align-items-center fs-14 fw-medium ">Driver</h6>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <span class="avatar avatar-rounded flex-shrink-0 me-2">
                                <img id="driver_img" src="/backend/assets/img/customer/customer-01.jpg" alt="">
                            </span>
                            <div>
                                <h6 class="fs-14 fw-medium mb-1" id="driver_name" aria-label="Driver Name">
                                    <span id="driver_name_text">Driver Name</span>
                                </h6>
                                <p id="driver_num"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <h6>Total Price</h6>
                    <h6 id="final_price" aria-label="Total Price Amount">
                        <span id="final_price_amount">Final Price</span>
                    </h6>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Event -->

<!-- Event -->
<div class="modal fade" id="add_booking">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="d-inline-flex align-items-center">Create Booking</h4>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <div class="wizard-form">
                <fieldset id="first-field">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="basicInfoForm" autocomplete="off">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="reservation-wizard mb-4">
                                            <ul class="d-flex align-items-center flex-wrap row-gap-2" id="progressbar">
                                                <li class="d-flex align-items-center active me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-calendar"></i></span>
                                                    <h6>Vehicle & Dates Info</h6>
                                                </li>
                                                <li class="d-flex align-items-center me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-user-check"></i></span>
                                                    <h6>Customer</h6>
                                                </li>
                                                <li class="d-flex align-items-center me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-float-center"></i></span>
                                                    <h6>Extra Services</h6>
                                                </li>
                                                <li class="d-flex align-items-center me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-file-invoice"></i></span>
                                                    <h6>Billing Details</h6>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card card-bg">
                                            <div class="card-body">
                                                <h4 class="d-flex align-items-center"><i class="ti ti-info-circle me-2 text-secondary fs-24"></i>Basic Info</h4>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mb-3">
                                                <h5 class="mb-1">Date & Time Of Travel</h5>
                                                <p>Add Information on Date of Travel</p>
                                            </div>
                                            <div class="border-bottom mb-3 pb-3">
                                                <div class="row gx-3">
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Tariff</label>
                                                            <select class="form-control select" name="tariff" id="tariff">
                                                                <option value="">Select</option>
                                                                @if ($priceTypes)
                                                                @foreach ($priceTypes as $priceType)
                                                                <option value="{{ $priceType->id }}">{{ $priceType->pricing_type }}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <span class="text-danger error-text" id="tariff_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Driving Type</label>
                                                            <select class="form-control select" name="driving_type" id="driving_type" data-placeholder="Select">
                                                                <option value="">Select</option>
                                                                @if ($drivingTypes)
                                                                @foreach ($drivingTypes as $drivingType)
                                                                <option value="{{ $drivingType->id }}">{{ $drivingType->name }}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <span class="error-text text-danger" id="driving_type_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">No of Passengers</label>
                                                            <input type="text" class="form-control" name="no_of_passengers" id="no_of_passengers">
                                                            <span class="text-danger error-text" id="no_of_passengers_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3">
                                                    <div class="col-xl-6">
                                                        <div class="row gx-3">
                                                            <div class="col-md-7">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Start Date <span class="text-danger"> *</span> </label>
                                                                    <div class="input-icon-end position-relative">
                                                                        <input type="text" class="form-control custom-datetimepicker" name="start_date" id="start_date" placeholder="dd/mm/yyyy">
                                                                        <span class="input-icon-addon">
                                                                            <i class="ti ti-calendar"></i>
                                                                        </span>
                                                                    </div>
                                                                    <span class="error-text text-danger" id="start_date_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Time <span class="text-danger"> *</span> </label>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="input-icon-end position-relative flex-fill">
                                                                            <input type="text" class="form-control custom-timepicker" name="start_time" id="start_time">
                                                                            <span class="input-icon-addon">
                                                                                <i class="ti ti-clock"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <span class="error-text text-danger" id="start_time_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                        <div class="row gx-3">
                                                            <div class="col-md-7">
                                                                <div class="mb-3">
                                                                    <label class="form-label">End Date <span class="text-danger"> *</span> </label>
                                                                    <div class="input-icon-end position-relative">
                                                                        <input type="text" class="form-control custom-datetimepicker" name="end_date" id="end_date" placeholder="dd/mm/yyyy">
                                                                        <span class="input-icon-addon">
                                                                            <i class="ti ti-calendar"></i>
                                                                        </span>
                                                                    </div>
                                                                    <span class="error-text text-danger" id="end_date_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Time <span class="text-danger"> *</span> </label>
                                                                    <div class="input-icon-end position-relative">
                                                                        <input type="text" class="form-control custom-timepicker" name="end_time" id="end_time">
                                                                        <span class="input-icon-addon">
                                                                            <i class="ti ti-clock"></i>
                                                                        </span>
                                                                    </div>
                                                                    <span class="error-text text-danger" id="end_time_error"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row gx-3">
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Pickup Location <span class="text-danger">*</span></label>
                                                            <select class="form-control select2" name="pickup_location" id="pickup_location" data-placeholder="Select">
                                                                <option value="">Select</option>
                                                                @if ($locations)
                                                                @foreach ($locations as $location)
                                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <span class="error-text text-danger" id="pickup_location_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Return Location <span class="text-danger">*</span></label>
                                                            <select class="form-control select2" name="return_location" id="return_location" data-placeholder="Select">
                                                                <option value="">Select</option>
                                                                @if ($locations)
                                                                @foreach ($locations as $location)
                                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                            <span class="error-text text-danger" id="return_location_error"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Security Deposit </label>
                                                            <input type="text" class="form-control" name="security_deposit" id="security_deposit">
                                                        </div>
                                                    </div>
                                                </div>
                                                <label class="d-flex align-items-center">
                                                    <input class="form-check-input m-0 me-2" type="checkbox" id="return_same_location">Return Same Location
                                                </label>
                                            </div>
                                            <div id="vehicle_list_main_container" style="display: none">
                                                <div class="row align-items-center">
                                                    <div class="col-lg-4">
                                                        <div class="mb-3">
                                                            <h5 class="mb-1">Select Vehicle</h5>
                                                            <p>Select Vehicle for your rental</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="d-flex align-items-center justify-content-end mb-3">
                                                            <div class="dropdown me-2">
                                                                <a href="#filtercollapse" class="filtercollapse coloumn d-inline-flex align-items-center" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="filtercollapse">
                                                                    <i class="ti ti-filter me-1"></i> Filter <span class="count text-center ms-2 fs-12">0</span>
                                                                </a>
                                                            </div>
                                                            <div class="top-search me-2">
                                                                <div class="top-search-group">
                                                                    <span class="input-icon">
                                                                        <i class="ti ti-search"></i>
                                                                    </span>
                                                                    <input type="text" class="form-control" name="overall_search" id="overall_search" placeholder="Search">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="collapse" id="filtercollapse">
                                                    <div class="filterbox mb-3 px-3">
                                                        <div class="row align-items-center">
                                                            <form id="filterForm">
                                                                <div class="col-lg-10">
                                                                    <div class=" d-flex align-items-center flex-wrap row-gap-3">
                                                                        <div class="dropdown me-2">
                                                                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                                                                Select Brand
                                                                            </a>
                                                                            <ul class="dropdown-menu dropdown-menu-lg p-2" id="brandList">
                                                                                <li>
                                                                                    <div class="top-search m-2">
                                                                                        <div class="top-search-group">
                                                                                            <span class="input-icon">
                                                                                                <i class="ti ti-search"></i>
                                                                                            </span>
                                                                                            <input type="text" class="form-control" id="brand_search" name="brand_search" placeholder="Search">
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="dropdown me-2">
                                                                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                                                                Select Type
                                                                            </a>
                                                                            <ul class="dropdown-menu dropdown-menu-lg p-2" id="typeList">
                                                                                <li>
                                                                                    <div class="top-search m-2">
                                                                                        <div class="top-search-group">
                                                                                            <span class="input-icon">
                                                                                                <i class="ti ti-search"></i>
                                                                                            </span>
                                                                                            <input type="text" class="form-control" name="type_search" id="type_search" placeholder="Search">
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="dropdown me-2">
                                                                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                                                                <i class="ti ti-badge me-1"></i>Select Model
                                                                            </a>
                                                                            <ul class="dropdown-menu dropdown-menu-lg p-2" id="modelList">
                                                                                <li>
                                                                                    <div class="top-search m-2">
                                                                                        <div class="top-search-group">
                                                                                            <span class="input-icon">
                                                                                                <i class="ti ti-search"></i>
                                                                                            </span>
                                                                                            <input type="text" class="form-control" name="model_search" id="model_search" placeholder="Search">
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="dropdown">
                                                                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                                                                <i class="ti ti-badge me-1"></i>Select Color
                                                                            </a>
                                                                            <ul class="dropdown-menu dropdown-menu-lg p-2" id="colorList">
                                                                                <li>
                                                                                    <div class="top-search m-2">
                                                                                        <div class="top-search-group">
                                                                                            <span class="input-icon">
                                                                                                <i class="ti ti-search"></i>
                                                                                            </span>
                                                                                            <input type="text" class="form-control" name="color_search" id="color_search" placeholder="Search">
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="d-flex align-items-center justify-content-end">
                                                                        <a href="javascript:void(0);" class="me-3 text-purple links" id="apply_filter">Apply</a>
                                                                        <a href="javascript:void(0);" class="text-danger links" id="reset_filter">Clear</a>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="list-loader">
                                                    <div class="skeleton card-sm-skeleton card-loader mb-2"></div>
                                                    <div class="skeleton card-sm-skeleton card-loader mb-2"></div>
                                                    <div class="skeleton card-sm-skeleton card-loader mb-2"></div>
                                                </div>
                                                <div id="vehicle_list_container" class="car-select d-none">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer px-0 pb-0">
                                            <div class="d-flex align-items-center justify-content-end flex-wrap row-gap-3">
                                                <div class="field-btns">
                                                    <button class="btn btn-light me-2" type="button"><i class="ti ti-chevron-left me-1"></i>Cancel</button>
                                                </div>
                                                <div class="field-btns">
                                                    <button class="btn btn-primary" id="basic_info_btn" type="button">Add Customer<i class="ti ti-chevron-right ms-1"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                       
                    </div>
                </fieldset>
                <fieldset id="second-field">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="customerForm">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="reservation-wizard mb-4">
                                            <ul class="d-flex align-items-center flex-wrap row-gap-2" id="progressbar">
                                                <li class="d-flex align-items-center activated me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-calendar"></i></span>
                                                    <span class="active-check me-2"><i class="ti ti-check"></i></span>
                                                    <h6>Car & Dates Info</h6>
                                                </li>
                                                <li class="d-flex align-items-center active me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-user-check"></i></span>
                                                    <h6>Customer</h6>
                                                </li>
                                                <li class="d-flex align-items-center me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-float-center"></i></span>
                                                    <h6>Extra Services</h6>
                                                </li>
                                                <li class="d-flex align-items-center me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-file-invoice"></i></span>
                                                    <h6>Billing Details</h6>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card card-bg">
                                            <div class="card-body">
                                                <h4 class="d-flex align-items-center"><i class="ti ti-user-check me-2 text-secondary fs-24"></i>Customer</h4>
                                            </div>
                                        </div>
                                        <div class="border-bottom mb-3">
                                            <div class="mb-3">
                                                <h6 class="mb-1">Select Customer </h6>
                                                <p>Add Information of Customer</p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Customer <span class="text-danger">*</span></label>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-fill ">
                                                        <select class="select2" name="customer_id" id="customer_id" data-placeholder="Select">
                                                            <option value="">Select</option>
                                                            @if ($customers)
                                                            @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}" data-image="{{ $customer->profile_image }}" data-phone_number="{{ $customer->phone_number }}" data-="">{{ $customer->full_name }}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                        <span class="error-text text-danger" id="customer_id_error"></span>
                                                    </div>
                                                    <div class="ms-4">
                                                        <a href="javascript:void(0);" class="btn btn-dark d-inline-flex align-items-center">
                                                            <i class="ti ti-plus me-1"></i>Add New
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="customer_details_list">

                                            </div>
                                        </div>
                                        <div>
                                            <div class="mb-3">
                                                <h6 class="mb-1">Select Driver </h6>
                                                <p>Add Information of Driver</p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Driver <span class="text-danger">*</span></label>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-fill ">
                                                        <select class="select2" name="driver_id" id="driver_id" data-placeholder="Select">
                                                            <option value="">Select</option>
                                                        </select>
                                                        <span class="error-text text-danger" id="driver_id_error"></span>
                                                    </div>
                                                    <div class="ms-4">
                                                        <a href="javascript:void(0);" class="btn btn-dark d-inline-flex align-items-center">
                                                            <i class="ti ti-plus me-1"></i>Add New
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="driver_details_list">

                                            </div>
                                        </div>
                                        <div class="card-footer px-0 pb-0">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <div class="field-btns">
                                                    <button class="btn btn-light me-2" id="customer_prev_btn" type="button"><i class="ti ti-chevron-left me-1"></i>Back</button>
                                                </div>
                                                <div class="field-btns">
                                                    <button class="btn btn-primary" id="customer_next_btn" type="button">Add Extra Services<i class="ti ti-chevron-right ms-1"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </fieldset>
                <fieldset id="third-field">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="extraServiceForm">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="reservation-wizard mb-4">
                                            <ul class="d-flex align-items-center flex-wrap row-gap-2" id="progressbar">
                                                <li class="d-flex align-items-center activated me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-calendar"></i></span>
                                                    <span class="active-check me-2"><i class="ti ti-check"></i></span>
                                                    <h6>Car & Dates Info</h6>
                                                </li>
                                                <li class="d-flex align-items-center activated  me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-user-check"></i></span>
                                                    <span class="active-check me-2"><i class="ti ti-check"></i></span>
                                                    <h6>Customer</h6>
                                                </li>
                                                <li class="d-flex align-items-center active me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-float-center"></i></span>
                                                    <h6>Extra Services</h6>
                                                </li>
                                                <li class="d-flex align-items-center me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-file-invoice"></i></span>
                                                    <h6>Billing Details</h6>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card card-bg">
                                            <div class="card-body">
                                                <h4 class="d-flex align-items-center"><i class="ti ti-float-center me-2 text-secondary fs-24"></i>Extra Service</h4>
                                            </div>
                                        </div>
                                        <div class="border-bottom mb-3">
                                            <div class="mb-3">
                                                <h6 class="mb-1">Select Extra Services</h6>
                                                <p>Add extra services for your rental</p>
                                            </div>
                                        </div>
                                        <div class="row" id="extra_service_list_container">

                                        </div>
                                        <div class="card-footer px-0 pb-0">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <div class="field-btns">
                                                    <button class="btn btn-light me-2" id="extra_service_prev_btn" type="button"><i class="ti ti-chevron-left me-1"></i>Back</button>
                                                </div>
                                                <div class="field-btns">
                                                    <button class="btn btn-primary" id="extra_service_next_btn" type="button">Proceed to Billing<i class="ti ti-chevron-right ms-1"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </fieldset>
                <fieldset id="fourth-field">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="billingForm">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="reservation-wizard mb-4">
                                            <ul class="d-flex align-items-center flex-wrap row-gap-2" id="progressbar">
                                                <li class="d-flex align-items-center activated me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-calendar"></i></span>
                                                    <span class="active-check me-2"><i class="ti ti-check"></i></span>
                                                    <h6>Car & Dates Info</h6>
                                                </li>
                                                <li class="d-flex align-items-center activated  me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-user-check"></i></span>
                                                    <span class="active-check me-2"><i class="ti ti-check"></i></span>
                                                    <h6>Customer</h6>
                                                </li>
                                                <li class="d-flex align-items-center activated me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-float-center"></i></span>
                                                    <span class="active-check me-2"><i class="ti ti-check"></i></span>
                                                    <h6>Extra Services</h6>
                                                </li>
                                                <li class="d-flex align-items-center active me-2">
                                                    <span class="me-2 wizard-icon"><i class="ti ti-file-invoice"></i></span>
                                                    <h6>Billing Details</h6>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card card-bg">
                                            <div class="card-body">
                                                <h4 class="d-flex align-items-center"><i class="ti ti-file-invoice me-2 text-secondary fs-24"></i>Billing Details</h4>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div>
                                                    <h6 class="mb-1">Insurance</h6>
                                                    <p>Add Insurance of Your Ride</p>
                                                </div>
                                            </div>
                                            <div class="row" id="insurance_list_container">

                                            </div>
                                        </div>
                                        <div class="card-footer px-0 pb-0">
                                            <div class="d-flex align-items-center justify-content-end">
                                                <div class="field-btns">
                                                    <button class="btn btn-light me-2" id="billing_prev_btn" type="button"><i class="ti ti-chevron-left me-1"></i>Back</button>
                                                </div>
                                                <div class="field-btns">
                                                    <button class="btn btn-primary" id="reservation_complete_btn" type="button">Finish & Save<i class="ti ti-chevron-right ms-1"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
<!-- /Event -->

<!-- Edit Pricing -->
<div class="modal fade addmodal" id="edit_price_modal">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title mb-0">Edit Pricing</h4>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x fs-16"></i>
                </button>
            </div>
            <form id="driverPriceForm">
                <div class="modal-body pb-1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Drivers <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center mt-2">
                                    <a href="#" class="avatar avatar-sm avatar-rounded me-2 flex-shrink-0"><img src="/backend/assets/img/default-profile.png" class="edit_driver_img" alt=""></a>
                                    <div>
                                        <a class="d-block fw-semibold edit_driver_name" href="#">Reuben Keen</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pricing <span class="text-danger">*</span></label>
                                <input type="text" name="driver_price" id="driver_price" value="0" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
                        <button type="submit" class="btn btn-primary driver_price_btn">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Edit Pricing -->
@endsection

@push('scripts')
<!-- Fullcalendar JS -->
<script src="{{ asset('backend/assets/plugins/fullcalendar/index.global.min.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/fullcalendar/calendar-data.js') }}"></script>
<script src="{{ asset('backend/assets/js/admin/calender.js') }}"></script>
@endpush

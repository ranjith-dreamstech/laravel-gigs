@extends('admin.admin')

@section('meta_title', __('admin.common.dashboard') . ' || ' . $companyName)

@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content pb-0">
            <!-- Breadcrumb -->
            <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
                <div class="my-auto mb-2">
                    <h4 class="mb-1">{{ __('admin.common.dashboard') }}</h4>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">{{ __('admin.common.home') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('admin.common.dashboard') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- /Breadcrumb -->
            <div class="row">
                <div class="col-xl-12 d-flex flex-column">
                    <div class="row">
                        <!-- Orders avtive -->
                        <div class="col-md-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body pb-1">
                                    <div class="border-bottom mb-0 pb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm bg-secondary-100 text-secondary me-2">
                                                <i class="ti ti-new-section fs-14"></i>
                                            </span>
                                            <p>{{ __('admin.main.active') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="py-2">
                                            <h5 class="mb-1">{{ $ordersCount['active'] ?? 0 }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Orders avtive -->

                        <!-- Pending -->
                        <div class="col-md-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body pb-1">
                                    <div class="border-bottom mb-0 pb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm bg-orange-100 text-orange me-2">
                                                <i class="ti ti-checks fs-14"></i>
                                            </span>
                                            <p>{{ __('admin.main.pending') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="py-2">
                                            <h5 class="mb-1">{{ $ordersCount['pending'] ?? 0 }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Pending -->

                        <!-- Completed -->
                        <div class="col-md-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body pb-1">
                                    <div class="border-bottom mb-0 pb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm bg-violet-100 text-violet me-2">
                                                <i class="ti ti-heart fs-14"></i>
                                            </span>
                                            <p>{{ __('admin.main.completed') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="py-2">
                                            <h5 class="mb-1">{{ $ordersCount['completed'] ?? 0 }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Completed -->

                        <!-- Total Reviews -->
                        <div class="col-md-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body pb-1">
                                    <div class="border-bottom mb-0 pb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm bg-violet-100 text-violet me-2">
                                                <i class="ti ti-star fs-14"></i>
                                            </span>
                                            <p>{{ __('admin.main.total_reviews') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="py-2">
                                            <h5 class="mb-1">{{ $totalReviews ?? 0 }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Total Reviews -->
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Income -->
                <div class="col-xl-8 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body pb-0">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-3">
                                <h5 class="mb-1">{{ __('admin.main.income') }}</h5>
                                <div class="chart-icon d-flex align-items-center gap-4 mb-1">
                                    <p class="mb-0 d-flex align-items-center"><span class="chart-color bg-primary me-1"></span>{{ __('admin.main.income') }}</p>
                                </div>
                                <div class="input-icon-start position-relative year">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-calendar-up"></i>
                                    </span>
                                    <input type="text" class="form-control income-year-picker" name="year" id="sales_year" value="{{ date('Y') }}">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-chevron-down"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center flex-wrap gap-4 mb-3">
                                <div class="border br-5 p-2">
                                    <p class="mb-1">{{ __('admin.main.income_from_year') }} <span>[{{ date('Y') }}]</span></p>
                                    <h5 class="total-income" aria-label="{{ __('admin.main.total_income') }}">
                                        <span class="visually-hidden">{{ __('admin.main.total_income') }}:</span>
                                    </h5>
                                </div>
                            </div>
                            <div id="income-sales-statistics"></div>
                        </div>
                    </div>
                </div>
                <!-- /Income -->

                <!-- Top Buyers -->
                <div class="col-xl-4 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body pb-1">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                <h5>{{ __('admin.main.top_buyers') }}</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table custom-table1">
                                    @if ($topBuyers->isNotEmpty())
                                        @foreach ($topBuyers as $buyer)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar flex-shrink-0">
                                                            <img src="{{ $buyer->user->profile_image }}" class="rounded-circle" alt="profile">
                                                        </div>
                                                        <div class="flex-grow-1 ms-2">
                                                            <h6 class="fs-14 fw-semibold mb-1 text-black">{{ $buyer->user->name ?? ''}}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <h6 class="fs-14 fw-semibold">{{ $buyer->final_price }}</h6>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center">{{ __('admin.main.no_buyers_found') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Top Buyers -->
            </div>
            <div class="row">
                <div class="col-xl-12 d-flex flex-column">
                    <div class="row">
                        <!-- Orders avtive -->
                        <div class="col-md-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body pb-1">
                                    <div class="border-bottom mb-0 pb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm bg-secondary-100 text-secondary me-2">
                                                <i class="ti ti-news fs-14"></i>
                                            </span>
                                            <p>{{ __('admin.main.orders_active') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="py-2">
                                            <h5 class="mb-1">{{ $ordersAmount['active'] ?? formatPrice(0) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Orders avtive -->

                        <!-- Pending -->
                        <div class="col-md-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body pb-1">
                                    <div class="border-bottom mb-0 pb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm bg-orange-100 text-orange me-2">
                                                <i class="ti ti-shopping-cart-bolt fs-14"></i>
                                            </span>
                                            <p>{{ __('admin.main.orders_pending') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="py-2">
                                            <h5 class="mb-1">{{ $ordersAmount['pending'] ?? formatPrice(0) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Pending -->

                        <!-- Completed -->
                        <div class="col-md-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body pb-1">
                                    <div class="border-bottom mb-0 pb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm bg-violet-100 text-violet me-2">
                                                <i class="ti ti-hexagon fs-14"></i>
                                            </span>
                                            <p>{{ __('admin.main.orders_completed') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="py-2">
                                            <h5 class="mb-1">{{ $ordersAmount['completed'] ?? formatPrice(0) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Completed -->

                        <!-- Orders Cancelled -->
                        <div class="col-md-3 d-flex">
                            <div class="card flex-fill">
                                <div class="card-body pb-1">
                                    <div class="border-bottom mb-0 pb-2">
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-sm bg-violet-100 text-violet me-2">
                                                <i class="ti ti-status-change fs-14"></i>
                                            </span>
                                            <p>{{ __('admin.main.orders_cancelled') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div class="py-2">
                                            <h5 class="mb-1">{{ $ordersAmount['cancelled'] ?? formatPrice(0) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Orders Cancelled -->
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Recent orders -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body pb-1">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                <h5>{{ __('admin.main.recent_orders') }}</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table custom-table1">
                                    @if ($recentOrders->isNotEmpty())
                                        @foreach ($recentOrders as $order)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($order->gig && $order->gig->imageMeta)
                                                            <div class="avatar avatar-lg flex-shrink-0">
                                                                <img src="{{ $order->gig->imageMeta->gigs_image_url }}" class="rounded-circle" alt="profile">
                                                            </div>
                                                        @else
                                                            <div class="avatar flex-shrink-0">
                                                                <img src="{{ uploadedAsset('', 'default') }}" class="rounded-circle" alt="default">
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1 ms-2">
                                                            <h6 class="fs-15 fw-semibold mb-1 text-black">{{  Str::limit($order->gig->title ?? '', 60)}}</h6>
                                                            <ul>
                                                                <li class="fs-13 text-default d-flex">
                                                                    <span class="fw-bold me-2">{{ __('admin.main.delivery_date') }} : </span>{{  $order->delivery_date ?? ''}}
                                                                </li>
                                                                <div class="d-flex align-items-center">
                                                                    <li class="fs-13 text-default me-2">
                                                                        <span class="fw-bold">{{ __('admin.main.buyer') }} : </span>{{  $order->user->name ?? ''}}
                                                                    </li>
                                                                    <li class="fs-13 text-default">
                                                                        <span class="fw-bold">{{ __('admin.main.seller') }} : </span>{{  $order->seller->name ?? ''}}
                                                                    </li>
                                                                </div>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge badge-pink-transparent">{{ $order->booking_status_text }}</span>
                                                </td>
                                                <td class="text-end amount-info">
                                                    <h6 class="mb-0">{{ $order->final_price }}</h6>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center">{{ __('admin.main.no_orders_found') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Recent Orders -->

                <!-- Recent Notifications -->
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body pb-1">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                <h5>{{ __('admin.main.recent_notifications') }}</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="table custom-table1">
                                    @if ($recentNotifications->isNotEmpty())
                                        @foreach ($recentNotifications as $notification)
                                            <tr>
                                                <td>
                                                    <div class="recent-payment">
                                                        <h6 class="fs-15 fw-semibold mb-1 text-black">{{ Str::limit($notification->content ?? '', 100) }}</h6>
                                                        <p>{{ $notification->created_date }}</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center">{{ __('admin.main.no_notifications_found') }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Recent Notifications -->
            </div>
        </div>
        @include('admin.partials.footer')
    </div>
    <!-- /Page Wrapper -->
@endsection

@push('plugins')
<!-- Apexchart JS -->
<script src="{{ asset('backend/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/apexchart/chart-data.js') }}"></script>
@endpush

@push('scripts')
<script src="{{ asset('backend/assets/js/admin/dashboard.js') }}"></script>
@endpush

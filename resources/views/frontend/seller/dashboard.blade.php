@extends('frontend.seller.partials.app')
@section('content')

<!-- Page Content -->
<div class="page-wrapper">
    <div class="page-content content pb-0 bg-light">
        <div class="main-title mb-4">
            <h4>{{ __('web.user.dashboard') }}</h4>
        </div>

        <!-- status -->
        <div class="row status-info">
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-start">
                            <div class="avatar avatar-md bg-muted rounded-circle me-2 d-flex align-item-center justify-content-center text-center avatar-info">
                                <i class="ti ti-new-section text-white d-flex align-items-center justify-content-center"></i>
                            </div>
                            <div>
                                <p class="mb-1">{{ __('web.user.orders_active') }}</p>
                                <h6 class="mb-0">{{ $ordersAmount['active'] ?? 0 }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-start">
                            <div class="avatar avatar-md bg-warning rounded-circle me-2 d-flex align-item-center justify-content-center text-center avatar-info">
                                <i class="ti ti-checks text-white d-flex align-items-center justify-content-center"></i>
                            </div>
                            <div>
                                <p class="mb-1">{{ __('web.common.pending') }}</p>
                                <h6 class="mb-0">{{ $ordersAmount['pending'] ?? 0 }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-start">
                            <div class="avatar avatar-md bg-info rounded-circle me-2 d-flex align-item-center justify-content-center text-center avatar-info">
                                <i class="ti ti-heart text-white d-flex align-items-center justify-content-center"></i>
                            </div>
                            <div>
                                <p class="mb-1">{{ __('web.common.completed') }}</p>
                                <h6 class="mb-0">{{ $ordersAmount['completed'] ?? 0 }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-start">
                            <div class="avatar avatar-md bg-pink rounded-circle me-2 d-flex align-item-center justify-content-center text-center avatar-info">
                                <i class="ti ti-star text-white d-flex align-items-center justify-content-center"></i>
                            </div>
                            <div>
                                <p class="mb-1">{{ __('web.common.cancelled') }}</p>
                                <h6 class="mb-0">{{ $ordersAmount['cancelled'] ?? 0 }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- status -->

        <!-- Overview -->
        <div class="row">
            <div class="col-xl-3 col-md-6 d-flex">
                <div class="dash-widget flex-fill w-100">
                    <div class="d-flex align-items-center mb-3">
                        <span class="dash-icon bg-success flex-shrink-0">
                            <i class="ti ti-credit-card"></i>
                        </span>
                        <div>
                            <p class="mb-1">{{ __('web.user.total_credit') }}</p>
                            <h5 class="mb-0">{{ $wallet['total_credit'] ?? '' }}</h5>
                        </div>
                    </div>
                    <div class="bg-light p-3 rounded-2">
                        <span class="{{ $wallet['credit_percentage'] >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $wallet['credit_percentage'] >= 0 ? '+' : '' }}{{ $wallet['credit_percentage'] }}%
                        </span>
                        {{ __('web.user.from_last_week') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 d-flex">
                <div class="dash-widget flex-fill w-100">
                    <div class="d-flex align-items-center mb-3">
                        <span class="dash-icon bg-danger flex-shrink-0">
                            <i class="ti ti-report-money"></i>
                        </span>
                        <div>
                            <p class="mb-1">{{ __('web.user.total_debit') }}</p>
                            <h5 class="mb-0">{{ $wallet['total_debit'] ?? '' }}</h5>
                        </div>
                    </div>
                    <div class="bg-light p-3 rounded-2">
                        <p>
                            <span class="{{ $wallet['debit_percentage'] >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $wallet['debit_percentage'] >= 0 ? '+' : '' }}{{ $wallet['debit_percentage'] }}%
                            </span>
                            {{ __('web.user.from_last_week') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-12 d-flex">
                <div class="dash-earning flex-fill w-100">
                    <div class="d-flex align-items-center gap-4">
                        <div class="earning-info">
                            <p class="mb-1">{{ __('web.user.earnings') }}</p>
                            <h5>{{ $earnings }}</h5>
                        </div>
                        <div class="earning-info">
                            <p class="mb-1">{{ __('web.user.wallet_balance') }}</p>
                            <h5>{{ $wallet['total_balance'] ?? '' }}</h5>
                        </div>
                    </div>
                    <div class="earning-btn text-end">
                        <a href="{{ route('user.wallet') }}" class="btn btn-primary btn-md" ><i class="ti ti-shopping-cart me-1"></i>{{ __('web.user.wallet') }}</a>
                    </div>
                    <a href="javascript:void(0);" class="withdraw-link" data-bs-toggle="modal" data-bs-target="#withdraw">{{ __('web.user.withdraw_funds') }}</a>
                </div>
            </div>
        </div>
        <!-- /Overview -->

        <!-- status -->
        <div class="row status-info">
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1">{{ __('web.user.orders_active') }}</p>
                                <h6 class="mb-0">{{ $ordersCount['active'] ?? 0 }}</h6>
                            </div>
                            <span class="bg-success-transparent status-icon"><i class="ti ti-news text-success"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1">{{ __('web.user.orders_pending') }}</p>
                                <h6 class="mb-1">{{ $ordersCount['pending'] ?? 0 }}</h6>
                            </div>
                            <span class="bg-warning-transparent status-icon"><i class="ti ti-shopping-cart-bolt text-warning"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1">{{ __('web.user.orders_completed') }}</p>
                                <h6 class="mb-1">{{ $ordersCount['completed'] ?? 0 }}</h6>
                            </div>
                            <span class="bg-info-transparent status-icon"><i class="ti ti-hexagon text-info"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1">{{ __('web.user.orders_cancelled') }}</p>
                                <h6 class="mb-1">{{ $ordersCount['cancelled'] ?? 0 }}</h6>
                            </div>
                            <span class="bg-error-transparent status-icon"><i class="ti ti-status-change text-error"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- status -->

        <!-- order -->
        <div class="row">
            <div class="col-xl-8 col-md-6 d-flex">
                <div class="card dashboard-card flex-fill w-100">
                    <div class="card-header">
                        <div class="gig-card-head">
                            <h5 class="mb-0">{{ __('web.user.recent_orders') }}</h5>
                        </div>
                        <a href="{{ route('seller.order-index') }}" class="view-link mb-0">{{ __('web.common.view_all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive card-table">
                            <table class="table">
                                <tbody>
                                    @if ($recentOrders->count() > 0)
                                        @foreach ($recentOrders as $order)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="table-img">
                                                        @if ($order->gig && $order->gig->imageMeta)
                                                            <a href="javascript:void(0);"><img src="{{ $order->gig->imageMeta->gigs_image_url }}" class="img-fluid rounded-pill" alt="img"></a>
                                                        @else
                                                            <a href="javascript:void(0);"><img src="{{ uploadedAsset(null, 'default') }}" class="img-fluid rounded-pill" alt="img"></a>
                                                        @endif
                                                    </div>
                                                    <div class="recent-payment">
                                                        <h6><a href="javascript:void(0);">{{ $order->gig->title }}</a></h6>
                                                        <ul>
                                                            <li>{{ __('web.user.delivery_date') }}: {{ $order->delivery_date }}</li>
                                                            <li>{{ __('web.user.buyer') }} : <span class="text-dark">{{ $order->user->name }}</span></li>
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
                                            <td class="text-center">{{ __('web.user.no_orders_found') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 d-flex">
                <div class="card files-card flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="gig-card-head">
                            <h5 class="mb-0">{{ __('web.user.files') }}</h5>
                        </div>
                        <a href="{{ route('seller.file-index') }}" class="view-link mb-0">{{ __('web.common.view_all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive card-table">
                            <table class="table">
                                <tbody>
                                    @if ($recentFiles->count() > 0)
                                        @foreach ($recentFiles as $file)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="files-icon flex-shrink-0">
                                                            @if ($file->file_data['extension'] == 'pdf')                                 
                                                                <i class="ti ti-pdf"></i>
                                                            @else
                                                                <i class="ti ti-photo"></i>
                                                            @endif
                                                        </span>
                                                        <div>
                                                            <h6 class="mb-1">{{ Str::limit($file->file_data['file_name'] ?? '', 15) }}</h6>
                                                            <p>{{ __('web.user.update_on') }}: {{ $file->updated_date }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="action-item text-end">
                                                    <a href="javascript:void(0);" class="me-2 btn btn-sm"><i class="ti ti-info-circle"></i></a>
                                                    <a href="{{ $file->data }}" download class="btn btn-sm"><i class="ti ti-download"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center">{{ __('web.user.no_files_found') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- order -->

        <div class="row">
            <!-- Sales Statistics -->
            <div class="col-lg-12">
                <div class="card dashboard-card">
                    <div class="card-header">
                        <div class="gig-card-head">
                            <h5 class="card-title mb-0">{{ __('web.user.sales_statistics') }}</h5>
                        </div>
                        <div class="d-flex align-item-center gap-3">
                            <ul class="revenue-tab nav nav-tabs m-0 border-0">
                                <li class="nav-item" role="presentation">
                                    <a href="javascript:void(0);" class="nav-link active" data-bs-toggle="tab" data-bs-target="#gigs_Information" aria-selected="false" role="tab" tabindex="-1">
                                        {{ __('web.common.gigs') }}
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="javascript:void(0);" class="nav-link" data-bs-toggle="tab" data-bs-target="#pay_Information" aria-selected="false" role="tab" tabindex="-1">
                                        {{ __('web.user.payments') }}
                                    </a>
                                </li>
                            </ul>
                            <div class="input-icon-start mb-0 position-relative year">
                                <span class="input-icon-addon"><i class="ti ti-calendar-up"></i></span>
                                <input type="text" class="form-control yearpicker" name="year" id="sales_year" value="{{ date('Y') }}" placeholder="{{ date('Y') }}">
                                <i class="ti ti-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="gigs_Information" role="tabpanel">
                            <div class="card-body pb-0">
                                <div id="gigs_chart"></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pay_Information" role="tabpanel">
                            <div class="card-body pb-0">
                                <div class="d-flex align-items-center justify-content-between gap-4 flex-wrap mb-4">
                                    <div class="d-flex align-items-center gap-4">
                                        <div>
                                            <p class="mb-1">{{ __('web.user.revenue') }}</p>
                                            <h5 class="mb-0" id="revenue_amount" aria-label="{{ __('web.user.revenue') }}">{{ __('web.user.revenue') }}</h5>
                                        </div>
                                        <div>
                                            <p class="mb-1">{{ __('web.user.withdrawn') }}</p>
                                            <h5 class="mb-0" id="withdrawn_amount" aria-label="{{ __('web.user.withdrawn') }}">{{ __('web.user.withdrawn') }}</h5>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="revenue me-2 mb-0"><i class="ti ti-point-filled"></i>{{ __('web.user.revenue') }}</p>
                                        <p class="mb-0"><i class="ti ti-point-filled"></i>{{ __('web.user.withdrawn') }}</p>
                                    </div>
                                </div>
                                <div id="custom-s-col" class="chart-set"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Sales Statistics -->

        </div>

        <div class="row">
            <div class="col-xl-8 col-md-6 d-flex">
                <div class="card recent-payment-card flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="gig-card-head">
                            <h5 class="mb-0">{{ __('web.user.recent_payments') }}</h5>
                        </div>
                        <a href="javascript:void(0);" class="view-link mb-0">{{ __('web.common.view_all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive card-table">
                            <table class="table">
                                <tbody>
                                    @if (count($recentPayments) > 0)
                                        @foreach ($recentPayments as $payment)
                                        <tr>
                                            <td>
                                                <div class="recent-payment">
                                                    <h6><a href="javascript:void(0);">{{ $payment->gig->title }}</a></h6>
                                                    <p>{{ __('web.user.id') }} : {{ $payment->order_id }} <span>|</span>{{ __('web.user.order_date') }}: {{ $payment->booking_date }}</p>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                @if ($payment->payment_status == 2)
                                                <span class="badge badge-success-transparent"><i class="ti ti-point-filled me-1"></i>{{ $payment->payment_status_text }}</span>
                                                @else
                                                <span class="badge badge-danger-transparent"><i class="ti ti-point-filled me-1"></i>{{ $payment->payment_status_text }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end amount-info w-25">
                                                <h6 class="mb-0">+{{ $payment->final_price }}</h6>
                                                <a href="javascript:void(0);" class="btn btn-primary btn-lg py-1 transaction_details" data-bs-toggle="modal" data-bs-target="#transaction_details"
                                                    data-transaction_id="{{ $payment->transaction_id }}"
                                                    data-currency="{{ $payment->currency ?? '$' }}"
                                                    data-final_price="{{ $payment->final_price }}"
                                                    data-payment_method="{{ $payment->payment_type }}"
                                                    data-sender="{{ $payment->user->name ?? '-'}}"
                                                    data-receiver="{{ $payment->seller->name ?? '-'}}"
                                                    data-payment_status = "{{ $payment->payment_status }}"
                                                    data-payment_status_text="{{ $payment->payment_status_text }}"
                                                    >{{ __('web.common.view') }}</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center">{{ __('web.user.no_payments_found') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 d-flex">
                <div class="card recent-payment-card flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="gig-card-head">
                            <h5 class="mb-0">{{ __('web.user.recent_notifications') }}</h5>
                        </div>
                        <a href="{{ route('seller.notifications') }}" class="view-link mb-0">{{ __('web.common.view_all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive card-table">
                            <table class="table">
                                <tbody>
                                    @if (count($recentNotifications) > 0)
                                        @foreach ($recentNotifications as $notification)
                                        <tr>
                                            <td>
                                                <div class="recent-payment">
                                                    <h6><a href="javascript:void(0);">{{ $notification->subject }}</a></h6>
                                                    <p>{{ $notification->created_date }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center">{{ __('web.user.no_notifications_found') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Page Content -->

<!-- Withdraw -->
<div class="modal new-modal fade" id="withdraw" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('web.user.withdraw_payment') }}</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <form id="buyerWithdraw">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="amt-wrap">
                                <div class="form-wrap">
                                    <label class="form-label" for="amount">
                                        {{ __('web.user.amount')}} ({{ getDefaultCurrencySymbol()}})<span class="text-danger ms-1">*</span>
                                    </label>
                                    <input type="number" id="amount" name="amount" class="form-control" min="0.01" step="0.01" />
                                    <span class="text-danger error-amount"></span>
                                </div>
                                <ul class="amt-list">
                                    <li>{{ __('web.user.or') }}</li>
                                    <li><a href="javascript:void(0);" class="vary-amt" data-value="50">{{ formatPrice(50) }}</a></li>
                                    <li><a href="javascript:void(0);" class="vary-amt" data-value="100">{{ formatPrice(100) }}</a></li>
                                    <li><a href="javascript:void(0);" class="vary-amt" data-value="150">{{ formatPrice(150) }}</a></li>
                                </ul>
                            </div>

                            <div class="buyer-method">
                                <h6>{{ __('web.user.select_payment_gateway')}} <span class="text-danger">*</span></h6>
                                <label class="custom_radio">
                                    <input type="radio" name="payment" value="paypal" />
                                    <span class="checkmark"></span> {{ __('web.user.paypal') }}
                                </label>
                                <label class="custom_radio">
                                    <input type="radio" name="payment" value="stripe" />
                                    <span class="checkmark"></span> {{ __('web.user.stripe') }}
                                </label>
                                <span class="text-danger error-payment"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100">{{ __('web.user.withdraw') }}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- /Withdraw -->

<!-- Transaction details  -->
<div class="modal new-modal fade" id="transaction_details" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-item-center">
                    <h5 class="modal-title me-2">{{ __('web.user.transaction_details')}} </h5>
                    <span class="badge badge-success-transparent" id="paid_badge">-</span>
                    <span class="badge badge-danger-transparent d-none" id="unpaid_badge">-</span>
                </div>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="summary-info">
                            <h6 class="mb-1">{{ __('web.user.transaction_id') }}</h6>
                            <p id="transaction_id">-</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-info">
                            <h6 class="mb-1">{{ __('web.user.payment_method') }}</h6>
                            <p id="payment_method">-</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-info">
                            <h6 class="mb-1">{{ __('web.user.amount') }}</h6>
                            <p id="transaction_amount"> - </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-info">
                            <h6 class="mb-1">{{ __('web.user.currency') }}</h6>
                            <p id="currency"> - </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-info mb-0">
                            <h6 class="mb-1">{{ __('web.user.sender') }}</h6>
                            <p id="sender"> - </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="summary-info mb-0">
                            <h6 class="mb-1">{{ __('web.user.receiver') }}</h6>
                            <p id="receiver"> - </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Transaction details -->

@endsection

@push('plugins')
<!-- Apexchart JS -->
<script src="{{ asset('frontend/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ asset('frontend/assets/plugins/apexchart/chart-data.js') }}"></script>
@endpush

@push('scripts')
<script src="{{ asset('frontend/custom/js/seller/dashboard.js') }}"></script>
@endpush

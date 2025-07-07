@extends('frontend.buyer.partials.app')
@push('styles')
<!-- Datatable CSS -->
@endpush
@section('content')

         <!-- Page Content -->
         <div class="page-wrapper">
            <div class="page-content content bg-light">
                <div class="main-title mb-4">
                    <h4>{{ __('web.user.transactions') }}</h4>
                </div>

                <!-- Status -->
                <div class="row status-info">
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-start mb-2">
                                    <div class="avatar avatar-md bg-success rounded-circle me-2 d-flex align-items-center justify-content-center text-center">
                                        <i class="ti ti-arrows-exchange text-white d-flex align-items-center justify-content-center"></i>
                                    </div>
                                    <div>
                                        <div class="skeleton label-skeleton label-loader mb-1"></div>
                                        <p class="mb-1 d-none real-label">{{ __('web.user.total_transactions') }}</p>
                                        <div class="skeleton label-skeleton label-loader"></div>
                                        <h5 class="mb-1 d-none real-label total-transactions" aria-label="{{ __('web.user.total_transactions') }}">Total Transaction</h5>
                                    </div>
                                </div>
                                <div class="bg-light p-2 d-none">
                                    <div class="skeleton label-skeleton label-loader w-50"></div>
                                    <span class="text-success d-none real-label">
                                        <i class="ti ti-arrow-up-right me-1"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Repeat the same structure for the other 3 cards -->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-start mb-2">
                                    <div class="avatar avatar-md bg-warning rounded-circle me-2 d-flex align-items-center justify-content-center text-center">
                                        <i class="ti ti-arrow-up text-white d-flex align-items-center justify-content-center"></i>
                                    </div>
                                    <div>
                                        <div class="skeleton label-skeleton label-loader mb-1"></div>
                                        <p class="mb-1 d-none real-label">{{ __('web.user.total_credits') }}</p>
                                        <div class="skeleton label-skeleton label-loader"></div>
                                        <h5 class="mb-1 d-none real-label total_credit" aria-label="{{ __('web.user.total_credits') }}">Total Credits</h5>
                                    </div>
                                </div>
                                <div class="bg-light p-2 d-none">
                                    <div class="skeleton label-skeleton label-loader w-50"></div>
                                    <span class="text-success d-none real-label">
                                        <i class="ti ti-arrow-up-right me-1"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-start mb-2">
                                    <div class="avatar avatar-md bg-error rounded-circle me-2 d-flex align-items-center justify-content-center text-center">
                                        <i class="ti ti-arrow-down text-white d-flex align-items-center justify-content-center"></i>
                                    </div>
                                    <div>
                                        <div class="skeleton label-skeleton label-loader mb-1"></div>
                                        <p class="mb-1 d-none real-label">{{ __('web.user.total_debits') }}</p>
                                        <div class="skeleton label-skeleton label-loader"></div>
                                        <h5 class="mb-1 d-none real-label total_debit" aria-label="{{ __('web.user.total_debits') }}">Total Debits</h5>
                                    </div>
                                </div>
                                <div class="bg-light p-2 d-none">
                                    <div class="skeleton label-skeleton label-loader w-50"></div>
                                    <span class="text-success d-none real-label">
                                        <i class="ti ti-arrow-up-right me-1"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-start mb-2">
                                    <div class="avatar avatar-md bg-info rounded-circle me-2 d-flex align-items-center justify-content-center text-center">
                                        <i class="ti ti-currency-dollar text-white d-flex align-items-center justify-content-center"></i>
                                    </div>
                                    <div>
                                        <div class="skeleton label-skeleton label-loader mb-1"></div>
                                        <p class="mb-1 d-none real-label">{{ __('web.user.pending_payments') }}</p>
                                        <div class="skeleton label-skeleton label-loader"></div>
                                        <h5 class="mb-1 d-none real-label available_balance" aria-label="{{ __('web.user.pending_payments') }}">Available Balance</h5>
                                    </div>
                                </div>
                                <div class="bg-light p-2 d-none">
                                    <div class="skeleton label-skeleton label-loader w-50"></div>
                                    <span class="text-success d-none real-label">
                                        <i class="ti ti-arrow-up-right me-1"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /Status -->

                <div class="sub-title">
                    <h5 class="mb-3">{{ __('web.user.transactions_list') }}</h5>
                </div>

                <!-- Types -->
                <div class="row">
                    <div class="col-lg-9 col-sm-8">
                        <ul class="filters-wrap">

                            <li>
                                <div class="collapse-card">
                                    <div class="filter-header">
                                        <button type="button" class="toggle-collapse" data-target="#payment-type">
                                            <i class="ti ti-transition-top"></i> {{ __('web.common.payment_type') }}
                                        </button>
                                    </div>
                                    <div id="payment-type" class="collapse-body" style="display: none;">
                                        <ul class="checkbox-list categories-lists">
                                            <li>
                                                <label class="custom_check">
                                                    <input type="checkbox" name="payment_type[]" value="paypal" class="payment-filter">
                                                    <span class="checked-title">{{ __('web.user.paypal') }}</span>
                                                </label>
                                            </li>
                                            <li>
                                                <label class="custom_check">
                                                    <input type="checkbox" name="payment_type[]" value="stripe" class="payment-filter">
                                                    <span class="checked-title">{{ __('web.user.stripe') }}</span>
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            <!-- Date -->
                            <li>
                                <div class="form-sort form-wrap mb-3 date-select d-none">
                                    <span class="form-icon">
                                        <i class="ti ti-calendar-event"></i>
                                    </span>
                                    <input type="text" class="form-control datetimepicker" placeholder="03-04-2025">
                                </div>
                            </li>
                            <!-- /Date -->
                        </ul>
                    </div>
                    <div class="col-lg-3 col-sm-4">
                        <div class="main-search">
                            <div id="tablefilter"></div>
                        </div>
                    </div>
                </div>
                <!-- Types -->

                <!--  Tables -->
                <div class="table-responsive custom-table">
                    <table class="table" id="buyerTransaction">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('web.user.transaction_id') }}</th>
                                <th>{{ __('web.user.uploaded_for') }}</th>
                                <th>{{ __('web.user.date') }}</th>
                                <th>{{ __('web.user.type') }}</th>
                                <th>{{ __('web.user.amount') }}</th>
                                <th>{{ __('web.user.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 0; $i < 4; $i++)
                            <tr>
                                @for ($j = 0; $j < 6; $j++)
                                    <td><div class="skeleton data-skeleton data-loader"></div></td>
                                @endfor
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="table-bottom-footer d-sm-flex d-md-flex d-lg-flex align-items-center justify-content-between mt-4">
                    <div class="table-footer mt-0">
                        <div id="tablepage"></div>
                    </div>
                </div>
                <!--  /Tables -->

                <!-- Transaction details  -->
                <div class="modal new-modal fade" id="transaction_details" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="d-flex align-item-center">
                                    <h5 class="modal-title me-2">{{ __('web.user.transaction_details') }}</h5>
                                    <span class="badge badge-pink-transparent">{{ __('web.user.completed') }}</span>
                                </div>
                                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
                            </div>
                            <div class="modal-body service-modal">
                                <div class="sumary-widget">
                                    <div class="summary-info">
                                        <h6 class="mb-1">{{ __('web.user.transaction_id') }}</h6>
                                        <p> #TXN-20250321-00123462 </p>
                                    </div>
                                    <div class="summary-info">
                                        <h6 class="mb-1">{{ __('web.user.transaction_type') }}</h6>
                                        <p>{{ __('web.user.purchase') }}</p>
                                    </div>
                                    <div class="summary-info">
                                        <h6 class="mb-1">{{ __('web.user.amount') }}</h6>
                                        <p> $320 </p>
                                    </div>

                                    <div class="summary-info">
                                        <h6 class="mb-1">{{ __('web.user.processing_fee') }}</h6>
                                        <p> $20 </p>
                                    </div>
                                    <div class="summary-info">
                                        <h6 class="mb-1">{{ __('web.user.payment_method') }}</h6>
                                        <p> {{ __('web.user.credit_card') }} </p>
                                    </div>
                                    <div class="summary-info mb-0">
                                        <h6 class="mb-1">{{ __('web.user.sender') }}</h6>
                                        <p> John Doe </p>
                                    </div>
                                    <div class="summary-info mb-0">
                                        <h6 class="mb-1">{{ __('web.user.receiver') }}</h6>
                                        <p> Jane Smith </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Transaction details -->
            </div>
        </div>
        <!-- /Page Content -->

@endsection
@push('scripts')
<script src="{{ asset('frontend/custom/js/user/buyer-transactions.js') }}"></script>
@endpush

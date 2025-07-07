@extends('frontend.buyer.partials.app')
@push('styles')
<!-- Datatable CSS -->
<link rel="stylesheet" href="{{ asset('frontend/assets/plugins/datatables/datatables.min.css') }}">
@endpush
@section('content')
<!-- Page Content -->
<div class="page-wrapper">
    <div class="container px-0">

            <!--User Wallet -->
            <div class="content mx-4 mb-4">
                <div class="main-title my-4">
                    <div class="skeleton label-skeleton label-loader me-2"></div>
                    <h4 class="d-none real-label">{{ __('web.user.wallet') }}</h4>
                </div>
                <div class="wallet-wrap">
                    <div class="wallet-list">
                        <div class="wallet-item">
                            <div class="wallet-info"><div class="skeleton label-skeleton label-loader me-2"></div>
                                <p class="d-none real-label">{{ __('web.user.amount_in_wallet') }}</p>
                                <div class="skeleton label-skeleton label-loader mt-2"></div>
                                <h5 class="available_balance d-none real-label" aria-live="polite" aria-label="{{ __('web.user.amount_in_wallet') }}">{{ __('web.user.amount_in_wallet') }}</h5>
                            </div>
                        </div>
                        <div class="wallet-item">
                            <div class="wallet-info">
                                <div class="skeleton label-skeleton label-loader me-2"></div>
                                <p class="d-none real-label">{{ __('web.user.total_credit') }}</p>
                                <div class="skeleton label-skeleton label-loader mt-2"></div>
                                <h5 class="total_credit d-none real-label" aria-live="polite" aria-label="{{ __('web.user.total_credit') }}">{{ __('web.user.total_credit') }}</h5>
                            </div>
                        </div>
                        <div class="wallet-item">
                            <div class="wallet-info">
                                <div class="skeleton label-skeleton label-loader me-2"></div>
                                <p class="d-none real-label">{{ __('web.user.total_debit') }}</p>
                                <div class="skeleton label-skeleton label-loader mt-2"></div>
                                <h5 class="total_debit d-none real-label" aria-live="polite" aria-label="{{ __('web.user.total_debit') }}">{{ __('web.user.total_debit') }}</h5>
                            </div>
                        </div>
                        <div class="wallet-item">
                            <div class="wallet-info">
                                <div class="skeleton label-skeleton label-loader me-2"></div>
                                <p class="d-none real-label">{{ __('web.user.withdrawn') }}</p>
                                <div class="skeleton label-skeleton label-loader mt-2"></div>
                                <h5 class="remaining_withdrawn d-none real-label" aria-live="polite" aria-label="{{ __('web.user.withdrawn') }}">{{ __('web.user.withdrawn') }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <input type="hidden" class="withdraw_available_balance" name="withdraw_available_balance" id="withdraw_available_balance">
                        <div class="skeleton label-skeleton label-loader me-2"></div>
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_payment" class="btn btn-white me-2 d-none real-label">{{ __('web.user.add_payment') }}</a>
                        <div class="skeleton label-skeleton label-loader"></div>
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#withdraw" class="btn btn-primary d-none real-label">{{ __('web.user.withdraw') }}</a>
                    </div>
                </div>

                <ul class="nav nav-tabs p-3 pb-0" id="addonsTab">
                    <li class="nav-item">
                        <button class="nav-link active" id="installed_addon_tab" data-bs-toggle="tab"
                            data-bs-target="#installed_addon_content" type="button" role="tab"
                            aria-controls="installed_addon_content" aria-selected="true">
                            {{ __('web.user.wallet') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="new_addon_tab" data-bs-toggle="tab"
                            data-bs-target="#new_addon_content" type="button" role="tab"
                            aria-controls="new_addon_content" aria-selected="false">
                            {{ __('web.user.withdrawn_request') }}
                        </button>
                    </li>
                </ul>


                <div class="tab-content p-3" id="addonsTabContent">

                    <div class="tab-pane fade show active" id="installed_addon_content" role="tabpanel" aria-labelledby="installed_addon_tab">
                        <div class="table-filter">
                            <ul class="filter-item">
                                <li>
                                    <div class="dropdown d-none">
                                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="ti ti-bulb me-2"></i>{{ __('web.user.reason') }}
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-lg p-2">
                                            <li>
                                                <div class="mb-2">
                                                    <div class="dropdown-add-search">
                                                        <span class="input-icon"><i class="ti ti-search"></i></span>
                                                        <input type="text" class="form-control" placeholder="Search">
                                                    </div>
                                                </div>
                                            </li>
                                            <li><a href="javascript:void(0);" class="dropdown-item">I will do designing..</a></li>
                                            <li><a href="javascript:void(0);" class="dropdown-item">Develop openAI...</a></li>
                                            <li><a href="javascript:void(0);" class="dropdown-item">I will do Professional</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li>
                                    <div class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="ti ti-user-code me-2"></i>{{ __('web.user.transaction_type') }}
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-lg p-2">
                                            <li><a href="javascript:void(0);" class="dropdown-item transaction-filter" data-type="all">All</a></li>
                                            <li><a href="javascript:void(0);" class="dropdown-item transaction-filter" data-type="1">Credit</a></li>
                                            <li><a href="javascript:void(0);" class="dropdown-item transaction-filter" data-type="2">Debit</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                            <div id="tablefilter"></div>
                        </div>

                        <div class="table-responsive custom-table">
                            <table id="walletTable" class="table table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('web.user.id') }}</th>
                                        <th>{{ __('web.user.payment_gateway') }}</th>
                                        <th>{{ __('web.user.date_time') }}</th>
                                        <th>{{ __('web.user.amount') }}</th>
                                        <th>{{ __('web.user.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < 4; $i++)
                                        <tr>
                                            @for ($j = 0; $j < 5; $j++)
                                                <td><div class="skeleton data-skeleton data-loader"></div></td>
                                            @endfor
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <div class="table-footer">
                            <div id="tablepage"></div>
                        </div>
                    </div>

                    <!-- New Addons Tab -->
                    <div class="tab-pane fade" id="new_addon_content" role="tabpanel" aria-labelledby="new_addon_tab">
                        <div class="table-filter">
                            <ul class="filter-item">
                                <li>
                                    <div class="dropdown d-none">
                                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="ti ti-bulb me-2"></i>{{ __('web.user.reason') }}
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-lg p-2">
                                            <li>
                                                <div class="mb-2">
                                                    <div class="dropdown-add-search">
                                                        <span class="input-icon"><i class="ti ti-search"></i></span>
                                                        <input type="text" class="form-control" placeholder="Search">
                                                    </div>
                                                </div>
                                            </li>
                                            <li><a href="javascript:void(0);" class="dropdown-item">I will do designing..</a></li>
                                            <li><a href="javascript:void(0);" class="dropdown-item">Develop openAI...</a></li>
                                            <li><a href="javascript:void(0);" class="dropdown-item">I will do Professional</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <li>
                                    <div class="dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                            <i class="ti ti-user-code me-2"></i>{{ __('web.user.transaction_type') }}
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-lg p-2">
                                            <li><a href="javascript:void(0);" class="dropdown-item">Debit</a></li>
                                            <li><a href="javascript:void(0);" class="dropdown-item">Credit</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                            <div id="tablefilter"></div>
                        </div>

                        <div class="table-responsive custom-table">
                            <table id="withDrawTable" class="table table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ __('web.user.payment_gateway') }}</th>
                                        <th>{{ __('web.user.date_time') }}</th>
                                        <th>{{ __('web.user.amount') }}</th>
                                        <th>{{ __('web.user.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < 4; $i++)
                                        <tr>
                                            @for ($j = 0; $j < 5; $j++)
                                                <td><div class="skeleton data-skeleton data-loader"></div></td>
                                            @endfor
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>


                        <div class="table-footer">
                            <div id="tablepage"></div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- /User Wallet -->

    </div>
</div>
<!-- /Page Content -->
<!-- Add Payment -->
<div class="modal new-modal fade" id="add_payment" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('web.user.add_payment') }}</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <form id="add_wallet">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="amt-wrap">
                                <div class="form-wrap">
                                    <label class="form-label">{{ __('web.user.enter_amount') }} ($)<span class="text-danger ms-1">*</span></label>
                                    <input type="text" class="form-control" name="wallet_amount" id="wallet_amount">
                                    <span class="text-danger error-text" id="wallet_amount_error"></span>
                                </div>
                                <ul class="amt-list d-none">
                                    <li>Or</li>
                                    <li>
                                        <a href="javascript:void(0);" class="vary-amt">$50</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="vary-amt">$100</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="vary-amt">$150</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="buyer-method">
                                <h6>Select Payment Gateway<span class="text-danger ms-1">*</span></h6>
                                <label class="custom_radio">
                                    <input type="radio" id="paypal" name="payment">
                                    <span class="checkmark"></span>Paypal
                                </label>
                                <label class="custom_radio">
                                    <input type="radio" id="stripe" name="payment">
                                    <span class="checkmark"></span>Stripe
                                </label>
                                <span class="text-danger error-text" id="payment_error"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100">{{ __('web.user.add_payment') }}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- /Add Payment -->

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

<!-- Gigs Publish -->
<div class="modal custom-modal fade" id="success_credit" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="success-message text-center">
                    <div class="success-popup-icon">
                        <img src="/frontend/assets/img/icons/happy-icon.svg" alt="icon">
                    </div>
                    <div class="success-content">
                        <h4>Credit Successfully</h4>
                        <p>Amount of <span>“$200”</span> has been successfully Credited to your account with transaction ID of <span>“#124454487878874”</span></p>
                    </div>
                    <div class="col-lg-12 text-center">
                        <a href="" class="btn btn-primary">Back to Wallet</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Gigs Publish -->
@endsection
@push('scripts')
<script src="{{ asset('frontend/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('frontend/assets/plugins/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('frontend/custom/js/user/wallet.js') }}"></script>
@endpush

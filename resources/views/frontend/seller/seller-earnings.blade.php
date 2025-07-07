@extends('frontend.seller.partials.app')
@section('content')
@push('styles')
<!-- Datatable CSS -->

@endpush
 <!-- Page Content -->
 <div class="page-wrapper">
    <div class="page-content content bg-light">
        <div class="main-title mb-4">
            <h4>Earnings</h4>
        </div>

        <!-- Status-->
        <div class="row status-info">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-start mb-2">
                            <div class="avatar avatar-md bg-success rounded-circle me-2 d-flex align-item-center justify-content-center text-center">
                                <i class="ti ti-arrows-exchange text-white d-flex align-items-center justify-content-center"></i>
                            </div>
                            <div>
                                <p class="mb-1"> Total Transactions</p>
                                <h5 class="mb-1 total_transaction" aria-label="Total Transactions">Total Transactions</h5>
                            </div>
                        </div>
                        <div class="badge bg-light text-success border-success-100 users-badge text-success w-100 text-start"> <i class="ti ti-arrow-up-right me-1"></i> 6.78% <span class="text-grey ps-1 fw-regular">From last month</span> </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-start mb-2">
                            <div class="avatar avatar-md bg-warning rounded-circle me-2 d-flex align-item-center justify-content-center text-center">
                                <i class="ti ti-arrow-up text-white d-flex align-items-center justify-content-center"></i>
                            </div>
                            <div>
                                <p class="mb-1"> Total Credits</p>
                                <h5 class="mb-1 total_credits" aria-label="Total Credits">Total Credits</h5>
                            </div>
                        </div>
                        <div class="badge bg-light text-success border-success-100 users-badge text-success w-100 text-start"> <i class="ti ti-arrow-up-right me-1"></i> 4.29% <span class="text-grey ps-1 fw-regular">From last month</span> </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-start mb-2">
                            <div class="avatar avatar-md bg-error rounded-circle me-2 d-flex align-item-center justify-content-center text-center">
                                <i class="ti ti-arrow-down text-white d-flex align-items-center justify-content-center"></i>
                            </div>
                            <div>
                                <p class="mb-1"> Total Debits</p>
                                <h5 class="mb-1 total_debits" aria-label="Total Debits">Total Debits</h5>
                            </div>
                        </div>
                        <div class="badge bg-light text-success border-success-100 users-badge text-success w-100 text-start"> <i class="ti ti-arrow-up-right me-1"></i> 12.8% <span class="text-grey ps-1 fw-regular">From last month</span> </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-start mb-2">
                            <div class="avatar avatar-md bg-info rounded-circle me-2 d-flex align-item-center justify-content-center text-center">
                                <i class="ti ti-currency-dollar text-white d-flex align-items-center justify-content-center"></i>
                            </div>
                            <div>
                                <p class="mb-1"> Pending Payments </p>
                                <h5 class="mb-1 pending_payments" aria-label="Pending Payments">Pending Payments</h5>
                            </div>
                        </div>
                        <div class="badge bg-light text-success border-success-100 users-badge text-success w-100 text-start"> <i class="ti ti-arrow-up-right me-1"></i> 9.78% <span class="text-grey ps-1 fw-regular">From last month</span> </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Status-->

        <!-- Graph -->
        <div class="graph-info bg-white mb-4">
            <div class="title d-flex align-items-center justify-content-between">
                <div class="sub-title">
                    <h5 class="mb-3"> Earnings </h5>
                </div>
                <div class="form-sort form-wrap m-0">
                    <span class="form-icon">
                        <i class="ti ti-calendar-event"></i>
                    </span>
                    <select class="select">
                        <option>Jan 2024</option>
                        <option>Feb 2024</option>
                        <option>Mar 2024</option>
                    </select>
                </div>
            </div>
            <div id="sales-income"></div>

        </div>
        <!-- /Graph -->

        <!-- Types -->
        <div class="row align-items-center">
            <div class="col-lg-9 col-sm-8">
                <div class="sub-title">
                    <h5 class="mb-3"> Earnings List </h5>
                </div>
                <div class="table-filter">
                    <ul class="filter-item">
                        <li>
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="ti ti-user-code me-2"></i>Payment Type
                                </a>
                                <ul class="dropdown-menu dropdown-menu-lg p-2">
                                    <li><a href="javascript:void(0);" class="dropdown-item">Paypal</a></li>
                                    <li><a href="javascript:void(0);" class="dropdown-item">Stripe</a></li>
                                </ul>
                            </div>
                        </li>
                        <li></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-sm-4">
                <div class="main-search">
                    <div id="tablefilter"></div>
                </div>
            </div>
        </div>
        <!-- Types -->

        <!-- Tables -->
        <div class="table-responsive custom-table">
            <table class="table " id="sellerEarningTable">
                <thead class="thead-light">
                    <tr>
                        <th> ID</th>
                        <th>Uploaded For</th>
                        <th>Payment Type</th>
                        <th class="text-start">Amount</th>
                    </tr>
                </thead>
                <tbody>




                </tbody>
            </table>
        </div>
        <div class="table-footer">
        </div>
        <!-- /Tables -->

        <!-- Transaction details -->
        <div class="modal new-modal fade" id="transaction_details" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Transaction details </h5>
                        <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <div class="modal-body service-modal">
                        <h6 class="model-head-text"> Transaction Summary   </h6>
                        <div class="sumary-widget">
                            <div class="summary-info">
                                <h6> Transaction ID</h6>
                                <p> #TXN-20250321-00123462 </p>
                            </div>
                            <div class="summary-info">
                                <h6> Transaction type </h6>
                                <p> Purchase </p>
                            </div>
                            <div class="summary-info">
                                <h6> Amount</h6>
                                <p> $320 </p>
                            </div>
                            <div class="summary-info">
                                <h6> Currency</h6>
                                <p> USD </p>
                            </div>
                            <div class="summary-info">
                                <h6> Processing Fee</h6>
                                <p> $20 </p>
                            </div>
                            <div class="summary-info">
                                <h6> Payment Method</h6>
                                <p> Credit Card </p>
                            </div>
                            <div class="summary-info mb-0">
                                <h6> Sender</h6>
                                <p> John Doe </p>
                            </div>
                            <div class="summary-info mb-0">
                                <h6> Receiver</h6>
                                <p> Jane Smith </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Transaction details -->
    </div>
</div>
<!-- /Page Content -->

@endsection

@push('plugins')
<!-- Apexchart JS -->
<script src="{{ asset('frontend/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ asset('frontend/assets/plugins/apexchart/chart-data.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('scripts')
<script src="{{ asset('frontend/custom/js/seller-earning.js') }}"></script>
@endpush

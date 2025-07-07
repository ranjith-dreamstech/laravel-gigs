@extends('frontend.buyer.partials.app')
@section('content')

<!-- Page Content -->
<div class="page-wrapper">
    <div class="page-content content">
        <div class="row">
            <!-- Purchase List -->
            <div class="col-12">
                <div class="main-title">
                    <h4 class="mb-4">{{ __('web.purchases.purchases') }}</h4>
                </div>
                <div class="table-filter mb-4 gap-3">
                    <ul class="filter-item">
                        <li>
                            <div class="skeleton label-skeleton label-loader me-2"></div>
                            <div class="d-none real-label">
                                <div id="reportrangeOrder" class="reportrange-picker d-flex align-items-center">
                                    <i class="ti ti-calendar text-gray-5 fs-14 me-1"></i><span class="reportrange-picker-field"></span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown">
                                <div class="skeleton label-skeleton label-loader me-2"></div>
                                <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-none real-label d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="ti ti-arrows-move-horizontal me-2"></i>{{ __('web.purchases.filter_by_status') }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-lg p-2">
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.purchases.new') }}</a></li>
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.purchases.processing') }}</a></li>
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.purchases.pending') }}</a></li>
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.purchases.completed') }}</a></li>
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.purchases.cancelled') }}</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown">
                                <div class="skeleton label-skeleton label-loader me-2"></div>
                                <a href="javascript:void(0);" class="dropdown-toggle btn d-none real-label btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="ti ti-user-heart me-2"></i>{{ __('web.purchases.filter_by_seller') }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-lg p-2 dropdown-search-add">
                                    <li>
                                        <div class="mb-2">
                                            <div class="dropdown-add-search">
                                                <span class="input-icon">
                                                    <i class="ti ti-search"></i>
                                                </span>
                                                <input type="hidden" name="buyer_id" id="buyer_id" value="{{ $buyer_id }}">
                                                <input type="text" class="form-control" placeholder="{{ __('web.common.search') }}" name="search_seller" id="search_seller">
                                            </div>
                                        </div>
                                    </li>
                                    <div id="appendSeller"></div>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown">
                                <div class="skeleton label-skeleton label-loader me-2"></div>
                                <a href="javascript:void(0);" class="dropdown-toggle d-none real-label btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="ti ti-user-code me-2"></i>{{ __('web.purchases.filter_by_payment_method') }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-lg p-2">
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.purchases.paypal') }}</a></li>
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.purchases.stripe') }}</a></li>
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.purchases.wallet') }}</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="skeleton label-skeleton label-loader me-2"></div>
                            <div class="dropdown d-none real-label">
                                <a href="javascript:void(0);" class="btn btn-white clearBtn d-inline-flex align-items-center">
                                    <i class="ti ti-x me-2"></i>{{ __('web.purchases.clear_filter') }}
                                </a>
                            </div>
                        </li>
                    </ul>
                    <div id="tablefilter"></div>
                </div>

                <div class="table-responsive table-loader">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" scope="col">
                                    <div class="skeleton th-skeleton th-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton th-skeleton th-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton th-skeleton th-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton th-skeleton th-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton th-skeleton th-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton th-skeleton th-loader"></div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <td>
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </td>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                                <th scope="col">
                                    <div class="skeleton data-skeleton data-loader"></div>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive d-none real-table">
                    <table class="table datatable border" id="purchaseTable">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('web.purchases.order_id') }}</th>
                                <th>{{ __('web.purchases.uploaded_for') }}</th>
                                <th>{{ __('web.purchases.purchase_on') }}</th>
                                <th>{{ __('web.purchases.seller') }}</th>
                                <th>{{ __('web.purchases.cancel') }}</th>
                                <th>{{ __('web.purchases.amount') }}</th>
                                <th>{{ __('web.purchases.payment_mode') }}</th>
                                <th>{{ __('web.purchases.status') }}</th>
                                <th>{{ __('web.purchases.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

            </div>

            <!-- /Purchase List -->
        </div>
    </div>
</div>
<!-- /Page Content -->

<!-- Top Scroll -->
<div class="back-to-top">
    <a class="back-to-top-icon align-items-center justify-content-center d-flex" href="#top">
        <img src="assets/img/icons/arrow-badge-up.svg" alt="img">
    </a>
</div>
<!-- /Top Scroll -->

<!-- Order Cancel -->
<div class="modal new-modal fade" id="cancel_order" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('web.purchases.cancel_order_title') }}</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                <form id="orderCancel">
                    <input type="hidden" name="booking_id" id="booking_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-wrap form-item">
                                <textarea class="form-control" placeholder="{{ __('web.purchases.cancel_order_reason_placeholder') }}"></textarea>
                            </div>
                            <div class="modal-btn">
                                <button class="btn btn-primary w-100" id="cancel_btn" type="submit">{{ __('web.purchases.cancel_order_submit') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Order Cancel -->

<!-- Order Details -->
<div class="modal new-modal fade" id="order_details" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <form id="validateOrder" autocomplete="off">
                @csrf
                <input type="hidden" name="booking_id" id="booking_id" value="">
                <input type="hidden" name="buyer_ids" id="buyer_ids" value="">
                <div class="modal-body service-modal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="order-status">
                                <div class="order-item">
                                    <input type="hidden" name="gig_id" id="gig_id" value="">
                                    <div class="order-img">
                                        <img id="gigs_image" src="/backend/assets/img/service/service-slide-01.jpg" alt="img">
                                    </div>
                                    <div class="order-info">
                                        <h5 id="gigs_title">I will design, redesign wordpress website using elementor pro</h5>
                                        <ul>
                                            <li>ID : #1245</li>
                                            <li>Delivery : Jan 29 2024 8:10 AM</li>
                                        </ul>
                                    </div>
                                </div>
                                <h6 class="title">Service Details</h6>
                                <div class="detail-table table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Service</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="gigs_service_title">Designing and developing...</td>
                                                <td id="gigs_service_qut">1</td>
                                                <td class="text-primary" id="gigs_service_price">$100</td>
                                            </tr>
                                            <tr>
                                                <td id="gigs_extra_title">Additional 1 : I can clean</td>
                                                <td id="gigs_extra_qut">1</td>
                                                <td class="text-primary" id="gigs_extra_price">$100</td>
                                            </tr>
                                            <tr>
                                                <td id="gigs_fast_title">Super Fast : Super fast delivery</td>
                                                <td id="gigs_fast_qut">1</td>
                                                <td class="text-primary" id="gigs_fast_price">$100</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2" scope="col">Grand Total</th>
                                                <th class="text-primary" id="">$300</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <h6 class="title">Upload Final Files</h6>
                                <div class="drag-upload form-wrap mb-2">
                                    <input type="file" name="file_data" id="finalFileInput" />
                                    <div class="img-upload">
                                        <p><i class="feather-upload-cloud"></i> Drag or Upload File</p>
                                    </div>
                                    <span class="invalid-feedback" id="file_data_error"></span>
                                </div>
                                <p>Maximum file upload size 5MB</p>

                                <div id="filePreviewContainer"></div>
                                <div class="modal-btn">
                                    <div class="row gx-2">
                                        <div class="col-6">
                                            <a href="#" data-bs-dismiss="modal" class="btn btn-light text-dark w-100 justify-content-center">Cancel</a>
                                        </div>
                                        <div class="col-6">
                                            <button type="submit" id="validate_btn" class="btn btn-primary w-100 validateBtn">
                                                <span class="btn-text">Update File</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Order Details -->

<!-- Order Cancel -->
<div class="modal new-modal fade" id="cancel_order" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel your Order</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                <form id="orderCancel">
                    <input type="hidden" name="booking_id" id="booking_id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-wrap form-item">
                                <textarea class="form-control" placeholder="Reason"></textarea>
                            </div>
                            <div class="modal-btn">
                                <button class="btn btn-primary w-100" id="cancel_btn" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Order Cancel -->

<!-- File Details -->
<div class="modal new-modal fade" id="file_view" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">File Details - #124</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <div class="file-view">
                    <div class="file-img">
                        <img src="assets/img/gigs/gigs-04.jpg" class="img-fluid" alt="img">
                    </div>
                    <div class="upload-wrap mb-0">
                        <div class="upload-image">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /File Details -->

@endsection
@push('scripts')
<script src="{{ asset('frontend/custom/js/purchase.js') }}"></script>
@endpush

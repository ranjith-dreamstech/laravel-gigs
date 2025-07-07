@extends('frontend.seller.partials.app')
@section('content')
<!-- Page Content -->
<div class="page-wrapper">
    <div class="page-content content bg-light">
        <div class="row">
            <!-- Purchase List -->
            <div class="col-12">
                <div class="main-title">
                    <h4 class="mb-4">{{ __('web.orders.title') }}</h4>
                </div>
                <div class="table-filter">
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
                            <div class="skeleton label-skeleton label-loader me-2"></div>
                            <div class="dropdown d-none real-label">
                                <button type="button" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="ti ti-arrows-move-horizontal me-2"></i> {{ __('web.orders.filter_by_status') }}

                                </button>
                                <ul class="dropdown-menu dropdown-menu-lg p-2">
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.orders.new') }}</a></li>
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.orders.processing') }}</a></li>
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.orders.pending') }}</a></li>
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.orders.completed') }}</a></li>
                                    <li><a href="javascript:void(0);" class="dropdown-item">{{ __('web.orders.cancelled') }}</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="skeleton label-skeleton label-loader me-2"></div>
                            <div class="dropdown d-none real-label">
                                <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="ti ti-user-heart me-2"></i>{{ __('web.orders.filter_by_buyer') }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-lg p-2 dropdown-employee">
                                    <li>
                                        <div class="mb-2">
                                            <div class="dropdown-add-search">
                                                <span class="input-icon">
                                                    <i class="ti ti-search"></i>
                                                </span>
                                                <input type="hidden" name="seller_id" id="seller_id" value="{{ $seller_id }}">
                                                <input type="text" class="form-control" placeholder="{{ __('web.orders.search_buyer') }}" name="search_buyer" id="search_buyer">
                                            </div>
                                        </div>
                                    </li>
                                    <div id="appendSeller"></div>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <div class="skeleton label-skeleton label-loader me-2"></div>
                            <div class="dropdown d-none real-label">
                                <a href="javascript:void(0);" class="btn btn-white clearBtn d-inline-flex align-items-center">
                                    <i class="ti ti-x me-2"></i>{{ __('web.orders.clear_filter') }}
                                </a>
                            </div>
                        </li>
                    </ul>
                    <div id="tablefilter"></div>
                </div>
                <div class="table-responsive d-none real-table">
                    <table class="table datatable border" id="orderTable">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ __('web.orders.id') }}</th>
                                <th>{{ __('web.orders.gigs') }}</th>
                                <th>{{ __('web.orders.delivery_on') }}</th>
                                <th>{{ __('web.orders.buyer') }}</th>
                                <th>{{ __('web.orders.cancel') }}</th>
                                <th>{{ __('web.orders.amount') }}</th>
                                <th>{{ __('web.orders.payment_mode') }}</th>
                                <th>{{ __('web.orders.status') }}</th>
                                <th>{{ __('web.orders.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
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
                <input type="hidden" name="buyer_id" id="buyer_id" value="">
                <div class="modal-body service-modal">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="order-status">
                                <div class="order-item">
                                    <input type="hidden" name="gig_id" id="gig_id" value="">
                                    <div class="order-img">
                                        <img id="gigs_image" src="assets/img/service/service-slide-01.jpg" alt="img">
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

<!-- Add Review -->
<div class="modal new-modal fade" id="add_review" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Review</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                <form action="buyer-purchase.html">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="review-item review-wrap">
                                <div class="review-user-info mb-0">
                                    <div class="review-img">
                                        <img src="assets/img/user/user-01.jpg" alt="img">
                                    </div>
                                    <div class="reviewer-info">
                                        <div class="reviewer-loc d-block">
                                            <h6><a href="javascript:void(0);" class="mb-2">kadajsalamander</a></h6>
                                            <div class="d-flex align-items-center">
                                                <div class="star-rate">
                                                    <span class="ratings">
                                                        <i class="ti ti-star-filled text-warning"></i>
                                                        <i class="ti ti-star-filled text-warning"></i>
                                                        <i class="ti ti-star-filled text-warning"></i>
                                                        <i class="ti ti-star-filled text-warning"></i>
                                                        <i class="ti ti-star-filled text-warning"></i>
                                                    </span>
                                                    <span class="rating-count">5.0 | 1day ago</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="review-content review-contentnew">
                                    <h6>I will do designing and executing targeted email campaigns</h6>
                                    <p>I recently hired a him to help me with a project and I must say, I am extremely impressed with their work. From start to finish, the freelancer was professional, efficient, and a pleasure to work with.</p>
                                </div>
                            </div>
                            <div class="review-item review-wrap">
                                <div class="review-user-info mb-0">
                                    <div class="review-img">
                                        <img src="assets/img/user/user-01.jpg" alt="img">
                                    </div>
                                    <div class="reviewer-info">
                                        <div class="reviewer-loc d-block">
                                            <h6><a href="javascript:void(0);" class="mb-2">kadajsalamander</a></h6>
                                            <div class="d-flex align-items-center">
                                                <div class="star-rate">
                                                    <span class="ratings">
                                                        <i class="ti ti-star-filled text-warning"></i>
                                                        <i class="ti ti-star-filled text-warning"></i>
                                                        <i class="ti ti-star-filled text-warning"></i>
                                                        <i class="ti ti-star-filled text-warning"></i>
                                                        <i class="ti ti-star-filled text-warning"></i>
                                                    </span>
                                                    <span class="rating-count">5.0 | 1day ago</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="review-content review-contentnew">
                                    <p class="pt-3"> Thank You</p>
                                </div>
                            </div>
                            <div class="modal-btn text-lg-end">
                                <button class="btn btn-primary" type="submit">Back</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Add Review -->

<!-- Change status -->
<div class="modal new-modal fade" id="change_status" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('web.orders.change_status') }}</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                <form id="orderStatus">
                    <div class="form-wrap">
                        <input type="hidden" name="status_booking_id" id="status_booking_id">
                        <label class="form-label mb-1 fw-medium text-dark">{{ __('web.orders.order_status') }}<span class="text-primary">*</span></label>
                        <select class="select" id="booking_status" name="booking_status">
                            <option value="">{{ __('web.orders.select_status') }}</option>
                            <option value="2">{{ __('web.orders.in_progress') }}</option>
                            <option value="3">{{ __('web.orders.pending') }}</option>
                            <option value="4">{{ __('web.orders.complete') }}</option>
                        </select>
                    </div>
                    <div class="modal-btn">
                        <button class="btn btn-primary w-100" type="submit">{{ __('web.orders.update_status') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- /Order Cancel -->

<!-- Order Cancel -->
<div class="modal new-modal fade" id="cancel_order" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('web.orders.cancel_order') }}</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><i class="ti ti-x"></i></button>
            </div>
            <div class="modal-body">
                <form id="orderCancel">
                    <input type="hidden" name="cancel_booking_id" id="cancel_booking_id">
                    <div class="row">
                        <div class="modal-btn">
                            <button class="btn btn-primary w-100" type="submit">{{ __('web.orders.cancel') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /Order Cancel -->

@endsection
@push('scripts')
<script src="{{ asset('frontend/custom/js/order.js') }}"></script>
@endpush

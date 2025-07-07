@extends($userlayout)
@section('content')
<!-- Page Content -->
<div class="page-wrapper">
    <div class="container px-0">

            <!--User Wallet -->
            <div class="content mx-4 mb-4">
                <div class="main-title my-4">
                    <h4>Wallet</h4>
                </div>
                <div class="wallet-wrap">
                    <div class="wallet-list">
                        <div class="wallet-item">
                            <div class="wallet-info">
                                <p>Amount in Wallet</p>
                                <h5>$1,302.50</h5>
                            </div>
                        </div>
                        <div class="wallet-item">
                            <div class="wallet-info">
                                <p>Total Credit</p>
                                <h5>$9,292.50</h5>
                            </div>
                        </div>
                        <div class="wallet-item">
                            <div class="wallet-info">
                                <p>Total Debit</p>
                                <h5>$1,541.21</h5>
                            </div>
                        </div>
                        <div class="wallet-item">
                            <div class="wallet-info">
                                <p>Withdrawn</p>
                                <h5>$8,874.21</h5>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_payment" class="btn btn-white me-2">Add Payment</a>
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#withdraw" class="btn btn-primary">Withdraw</a>
                    </div>
                </div>
                <div class="table-filter">
                    <ul class="filter-item">
                        <li>
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                 <i class="ti ti-bulb me-2"></i>Reason
                                </a>
                                <ul class="dropdown-menu dropdown-menu-lg p-2">
                                    <li>
                                        <div class="mb-2">
                                            <div class="dropdown-add-search">
                                                <span class="input-icon">
                                                    <i class="ti ti-search"></i>
                                                </span>
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
                                <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                 <i class="ti ti-user-code me-2"></i>Transaction Type
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
                    <table class="table  datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Uploaded For</th>
                                <th>Payment Gateway</th>
                                <th>Date & Time</th>
                                <th>Type</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#WT120</td>
                                <td>
                                    <h2 class="table-avatar d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img src="assets/img/gigs/gigs-09.jpg" alt="user">
                                        </a>
                                        <a href="javascript:void(0);" class="text-dark">I will do designing and executing targeted email campaigns</a>
                                    </h2>
                                </td>
                                <td>Paypal</td>
                                <td>22 May 2023 10:50PM</td>
                                <td><span class="badge new-badge badge-soft-danger"><i class="ti ti-arrow-up"></i> Debit</span></td>
                                <td>-$154</td>
                            </tr>
                            <tr>
                                <td>#WT119</td>
                                <td>
                                    <h2 class="table-avatar d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img src="assets/img/gigs/gigs-03.jpg" alt="user">
                                        </a>
                                        <a href="javascript:void(0);" class="text-dark">I will develop openai, dalle, chat gpt app for mobile</a>
                                    </h2>
                                </td>
                                <td>Stripe</td>
                                <td>21 May 2023 11:25 PM</td>
                                <td><span class="badge new-badge badge-soft-success"><i class="ti ti-arrow-down"></i> Credit</span></td>
                                <td>+$1154</td>
                            </tr>
                            <tr>
                                <td>#WT118</td>
                                <td>
                                    <h2 class="table-avatar d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img src="assets/img/gigs/gigs-02.jpg" alt="user">
                                        </a>
                                        <a href="javascript:void(0);" class="text-dark">I will do professional lifestyle and product photography</a>
                                    </h2>
                                </td>
                                <td>Paypal</td>
                                <td>17 May 2023 12:16 AM</td>
                                <td><span class="badge new-badge badge-soft-success"><i class="ti ti-arrow-down"></i> Credit</span></td>
                                <td>+$6547</td>
                            </tr>
                            <tr>
                                <td>#WT117</td>
                                <td>
                                    <h2 class="table-avatar d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img src="assets/img/gigs/gigs-04.jpg" alt="user">
                                        </a>
                                        <a href="javascript:void(0);" class="text-dark">Embedded Android & AOSP customizations</a>
                                    </h2>
                                </td>
                                <td>Stripe</td>
                                <td>15 May 2023 11:21 PM</td>
                                <td><span class="badge new-badge badge-soft-success"><i class="ti ti-arrow-down"></i> Credit</span></td>
                                <td>+$1454</td>
                            </tr>
                            <tr>
                                <td>#WT116</td>
                                <td>
                                    <h2 class="table-avatar d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img src="assets/img/gigs/gigs-01.jpg" alt="user">
                                        </a>
                                        <a href="javascript:void(0);" class="text-dark">I will do creating and promoting video content to engage audiences </a>
                                    </h2>
                                </td>
                                <td>Paypal</td>
                                <td>17 Feb 2025 10:50 PM</td>
                                <td><span class="badge new-badge badge-soft-danger"><i class="ti ti-arrow-up"></i> Debit</span></td>
                                <td>+$1454</td>
                            </tr>
                            <tr>
                                <td>#WT115</td>
                                <td>
                                    <h2 class="table-avatar d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img src="assets/img/gigs/gigs-09.jpg" alt="user">
                                        </a>
                                        <a href="javascript:void(0);" class="text-dark">I will do implementing chatbots on websites or messaging apps</a>
                                    </h2>
                                </td>
                                <td>Stripe</td>
                                <td>02 Feb 2025 10:04 PM</td>
                                <td><span class="badge new-badge badge-soft-success"><i class="ti ti-arrow-down"></i> Credit</span></td>
                                <td>+$1454</td>
                            </tr>
                            <tr>
                                <td>#WT114</td>
                                <td>
                                    <h2 class="table-avatar d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img src="assets/img/gigs/gigs-03.jpg" alt="user">
                                        </a>
                                        <a href="javascript:void(0);" class="text-dark">I will develop openai, dalle, chat gpt app for mobile </a>
                                    </h2>
                                </td>
                                <td>Paypal</td>
                                <td>29 Jan 2025 03:50 PM</td>
                                <td><span class="badge new-badge badge-soft-danger"><i class="ti ti-arrow-up"></i> Debit</span></td>
                                <td>-$545</td>
                            </tr>
                            <tr>
                                <td>#WT113</td>
                                <td>
                                    <h2 class="table-avatar d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img src="assets/img/gigs/gigs-11.jpg" alt="user">
                                        </a>
                                        <a href="javascript:void(0);" class="text-dark">I will do professional lifestyle and product photography</a>
                                    </h2>
                                </td>
                                <td>Stripe</td>
                                <td>15 Jan 2025 05:50 AM</td>
                                <td><span class="badge new-badge badge-soft-danger"><i class="ti ti-arrow-up"></i> Debit</span></td>
                                <td>-$545</td>
                            </tr>
                            <tr>
                                <td>#WT112</td>
                                <td>
                                    <h2 class="table-avatar d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img src="assets/img/gigs/gigs-14.jpg" alt="user">
                                        </a>
                                        <a href="javascript:void(0);" class="text-dark">Embedded Android & AOSP customizations</a>
                                    </h2>
                                </td>
                                <td>Paypal</td>
                                <td>12 Jan 2025 09:15 AM</td>
                                <td><span class="badge new-badge badge-soft-danger"><i class="ti ti-arrow-up"></i> Debit</span></td>
                                <td>-$545</td>
                            </tr>
                            <tr>
                                <td>#WT111</td>
                                <td>
                                    <h2 class="table-avatar d-flex align-items-center">
                                        <a href="javascript:void(0);" class="avatar">
                                            <img src="assets/img/gigs/gigs-13.jpg" alt="user">
                                        </a>
                                        <a href="javascript:void(0);" class="text-dark">I will do creating and promoting video content to engage audiences</a>
                                    </h2>
                                </td>
                                <td>Stripe</td>
                                <td>05 Jan 2025 10:50 PM</td>
                                <td><span class="badge new-badge badge-soft-success"><i class="ti ti-arrow-down"></i> Credit</span></td>
                                <td>+$545</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <div id="tablepage"></div>
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
                <h5 class="modal-title">Add Payment</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="amt-wrap">
                            <div class="form-wrap">
                                <label class="form-label">Enter Amount ($)<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control">
                            </div>
                            <ul class="amt-list">
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
                            <h6>Select Payment Gateway *</h6>
                            <label class="custom_radio">
                                <input type="radio" name="payment">
                                <span class="checkmark"></span>Paypal
                            </label>
                            <label class="custom_radio">
                                <input type="radio" name="payment">
                                <span class="checkmark"></span>Stripe
                            </label>
                            <label class="custom_radio">
                                <input type="radio" name="payment" checked>
                                <span class="checkmark"></span>Credit Card
                            </label>
                        </div>
                        <div class="form-wrap form-item">
                            <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-wrap form-item">
                            <label class="form-label">Password<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#success_credit" data-bs-dismiss="modal" class="btn btn-primary w-100">Add Payment</a>
                    </div>
                </div>
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
                <h5 class="modal-title">Withdraw Payment</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="amt-wrap">
                            <div class="form-wrap">
                                <label class="form-label">Enter Amount ($)<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control">
                            </div>
                            <ul class="amt-list">
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
                            <h6>Select Payment Gateway *</h6>
                            <label class="custom_radio">
                                <input type="radio" name="payment">
                                <span class="checkmark"></span>Paypal
                            </label>
                            <label class="custom_radio">
                                <input type="radio" name="payment">
                                <span class="checkmark"></span>Stripe
                            </label>
                        </div>
                        <div class="form-wrap form-item">
                            <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-wrap form-item">
                            <label class="form-label">Password<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#success_credit" data-bs-dismiss="modal" class="btn btn-primary w-100">Withdraw</a>
                    </div>
                </div>
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
                        <img src="assets/img/icons/happy-icon.svg" alt="icon">
                    </div>
                    <div class="success-content">
                        <h4>Credit Successfully</h4>
                        <p>Amount of <span>“$200”</span> has been successfully Credited to your account with transaction ID of <span>“#124454487878874”</span></p>
                    </div>
                    <div class="col-lg-12 text-center">
                        <a href="buyer-wallet.html" class="btn btn-primary">Back to Wallet</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Gigs Publish -->
@endsection

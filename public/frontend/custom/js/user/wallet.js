/* global $, loadTranslationFile, window, document, showToast, _l */
(async () => {
    await loadTranslationFile("web", "user,common");

    $(document).ready(function () {
        $(document).on("click", ".vary-amt", function () {
            let value = $(this).data("value");
            $("#amount").val(value);
        });
        $.validator.addMethod("maxAvailableBalance", function(value) {
            const availableBalance = parseFloat($("#withdraw_available_balance").val());
            return parseFloat(value) <= availableBalance;
        }, _l("web.user.amount_exceeds_balance"));

        $("#buyerWithdraw").validate({
            rules: {
                amount: {
                    required: true,
                    number: true,
                    min: 50,
                    maxAvailableBalance: true
                },
                payment: {
                    required: true
                }
            },
            messages: {
                amount: {
                    required: _l("web.user.enter_amount"),
                    number: _l("web.user.enter_valid_amount"),
                    min: _l("web.user.amount_minimum"),
                    maxAvailableBalance: _l("web.user.amount_exceeds_balance")
                },
                payment: {
                    required: _l("web.user.select_payment_method")
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "payment") {
                    $(".error-payment").text(error.text());
                } else if (element.attr("name") == "amount") {
                    $(".error-amount").text(error.text());
                }
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");

                if (element.name === "amount") {
                    $(".error-amount").text("");
                } else if (element.name === "payment") {
                    $(".error-payment").text("");
                }
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onchange: function (element) {
                $(element).valid();
            },
            submitHandler: function () {
                let amount = $("input[name='amount']").val();
                let paymentType = $("input[name='payment']:checked").val();

                $.ajax({
                    url: "/user/buyer-withdraw",
                    type: "POST",
                    data: {
                        amount: amount,
                        payment_type: paymentType,
                        _token: $("meta[name=\"csrf-token\"]").attr("content"),
                    },
                    beforeSend: function () {
                        $(".btn-outline-light").text("Please Wait...").prop("disabled", true);
                        $(".btn[type='submit']").attr("disabled", true).html(
                            `<span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span> ${_l("web.user.saving")}..`
                        );
                    },
                    success: function (response) {
                        $(".error-text").text("");
                        $(".form-control").removeClass("is-invalid is-valid");
                        $(".btn-outline-light").text("Withdraw").prop("disabled", false);
                        $(".btn[type='submit']").removeAttr("disabled").html(_l("web.user.withdraw"));

                        withDrawTable();
                        $("#withdraw").modal("hide");

                        if (response.code == 200) {
                            showToast("success", response.message);
                            $("#buyerWithdraw")[0].reset();
                        } else {
                            showToast("error", response.message);
                        }
                    },
                    error: function () {
                        $(".btn-outline-light").text("Withdraw").prop("disabled", false);
                        $(".btn[type='submit']").removeAttr("disabled").html(_l("web.user.withdraw"));
                        showToast("error", _l("web.user.something_went_wrong"));
                    }
                });
            }
        });

        withDrawTable();
        walletTable();
        $("#add_wallet").validate({
            rules: {
                wallet_amount: {
                    required: true,
                    number: true,
                    min: 50
                },
                payment: {
                    required: true
                }
            },
            messages: {
                wallet_amount: {
                    required: _l("web.user.enter_amount"),
                    number: _l("web.user.enter_valid_amount"),
                    min: _l("web.user.amount_minimum")
                },
                payment: {
                    required: _l("web.user.select_payment_method")
                }
            },
            errorPlacement: function (error, element) {
                var errorId = element.attr("id") + "_error";
                $("#" + errorId).text(error.text());
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
                var errorId = element.id + "_error";
                $("#" + errorId).text("");
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onchange: function (element) {
                $(element).valid();
            },
            submitHandler: function () {
                let walletAmount = $("input[name='wallet_amount']").val();
                let paymentType = $("input[name='payment']:checked").attr("id");

                $.ajax({
                    url: "/user/addwallet",
                    type: "POST",
                    data: {
                        wallet_amount: walletAmount,
                        payment_type: paymentType,
                        _token: $("meta[name=\"csrf-token\"]").attr("content"),
                    },
                    beforeSend: function () {
                        $(".submitbtn").attr("disabled", true).html(`<span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span> ${_l("web.user.saving")}..`);
                    },
                    success: function (response) {
                        $(".error-text").text("");
                        $(".form-control").removeClass("is-invalid is-valid");
                        $(".submitbtn").removeAttr("disabled").html(_l("web.user.add_payment"));

                        if (response.code == 200) {
                            showToast("success", response.message);
                            if (response.paypal_url) {
                                window.location.href = response.paypal_url;  // Redirect to PayPal if the URL is provided
                            }
                            else if (response.stripe_url) {
                                window.location.href = response.stripe_url;  // Redirect to Stripe if the URL is provided
                            }
                            $("#add_payment").modal("hide"); // Close the modal
                        } else {
                            showToast("error", response.message);
                        }
                    },
                    error: function () {
                        $(".submitbtn").removeAttr("disabled").html(_l("web.user.add_payment"));
                        showToast("error", _l("web.user.something_went_wrong"));  // Handle any errors in the AJAX request
                    }
                });
            }
        });


    });

$(document).on("click", ".transaction-filter", function() {
    const selectedType = $(this).data("type");
    walletTable(selectedType);
});

    function walletTable(filterType = "all") {
        $.ajax({
            url: "/user/wallet-list",
            type: "GET",
            success: function(response) {
                const currencySymbol = response.currency_symbol || "$";

                $(".total_credit").text(`${currencySymbol}${parseFloat(response.total_credit).toFixed(2)}`);
                $(".total_debit").text(`${currencySymbol}${parseFloat(response.total_debit).toFixed(2)}`);
                $(".available_balance").text(`${currencySymbol}${parseFloat(response.total_balance).toFixed(2)}`);

                let tableBody = "";

                if ($.fn.DataTable.isDataTable("#walletTable")) {
                    $("#walletTable").DataTable().destroy();
                }

                let walletData = response.data;


                if (filterType !== "all") {
                    walletData = walletData.filter(item => item.type == filterType);
                }

                if (walletData.length > 0) {
                    $.each(walletData, function(index, value) {
                        const isCompleted = value.type == "1";
                        const typeSymbol = isCompleted ? "+ " : "- ";
                        const amountColor = isCompleted ? "success" : "danger";
                        const statusColor = value.status === "Completed" ? "success" : "danger";
                        const badgeLabel = isCompleted ? "Credit" : "Debit";
                        const badgeIcon = isCompleted ? "ti-arrow-down" : "ti-arrow-up";

                        tableBody += `
                            <tr>
                                <td>#${value.id ?? "N/A"}</td>
                                <td>${value.payment_type ?? "-"}</td>
                                <td>${new Date(value.transaction_date).toLocaleString()}</td>
                                <td class="text-${amountColor}-light">
                                    <span class="badge new-badge badge-soft-${amountColor}">
                                        <i class="ti ${badgeIcon}"></i> ${badgeLabel}
                                    </span> ${typeSymbol}${currencySymbol}${parseFloat(value.amount).toFixed(2)}
                                </td>
                                <td>
                                    <span class="badge new-badge badge-soft-${statusColor}">
                                        ${value.status}
                                    </span>
                                </td>
                            </tr>`;
                    });
                } else {
                    tableBody += `
                        <tr>
                            <td colspan="5" class="text-center">${_l("web.user.no_wallet_transaction_available")}</td>
                        </tr>`;
                }

                $("#walletTable tbody").html(tableBody);

                if (walletData.length > 0) {
                    $("#walletTable").DataTable({
                        ordering: false,
                        searching: false,
                        pageLength: 10,
                        lengthChange: false,
                    });
                }
            },
            error: function(error) {
                if (error.responseJSON && error.responseJSON.error) {
                    showToast("error", error.responseJSON.error);
                } else {
                    showToast("error", _l("web.user.error_occured_while_retrieving_wallet_history"));
                }
            },
            complete: function() {
                $(".table-loader, .input-loader, .label-loader").hide();
                $(".real-table, .real-label, .real-input").removeClass("d-none");
            },
        });
    }

    function withDrawTable() {
        $.ajax({
            url: "/user/buyer-withdraw/list",
            type: "GET",
            success: function (response) {

                const symbol = response.data.currency_symbol ?? "$";
                $(".remaining_withdrawn").text(`${symbol}${parseFloat(response.data.available_balance).toFixed(2)}`);

                $("#withdraw_available_balance").val(parseFloat(response.data.available_balance).toFixed(2));



                let tableBody = "";


                if ($.fn.DataTable.isDataTable("#withDrawTable")) {
                    $("#withDrawTable").DataTable().destroy();
                }

                const requests = response.data.withdraw_requests || [];

                if (requests.length > 0) {
                    $.each(requests, function (index, value) {

                        const paymentType = value.payment_id == 1 ? "Paypal" : value.payment_id == 2 ? "Stripe" : "N/A";


                        let statusLabel = "Pending";
                        let statusColor = "danger";
                        if (value.status == 1) {
                            statusLabel = "Completed";
                            statusColor = "success";
                        } else if (value.status == 2) {
                            statusLabel = "Rejected";
                            statusColor = "danger";
                        }

                        tableBody += `
                            <tr>
                                <td>${paymentType}</td>
                                <td>${new Date(value.created_at).toLocaleString()}</td>
                                <td>${symbol}${parseFloat(value.amount).toFixed(2)}</td>
                                <td><span class="badge new-badge badge-soft-${statusColor}">${statusLabel}</span></td>
                            </tr>`;
                    });
                } else {
                    tableBody += `
                        <tr>
                            <td colspan="4" class="text-center">${_l("web.user.no_wallet_transaction_available")}</td>
                        </tr>`;
                    // $('#new_addon_content .table-footer').empty();
                }

                $("#withDrawTable tbody").html(tableBody);

                if (requests.length > 0) {
                    $("#withDrawTable").DataTable({
                        ordering: false,
                        searching: false,
                        pageLength: 10,
                        lengthChange: false,
                        // drawCallback: function () {
                        //     $(".dataTables_info").addClass('d-none');
                        //     $(".dataTables_wrapper .dataTables_paginate").addClass('d-none');

                        //     const tableWrapper = $(this).closest('.dataTables_wrapper');
                        //     const info = tableWrapper.find('.dataTables_info');
                        //     const pagination = tableWrapper.find('.dataTables_paginate');

                        //     $('#new_addon_content .table-footer').empty().append(`
                        //         <div class="d-flex justify-content-between align-items-center w-100">
                        //             <div class="datatable-info">${info.clone(true).html()}</div>
                        //             <div class="datatable-pagination">${pagination.clone(true).html()}</div>
                        //         </div>
                        //     `);

                        //     $("#new_addon_content .table-footer").find(".dataTables_paginate").removeClass("d-none");
                        // }
                    });
                }
            },
            error: function (error) {
                if (error.responseJSON && error.responseJSON.error) {
                    showToast("error", error.responseJSON.error);
                } else {
                    showToast("error", _l("web.user.error_occured_while_retrieving_wallet_history"));
                }
            },
            complete: function () {
                $(".table-loader, .input-loader, .label-loader").hide();
                $(".real-table, .real-label, .real-input").removeClass("d-none");
            },
        });
    }


})();
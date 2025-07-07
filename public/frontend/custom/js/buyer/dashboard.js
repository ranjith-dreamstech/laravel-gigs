/* global $, loadTranslationFile, withDrawTable, document, showToast, _l */
"use strict";

(async () => {
    await loadTranslationFile("web", "user, common");

    // Bind transaction detail modal
    $(document).on("click", ".transaction_details", function () {
        const $this = $(this);

        $("#transaction_id").text($this.data("transaction_id") || "N/A");
        $("#payment_method").text($this.data("payment_method") || "N/A");
        $("#transaction_amount").text($this.data("final_price") || "0.00");
        $("#currency").text($this.data("currency") || "USD");
        $("#sender").text($this.data("sender") || "-");
        $("#receiver").text($this.data("receiver") || "-");

        const paymentStatus = Number($this.data("payment_status"));
        const statusText = $this.data("payment_status_text") || "";

        $("#paid_badge, #unpaid_badge").addClass("d-none");
        if (paymentStatus === 2) {
            $("#paid_badge").removeClass("d-none").text(statusText);
        } else {
            $("#unpaid_badge").removeClass("d-none").text(statusText);
        }
    });

    // Bind predefined amount
    $(document).on("click", ".vary-amt", function () {
        const value = $(this).data("value") || 0;
        $("#amount").val(value);
    });

    // Custom validator for max available balance
    $.validator.addMethod("maxAvailableBalance", function (value) {
        const available = parseFloat($("#withdraw_available_balance").val()) || 0;
        return parseFloat(value) <= available;
    }, _l("web.user.amount_exceeds_balance"));

    // Form validation
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
            const name = element.attr("name");
            if (name === "payment") {
                $(".error-payment").text(error.text());
            } else if (name === "amount") {
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
            const amount = $("input[name='amount']").val();
            const paymentType = $("input[name='payment']:checked").val();
            const csrfToken = $("meta[name=\"csrf-token\"]").attr("content");

            $.ajax({
                url: "/user/buyer-withdraw",
                type: "POST",
                data: {
                    amount,
                    payment_type: paymentType,
                    _token: csrfToken
                },
                beforeSend: function () {
                    $(".btn[type='submit']").attr("disabled", true).html(`
                        <span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span> ${_l("web.user.saving")}..`);
                },
                success: function (response) {
                    $(".error-text").text("");
                    $(".form-control").removeClass("is-invalid is-valid");
                    $(".btn[type='submit']").removeAttr("disabled").html(_l("web.user.withdraw"));

                    if (response.code === 200) {
                        showToast("success", response.message);
                        $("#buyerWithdraw")[0].reset();
                        withDrawTable();
                    } else {
                        showToast("error", response.message);
                    }
                },
                error: function () {
                    $(".btn[type='submit']").removeAttr("disabled").html(_l("web.user.withdraw"));
                    showToast("error", _l("web.user.something_went_wrong"));
                }
            });
        }
    });
})();

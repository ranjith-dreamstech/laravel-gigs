/* global $, loadTranslationFile, withDrawTable, ApexCharts, document, showToast, _l */
"use strict";
(async () => {
    await loadTranslationFile("web", "common, user");

    $(document).on("click", ".transaction_details", function () {
        $("#transaction_id").text($(this).data("transaction_id"));
        $("#payment_method").text($(this).data("payment_method"));
        $("#transaction_amount").text($(this).data("final_price"));
        $("#currency").text($(this).data("currency"));
        $("#sender").text($(this).data("sender"));
        $("#receiver").text($(this).data("receiver"));

        if ($(this).data("payment_status") === 2) {
            $("#paid_badge").removeClass("d-none").text($(this).data("payment_status_text"));
            $("#unpaid_badge").addClass("d-none");
        } else {
            $("#unpaid_badge").removeClass("d-none").text($(this).data("payment_status_text"));
            $("#paid_badge").addClass("d-none");
        }
    });

    $(document).on("click", ".vary-amt", function () {
        const value = $(this).data("value");
        $("#amount").val(value);
    });

    $.validator.addMethod("maxAvailableBalance", function (value) {
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
            if (element.attr("name") === "payment") {
                $(".error-payment").text(error.text());
            } else if (element.attr("name") === "amount") {
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

            $.ajax({
                url: "/user/buyer-withdraw",
                type: "POST",
                data: {
                    amount: amount,
                    payment_type: paymentType,
                    _token: $("meta[name='csrf-token']").attr("content"),
                },
                beforeSend: function () {
                    $(".btn[type='submit']").attr("disabled", true).html(
                        `<span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span> ${_l("web.user.saving")}..`
                    );
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

    $(document).ready(function () {
        getGigsSaleStatistics();
        getPaymentsSaleStatistics();
    });

    $(document).on("dp.change", "#sales_year", function () {
        getGigsSaleStatistics();
        getPaymentsSaleStatistics();
    });

    let payments_chart = null;
    function getPaymentsSaleStatistics() {
        $.ajax({
            url: "/seller/get-payments-sale-statistics",
            method: "POST",
            data: {
                year: $("#sales_year").val(),
            },
            dataType: "json",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            },
            success: function (response) {
                $("#revenue_amount").text(response.total_revenue_amount);
                $("#withdrawn_amount").text(response.total_withdrawn_amount);

                if (payments_chart) {
                    payments_chart.destroy();
                }

                const sCol = {
                    chart: {
                        height: 290,
                        type: "bar",
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: "80%",
                            borderRadius: 5,
                            endingShape: "rounded"
                        }
                    },
                    colors: ["#FF781A", "#45505C"],
                    dataLabels: { enabled: false },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ["transparent"]
                    },
                    series: [
                        { name: "Revenue", data: response.revenue },
                        { name: "Withdrawn", data: response.withdrawn }
                    ],
                    xaxis: {
                        categories: response.months,
                        labels: {
                            style: {
                                colors: "#5D6772",
                                fontSize: "14px"
                            }
                        }
                    },
                    yaxis: {
                        min: 0,
                        labels: {
                            offsetX: -15,
                            style: {
                                colors: "#5D6772",
                                fontSize: "13px"
                            },
                            formatter: val => response.currency + val.toFixed(2)
                        }
                    },
                    grid: {
                        borderColor: "#E2E4E6",
                        strokeDashArray: 5,
                        padding: { left: -8, right: -15 }
                    },
                    fill: { opacity: 1 },
                    tooltip: {
                        y: {
                            formatter: val => response.currency + val.toFixed(2)
                        }
                    }
                };

                payments_chart = new ApexCharts(document.querySelector("#custom-s-col"), sCol);
                payments_chart.render();
            }
        });
    }

    let gigs_chart = null;
    function getGigsSaleStatistics() {
        $.ajax({
            url: "/seller/get-gigs-sale-statistics",
            method: "POST",
            data: {
                year: $("#sales_year").val(),
            },
            dataType: "json",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
            },
            success: function (response) {
                if (gigs_chart) {
                    gigs_chart.destroy();
                }

                const sCol = {
                    chart: {
                        height: 290,
                        type: "bar",
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: "60%",
                            borderRadius: 5,
                            endingShape: "rounded"
                        }
                    },
                    colors: ["#FF781A"],
                    dataLabels: { enabled: false },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ["transparent"]
                    },
                    series: [
                        { name: "Gigs Count", data: response.no_gigs }
                    ],
                    xaxis: {
                        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "July", "Aug", "Sep", "Oct", "Nov", "Dec"],
                        labels: {
                            style: {
                                colors: "#5D6772",
                                fontSize: "14px"
                            }
                        }
                    },
                    yaxis: {
                        min: 0,
                        labels: {
                            offsetX: -15,
                            style: {
                                colors: "#5D6772",
                                fontSize: "13px"
                            }
                        }
                    },
                    grid: {
                        borderColor: "#E2E4E6",
                        strokeDashArray: 5,
                        padding: { left: -8, right: -15 }
                    },
                    fill: { opacity: 1 },
                    tooltip: {
                        y: {
                            formatter: val => val
                        }
                    }
                };

                gigs_chart = new ApexCharts(document.querySelector("#gigs_chart"), sCol);
                gigs_chart.render();
            }
        });
    }
})();
/* global $, loadTranslationFile, window, Chart, document, showToast, _l */
(async () => {
    await loadTranslationFile("web", "user,common");

    $(document).ready(function () {
        sellerEarningTable();
        loadEarningsChart();
    });
    function sellerEarningTable() {
        $.ajax({
            url: "/seller/earning/list",
            type: "GET",
            success: function (response) {
                let tableBody = "";
                const currencySymbol = response.data[0]?.currency_symbol || "$";
                const summary = response.data[0] || {};


                $(".total_transaction").text(`${summary.total_bookings ?? 0}`);
                $(".total_credits").text(`${currencySymbol}${parseFloat(summary.total_gross_amount ?? 0).toFixed(2)}`);
                $(".total_debits").text(`${currencySymbol}${parseFloat(summary.withdrawn ?? 0).toFixed(2)}`);
                $(".pending_payments").text(`${currencySymbol}${parseFloat(summary.remaining ?? 0).toFixed(2)}`);


                if ($.fn.DataTable.isDataTable("#sellerEarningTable")) {
                    $("#sellerEarningTable").DataTable().destroy();
                }


                if (response.data.length > 0) {
                    response.data.forEach(provider => {
                        provider.bookings.forEach(booking => {
                            tableBody += `
                                <tr>
                                    <td><a href="javascript:void(0);" class="text-grey fw-regular">${booking.order_id ?? "N/A"}</a></td>
                                    <td>
                                        <h2 class="table-avatar d-flex align-items-center">
                                            <a href="javascript:void(0);" class="avatar avatar-md">
                                                <img src="${booking.gig_image}" alt="User Image">
                                            </a>
                                            <a href="javascript:void(0);" class="text-dark">${booking.gig_title ?? "-"}</a>
                                        </h2>
                                    </td>
                                   <td>${booking.payment_type ? booking.payment_type.charAt(0).toUpperCase() + booking.payment_type.slice(1) : "N/A"}</td>
                                    <td class="text-start">${currencySymbol}${parseFloat(booking.final_price ?? 0).toFixed(2)}</td>
                                </tr>`;
                        });
                    });
                } else {
                    tableBody += `
                        <tr>
                            <td colspan="4" class="text-center">${_l("web.user.no_wallet_transaction_available")}</td>
                        </tr>`;
                }

                $("#sellerEarningTable tbody").html(DOMPurify.sanitize(tableBody));


                if (response.data.length > 0) {
                    $("#sellerEarningTable").DataTable({
                        ordering: false,
                        searching: false,
                        pageLength: 10,
                        lengthChange: false,

                    });
                }
            },
            error: function (error) {
                if (error.responseJSON?.error) {
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


    function loadEarningsChart() {
        $.ajax({
            url: "/seller/earnings/chart",
            type: "GET",
            success: function(response) {
                if (response.success && response.data.length > 0) {
                    const labels = response.data.map(item => `Day ${item.day}`);
                    const totalEarnings = response.data.map(item => item.total_earnings);

                    $("#sales-income").html("<canvas id='earningsChartCanvas'></canvas>");

                    const ctx = document.getElementById("earningsChartCanvas").getContext("2d");

                    if (window.earningsChart) {
                        window.earningsChart.destroy();
                    }

                    window.earningsChart = new Chart(ctx, {
                        type: "line",
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: "Total Earnings",
                                    data: totalEarnings,
                                    borderColor: "#4CAF50",
                                    backgroundColor: "rgba(76, 175, 80, 0.2)",
                                    fill: true,
                                    tension: 0.3,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    mode: "index",
                                    intersect: false
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: "Day of Month"
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: "Earnings (" + response.currency + ")"
                                    },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    $("#sales-income").html("<p>No earnings data available.</p>");
                }
            },
            error: function() {
                showToast("error", "Error fetching chart data");
                $("#sales-income").html("<p>Error loading chart data.</p>");
            }
        });
    }
})();

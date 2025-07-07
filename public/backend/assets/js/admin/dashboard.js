/* global $, loadTranslationFile, document, DOMPurify, ApexCharts*/

(async () => {
    "use strict";
    await loadTranslationFile("web", "common, user");
    let income_chart = null;

    $(document).ready(function () {
        getIncome();
    });

    $(document).on("dp.change", "#sales_year", function () {
        getIncome();
    });

    function getIncome() {
        $.ajax({
            url: "/admin/get-income",
            method: "POST",
            data: {
              year: $("#sales_year").val(),
            },
            dataType: "json",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content")
            },
            success: function (response) {
                let text_class = response.income_percentage >= 0 ? "text-success" : "text-danger";
                let percentage = response.income_percentage >= 0 ? "+" + response.income_percentage + "%" : response.income_percentage + "%";

                $(".total-income").html(DOMPurify.sanitize(`
                    ${response.total_income_amount}
                    <span class="fs-13 fw-semibold ${text_class} ms-2">${percentage}</span>
                `));
                
                if (income_chart) {
                  income_chart.destroy();
                }
            
                let sCol = {
                    chart: {
                        height: 290,
                        type: "bar",
                        toolbar: {
                            show: false,
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: "80%",
                            borderRadius: 5,
                            endingShape: "rounded",
                        },
                    },
                    colors: ["#FF781A", "#45505C"],
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ["transparent"]
                    },
                    series: [{
                        name: "Income",
                        data: response.revenue
                    },],
                    xaxis: {
                        categories: response.months,
                        labels: {
                            style: {
                                colors: "#5D6772",
                                fontSize: "14px",
                            }
                        }
                    },
                    yaxis: {
                        min: 0,
                        labels: {
                            offsetX: -15,
                            style: {
                                colors: "#5D6772",
                                fontSize: "13px",
                            },
                            formatter: function (val) {
                                return response.currency + val.toFixed(2);
                            }
                        }
                    },
                    grid: {
                        borderColor: "#E2E4E6",
                        strokeDashArray: 5,
                        padding: {
                            left: -8,
                            right: -15,
                        },
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return response.currency + val.toFixed(2);
                            }
                        }
                    }
                };
          
                income_chart = new ApexCharts(document.querySelector("#income-sales-statistics"), sCol);
                income_chart.render();
            }
          
        });
    }
}) ();
/* global $, moment, toastr, FormData, alert, document, showToast, _l */
let isDateSelected = false;

$(document).ready(function () {
    if($("#reportrangeOrder").length > 0) {

        $("#reportrangeOrder").daterangepicker({
            autoUpdateInput: false,
            ranges: {
                "Today": [moment(), moment()],
                "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [moment().startOf("month"), moment().endOf("month")],
                "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
            }
        });

        $("#reportrangeOrder").on("apply.daterangepicker", function() {
            isDateSelected = true;
            $("#reportrangeOrder span").html("");
            initTable(getFilters());
        });

        $("#reportrangeOrder").on("cancel.daterangepicker", function() {
            isDateSelected = false;
            $("#reportrangeOrder span").html("");
            initTable(getFilters());
        });

        $("#reportrangeOrder span").html("");
    }

    initTable(getFilters());

    $("#search_buyer").on("keyup", function () {
        fetchBuyer();
    });
});

$(document).on("click", ".clearBtn", function () {
    // 1. Clear all active classes
    $(".seller-filter, .status-filter, .payment-filter").removeClass("active");

    // 2. Reset the date picker
    if ($("#reportrangeOrder").data("daterangepicker")) {
        $("#reportrangeOrder").data("daterangepicker").setStartDate(moment().subtract(29, "days"));
        $("#reportrangeOrder").data("daterangepicker").setEndDate(moment());
        $("#reportrangeOrder span").html("");
    }
    isDateSelected = false;

    // 3. Optionally reset any dropdown selected text if you want (depends how you render it)
    $(".dropdown-menu .dropdown-item").removeClass("active");

    // 4. Re-initialize table with empty filters
    initTable(getFilters());
});

function getFilters() {
    const dateRange = $("#reportrangeOrder").data("daterangepicker");

    return {
        start_date: isDateSelected ? dateRange.startDate.format("YYYY-MM-DD") : "",
        end_date: isDateSelected ? dateRange.endDate.format("YYYY-MM-DD") : "",
        status: $(".status-filter.active").data("status") || "",
        buyer_id: $(".seller-filter.active").data("id") || "",
        payment_method: $(".payment-filter.active").data("method") || "",
    };
}

$(document).on("click", ".seller-filter", function () {
    if (!$(this).hasClass("active")) {
        $(".seller-filter").removeClass("active");
        $(this).addClass("active");
        initTable(getFilters());
    }
});

$(document).on("click", ".dropdown-menu .dropdown-item", function () {
    const $icon = $(this).closest(".dropdown-menu").parent().find("i");
    const text = $(this).text().trim();

    if ($icon.hasClass("ti-arrows-move-horizontal")) {
        const statusMap = {
            New: 1,
            Processing: 2,
            Pending: 3,
            Completed: 4,
            Cancelled: 6,
        };

        $(".status-filter").removeClass("active");
        $(this)
            .addClass("active status-filter")
            .data("status", statusMap[text] || "");
    } else if ($icon.hasClass("ti-user-code")) {
        $(".payment-filter").removeClass("active");
        $(this).addClass("active payment-filter").data("method", text);
    }

    initTable(getFilters());
});

function initTable(filters = {}) {
    $(".table-loader").show();
    $(" .input-loader").show();
    $(" .real-table, .real-data").addClass("d-none");
    $.ajax({
        url: "/seller/seller-orders-list",
        type: "GET",
        data: filters,
        success: function (response) {
            let tableBody = "";
            if ($.fn.DataTable.isDataTable("#orderTable")) {
                $("#orderTable").DataTable().destroy();
            }

            if (response.code === 200 && response.data.length > 0) {
                let data = response.data;

                function formatDate(dateString) {
                    if (!dateString) return "-";
                    const date = new Date(dateString);
                    const options = {
                        day: "2-digit",
                        month: "short",
                        year: "numeric",
                    };
                    return date.toLocaleDateString("en-US", options);
                }

                $.each(data, function (index, value) {
                    tableBody += `<tr>
                        <td>
                            <a class="fw-regular text-grey" data-bs-toggle="modal" data-bs-target="#purchase-details">#${
                                value.order_id
                            }</a>
                        </td>
                        <td class="upload-for-text table-avatar">
                            <a href="javascript:void(0);">${
                                value.gigs?.title ?? "-"
                            }</a>
                        </td>
                       <td>${formatDate(value.delivery_by)}</td>
                        <td>
                             <span class="table-avatar d-flex align-items-center">
                               <a class="avatar">
                                   <img src="${
                                       value.user?.user_detail?.profile_image
                                           ? value.user.user_detail
                                                 .profile_image
                                           : ""
                                   }" alt="User Image">
                                </a>
                                <a>${
                                    value.user?.user_detail?.first_name ??
                                    "Unknown"
                                } ${
                                    value.user?.user_detail?.last_name ?? "Unknown"
                                }</a>
                            </span>
                        </td>
                        <td>
                        ${
                            value.booking_status === 6
                                ? "<span class='text-danger'>Canceled</span>"
                                : value.booking_status === 4
                                    ? "<span class='text-danger'>Completed</span>" // If status is 4, show nothing (no cancel button)
                                    : `<a href="javascript:void(0);" class="cancel" data-id="${value.id}" data-bs-toggle="modal" data-bs-target="#cancel_order">Cancel</a>`
                        }
                        </td>
                        <td>$${value.final_price ?? "0.00"}</td>
                        <td>${value.payment_type ?? "-"}</td>
                        <td>
                            ${
                                value.booking_status === 6
                                    ? `
                                        <span class="badge badge-info-transparent">
                                            ${getBookingStatus(value.booking_status)}
                                        </span>
                                    `
                                    : `
                                        <span class="badge badge-info-transparent statusChange" data-id="${value.id}" data-bs-toggle="modal" data-bs-target="#change_status">
                                            ${getBookingStatus(value.booking_status)}
                                        </span>
                                    `
                            }
                        </td>
                        <td class="action-item text-center">
                            <a href="javascript:void(0);"
                            class="view-icon"
                            data-gigs-id="${value.gigs_id}"
                            data-order-id="${value.id}">
                            <i class="ti ti-eye"></i>
                            </a>
                        </td>
                    </tr>`;
                });
            } else {
                tableBody += `
                        <tr>
                            <td colspan="10" class="text-center">No data available</td>
                        </tr>`;
                $(".table-footer").empty();
            }

            $("#orderTable tbody").html(tableBody);
            if (response.data.length > 0) {
                $("#orderTable").DataTable({
                    ordering: true,
                    searching: false,
                    pageLength: 10,
                    lengthChange: false,
                    drawCallback: function () {
                        $(".dataTables_info").addClass("d-none");
                        $(".dataTables_wrapper .dataTables_paginate").addClass(
                            "d-none"
                        );

                        var tableWrapper = $(this).closest(
                            ".dataTables_wrapper"
                        );
                        var info = tableWrapper.find(".dataTables_info");
                        var pagination = tableWrapper.find(
                            ".dataTables_paginate"
                        );

                        $(".table-footer")
                            .empty()
                            .append(
                                $(
                                    "<div class='d-flex justify-content-between align-items-center w-100'></div>"
                                )
                                    .append(
                                        $(
                                            "<div class='datatable-info'></div>"
                                        ).append(info.clone(true))
                                    )
                                    .append(
                                        $(
                                            "<div class='datatable-pagination'></div>"
                                        ).append(pagination.clone(true))
                                    )
                            );
                        $(".table-footer")
                            .find(".dataTables_paginate")
                            .removeClass("d-none");
                    },
                });
            }

            $(".table-loader").hide();
            $(".label-loader, .input-loader").hide();
            $(".real-label, .real-table, .real-data").removeClass("d-none");
        },
        error: function (error) {
            if (error.responseJSON.code === 500) {
                toastr.error(error.responseJSON.message);
            } else {
                toastr.error(_l("admin.general_settings.retrive_error"));
            }
        },
    });
}

$(document).on("click", ".view-icon", function () {
    const gigsId = $(this).data("gigs-id");
    const orderId = $(this).data("order-id");
    editOrder(gigsId, orderId);
});

$(document).on("click", ".cancel", function () {
    const bookingId = $(this).data("id");
    $("#cancel_booking_id").val(bookingId);
});

$(document).on("click", ".statusChange", function () {
    const bookingId = $(this).data("id");
    $("#status_booking_id").val(bookingId);
});

function getBookingStatus(status) {
    switch (status) {
        case 1:
            return "New";
        case 2:
            return "In Progress";
        case 3:
            return "Pending";
        case 4:
            return "Complete";
        case 5:
            return "Refund";
        case 6:
            return "Cancelled ";
        case 7:
            return "Refund Complete";
        default:
            return "New";
    }
}

function editOrder(gigsId, orderId) {
    const finalFormData = new FormData();
    finalFormData.append("gigs_id", gigsId);
    finalFormData.append("booking_id", orderId);

    $.ajax({
        url: "/seller/seller-orders-details",
        method: "POST",
        data: finalFormData,
        dataType: "json",
        contentType: false,
        processData: false,
        cache: false,
        headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
        },
    })
        .done((response) => {
            if (!response.status || !response.data) return;

            // Open the modal first
            const data = response.data;
            const currency = data.currency ?? "$";

            $("#booking_id").val(data.booking_id);
            $("#buyer_id").val(data.buyer_id);
            $("#gig_id").val(data.gig?.id ?? "");
            $("#extra_service_total").val(data.extra_services_total ?? 0);
            $("#extra_service_ids").val(
                data.extra_services?.map((s) => s.id).join(",") ?? ""
            );
            $("#fast_service_total").val(data.fast_service?.total ?? 0);
            $("#total_price").val(data.base_price_total ?? 0);
            $("#quantity").val(data.quantity ?? 1);
            $("#final_price").val(data.final_price ?? 0);

            // Set main gig image
            $("#gigs_image").attr(
                "src",
                data.gig.image ?? "/backend/assets/img/service/service-slide-01.jpg"
            );

            // Set gig title
            $("#gigs_title").text(data.gig.title ?? "N/A");

            // Remove Order ID line (if present)
            $("li:contains('ID :')").remove();

            // Calculate delivery date
            let totalDays = parseInt(data.gig.days);
            if (data.extra_services) {
                totalDays += data.extra_services.reduce(
                    (sum, e) => sum + parseInt(e.days),
                    0
                );
            }
            if (data.fast_service) {
                totalDays += parseInt(data.fast_service.days);
            }

            const deliveryDate = new Date();
            deliveryDate.setDate(deliveryDate.getDate() + totalDays);
            const deliveryString = deliveryDate.toLocaleString("en-US", {
                month: "short",
                day: "numeric",
                year: "numeric",
                hour: "numeric",
                minute: "numeric",
                hour12: true,
            });
            $("ul li:contains('Delivery')").text(
                `Delivery : ${deliveryString}`
            );

            // Seller info
            $("#gigs_owner").text(
                `${data.provide_info.first_name} ${data.provide_info.last_name}`
            );
            $("#gigs_rating").html(
                `<i class="fa-solid fa-star"></i>Ratings ${data.provide_info.rating} <span id="gigs_review">(${data.provide_info.review_count} Reviews)</span>`
            );
            $("#gigs_location").html(`${data.provide_info.address}`);
            $("#providerImg").attr(
                "src",
                data.provide_info.profile_image ?? "/backend/assets/img/user/user-05.jpg"
            );

            // Reset previous rows except base
            $("#gigs_service_price").closest("tbody").find("tr:gt(0)").remove();

            // Base service row
            $("#gigs_service_title").text(data.gig.title);
            $("#gigs_service_qut").text(data.quantity);
            $("#gigs_service_price").text(
                `${currency}${parseFloat(data.base_price_total).toFixed(2)}`
            );

            // Extra service rows
            if (data.extra_services && data.extra_services.length > 0) {
                let extraHtml = "";
                data.extra_services.forEach((extra, i) => {
                    extraHtml += `
                <tr>
                    <td id="gigs_extra_title">Additional ${i + 1} : ${
                        extra.name
                    }</td>
                    <td id="gigs_extra_qut">1</td>
                    <td class="text-primary" id="gigs_extra_price">${currency}${parseFloat(
                        extra.price
                    ).toFixed(2)}</td>
                </tr>`;
                });
                $("#gigs_service_price").closest("tbody").append(extraHtml);
            }

            // Fast service row
            if (data.fast_service) {
                const fastHtml = `
            <tr>
                <td id="gigs_fast_title">Super Fast : ${
                    data.fast_service.title
                }</td>
                <td id="gigs_fast_qut">1</td>
                <td class="text-primary" id="gigs_fast_price">${currency}${parseFloat(
                    data.fast_service.price
                ).toFixed(2)}</td>
            </tr>`;
                $("#gigs_service_price").closest("tbody").append(fastHtml);
            }

            // Set final total
            $(".detail-table tfoot th.text-primary").text(
                `${currency}${parseFloat(data.final_price).toFixed(2)}`
            );

            // Final modal show and reset button
            $("#order_details").modal("show");
            $("#sumbit_btn").text("Buy this gig").prop("disabled", false);
        })
        .fail((error) => {
            $(".error-text").text("");
            $(".form-control").removeClass("is-invalid");
            $(".add_btn").removeAttr("disabled").html("Submit");
            if (error.status === 422) {
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (key, val) {
                        $("#" + key).addClass("is-invalid");
                        $("#" + key + "_error").text(val[0]);
                    });
                } else if (error.responseJSON.message) {
                    showToast("error", error.responseJSON.message);
                }
            } else {
                showToast(
                    "error",
                    error.responseJSON.message ||
                        "Something went wrong. Please try again."
                );
            }
        });
}

$(document).ready(function () {
    $("#finalFileInput").on("change", function () {
        const file = this.files[0];
        if (!file) return;

        // Validate max 5MB
        if (file.size > 5 * 1024 * 1024) {
            alert("File size must be under 5MB.");
            $(this).val(""); // reset input
            return;
        }

        const fileName = file.name;
        const fileSize = (file.size / 1024).toFixed(2) + " KB";
        const fileIcon = getFileIcon(file.type);

        const previewHTML = `
            <div class="d-flex align-items-center justify-content-between mb-3 file-preview-box">
                <div class="d-flex align-items-center">
                    <span class="files-icon flex-shrink-0 rounded-2">
                        <i class="ti ${fileIcon}"></i>
                    </span>
                    <div class="file-text">
                        <h6 class="mb-1">${fileName}</h6>
                        <p class="mb-0">Size: ${fileSize}</p>
                    </div>
                </div>
                <a href="javascript:void(0);" class="d-flex p-2 rounded bg-light delete-file">
                    <i class="ti ti-trash"></i>
                </a>
            </div>
        `;

        $("#filePreviewContainer").html(previewHTML);
    });

    // Delete file preview
    $(document).on("click", ".delete-file", function () {
        $("#finalFileInput").val(""); // clear input
        $("#filePreviewContainer").empty(); // remove preview
    });

    function getFileIcon(type) {
        if (type.startsWith("image/")) return "ti-photo";
        if (type.startsWith("video/")) return "ti-video";
        if (type === "application/pdf") return "ti-file";
        return "ti-file-description";
    }
});

$(document).ready(function () {
    $("#validateOrder").validate({
        rules: {
            file_data: {
                required: false,
            },
        },
        messages: {
            file_data: {
                required: "Final files field is required",
            },
        },
        errorPlacement: function (error, element) {
            var errorId = element.attr("id") + "_error";
            if (element.hasClass("select2-hidden-accessible")) {
                $("#" + errorId).text(error.text());
            } else {
                $("#" + errorId).text(error.text());
            }
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
        submitHandler: function (form) {
            let formData = new FormData(form);

            $.ajax({
                type: "POST",
                url: "/seller/seller-orders-file",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $(".validateBtn .btn-text").text("Uploading...");
                    $(".validateBtn").prop("disabled", true);
                },
                success: function (resp) {
                    $(".error-text").text("");
                    $(".form-control").removeClass("is-invalid is-valid");
                    $(".validateBtn .btn-text").text("Update File");
                    $(".validateBtn").prop("disabled", false);
            
                    if (resp.code === 200) {
                        $("#order_details").modal("hide");
                        initTable();
                        $("#finalFileInput").val("");
                        $("#filePreviewContainer").empty();
                        showToast("success", resp.message);
                    }
                },
                error: function (error) {
                    $(".error-text").text("");
                    $(".form-control").removeClass("is-invalid is-valid");
                    $(".validateBtn .btn-text").text("Update File");
                    $(".validateBtn").prop("disabled", false);
            
                    if (error.responseJSON.code === 422) {
                        if (error.responseJSON.errors) {
                            $.each(error.responseJSON.errors, function (key, val) {
                                $("#" + key).addClass("is-invalid");
                                $("#" + key + "_error").text(val[0]);
                            });
                        } else if (error.responseJSON.message) {
                            showToast("error", error.responseJSON.message);
                        } else {
                            showToast("error", "Something went wrong.");
                        }
                    } else {
                        showToast("error", error.responseJSON?.message || "Unexpected error occurred.");
                    }
                },
            });            
        },
    });
});

$("#orderCancel").on("submit", function (e) {
    e.preventDefault();

    let booking_id = $("#cancel_booking_id").val();

    const $submitBtns = $(this).find("button[type='submit']");
    $submitBtns.prop("disabled", true).text("Canceling...");
    $.ajax({
        url: "/buyer/buyer-purchase-delete",
        type: "POST",
        data: {
            booking_id: booking_id,
        },
        headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
        },
        success: function (response) {
            $submitBtns.prop("disabled", false).text("Cancel");
            if (response.code === 200) {
                $("#cancel_order").modal("hide"); // Hide modal
                initTable();
                showToast("success", "Order cancelled successfully.");
            } else {
                toastr.error(response.message ?? "Something went wrong.");
            }
        },
        error: function (xhr) {
            $submitBtns.prop("disabled", false).text("Cancel");
            toastr.error(
                xhr.responseJSON?.message ?? "Server error. Try again."
            );
        },
    });
});

$("#orderStatus").on("submit", function (e) {
    e.preventDefault();

    const bookingStatus = $("#booking_status").val();
    const bookingId = $("#status_booking_id").val(); // You need to set this dynamically when opening the modal

    if (!bookingStatus) {
        showToast("error", "Please select a status.");
        return;
    }

    if (!bookingId) {
        showToast("error", "Booking ID is missing.");
        return;
    }

    const $submitBtn = $(this).find("button[type='submit']");
    $submitBtn.prop("disabled", true).text("Updating...");

    $.ajax({
        url: "/seller/update-order-status",
        method: "POST",
        data: {
            booking_id: bookingId,
            booking_status: bookingStatus,
        },
        headers: {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
            Accept: "application/json",
        },
        success: function (response) {
            $submitBtn.prop("disabled", false).text("Update Status");

            if (response.code === 200) {
                $("#change_status").modal("hide"); // Adjust ID if different
                showToast("success", "Order status updated.");
                initTable(); // Refresh table
            } else {
                toastr.error(response.message ?? "Failed to update status.");
            }
        },
        error: function (xhr) {
            $submitBtn.prop("disabled", false).text("Update Status");
            toastr.error(
                xhr.responseJSON?.message ?? "Server error. Try again."
            );
        },
    });
});

function fetchBuyer() {
    let sellerId = $("#seller_id").val();
    let search = $("#search_buyer").val();

    // Show loading message
    $("#appendSeller").html("<li class='text-center py-2'>Loading...</li>");

    $.ajax({
        url: "/seller/buyer-list",
        type: "POST",
        data: {
            seller_id: sellerId,
            search: search,
        },
        headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
        },
        success: function (response) {
            let html = "";
            if (response.length > 0) {
                response.forEach(function (seller) {
                    html += `
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item seller-filter" data-id="${seller.id}">
                                <label class="d-flex align-items-center">
                                    <span class="avatar avatar-sm rounded-circle me-2">
                                        <img src="${seller.profile_image}" alt="img">
                                    </span> ${seller.full_name}
                                </label>
                            </a>
                        </li>`;
                });
            } else {
                html = "<li class='text-center py-2'>No sellers found.</li>";
            }
            $("#appendSeller").html(DOMPurify.sanitize(html));
        },
        error: function () {
            $("#appendSeller").html(
                "<li class='text-center py-2 text-danger'>Error loading sellers.</li>"
            );
            showToast("error", "Error fetching sellers.");
        },
    });
}

// Initial load
$(document).ready(fetchBuyer);

// On typing in the search box
$("#search_buyer").on("keyup", function () {
    fetchBuyer();
});

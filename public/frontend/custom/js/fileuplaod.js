/* global $, fetchBuyer, toastr, moment, document, showToast, _l */
let isDateSelected = false;

$(document).ready(function () {
    "use strict";

    if ($("#reportrangeOrder").length > 0) {
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

        $("#reportrangeOrder").on("apply.daterangepicker", function () {
            const picker = $(this).data("daterangepicker");
            isDateSelected = true;
            $("#reportrangeOrder span").html(
                picker.startDate.format("D MMM YY") + " - " + picker.endDate.format("D MMM YY")
            );
            initTable(getFilters());
        });

        $("#reportrangeOrder").on("cancel.daterangepicker", function () {
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
    // 1. Clear the active classes
    $(".gig-filter").removeClass("active");
    $(".orderType-filter").removeClass("active");

    // 2. Reset the date picker
    if ($("#reportrangeOrder").data("daterangepicker")) {
        $("#reportrangeOrder").data("daterangepicker").setStartDate(moment().subtract(29, "days"));
        $("#reportrangeOrder").data("daterangepicker").setEndDate(moment());
        $("#reportrangeOrder span").html("");
    }
    isDateSelected = false;

    // 3. Re-initialize table with empty filters
    initTable(getFilters());
});

function getFilters() {
    const dateRange = $("#reportrangeOrder").data("daterangepicker");

    return {
        start_date: isDateSelected ? dateRange.startDate.format("YYYY-MM-DD") : "",
        end_date: isDateSelected ? dateRange.endDate.format("YYYY-MM-DD") : "",
        gigs_id: $(".gig-filter.active").data("id") || "",
        type: $(".orderType-filter.active").data("type") || "",
    };
}

$(document).on("click", ".gig-filter", function () {
    if (!$(this).hasClass("active")) {
        $(".gig-filter").removeClass("active");
        $(this).addClass("active");
        initTable(getFilters());
    }
});
$(document).on("click", ".orderType-filter", function () {
    if (!$(this).hasClass("active")) {
        $(".orderType-filter").removeClass("active");
        $(this).addClass("active");
        initTable(getFilters());
    }
});

function initTable(filters = {}) {
    $(".table-loader").show();
    $(" .input-loader").show();
    $(" .real-table, .real-data").addClass("d-none");
    $.ajax({
        url: "/seller/seller-uploaded-list",
        type: "GET",
        data: filters,
        success: function (response) {
            let tableBody = "";
            if ($.fn.DataTable.isDataTable("#uploadedFile")) {
                $("#uploadedFile").DataTable().destroy();
            }

            if (response.code === 200 && response.data.length > 0) {
                let data = response.data;

                $.each(data, function (index, value) {
                    let createdAt = value.created_at
                        ? new Date(value.created_at).toLocaleDateString(
                              "en-GB",
                              {
                                  day: "2-digit",
                                  month: "short",
                                  year: "numeric",
                              }
                          )
                        : "-";

                    let fileType = value.file_type
                        ? value.file_type.toUpperCase()
                        : "-";

                    tableBody += `<tr>
                        <td>
                            <a class="fw-regular text-grey" data-bs-toggle="modal" data-bs-target="#purchase-details">#${
                                value.booking.order_id
                            }</a>
                        </td>
                        <td class="upload-for-text table-avatar">
                            <a href="javascript:void(0);">${
                                value.gigs?.title ?? "-"
                            }</a>
                        </td>
                        <td>${createdAt}</td>
                        <td>${fileType}</td>
                        <td class="action-item text-center">
                                        <div class="table-action">
                                         <a href="${value.data}" download>
                                <i class="ti ti-download"></i>
                            </a>
                           <a href="javascript:void(0);" class="view-icon">
                                <i class="ti ti-eye"></i>
                            </a>
                              <a  href="javascript:void(0);" data-id="${value.id}" data-bs-toggle="modal" data-bs-target="#cancel_order" class="deleteFiles">
                                <i class="ti ti-trash text-error"></i>
                            </a>
                            </div>
                        </td>
                    </tr>`;
                });
            } else {
                tableBody += `
                        <tr>
                            <td colspan="10" class="text-center">No Data Available</td>
                        </tr>`;
                $(".table-footer").empty();
            }

            $("#uploadedFile tbody").html(tableBody);
            if (response.data.length > 0) {
                $("#uploadedFile").DataTable({
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

$(document).on("click", ".view-icon", function() {
    const dataUrl = $(this).data("url");
    const fileType = $(this).data("type");
    const fileName = $(this).data("filename");
    viewOrder(dataUrl, fileType, fileName);
});

function viewOrder(dataUrl, fileType = "file", fileName = "Uploaded File") {
    $("#file_view .modal-title").text("File Details");

    let filePreview = "";
    let ext = fileType.toLowerCase();

    if (["jpg", "jpeg", "png", "gif", "webp"].includes(ext)) {
        filePreview = `<img src="${dataUrl}" class="img-fluid" alt="preview">`;
    } else if (["mp4", "webm", "ogg"].includes(ext)) {
        filePreview = `<video controls class="w-100" style="max-height: 300px;">
                          <source src="${dataUrl}" type="video/${ext}">
                          Your browser does not support the video tag.
                       </video>`;
    } else if (ext === "pdf") {
        filePreview = `<embed src="${dataUrl}" type="application/pdf" width="100%" height="300px"/>`;
    } else {
        filePreview = `<div class="file-img text-center">
                          <i class="ti ti-file text-large" style="font-size: 5rem;"></i>
                          <p class="mt-2">${fileName}</p>
                       </div>`;
    }

    $("#file_view .file-img").html(filePreview);

    $("#file_view .upload-image p").text(fileName);
    $("#file_view .ti-download").parent().attr("href", dataUrl);
    $("#file_view .ti-trash")
        .parent()
        .attr("onclick", `deleteFile('${dataUrl}')`);

    $("#file_view").modal("show");
}

function fetchSellers() {
    let sellerId = $("#buyer_id").val();
    let search = $("#search_seller").val();

    // Show loading message
    $("#appendSeller").html("<li class='text-center py-2'>Loading...</li>");

    $.ajax({
        url: "/seller/seller-gigs-list",
        type: "POST",
        data: {
            seller_id: sellerId,
            search: search,
        },
        headers: {
            "Accept": "application/json",
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
        },
        success: function (response) {
            let html = "";
            if (response.length > 0) {
                response.forEach(function (gig) {
                    html += `
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item gig-filter" data-id="${gig.id}">
                                <label class="d-flex align-items-center">
                                    ${gig.title}
                                </label>
                            </a>
                        </li>`;
                });
            } else {
                html = "<li class='text-center py-2'>No gigs found.</li>";
            }
            $("#appendGigs").html(DOMPurify.sanitize(html)); // **Use appendGigs** not appendSeller
        },        
        error: function () {
            $("#appendGigs").html("<li class='text-center py-2 text-danger'>Error loading gigs.</li>");
            showToast("error", "Error fetching gigs.");
        }
    });
}

// Initial load
$(document).ready(fetchSellers);

// On typing in the search box
$("#search_seller").on("keyup", function () {
    fetchSellers();
});

function fetchType() {

    $.ajax({
        url: "/order/types",
        type: "POST",
        headers: {
            "Accept": "application/json",
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
        },
        success: function (response) {
            let html = "";
            if (response.length > 0) {
                response.forEach(function (types) {
                    html += `
                        <li>
                            <a href="javascript:void(0);" class="dropdown-item orderType-filter" data-type="${types}">
                                    ${types}
                            </a>
                        </li>`;
                });
            } else {
                html = "<li class='text-center py-2'>No type found.</li>";
            }
            $("#appendTypes").html(DOMPurify.sanitize(html));
        },
        error: function () {
            $("#appendTypes").html("<li class='text-center py-2 text-danger'>Error loading type.</li>");
            showToast("error", "Error fetching type.");
        }
    });
}

// Initial load
$(document).ready(fetchType);

$(document).on("click", ".deleteFiles", function () {
    const orderID = $(this).data("id");
    $("#order_file_id").val(orderID);
});

$("#orderCancel").on("submit", function (e) {
    e.preventDefault();

    let order_id = $("#order_file_id").val();

    const $submitBtns = $(this).find("button[type='submit']");
    $submitBtns.prop("disabled", true).text("Deleteing...");
    $.ajax({
        url: "/order/order-delete",
        type: "POST",
        data: {
            order_id: order_id,
        },
        headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
        },
        success: function (response) {
            $submitBtns.prop("disabled", false).text("Delete");
            if (response.code === 200) {
                $("#cancel_order").modal("hide"); // Hide modal
                initTable();
                showToast("success", "File deleted successfully.");
            } else {
                toastr.error(response.message ?? "Something went wrong.");
            }
        },
        error: function (xhr) {
            $submitBtns.prop("disabled", false).text("Delete");
            toastr.error(
                xhr.responseJSON?.message ?? "Server error. Try again."
            );
        },
    });
});
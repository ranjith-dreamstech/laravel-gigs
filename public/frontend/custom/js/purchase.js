/* global $, loadTranslationFile, moment, alert, document, showToast, _l, fetchBuyer, toastr, FormData, Blob, atob, URL */
let isDateSelected = false;

(async () => {
    await loadTranslationFile("web", "purchases, user, common");
    $(document).ready(function () {
        if ($("#reportrangeOrder").length > 0) {
            $("#reportrangeOrder").daterangepicker({
                autoUpdateInput: false,
                ranges: {
                    Today: [moment(), moment()],
                    Yesterday: [
                        moment().subtract(1, "days"),
                        moment().subtract(1, "days"),
                    ],
                    "Last 7 Days": [moment().subtract(6, "days"), moment()],
                    "Last 30 Days": [moment().subtract(29, "days"), moment()],
                    "This Month": [
                        moment().startOf("month"),
                        moment().endOf("month"),
                    ],
                    "Last Month": [
                        moment().subtract(1, "month").startOf("month"),
                        moment().subtract(1, "month").endOf("month"),
                    ],
                },
            });

            $("#reportrangeOrder").on(
                "apply.daterangepicker",
                function () {
                    isDateSelected = true;
                    $("#reportrangeOrder span").html("");
                    initTable(getFilters());
                }
            );

            $("#reportrangeOrder").on(
                "cancel.daterangepicker",
                function () {
                    isDateSelected = false;
                    $("#reportrangeOrder span").html("");
                    initTable(getFilters());
                }
            );

            $("#reportrangeOrder span").html("");
        }

        initTable(getFilters());

        $("#search_seller").on("keyup", function () {
            fetchBuyer();
        });
    });
    $("#file_view").on("shown.bs.modal", function () {
        $("#file_view .close-btn").focus(); // Only if you use .close-btn inside modal
    });
    $(document).on("click", ".clearBtn", function () {
        // 1. Remove all active classes
        $(".seller-filter, .status-filter, .payment-filter").removeClass(
            "active"
        );

        // 2. Reset the date picker
        if ($("#reportrangeOrder").data("daterangepicker")) {
            $("#reportrangeOrder")
                .data("daterangepicker")
                .setStartDate(moment().subtract(29, "days"));
            $("#reportrangeOrder").data("daterangepicker").setEndDate(moment());
            $("#reportrangeOrder span").html("");
        }
        isDateSelected = false;

        // 3. Optionally reset any dropdown selections
        $(".dropdown-menu .dropdown-item").removeClass("active");

        // 4. Re-fetch table with cleared filters
        initTable(getFilters());
    });

    function getFilters() {
        const dateRange = $("#reportrangeOrder").data("daterangepicker");
        return {
            start_date: isDateSelected
                ? dateRange.startDate.format("YYYY-MM-DD")
                : "",
            end_date: isDateSelected
                ? dateRange.endDate.format("YYYY-MM-DD")
                : "",
            status: $(".status-filter.active").data("status") || "",
            seller_id: $(".seller-filter.active").data("id") || "",
            payment_method: $(".payment-filter.active").data("method") || "",
        };
    }

    $(document).on("click", ".seller-filter", function () {
        $(".seller-filter").removeClass("active");
        $(this).addClass("active");

        initTable(getFilters());
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
            url: "/buyer/buyer-purchase-list",
            type: "GET",
            data: filters,
            success: function (response) {
                let tableBody = "";
                if ($.fn.DataTable.isDataTable("#purchaseTable")) {
                    $("#purchaseTable").DataTable().destroy();
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
                            <a">${value.gigs?.title ?? "-"}</a>
                        </td>
                       <td>${formatDate(value.created_at)}</td>
                        <td>
                            <span class="table-avatar d-flex align-items-center">
                                <a class="avatar">
									<img src="${value.seller.seleter_detail.profile_image}" alt="User Image">
								</a>
                                <a>${
                                    value.seller.seleter_detail.first_name ??
                                    "Unknown"
                                } ${
                            value.seller.seleter_detail.last_name ?? "Unknown"
                        }</a>
                            </span>
                        </td>
                        <td>
                            ${
                                value.booking_status === 6
                                    ? "<span class='text-danger'>Canceled</span>"
                                    : `<a href="javascript:void(0);" class="cancel" data-id="${value.id}" data-bs-toggle="modal" data-bs-target="#cancel_order">Cancel</a>`
                            }
                        </td>
                        <td>$${value.final_price ?? "0.00"}</td>
                        <td>${capitalizeFirst(value.payment_type ?? "-")}</td>
                        <td>
                            <span class="badge badge-info-transparent" data-bs-toggle="modal" data-bs-target="#change_status">
                                ${getBookingStatus(value.booking_status)}
                            </span>
                        </td>
                       <td class="action-item text-center">
                            <div class="table-action">
                                <a href="javascript:void(0);" class="view-icon view-purchase" data-order='${JSON.stringify(value.order_data ?? [])}'>
                                    <i class="ti ti-eye"></i>
                                </a>
                                <a href="javascript:void(0);" class="download-icon" data-order='${JSON.stringify(value.order_data ?? [])}'>
                                    <i class="ti ti-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>`;
                    });
                } else {
                    tableBody += `
                        <tr>
                            <td colspan="10" class="text-center">${ _l("web.common.no_data_found")}</td>
                        </tr>`;
                    $(".table-footer").empty();
                }

                $("#purchaseTable tbody").html(tableBody);
                if (response.data.length > 0) {
                    $("#purchaseTable").DataTable({
                        ordering: true,
                        searching: false,
                        pageLength: 10,
                        lengthChange: false,
                        drawCallback: function () {
                            $(".dataTables_info").addClass("d-none");
                            $(
                                ".dataTables_wrapper .dataTables_paginate"
                            ).addClass("d-none");

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

    $(document).on("click", ".view-purchase", function () {
        const orderDataRaw = $(this).attr("data-order");
        let orderData;
        try {
            orderData = JSON.parse(orderDataRaw);
        } catch {
            showToast("error", "Invalid order data");
            return;
        }
        viewOrder(orderData);
    });

    $(document).on("click", ".download-icon", function () {
        const orderDataRaw = $(this).attr("data-order");

        let orderData;
        try {
            orderData = JSON.parse(orderDataRaw);
        } catch {
            showToast("error", "Invalid file data");
            return;
        }

        if (!Array.isArray(orderData) || orderData.length === 0) {
            showToast("info", "No files available to download");
            return;
        }

        orderData.forEach((file, index) => {
            const ext = file.file_type?.toLowerCase() ?? "file";
            const rawData = file.data;
            const fileName = `Attachment_${index + 1}.${ext}`;

            // Case 1: Direct file URL
            if (typeof rawData === "string" && rawData.startsWith("http")) {
                const a = document.createElement("a");
                a.href = rawData;
                a.download = fileName;
                a.target = "_blank";
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }

            // Case 2: Base64 Data URI (data:image/png;base64,...)
            else if (typeof rawData === "string" && rawData.startsWith("data:")) {
                const blob = base64ToBlob(rawData);
                const url = URL.createObjectURL(blob);

                const a = document.createElement("a");
                a.href = url;
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }

            // Case 3: Raw base64 (no prefix)
            else if (typeof rawData === "string") {
                const safeUrl = `data:application/${ext};base64,${rawData}`;
                const blob = base64ToBlob(safeUrl);
                const url = URL.createObjectURL(blob);

                const a = document.createElement("a");
                a.href = url;
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            } else {
                showToast("error", "Unsupported file format");
            }
        });
    });

    $(document).on("click", ".cancel", function () {
        const bookingId = $(this).data("id");
        $("#booking_id").val(bookingId);
    });

    $(document).on("click", ".force-download", function (e) {
        e.preventDefault();

        const fileUrl = $(this).data("url");
        const fileName = $(this).data("filename");

        if (fileUrl.startsWith("data:")) {
            // base64 blob handling
            const blob = base64ToBlob(fileUrl);
            const url = URL.createObjectURL(blob);

            const a = document.createElement("a");
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

            URL.revokeObjectURL(url);
        } else {
            // direct URL
            const a = document.createElement("a");
            a.href = fileUrl;
            a.download = fileName;
            a.target = "_blank";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    });


    function base64ToBlob(base64Data) {
        const parts = base64Data.split(",");
        const byteString = atob(parts[1]); // base64 content
        const mimeString = parts[0].match(/:(.*?);/)[1]; // MIME type

        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);

        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }

        return new Blob([ab], { type: mimeString });
    }

    function capitalizeFirst(str) {
        if (!str || typeof str !== "string") return "-";
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

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


    function viewOrder(orderData = [], fallbackType = "file") {
        $("#file_view .modal-title").text("File Attachments");

        let previewHTML = "";

        // Check if orderData is empty and display a message if so
        if (orderData.length === 0) {
            previewHTML = `<div class="text-center">
            <p class="fs-3">No file attached</p>
        </div>`;
        } else {
            // Process each file in the orderData array
            orderData.forEach((file, idx) => {
                const ext = file.file_type?.toLowerCase() ?? fallbackType;
                const dataUrl = file.data;
                const fileName = `File_${file.id}.${ext}`;
                const attachmentLabel = `Attachment ${toWords(idx + 1)}`;

                // Apply border except for the last item
                const borderClass =
                    idx !== orderData.length - 1
                        ? "border-bottom pb-3 mb-4"
                        : "";

                let filePreview = "";

                if (["jpg", "jpeg", "png", "gif", "webp"].includes(ext)) {
                    filePreview = `<div class="${borderClass}">
                    <h6 class="mb-2 text-center">${attachmentLabel}</h6>
                    <img src="${dataUrl}" class="img-fluid d-block mx-auto mb-2" style="max-height:200px;" alt="preview">
                    <p class="small text-center">${fileName}</p>
                    <div class="text-center">
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary force-download" data-url="${dataUrl}" data-filename="${fileName}">Download</a>
                    </div>
                </div>`;
                } else if (["mp4", "webm", "ogg"].includes(ext)) {
                    filePreview = `<div class="${borderClass}">
                    <h6 class="mb-2 text-center">${attachmentLabel}</h6>
                    <video controls class="w-100 mb-2" style="max-height: 300px;">
                        <source src="${dataUrl}" type="video/${ext}">
                        Your browser does not support the video tag.
                    </video>
                    <p class="small text-center">${fileName}</p>
                    <div class="text-center">
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary force-download" data-url="${dataUrl}" data-filename="${fileName}">Download</a>
                    </div>
                </div>`;
                } else if (ext === "pdf") {
                    filePreview = `<div class="${borderClass}">
                    <h6 class="mb-2 text-center">${attachmentLabel}</h6>
                    <embed src="${dataUrl}" type="application/pdf" width="100%" height="300px"/>
                    <p class="small mt-2 text-center">${fileName}</p>
                    <div class="text-center">
                        <a href="javascript:void(0);" class="btn btn-sm btn-primary force-download" data-url="${dataUrl}" data-filename="${fileName}">Download</a>
                    </div>
                </div>`;
                } else {
                    filePreview = `<div class="${borderClass} text-center">
                    <h6 class="mb-2">${attachmentLabel}</h6>
                    <i class="ti ti-file text-large d-block mb-2" style="font-size: 5rem;"></i>
                    <p class="mt-2">${fileName}</p>
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary force-download" data-url="${dataUrl}" data-filename="${fileName}">Download</a>
                </div>`;
                }

                previewHTML += filePreview;
            });
        }

        $("#file_view .file-img").html(previewHTML);
        $("#file_view").modal("show");
    }

    function toWords(num) {
        const words = [
            "One",
            "Two",
            "Three",
            "Four",
            "Five",
            "Six",
            "Seven",
            "Eight",
            "Nine",
            "Ten",
        ];
        return words[num - 1] ?? num;
    }

    $("#orderCancel").on("submit", function (e) {
        e.preventDefault();

        let booking_id = $("#booking_id").val();
        let reason = $("#orderCancel textarea").val();

        if (!reason.trim()) {
            showToast("error", _l("web.purchases.provide_cancellation_reason"));
            return;
        }

        // Disable the submit button and show "Cancelling..." state
        const $cancelBtn = $("#cancel_btn");
        $cancelBtn
            .prop("disabled", true)
            .html(
                "<span class='spinner-border spinner-border-sm me-2'></span>Cancelling..."
            );

        $.ajax({
            url: "/buyer/buyer-purchase-delete",
            type: "POST",
            data: {
                booking_id: booking_id,
                reason: reason,
            },
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
            },
            success: function (response) {
                if (response.code === 200) {
                    $("#cancel_order").modal("hide"); // Hide modal
                    initTable();
                    showToast("success", "Order cancelled successfully.");
                } else {
                    toastr.error(response.message ?? "Something went wrong.");
                }
            },
            error: function (xhr) {
                toastr.error(
                    xhr.responseJSON?.message ?? "Server error. Try again."
                );
            },
            complete: function () {
                // Re-enable the button and reset the text
                $cancelBtn.prop("disabled", false).html("Submit");
            },
        });
    });

    function fetchSellers() {
        let buyerId = $("#buyer_id").val();
        let search = $("#search_seller").val();

        // Show loading message
        $("#appendSeller").html("<li class='text-center py-2'>Loading...</li>");

        $.ajax({
            url: "/buyer/seller-list",
            type: "POST",
            data: {
                buyer_id: buyerId,
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
                    html =
                    `<li class="text-center py-2">${_l("web.user.no_sellers_found")}</li>`;
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

    $(document).ready(fetchSellers);

    $("#search_seller").on("keyup", function () {
        fetchSellers();
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
                    url: "/buyer/buyer-orders-file",
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
                                $.each(
                                    error.responseJSON.errors,
                                    function (key, val) {
                                        $("#" + key).addClass("is-invalid");
                                        $("#" + key + "_error").text(val[0]);
                                    }
                                );
                            } else if (error.responseJSON.message) {
                                showToast("error", error.responseJSON.message);
                            } else {
                                showToast("error", "Something went wrong.");
                            }
                        } else {
                            showToast(
                                "error",
                                error.responseJSON?.message ||
                                    "Unexpected error occurred."
                            );
                        }
                    },
                });
            },
        });
    });

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
})();

/* global $, loadTranslationFile, document, showToast, _l, FormData, loadUserPermissions, hasPermission*/

(async () => {
    await loadTranslationFile("admin", "common, manage");
    const permissions = await loadUserPermissions();

$(document).ready(function() {
    initTable();

    $("#adminRefundForm").validate({
        rules: {
            codFile: {
                required: true,
                extension: "jpeg|jpg|png|pdf",
                filesize: 2048 // 2MB max
            }
        },
        messages: {
            codFile: {
                required: _l("admin.common.image_required"),
                extension: _l("admin.common.image_format"),
                filesize: _l("admin.common.image_size", { size: 2 })
            }
        },
        errorPlacement: function (error, element) {
            $("#" + element.attr("id") + "_error").text(error.text());
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
            $("#" + element.id + "_error").text("");
        },
        submitHandler: function (){
            let formData = new FormData();
            let file = $("#codFile")[0].files[0];

            formData.append("bookingid", $("#bookingid").val());
            formData.append("payment_proof", file);

            $.ajax({
                url: "/api/refund/upload",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content")
                },
                beforeSend: function () {
                    $(".submitbtn").attr("disabled", true).html(`
                        <span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span> ${_l("admin.common.saving")}..
                    `);
                },
                success: function (response) {
                    $(".submitbtn").removeAttr("disabled").html(_l("admin.common.update"));
                    $(".form-control").removeClass("is-invalid is-valid");
                    $(".error-text").text("");

                    if (response.code === 200) {
                        showToast("success", response.message);
                        $("#add_category_modal").modal("hide");
                        initTable();
                    }
                },
                error: function (error) {
                    $(".submitbtn").removeAttr("disabled").html(_l("admin.common.update"));
                    $(".form-control").removeClass("is-invalid is-valid");

                    if (error.responseJSON?.code === 422) {
                        $.each(error.responseJSON.errors, function (key, val) {
                            $("#" + key).addClass("is-invalid");
                            $("#" + key + "_error").text(val[0]);
                        });
                    } else {
                        showToast("error", error.responseJSON?.message || "Something went wrong.");
                    }
                }
            });
        }
    });



    $.validator.addMethod("filesize", function (value, element, param) {
        let sizeInKB = element.files[0]?.size / 1024;
        return this.optional(element) || (sizeInKB <= param);
    }, _l("admin.common.image_size", { size: 2 }));

    $("#provider_amount").on("input", function () {
        const entered = parseFloat($(this).val());
        const remaining = parseFloat($("#remaining_amount").val());

        if (entered > remaining) {
            $("#amountError").show();
            $("#uploadPaymentProof").prop("disabled", true);
        } else {
            $("#amountError").hide();
            $("#uploadPaymentProof").prop("disabled", false);
        }
    });
});

function initTable() {
    let search = $("#search").val();
    let sort_by = $("#sort_by_input").val();
    let sort_status = $("#sort_by_status").val();
    let language_id = $("#language_id").val();

    $.ajax({
        url: "/admin/bookings/refund/list",
        type: "POST",
        data: {
            search,
            sort_by,
            sort_status,
            language_id
        },
        headers: {
            "Accept": "application/json",
            "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
        },
        beforeSend: function () {
            $(".table-loader").show();
            $(".real-table, .table-footer").addClass("d-none");
        },
        success: function (response) {
            if ($.fn.DataTable.isDataTable("#refundTable")) {
                $("#refundTable").DataTable().destroy();
            }

            let tableBody = "";

            if (response.data && response.data.length > 0) {


                $.each(response.data, function (index, row) {
                    let statusLabel = "";

                    switch (row.booking_status) {
                        case 5:
                            statusLabel = `<span class="badge bg-warning">${_l("admin.common.refund")}</span>`;
                            break;
                        case 7:
                            statusLabel = `<span class="badge bg-success">${_l("admin.common.refund_completed")}</span>`;
                            break;
                        default:
                            statusLabel = `<span class="badge bg-secondary">${_l("admin.common.unknown")}</span>`;
                    }
                    tableBody += `
                        <tr>
                            <td>${row.user_info?.first_name || ""} ${row.user_info?.last_name || "-"}</td>
                            <td>${row.gigs?.title || "-"}</td>
                            <td>${row.final_price ? row.final_price.toFixed(2) : "0.00"}</td>
                            <td>${statusLabel}</td>
                            <td>${row.booking_date || "-"}</td>
                            ${hasPermission(permissions, "refund", "edit") ?
                                `<td>
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end p-2">
                                            ${hasPermission(permissions, "refund", "edit") ?
                                                `<li>
                                                    <a class="dropdown-item rounded-1"
                                                        href="javascript:void(0);"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#add_category_modal"
                                                        onclick='viewRefundBooking({ id: ${row.id || 0} })'>
                                                        <i class="ti ti-eye me-1"></i>${_l("admin.common.view")}
                                                    </a>
                                                </li>`
                                            : ""}
                                        </ul>
                                    </div>
                                </td>`
                            : ""}

                        </tr>`;
                });
            } else {
                tableBody = `<tr><td colspan="6" class="text-center">${_l("admin.common.empty_table")}</td></tr>`;
            }

            $("#refundTable tbody").html(tableBody);

            if (response.data && response.data.length > 0) {
                $("#refundTable").DataTable({
                    ordering: true,
                    searching: false,
                    pageLength: 10,
                    lengthChange: false,
                    responsive: true,
                    autoWidth: false,
                    language: {
                        emptyTable: _l("admin.common.empty_table"),
                        info: _l("admin.common.showing") + " _START_ " + _l("admin.common.to") + " _END_ " + _l("admin.common.of") + " _TOTAL_ " + _l("admin.common.entries"),
                        infoEmpty: _l("admin.common.showing") + " 0 " + _l("admin.common.to") + " 0 " + _l("admin.common.of") + " 0 " + _l("admin.common.entries"),
                        infoFiltered: "(" + _l("admin.common.filtered_from") + " _MAX_ " + _l("admin.common.total_entries") + ")",
                        paginate: {
                            first: _l("admin.common.first"),
                            last: _l("admin.common.last"),
                            next: _l("admin.common.next"),
                            previous: _l("admin.common.previous"),
                        },
                    },
                    drawCallback: function () {
                        const tableWrapper = $(this).closest(".dataTables_wrapper");
                        const info = tableWrapper.find(".dataTables_info");
                        const pagination = tableWrapper.find(".dataTables_paginate");

                        $(".table-footer").empty().append(`
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="datatable-info">${info.clone(true).html()}</div>
                                <div class="datatable-pagination">${pagination.clone(true).html()}</div>
                            </div>
                        `);
                    }
                });
            }
        },
        error: function (error) {
            showToast("error", error.responseJSON?.error || _l("admin.common.default_retrieve_error"));
        },
        complete: function() {
            $(".table-loader, .input-loader, .label-loader").hide();
            $(".real-table, .real-label, .real-input").removeClass("d-none");
        }
    });
}

}) ();

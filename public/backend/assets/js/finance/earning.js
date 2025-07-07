/* global $, loadTranslationFile, document, showToast, _l, FormData, loadUserPermissions, hasPermission*/

(async () => {
    await loadTranslationFile("admin", "common, manage");
    const permissions = await loadUserPermissions();

$(document).ready(function() {
    initTable();

    $("#adminPayoutForm").validate({
        rules: {
            provider_amount: {
                required: true,
                number: true,
                min: 1,
                max: function () {
                    return parseFloat($("#remaining_amount").val() || 0);
                }
            },
            codFile: {
                required: true,
                extension: "jpeg|jpg|png|pdf",
                filesize: 2048 // in KB
            }
        },
        messages: {
            provider_amount: {
                required: _l("admin.common.amount_required"),
                number: _l("admin.common.amount_number"),
                min: _l("admin.common.amount_minimum"),
                max: _l("amount_exceed")
            },
            codFile: {
                required: _l("admin.common.image_required"),
                extension: _l("admin.common.image_format"),
                filesize: _l("admin.common.image_size", { size: 2 })
            }
        },
        errorPlacement: function (error, element) {
            let errorId = element.attr("id") + "_error";
            $("#" + errorId).text(error.text());
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
            $("#" + element.id + "_error").text("");
        },
        submitHandler: function () {
            let formData = new FormData();
            let paymentProof = $("#codFile")[0].files[0];

            formData.append("provider_id", $("#provider_id").val());
            formData.append("provider_name", $("#provider_name").val());
            formData.append("provider_email", $("#provider_email").val());
            formData.append("total_bookings", $("#total_bookings").val());
            formData.append("total_earnings", $("#total_gross_amount").val());
            formData.append("admin_earnings", $("#total_commission_amount").val());
            formData.append("provider_pay_due", $("#provider_amount_hidden").val());
            formData.append("entered_amount", $("#provider_amount").val());
            formData.append("payment_proof", paymentProof);
            formData.append("remaining_amount", $("#remaining_amount").val());

            $.ajax({
                url: "/api/payout/store",
                type: "POST",
                data: formData,
                enctype: "multipart/form-data",
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
                    $(".error-text").text("");
                    $(".form-control").removeClass("is-invalid is-valid");
                    $(".submitbtn").removeAttr("disabled").html(_l("admin.common.update"));
                    initTable();
                    if (response.code === 200) {
                        showToast("success", response.message);
                        $("#add_category_modal").modal("hide");


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
                        showToast("error", error.responseJSON.message || "Something went wrong.");
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
        url: "/admin/buyer-earning/list",
        type: "POST",
        data: {
            search: search,
            sort_by: sort_by,
            sort_status: sort_status,
            language_id: language_id
        },
        headers: {
            "Accept": "application/json",
            "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
        },
        beforeSend: function() {
            $(".table-loader").show();
            $(".real-table, .table-footer").addClass("d-none");
        },
        success: function(response) {
            if ($.fn.DataTable.isDataTable("#earningTable")) {
                $("#earningTable").DataTable().destroy();
            }

            let tableBody = "";

            if (response.data && response.data.length > 0) {
                $.each(response.data, function(index, row) {
                    tableBody += `
                        <tr>
                            <td>${row.provider?.name || "-"}</td>
                            <td>${row.transactions?.total_bookings || 0}</td>
                            <td>${row.currencySymbol || ""}${(row.transactions?.total_gross_amount || 0).toFixed(2)}</td>
                            <td>${row.currencySymbol || ""}${(row.transactions?.total_commission_amount || 0).toFixed(2)}</td>
                            <td>${row.currencySymbol || ""}${Number(row.transactions?.total_reduced_amount || 0).toFixed(2)}</td>
                            <td>${row.currencySymbol || ""}${Number(row.transactions?.pending_request_amount || 0).toFixed(2)}</td>
                            <td>${row.currencySymbol || ""}${(row.transactions?.available_balance || 0).toFixed(2)}</td>
                            ${hasPermission(permissions, "buyer_earning", "edit") || hasPermission(permissions, "buyer_earning", "delete") ?
                                `<td>
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end p-2">
                                            ${hasPermission(permissions, "buyer_earning", "edit") ?
                                                `<li>
                                                    <a class="dropdown-item rounded-1" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add_category_modal" onclick="viewTransactions({
                                                                id: ${row.provider?.id || 0},
                                                                name: '${row.provider?.name || ""}',
                                                                email: '${row.provider?.email || ""}',
                                                                total_bookings: ${row.transactions?.total_bookings || 0},
                                                                total_gross_amount: ${row.transactions?.total_gross_amount || 0},
                                                                total_commission_amount: ${row.transactions?.total_commission_amount || 0},
                                                                entered_amount: ${row.transactions?.entered_amount || 0},
                                                                remaining_amount: ${row.transactions?.available_balance || 0}
                                                            })">
                                                        <i class="ti ti-eye me-1"></i>${_l("admin.common.view")}
                                                    </a>
                                                </li>` : ""}

                                            
                                        </ul>
                                    </div>
                                </td>` : ""}

                        </tr>`;
                });
            } else {
                tableBody += `
                    <tr>
                        <td colspan="7" class="text-center">${_l("admin.common.empty_table")}</td>
                    </tr>`;
                $(".table-footer").empty();
            }

            $("#earningTable tbody").html(tableBody);

            if (response.data && response.data.length > 0) {
                $("#earningTable").DataTable({
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
                        lengthMenu: _l("admin.common.show") + " _MENU_ " + _l("admin.common.entries"),
                        search: _l("admin.common.search") + ":",
                        zeroRecords: _l("admin.common.no_matching_records"),
                        paginate: {
                            first: _l("admin.common.first"),
                            last: _l("admin.common.last"),
                            next: _l("admin.common.next"),
                            previous: _l("admin.common.previous"),
                        },
                    },
                    drawCallback: function() {
                        let tableWrapper = $(this).closest(".dataTables_wrapper");
                        let info = tableWrapper.find(".dataTables_info");
                        let pagination = tableWrapper.find(".dataTables_paginate");

                        $(".table-footer").empty().append(`
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="datatable-info">${info.clone(true).html()}</div>
                                <div class="datatable-pagination">${pagination.clone(true).html()}</div>
                            </div>
                        `);

                        $(".table-footer").find(".dataTables_paginate").removeClass("d-none");
                    }
                });
            }
        },
        error: function(error) {
            if (error.responseJSON && error.responseJSON.error) {
                showToast("error", error.responseJSON.error);
            } else {
                showToast("error", _l("admin.common.default_retrieve_error"));
            }
        },
        complete: function() {
            $(".table-loader, .input-loader, .label-loader").hide();
            $(".real-table, .real-label, .real-input").removeClass("d-none");
        }
    });
}

}) ();



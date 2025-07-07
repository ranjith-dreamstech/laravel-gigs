/* global $, loadTranslationFile, document, showToast, _l, FormData, DOMPurify, loadUserPermissions, hasPermission*/
(async () => {
    await loadTranslationFile("admin", "common, manage");
    const permissions = await loadUserPermissions();

$(document).ready(function() {
    initTable();
    $.validator.addMethod("filesize", function (value, element, param) {
        if (element.files.length === 0) return true; // no file = handled by 'required'
        return (element.files[0].size <= param * 1024);
    }, _l("admin.common.image_size", { size: 2 }));

    $("#adminRequestForm").validate({
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

            formData.append("provider_id", $("#provider_id").val());
            formData.append("id", $("#id").val());
            formData.append("provider_amount", $("#provider_amount").val());
            formData.append("payment_proof", file);

            $.ajax({
                url: "/api/request/update",
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


});

function initTable() {


    $.ajax({
        url: "/admin/buyer-request/list",
        type: "GET",

        headers: {
            "Accept": "application/json",
            "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
        },
        beforeSend: function () {
            $(".table-loader").show();
            $(".real-table, .table-footer").addClass("d-none");
        },
        success: function (response) {
            if ($.fn.DataTable.isDataTable("#requestTable")) {
                $("#requestTable").DataTable().destroy();
            }

            let tableBody = "";
            const requests = response.data?.withdraw_requests || [];

            if (requests.length > 0) {
                $.each(requests, function (index, row) {
                    let statusLabel = "";
                    switch (row.status) {
                        case 0:
                            statusLabel = `<span class="badge bg-warning">${_l("admin.common.pending")}</span>`;
                            break;
                        case 1:
                            statusLabel = `<span class="badge bg-success">${_l("admin.common.completed")}</span>`;
                            break;
                        case 2:
                            statusLabel = `<span class="badge bg-danger">${_l("admin.common.rejected")}</span>`;
                            break;
                        default:
                            statusLabel = `<span class="badge bg-secondary">${_l("admin.common.unknown")}</span>`;
                    }

                    let actionBtn = "";
                    if (hasPermission(permissions, "buyer_earning", "edit")) {
                        if (row.status === 1) {
                            actionBtn = `
                                <a class="dropdown-item rounded-1" href="javascript:void(0);"
                                   data-bs-toggle="modal" data-bs-target="#statusCompletedModal">
                                    <i class="ti ti-eye me-1"></i>${_l("admin.common.view")}
                                </a>`;
                        } else {
                            actionBtn = `
                                <a class="dropdown-item rounded-1" href="javascript:void(0);"
                                   data-bs-toggle="modal" data-bs-target="#add_category_modal"
                                   onclick="viewRequest({
                                       id: ${row.id || 0},
                                       provider_id: ${row.provider?.id || 0},
                                       provider_name: '${row.provider?.name || ""}',
                                       amount: ${parseFloat(row.amount).toFixed(2) || 0},
                                       status: ${row.status || 0},
                                       created_at: '${row.created_at || ""}'
                                   })">
                                    <i class="ti ti-eye me-1"></i>${_l("admin.common.view")}
                                </a>`;
                        }
                    }

                    tableBody += `
                        <tr>
                            <td>${row.provider?.name ? row.provider.name.charAt(0).toUpperCase() + row.provider.name.slice(1) : "-"}</td>
                            <td>${row.payment_id === 1 ? "PayPal" : row.payment_id === 2 ? "Stripe" : "-"}</td>
                            <td>${response.data.currency_symbol || ""}${parseFloat(row.amount).toFixed(2)}</td>
                            <td>${statusLabel}</td>
                            ${hasPermission(permissions, "buyer_earning", "edit") || hasPermission(permissions, "buyer_earning", "delete") ?
                                `<td>
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end p-2">
                                            ${actionBtn}
                                        </ul>
                                    </div>
                                </td>` : ""
                            }
                        </tr>
                    `;
                });
            } else {
                tableBody = `<tr><td colspan="5" class="text-center">${_l("admin.common.empty_table")}</td></tr>`;
                $(".table-footer").empty();
            }

            $("#requestTable tbody").html(DOMPurify.sanitize(tableBody));

            if (requests.length > 0) {
                $("#requestTable").DataTable({
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
                    drawCallback: function () {
                        const wrapper = $(this).closest(".dataTables_wrapper");
                        $(".table-footer").html(`
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="datatable-info">${wrapper.find(".dataTables_info").html()}</div>
                                <div class="datatable-pagination">${wrapper.find(".dataTables_paginate").html()}</div>
                            </div>
                        `);
                    }
                });
            }
        },
        error: function (error) {
            showToast("error", error.responseJSON?.error || _l("admin.common.default_retrieve_error"));
        },
        complete: function () {
            $(".table-loader, .input-loader, .label-loader").hide();
            $(".real-table, .real-label, .real-input").removeClass("d-none");
        }
    });
}


}) ();

/* global $, loadTranslationFile, document, hasPermission, FormData, loadUserPermissions, showToast, _l */
(async () => {
    "use strict";

    await loadTranslationFile("admin", "common, general_settings");
    const permissions = await loadUserPermissions();

    $(document).ready(function () {
        initTable();

        $(document).on("input", ".NumOnly", function () {
            this.value = this.value.replace(/[^0-9]/g, "");
        });

        $(document).on("click", ".dataTables_paginate a", function () {
            $(".table-footer")
                .find(".dataTables_paginate")
                .removeClass("d-none");
        });

        $(document).on("click", "#edit-bank", function () {
            const id = $(this).data("id");
            editBank(id);
        });

        $(document).on("click", "#delete-bank", function () {
            const id = $(this).data("id");
            delateBank(id);
        });

        $("#bank_clear").on("click", function () {
            $(".modal-title").text(
                _l("admin.general_settings.add_bank_account")
            );
            $(".submitbtn").text(_l("admin.general_settings.create_new"));
            $("#bankForm")[0].reset();
            $("#id").val("");
            $(".error-text").text("");
            $(".form-control").removeClass("is-invalid is-valid");
        });

        $("#search").on("input", function () {
            const searchQuery = $(this).val().trim();
            initTable(searchQuery);
        });

        $("#bankForm").validate({
            rules: {
                bank_name: { required: true },
                account_number: {
                    required: true,
                    digits: true,
                    minlength: 8,
                    maxlength: 20,
                },
                holder_name: {
                    required: true,
                    minlength: 3,
                },
                branch: { required: true },
                ifsc: {
                    required: true,
                    minlength: 5,
                },
            },
            messages: {
                bank_name: {
                    required: _l("admin.general_settings.bank_required"),
                },
                account_number: {
                    required: _l(
                        "admin.general_settings.account_number_required"
                    ),
                    digits: _l("admin.general_settings.account_number_numeric"),
                    minlength: _l(
                        "admin.general_settings.account_number_minlength"
                    ),
                    maxlength: _l(
                        "admin.general_settings.account_number_maxlength"
                    ),
                },
                holder_name: {
                    required: _l(
                        "admin.general_settings.account_holder_name_required"
                    ),
                    minlength: _l(
                        "admin.general_settings.account_holder_name_minlength"
                    ),
                },
                branch: {
                    required: _l(
                        "admin.general_settings.branch_field_required"
                    ),
                },
                ifsc: {
                    required: _l("admin.general_settings.ifsc_field_required"),
                    minlength: _l("admin.general_settings.ifsc_minlength"),
                },
            },
            errorPlacement: function (error, element) {
                const errorId = element.attr("id") + "_error";
                $("#" + errorId).text(error.text());
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element)
                        .next(".select2-container")
                        .addClass("is-invalid");
                }
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element)
                        .next(".select2-container")
                        .removeClass("is-invalid")
                        .addClass("is-valid");
                }
                const errorId = element.id + "_error";
                $("#" + errorId).text("");
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onchange: function (element) {
                $(element).valid();
            },
            submitHandler: function (form) {
                const formData = new FormData(form);
                formData.append("status", $("#status").is(":checked") ? 1 : 0);

                $.ajax({
                    type: "POST",
                    url: "/admin/settings/bank-store",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: () => {
                        $(".table-loader, .input-loader").show();
                        $(".real-table, .real-data").addClass("d-none");
                    },
                    success: function (resp) {
                        if (resp.code === 200) {
                            showToast("success", resp.message);
                            $("#add_bank").modal("hide");
                            initTable();
                        }
                    },
                    error: function (error) {
                        $(".error-text").text("");
                        $(".form-control").removeClass("is-invalid is-valid");
                        if (error.responseJSON.code === 422) {
                            $.each(
                                error.responseJSON.errors,
                                function (key, val) {
                                    $("#" + key).addClass("is-invalid");
                                    $("#" + key + "_error").text(val[0]);
                                }
                            );
                        } else {
                            showToast("error", error.responseJSON.message);
                        }
                    },
                });
            },
        });

        $("#delateBank").on("submit", function (e) {
            e.preventDefault();
            $.ajax({
                url: "/admin/settings/bank/delete",
                type: "POST",
                data: { id: $("#delete_id").val() },
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.code === 200) {
                        showToast("success", response.message);
                        $("#delete_bank").modal("hide");
                        initTable();
                    }
                },
                error: function (res) {
                    const msg =
                        res.responseJSON?.message ||
                        _l("admin.general_settings.retrieve_error");
                    showToast("error", msg);
                },
            });
        });
    });

    function initTable(search = "") {
        $.ajax({
            url: "/admin/settings/bank/datatable",
            type: "GET",
            data: { search },
            beforeSend: () => {
                $(".table-loader, .input-loader").show();
                $(".real-table, .real-data").addClass("d-none");
            },
            success: function (response) {
                let tableBody = "";
                if ($.fn.DataTable.isDataTable("#bankTable")) {
                    $("#bankTable").DataTable().destroy();
                }

                if (response.code === 200 && response.data.length > 0) {
                    response.data.forEach((value) => {
                        const statusBadge =
                            value.default == 1
                                ? "badge-success-transparent"
                                : "badge-danger-transparent";

                        tableBody += `<tr>
              <td>${
                  value.bank_name.length > 24
                      ? value.bank_name.substring(0, 24) + "..."
                      : value.bank_name
              }</td>
              <td>${value.account_number}</td>
              <td>${value.account_holder_name}</td>
              <td>${value.branch}</td>
              <td>${value.ifsc}</td>
              <td><span class="badge ${statusBadge} d-inline-flex align-items-center badge-sm">
              <i class="ti ti-point-filled me-1"></i>${
                  value.default == 1
                      ? _l("admin.common.active")
                      : _l("admin.common.inactive")
              }</span></td>
              ${
                  hasPermission(permissions, "finance_settings", "edit") ||
                  hasPermission(permissions, "finance_settings", "delete")
                      ? `
              <td>
                <div class="dropdown">
                  <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-dots-vertical"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end p-2">
                    ${
                        hasPermission(permissions, "finance_settings", "edit")
                            ? `<li><button class="dropdown-item rounded-1" data-id="${
                                  value.id
                              }" id="edit-bank"><i class="ti ti-edit me-1"></i>${_l(
                                  "admin.common.edit"
                              )}</button></li>`
                            : ""
                    }
                    ${
                        hasPermission(permissions, "finance_settings", "delete")
                            ? `<li><button class="dropdown-item rounded-1" data-id="${
                                  value.id
                              }" id="delete-bank" data-bs-toggle="modal" data-bs-target="#delete_bank"><i class="ti ti-trash me-1"></i>${_l(
                                  "admin.common.delete"
                              )}</button></li>`
                            : ""
                    }
                  </ul>
                </div>
              </td>`
                      : ""
              }
            </tr>`;
                    });
                } else {
                    tableBody += `<tr><td colspan="4" class="text-center">${_l(
                        "admin.general_settings.no_data"
                    )}</td></tr>`;
                }

                $("#bankTable tbody").html(DOMPurify.sanitize(tableBody));

                if (response.data.length > 0) {
                    $("#bankTable").DataTable({
                        ordering: true,
                        searching: false,
                        pageLength: 10,
                        lengthChange: false,
                        drawCallback: function () {
                            $(
                                ".dataTables_info, .dataTables_paginate"
                            ).addClass("d-none");
                            const tableWrapper = $(this).closest(
                                ".dataTables_wrapper"
                            );
                            const info = tableWrapper.find(".dataTables_info");
                            const pagination = tableWrapper.find(
                                ".dataTables_paginate"
                            );

                            $(".table-footer")
                                .empty()
                                .append(
                                    $(
                                        "<div class=\"d-flex justify-content-between align-items-center w-100\"></div>"
                                    )
                                        .append(
                                            $(
                                                "<div class=\"datatable-info\"></div>"
                                            ).append(info.clone(true))
                                        )
                                        .append(
                                            $(
                                                "<div class=\"datatable-pagination\"></div>"
                                            ).append(pagination.clone(true))
                                        )
                                );
                            $(".table-footer .dataTables_paginate").removeClass(
                                "d-none"
                            );
                        },
                    });
                }

                $(".table-loader, .label-loader, .input-loader").hide();
                $(".real-label, .real-table, .real-data").removeClass("d-none");
            },
            error: function (error) {
                showToast(
                    "error",
                    error.responseJSON?.message ||
                        _l("admin.general_settings.retrieve_error")
                );
            },
        });
    }

    function editBank(id) {
        $.ajax({
            type: "GET",
            url: "/admin/settings/bank/edit/" + id,
            success: function (response) {
                if (response.code === 200) {
                    const data = response.data;
                    $("#bank_name").val(data.bank_name);
                    $("#account_number").val(data.account_number);
                    $("#holder_name").val(data.account_holder_name);
                    $("#branch").val(data.branch);
                    $("#ifsc").val(data.ifsc);
                    $("#flexCheckChecked").prop("checked", data.default == 1);
                    $("#id").val(data.id);

                    $("#add_bank .modal-title").text(
                        _l("admin.general_settings.edit_bank_account")
                    );
                    $(".submitbtn").text(
                        _l("admin.general_settings.save_changes")
                    );
                    $("#add_bank").modal("show");
                }
            },
        });
    }

    function delateBank(id) {
        $("#delete_id").val(id);
    }
})();

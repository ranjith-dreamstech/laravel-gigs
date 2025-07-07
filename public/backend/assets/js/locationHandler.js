(async () => {
    "use strict";

    await loadTranslationFile("admin", "cms,common");
    const permissions = await loadUserPermissions();

    let selectedStatus = "";

    $(document).ready(function () {
        initTable();
        $("#countryForm").validate({
            rules: {
                name: {
                    required: true,
                },
                code: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: _l("admin.cms.country_required"),
                },
                code: {
                    required: _l("admin.cms.country_code_required"),
                },
            },
            errorPlacement: function (error, element) {
                var errorId = element.attr("id") + "_error";
                $("#" + errorId).text(error.text());
            },
            highlight: function (element) {
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element)
                        .next(".select2-container")
                        .addClass("is-invalid")
                        .removeClass("is-valid");
                }
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element)
                        .next(".select2-container")
                        .removeClass("is-invalid")
                        .addClass("is-valid");
                }
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
                formData.append("status", $("#status").is(":checked") ? 1 : 0);

                $.ajax({
                    type: "POST",
                    url: "/admin/country/store",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (resp) {
                        $(".error-text").text("");
                        $(".form-control").removeClass("is-invalid is-valid");
                        if (resp.code === 200) {
                            showToast("success", resp.message);
                            $("#country_modal").modal("hide");
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

        $(document).on("click", ".selectStatus", function () {
            selectedStatus = $(this).data("status");
            initTable();
        });

        $(document).on("keyup", "#search", function () {
            setTimeout(function () {
                initTable();
            }, 500);
        });

        $(document).on("click", "#edit-country", function () {
            let id = $(this).data("id");
            editCountry(id);
        });

        $(document).on("click", "#delete-country", function () {
            let id = $(this).data("id");
            delateCountry(id);
        });

        $(document).on("click", ".dataTables_paginate a", function () {
            $(".table-footer")
                .find(".dataTables_paginate")
                .removeClass("d-none");
        });

        $("#delateCountry").on("submit", function (e) {
            e.preventDefault();
            $.ajax({
                url: "/admin/country/delete",
                type: "POST",
                data: {
                    id: $("#delete_id").val(),
                },
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.code === 200) {
                        showToast("success", response.message);
                        $("#delete-modal").modal("hide");
                        initTable();
                    }
                },
                error: function (res) {
                    if (res.responseJSON.code === 500) {
                        showToast("success", res.responseJSON.message);
                    } else {
                        showToast(
                            "error",
                            "An error occurred while deleting door type."
                        );
                    }
                },
            });
        });
    });

    function initTable() {
        $.ajax({
            url: "/admin/country/datatable",
            type: "GET",
            data: {
                search: $("#search").val(),
                status: selectedStatus,
            },
            beforeSend: function () {
                $(".table-loader").show();
                $(".real-table, .table-footer").addClass("d-none");
            },
            complete: function () {
                $(".table-loader, .input-loader, .label-loader").hide();
                $(".real-table, .real-label, .real-input").removeClass(
                    "d-none"
                );
                if ($("#countryTable").length === 0) {
                    $(".table-footer").addClass("d-none");
                } else {
                    $(".table-footer").removeClass("d-none");
                }
            },
            success: function (response) {
                let tableBody = "";
                if ($.fn.DataTable.isDataTable("#countryTable")) {
                    $("#countryTable").DataTable().destroy();
                }

                if (response.code === 200 && response.data.length > 0) {
                    let data = response.data;

                    $.each(data, function (index, value) {
                        let countryCode = value.code.toLowerCase();
                        let flagImage = `<img src="/backend/assets/img/flags/${countryCode}.svg"
                        alt="${value.name} Flag" width="20"
                        onerror="this.style.display='none'; this.parentNode.innerHTML='${value.name}';">`;
                        tableBody += `<tr>
                                <td>${flagImage} ${value.name}</td>
                                <td>${value.code}</td>
                                <td>
                                    <span class="badge ${
                                        value.status == 1
                                            ? "badge-success-transparent"
                                            : "badge-danger-transparent"
                                    } d-inline-flex align-items-center badge-sm">
                                        <i class="ti ti-point-filled me-1"></i>${
                                            value.status == 1
                                                ? `${_l("admin.common.active")}`
                                                : `${_l(
                                                      "admin.common.inactive"
                                                  )}`
                                        }
                                    </span>
                                </td>
                                ${
                                    hasPermission(
                                        permissions,
                                        "cms_locations",
                                        "edit"
                                    ) ||
                                    hasPermission(
                                        permissions,
                                        "cms_locations",
                                        "delete"
                                    )
                                        ? `<td>
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end p-2">
                                           ${
                                               hasPermission(
                                                   permissions,
                                                   "cms_locations",
                                                   "edit"
                                               )
                                                   ? `<li>
                                                <button type="button" class="dropdown-item rounded-1" data-id="${
                                                    value.id
                                                }" id="edit-country"><i class="ti ti-edit me-1"></i>${_l(
                                                         "admin.common.edit"
                                                     )}</button>
                                            </li>`
                                                   : ""
                                           }
                                              ${
                                                  hasPermission(
                                                      permissions,
                                                      "cms_locations",
                                                      "delete"
                                                  )
                                                      ? `<li>
                                                <button type="button" class="dropdown-item rounded-1" data-id="${
                                                    value.id
                                                }" id="delete-country" data-bs-toggle="modal" data-bs-target="#delete-modal"><i class="ti ti-trash me-1"></i>${_l(
                                                            "admin.common.delete"
                                                        )}</button>
                                            </li>`
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
                    tableBody += `
                            <tr>
                                <td colspan="6" class="text-center">${_l(
                                    "admin.common.empty_table"
                                )}</td>
                            </tr>`;
                    $(".table-footer").empty();
                }

                $("#countryTable tbody").html(tableBody);
                if (response.data.length > 0) {
                    $("#countryTable").DataTable({
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
                                        '<div class="d-flex justify-content-between align-items-center w-100"></div>'
                                    )
                                        .append(
                                            $(
                                                '<div class="datatable-info"></div>'
                                            ).append(info.clone(true))
                                        )
                                        .append(
                                            $(
                                                '<div class="datatable-pagination"></div>'
                                            ).append(pagination.clone(true))
                                        )
                                );
                            $(".table-footer")
                                .find(".dataTables_paginate")
                                .removeClass("d-none");
                        },
                        language: {
                            emptyTable: _l("admin.common.empty_table"),
                            info:
                                _l("admin.common.showing") +
                                " _START_ " +
                                _l("admin.common.to") +
                                " _END_ " +
                                _l("admin.common.of") +
                                " _TOTAL_ " +
                                _l("admin.common.entries"),
                            infoEmpty:
                                _l("admin.common.showing") +
                                " 0 " +
                                _l("admin.common.to") +
                                " 0 " +
                                _l("admin.common.of") +
                                " 0 " +
                                _l("admin.common.entries"),
                            infoFiltered:
                                "(" +
                                _l("admin.common.filtered_from") +
                                " _MAX_ " +
                                _l("admin.common.total_entries") +
                                ")",
                            lengthMenu:
                                _l("admin.common.show") +
                                " _MENU_ " +
                                _l("admin.common.entries"),
                            search: _l("admin.common.search") + ":",
                            zeroRecords: _l("admin.common.empty_table"),
                            paginate: {
                                first: _l("admin.common.first"),
                                last: _l("admin.common.last"),
                                next: _l("admin.common.next"),
                                previous: _l("admin.common.previous"),
                            },
                        },
                    });
                }
            },
            error: function (error) {
                if (error.responseJSON.code === 500) {
                    showToast("error", error.responseJSON.message);
                } else {
                    showToast(
                        "error",
                        _l("admin.common.default_retrieve_error")
                    );
                }
            },
        });
    }

    function editCountry(id) {
        $.ajax({
            type: "GET",
            url: "/admin/country/edit/" + id,
            success: function (response) {
                $(".error-text").text("");
                $(".form-control").removeClass("is-invalid is-valid");
                if (response.code === 200) {
                    let data = response.data;
                    $("#name").val(data.name);
                    $("#code").val(data.code);
                    $("#status").prop("checked", data.status === 1);
                    $("#id").val(data.id);

                    $("#country_modal .modal-title").text(
                        _l("admin.cms.edit_country")
                    );
                    $(".submitbtn").text(_l("admin.common.save_changes"));
                    $("#statusDiv")
                        .removeClass("d-none")
                        .parent()
                        .removeClass("justify-content-end")
                        .addClass("justify-content-between");
                    $("#country_modal").modal("show");
                }
            },
        });
    }

    function delateCountry(id) {
        $("#delete_id").val(id);
    }

    $("#add_country").on("click", function () {
        $(".modal-title").text(_l("admin.cms.create_country"));
        $(".submitbtn").text(_l("admin.common.create_new"));
        $("#countryForm")[0].reset();
        $("#id").val("");
        $(".error-text").text("");
        $(".form-control").removeClass("is-invalid is-valid");
        $("#statusDiv")
            .addClass("d-none")
            .parent()
            .removeClass("justify-content-between")
            .addClass("justify-content-end");
    });
})();

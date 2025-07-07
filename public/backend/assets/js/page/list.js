/* global $, loadTranslationFile, document, showToast, _l,window, toastr, loadUserPermissions, hasPermission*/

(async () => {
    "use strict";
    await loadTranslationFile("admin", "common, page");
    const permissions = await loadUserPermissions();

    let currentStatus = "";
    let currentSortType = "";
    let currentLang = "";

    function deletePage(id) {
        $("#delete_id").val(id);
    }

    function initTable(
        search = "",
        status = "",
        sortType = "",
        selectedLanguageId = ""
    ) {
        $(".table-loader").show();
        $(".input-loader").show();
        $(".real-table, .real-data").addClass("d-none");

        const langId = $("#lang_id").val();

        $.ajax({
            url: "/page-builder/page-builder-list",
            type: "GET",
            data: {
                search: search,
                status: status,
                sort: sortType,
                lang_id: langId,
                language_id: selectedLanguageId,
            },
            success: function (response) {
                let tableBody = "";
                if ($.fn.DataTable.isDataTable("#page_datatable")) {
                    $("#page_datatable").DataTable().destroy();
                }

                if (response.code === 200 && response.data.length > 0) {
                    let data = response.data;

                    $.each(data, function (index, value) {
                        tableBody += `<tr>
                            <td>${value.page_title}</td>
                            <td>${value.slug}</td>
                            <td>${value.updated_date}</td>
                            <td>
                                <span class="badge ${
                                    value.status == 1
                                        ? "badge-success-transparent"
                                        : "badge-danger-transparent"
                                } d-inline-flex align-items-center badge-sm">
                                    <i class="ti ti-point-filled me-1"></i>${
                                        value.status == 1
                                            ? "Active"
                                            : "Inactive"
                                    }
                                </span>
                            </td>
                            ${
                                hasPermission(permissions, "page", "edit") ||
                                hasPermission(permissions, "page", "delete")
                                    ? `<td>
                                    <div class="dropdown">
                                        <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end p-2">
                                            ${
                                                hasPermission(
                                                    permissions,
                                                    "page",
                                                    "edit"
                                                )
                                                    ? `<li>
                                                        <button class="dropdown-item border-0 bg-white rounded-1 edit-page" data-slug="${
                                                            value.slug
                                                        }">
                                                            <i class="ti ti-edit me-1"></i>${_l(
                                                                "admin.common.edit"
                                                            )}
                                                        </button>
                                                    </li>`
                                                    : ""
                                            }
                                            ${
                                                hasPermission(
                                                    permissions,
                                                    "page",
                                                    "delete"
                                                ) && value.read !== "static"
                                                    ? `<li>
                                                        <button type="button" id="delete-page" class="dropdown-item rounded-1 delete-seat-type-btn" data-id="${value.id}" data-bs-toggle="modal" data-bs-target="#delete_page">
                                                            <i class="ti ti-trash me-1"></i>Delete
                                                        </button>
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
                            <td colspan="5" class="text-center">No Data Available</td>
                        </tr>`;
                    $(".table-footer").empty();
                }

                $("#page_datatable tbody").html(tableBody);
                if (response.data.length > 0) {
                    $("#page_datatable").DataTable({
                        ordering: true,
                        searching: false,
                        pageLength: 10,
                        lengthChange: false,
                        drawCallback: function () {
                            $(".dataTables_info").addClass("d-none");
                            $(
                                ".dataTables_wrapper .dataTables_paginate"
                            ).addClass("d-none");

                            let tableWrapper = $(this).closest(
                                ".dataTables_wrapper"
                            );
                            let info = tableWrapper.find(".dataTables_info");
                            let pagination = tableWrapper.find(
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
                            $(".table-footer")
                                .find(".dataTables_paginate")
                                .removeClass("d-none");
                        },
                        language: {
                            emptyTable: _l("admin.common.no_matching_records"),
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
                    toastr.error(error.responseJSON.message);
                } else {
                    toastr.error(
                        "An error occurred while retrieving door type."
                    );
                }
            },
            complete: function () {
                $(".table-loader").hide();
                $(".label-loader, .input-loader").hide();
                $(
                    ".real-label, .real-table, .real-data, .table-footer"
                ).removeClass("d-none");
            },
        });
    }

    $(document).ready(function () {
        initTable();

        $("#search").on("input", function () {
            let searchQuery = $(this).val().trim();
            initTable(searchQuery, currentStatus, currentSortType, currentLang); // Keep current filters
        });

        $(document).on("click", ".dataTables_paginate a", function () {
            $(".table-footer")
                .find(".dataTables_paginate")
                .removeClass("d-none");
        });

        $("#deletePage").on("submit", function (e) {
            e.preventDefault();
            $.ajax({
                url: "/admin/page/delete",
                type: "POST",
                data: {
                    id: $("#delete_id").val(),
                },
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.code === 200) {
                        showToast("success", response.message);
                        $("#delete_page").modal("hide");
                        initTable();
                    }
                },
                error: function (res) {
                    if (res.responseJSON.code === 500) {
                        showToast("success", res.responseJSON.message);
                    } else {
                        showToast(
                            "error",
                            _l("admin.common.default_delete_error")
                        );
                    }
                },
            });
        });

        $(document).on("click", "#delete-page", function () {
            let id = $(this).data("id");
            deletePage(id);
        });

        $(document).on("click", ".sort-option", function () {
            const sortType = $(this).data("sort");

            $("#sortFilter .sort-option").removeClass("active");
            $(this).addClass("active");

            currentSortType = sortType;
            $("#sortLabel").text($(this).text().trim());

            initTable(
                $("#search").val().trim(),
                currentStatus,
                currentSortType
            ); // Keep search & status
        });

        $(document).on("click", ".edit-page", function () {
            let pageSlug = $(this).data("slug");

            if (pageSlug.startsWith("pages/")) {
                pageSlug = pageSlug.replace("pages/", "");
            }

            $.ajax({
                url: "/edit/check-vehicle",
                type: "GET",
                data: { page_slug: pageSlug },
                success: function (response) {
                    if (response.exists === "yes") {
                        window.location.href = `/admin/edit-pages/${pageSlug}`;
                    } else {
                        showToast("error", "Page not found.");
                    }
                },
                error: function (){
                    showToast("error", "Failed to check page.");
                },
            });
        });

        $(document).on("change", "#language_id", function () {
            const selectedLanguageId = $(this).val();
            currentLang = selectedLanguageId;

            initTable(
                $("#search").val().trim(),
                currentStatus,
                currentSortType,
                currentLang
            );
        });

        $(document).on("click", "#statusFilter a", function (e) {
            e.preventDefault();

            $("#statusFilter a").removeClass("active");
            $(this).addClass("active");

            const status = $(this).data("status");

            currentStatus = status;

            initTable(
                $("#search").val().trim(),
                currentStatus,
                currentSortType
            );
        });
    });
})();

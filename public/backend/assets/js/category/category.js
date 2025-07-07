/* global $, loadTranslationFile, document, showToast, _l, FormData, FileReader, Image, URL*/

(async () => {
    await loadTranslationFile("admin", "common, manage");

    $(document).ready(function () {
        initTable();

        $("#categoryForm").validate({
            rules: {
                image: {
                    required: true,
                    extension: "jpeg|jpg|png",
                    filesize: 2048,
                },
                icon: {
                    required: true,
                    extension: "jpeg|jpg|png",
                    filesize: 2048,
                },
                categoryname: {
                    required: true,
                    maxlength: 100,
                },
                slug: {
                    required: true,
                    maxlength: 100,
                },
                description: {
                    required: true,
                    maxlength: 255,
                },
            },
            messages: {
                image: {
                    required: _l("admin.common.image_required"),
                    extension: _l("admin.common.image_format"),
                    filesize: _l("admin.common.image_size", { size: 2 }),
                },
                icon: {
                    required: _l("admin.common.icon_required"),
                    extension: _l("admin.common.image_format"),
                    filesize: _l("admin.common.image_size", { size: 2 }),
                },
                categoryname: {
                    required: _l("admin.common.name_required"),
                    maxlength: _l("admin.common.name_maxlength", { max: 100 }),
                },
                slug: {
                    required: _l("admin.common.slug_required"),
                    maxlength: _l("admin.common.slug_maxlength", { max: 100 }),
                },
                description: {
                    required: _l("admin.common.description_required"),
                    maxlength: _l("admin.common.description_maxlength", {
                        max: 255,
                    }),
                },
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
                let errorId = element.id + "_error";
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
                    url: "/admin/category/save",
                    data: formData,
                    enctype: "multipart/form-data",
                    processData: false,
                    contentType: false,
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    beforeSend: function () {
                        $(".submitbtn").attr("disabled", true).html(`
                        <span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span> ${_l(
                            "admin.common.saving"
                        )}..
                    `);
                    },
                    success: function (resp) {
                        $(".error-text").text("");
                        $(".form-control, .select2-container").removeClass(
                            "is-invalid is-valid"
                        );
                        $(".submitbtn")
                            .removeAttr("disabled")
                            .html(_l("admin.common.create_new"));
                        if (resp.code === 200) {
                            showToast("success", resp.message);
                            $("#add_category_modal").modal("hide");
                            $("#categoryTable").DataTable().ajax.reload();
                        }
                        initTable();
                    },
                    error: function (error) {
                        $(".error-text").text("");
                        $(".form-control, .select2-container").removeClass(
                            "is-invalid is-valid"
                        );
                        $(".submitbtn")
                            .removeAttr("disabled")
                            .html(_l("admin.common.create_new"));
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

        $("#editcategoryForm").validate({
            rules: {
                image: {
                    extension: "jpeg|jpg|png|webp",
                    filesize: 2048, // size in KB
                },
                icon: {
                    extension: "jpeg|jpg|png|svg|webp",
                    filesize: 2048,
                },
                categoryname: {
                    required: true,
                    maxlength: 100,
                },
                slug: {
                    required: true,
                    maxlength: 100,
                },
                description: {
                    required: true,
                    maxlength: 255,
                },
            },
            messages: {
                image: {
                    extension: _l("admin.common.image_format"),
                    filesize: _l("admin.common.image_size", { size: 2 }),
                },
                icon: {
                    extension: _l("admin.common.image_format"),
                    filesize: _l("admin.common.image_size", { size: 2 }),
                },
                categoryname: {
                    required: _l("admin.common.name_required"),
                    maxlength: _l("admin.common.name_maxlength", { max: 100 }),
                },
                slug: {
                    required: _l("admin.common.slug_required"),
                    maxlength: _l("admin.common.slug_maxlength", { max: 100 }),
                },
                description: {
                    required: _l("admin.common.description_required"),
                    maxlength: _l("admin.common.description_maxlength", {
                        max: 255,
                    }),
                },
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
                let errorId = element.id + "_error";
                $("#" + errorId).text("");
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onchange: function (element) {
                $(element).valid();
            },
            submitHandler: function (form) {
                let statusValue = $("#status").prop("checked") ? "on" : "off";

                let formData = new FormData(form);
                formData.append("status", statusValue);

                $.ajax({
                    type: "POST",
                    url: "/admin/category/save", // make sure this route is correct in your backend
                    data: formData,
                    enctype: "multipart/form-data",
                    processData: false,
                    contentType: false,
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    beforeSend: function () {
                        $(".submitbtn").attr("disabled", true).html(`
                        <span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span> ${_l(
                            "admin.common.saving"
                        )}..
                    `);
                    },
                    success: function (resp) {
                        $(".error-text").text("");
                        $(".form-control, .select2-container").removeClass(
                            "is-invalid is-valid"
                        );
                        $(".submitbtn")
                            .removeAttr("disabled")
                            .html(_l("admin.common.save_changes"));

                        if (resp.code === 200) {
                            showToast("success", resp.message);
                            $("#editCategoryModal").modal("hide");
                            $("#categoryTable").DataTable().ajax.reload();
                        }
                    },
                    error: function (error) {
                        $(".error-text").text("");
                        $(".form-control, .select2-container").removeClass(
                            "is-invalid is-valid"
                        );
                        $(".submitbtn")
                            .removeAttr("disabled")
                            .html(_l("admin.common.save_changes"));

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

        $.validator.addMethod(
            "filesize",
            function (value, element, param) {
                if (element.files.length === 0) return true;
                return element.files[0].size <= param * 1024;
            },
            "File size must be less than {0} KB."
        );
    });

    $("#image").on("change", function (event) {
        if ($(this).val() !== "") {
            $(this).valid();
        }

        let reader = new FileReader();
        reader.onload = function (e) {
            $("#imagePreview")
                .attr("src", e.target.result)
                .removeClass("d-none");
            $(".upload_icon").addClass("d-none");
        };
        reader.readAsDataURL(event.target.files[0]);

        let file = this.files[0];
        if (file) {
            let objectURL = URL.createObjectURL(file);

            let img = new Image();
            img.onload = function () {
                // No dimension validation here
                URL.revokeObjectURL(objectURL);
            };
            img.src = objectURL;
        }
    });

    $("#add_category").on("click", function () {
        const form = $("#categoryForm");

        form[0].reset();
        $(".error-text").text("");

        $(".form-control, .select2-container").removeClass(
            "is-invalid is-valid"
        );

        $("#imagePreview, #iconPreview").attr("src", "").addClass("d-none");
        form.find(".upload_icon").removeClass("d-none");

        $("#feature").prop("checked", true);

        $(".submitbtn").text(_l("admin.common.create_new"));
    });

    $(document).ready(function () {
        // Initial load
        initTable();

        // On search input
        $("#search").on("input", function () {
            initTable();
        });

        // On language change
        $("#language_id").on("change", function () {
            initTable();
        });

        // On sort option click
        $(".sort_by_list .dropdown-item").on("click", function () {
            const sortBy = $(this).data("sort");
            $("#sort_by_input").val(sortBy);
            $("#current_sort").text($(this).text());
            initTable();
        });
    });

    function initTable() {
        let search = $("#search").val();
        let sort_by = $("#sort_by_input").val();
        let sort_status = $("#sort_by_status").val();
        let language_id = $("#language_id").val();

        $("#categoryTable").DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "/admin/category/list",
                type: "POST",
                data: function (d) {
                    d.search = search;
                    d.sort_by = sort_by;
                    d.sort_status = sort_status;
                    d.language_id = language_id;
                },
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                error: function (error) {
                    if (error.responseJSON && error.responseJSON.code === 500) {
                        showToast("error", error.responseJSON.message);
                    } else {
                        showToast(
                            "error",
                            _l("admin.common.default_retrieve_error")
                        );
                    }
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

                    if ($("#categoryTable").DataTable().rows().count() === 0) {
                        $(".table-footer").addClass("d-none");
                    } else {
                        $(".table-footer").removeClass("d-none");
                    }
                },
            },
            columns: [
                { data: "categoryname" },
                { data: "slug" },
                {
                    data: "status",
                    render: function (data) {
                        return data == "active"
                            ? `<span class="badge bg-primary">${_l(
                                  "admin.common.active"
                              )}</span>`
                            : `<span class="badge bg-danger">${_l(
                                  "admin.common.inactive"
                              )}</span>`;
                    },
                },
                {
                    data: "feature",
                    render: function (data) {
                        return data == 1
                            ? `<span class="badge bg-primary">${_l(
                                  "admin.common.active"
                              )}</span>`
                            : `<span class="badge bg-danger">${_l(
                                  "admin.common.inactive"
                              )}</span>`;
                    },
                },
                {
                    data: "id",
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                        <div class="dropdown">
                            <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end p-2">

                               <li>
                                    <a class="dropdown-item rounded-1" href="javascript:void(0);"
                                    onclick="editCategory(
                                            ${row.id},
                                            '${row.categoryname}',
                                            '${row.slug}',
                                            '${row.description}',
                                            '${row.image}',
                                            '${row.icon}',
                                            ${row.feature}, 
                                            '${row.status}',
                                            '${row.language_id}'
                                    );">
                                    <i class="ti ti-edit me-1"></i>${_l(
                                        "admin.common.edit"
                                    )}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-1" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_modal" onclick="deleteCategory(${
                                        row.id
                                    });"><i class="ti ti-trash me-1"></i>${_l(
                            "admin.common.delete"
                        )}</a>
                                </li>
                            </ul>
                        </div>`;
                    },
                },
            ],
            order: [[0, "asc"]],
            ordering: true,
            searching: false,
            pageLength: 10,
            lengthChange: false,
            responsive: true,
            autoWidth: false,
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
                zeroRecords: _l("admin.common.no_matching_records"),
                paginate: {
                    first: _l("admin.common.first"),
                    last: _l("admin.common.last"),
                    next: _l("admin.common.next"),
                    previous: _l("admin.common.previous"),
                },
            },
            drawCallback: function () {
                $(".dataTables_info").addClass("d-none");
                $(".dataTables_wrapper .dataTables_paginate").addClass(
                    "d-none"
                );

                let tableWrapper = $(this).closest(".dataTables_wrapper");
                let info = tableWrapper.find(".dataTables_info");
                let pagination = tableWrapper.find(".dataTables_paginate");

                $(".table-footer")
                    .empty()
                    .append(
                        $(
                            '<div class="d-flex justify-content-between align-items-center w-100"></div>'
                        )
                            .append(
                                $('<div class="datatable-info"></div>').append(
                                    info.clone(true)
                                )
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
        });
    }

    $(document).on("click", ".dataTables_paginate a", function () {
        $(".table-footer").find(".dataTables_paginate").removeClass("d-none");
    });

    $(document).on("keyup", "#search", function () {
        $("#categoryTable").DataTable().ajax.reload();
    });

    $(document).on("click", ".sort_by_list .dropdown-item", function () {
        let sortBy = $(this).data("sort");
        $("#sort_by_input").val(sortBy);
        $("#current_sort").text(
            sortBy.charAt(0).toUpperCase() + sortBy.slice(1).toLowerCase()
        );
        $(".sort_by_list .dropdown-item").removeClass("active");
        $(this).addClass("active");
        $("#categoryTable").DataTable().ajax.reload();
    });

    $("#sort_by_date").on("change", function () {
        let sort_by_date = $(this).val();
        initTable(sort_by_date);
    });

    $(document).on("click", "#apply_filter", function () {
        $("#categoryTable").DataTable().ajax.reload();
    });

    $(document).on("click", "#reset_filter", function () {
        $("#language_list input:checkbox").prop("checked", false);
        $("#sort_by_date").val("").trigger("change");
        $("#sort_by_input").val("");
        $("#categoryTable").DataTable().ajax.reload();
    });
})();

$("#deleteCategoryForm").on("submit", function (e) {
    e.preventDefault();
    $.ajax({
        url: "/admin/category/delete",
        type: "POST",
        data: {
            id: $("#delete_id").val(),
        },
        headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            if (response.code === 200) {
                showToast("success", response.message);
                $("#delete_modal").modal("hide");
                $("#categoryTable").DataTable().ajax.reload();
            }
        },
        error: function (res) {
            if (res.responseJSON.code === 500) {
                showToast("error", res.responseJSON.message);
            } else {
                showToast("error", _l("admin.common.default_delete_error"));
            }
        },
    });
});

function editCategory(
    id,
    categoryname,
    slug,
    description,
    image,
    icon,
    feature,
    status,
    languageId,
    sourceType
) {
    $("#editCategoryModal #id").val(id);
    $("#editCategoryModal #categoryname").val(categoryname);
    $("#editCategoryModal #slug").val(slug);
    $("#editCategoryModal #description").val(description);
    $("#editCategoryModal #language_id").val(languageId).trigger("change");
    $("#editCategoryModal #category_type").val(sourceType).trigger("change");

    if (image) {
        let imagePath = "/storage/" + image.replace(/^\/+|\/+$/g, "");
        $("#editCategoryModal #editImagePreview")
            .removeClass("d-none")
            .attr("src", imagePath);
    } else {
        $("#editCategoryModal #editImagePreview")
            .addClass("d-none")
            .attr("src", "");
    }

    if (icon) {
        let iconPath = "/storage/" + icon.replace(/^\/+|\/+$/g, "");
        $("#editCategoryModal #editIconPreview")
            .removeClass("d-none")
            .attr("src", iconPath);
    } else {
        $("#editCategoryModal #editIconPreview")
            .addClass("d-none")
            .attr("src", "");
    }

    $("#editCategoryModal #feature").prop("checked", feature == 1);
    $("#editCategoryModal #status").prop("checked", status === "active");

    $("#editCategoryModal").modal("show");
}

function previewImage(event, previewId, requiredWidth, requiredHeight) {
    const file = event.target.files[0];
    const reader = new FileReader();
    const preview = $("#" + previewId);
    const frames = preview.closest(".frames");

    if (file) {
        if (file.size > 5 * 1024 * 1024) {
            showToast("error", "Image must be within 5MB.");
            $(event.target).val("");
            preview.addClass("d-none");
            frames.removeClass("d-none");
            return;
        }

        reader.onload = function (e) {
            const img = new Image();
            img.src = e.target.result;

            img.onload = function () {
                if (
                    (requiredWidth && img.width !== requiredWidth) ||
                    (requiredHeight && img.height !== requiredHeight)
                ) {
                    showToast(
                        "error",
                        `Image dimensions must be ${requiredWidth}x${requiredHeight} pixels.`
                    );
                    $(event.target).val("");
                    preview.addClass("d-none");
                    return;
                }

                preview.attr("src", e.target.result).removeClass("d-none");
                frames.removeClass("d-none");
            };
        };

        reader.readAsDataURL(file);
    }
}


function deleteCategory(id){
    $("#delete_id").val(id);
}
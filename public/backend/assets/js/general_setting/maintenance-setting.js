/* global $, loadTranslationFile, document, FormData, Image, FileReader, showToast, _l */
(async () => {
    "use strict";
    await loadTranslationFile("admin", "general_settings,common");

    $(document).ready(function () {
        $("#maintenanceSettingsForm").validate({
            rules: {
                maintenance_image: {
                    required: false,
                    accept: "image/*",
                },
                maintenance_description: {
                    required: true,
                    minlength: 10,
                },
                maintenance_status: {
                    required: false,
                },
            },
            messages: {
                maintenance_image: {
                    required: _l("admin.general_settings.please_upload_image"),
                    accept: _l(
                        "admin.general_settings.favicon_image_resolution"
                    ),
                },
                maintenance_description: {
                    required: _l("admin.general_settings.enter_description"),
                    minlength: _l(
                        "admin.general_settings.description_characters"
                    ),
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
                let errorId = $(element).attr("id") + "_error";
                $("#" + errorId).text("");
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onchange: function (element) {
                $(element).valid();
            },
            submitHandler: function (form) {
                let maintenanceData = new FormData(form);

                let maintenanceStatus = $("#maintenance_status").prop("checked")
                    ? 1
                    : 0;
                maintenanceData.set("maintenance_status", maintenanceStatus);

                $.ajax({
                    type: "POST",
                    url: "/admin/settings/maintenance/update",
                    data: maintenanceData,
                    processData: false,
                    contentType: false,
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr(
                            "content"
                        ),
                    },
                    beforeSend: function () {
                        $(".btn-primary").attr("disabled", true).html(`
                            <span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span> ${_l(
                                "admin.common.saving"
                            )}..
                        `);
                    },
                    complete: function () {
                        $(".btn-primary")
                            .attr("disabled", false)
                            .html(_l("admin.common.save_changes"));
                    },
                    success: function (resp) {
                        if (resp.code === 200) {
                            loadMaintenanceSettings();
                            showToast("success", resp.message);
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

        loadMaintenanceSettings();

        function loadMaintenanceSettings() {
            $.ajax({
                url: "/admin/settings/company/list",
                type: "POST",
                data: { group_id: 4 },
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.code === 200) {
                        const settings = response.data;

                        settings.forEach((setting) => {
                            const element = $("#" + setting.key);

                            if (setting.key === "maintenance_image") {
                                $("#maintenance_photo_preview")
                                    .attr("src", setting.value)
                                    .show();
                            } else if (
                                setting.key === "maintenance_description"
                            ) {
                                $("#maintenance_description").summernote(
                                    "code",
                                    setting.value
                                );
                            } else if (setting.key === "maintenance_status") {
                                element.prop("checked", setting.value == 1);
                            } else if (element.length) {
                                element.val(setting.value);
                            }
                        });
                    }
                },
                error: function () {
                    showToast(
                        "error",
                        _l("admin.common.default_retrieve_error")
                    );
                },
                complete: function () {
                    $(".label-loader, .input-loader, .card-loader").hide();
                    $(".real-label, .real-input, .real-card").removeClass(
                        "d-none"
                    );
                },
            });
        }
    });

    $(document).on("click", ".remove-maintenance-image", function () {
        const preview = document.getElementById("maintenance_photo_preview");
        preview.src = $(this).data("default_image");
        $("#maintenance_image").val("");
        $("#is_remove_image").val(1);
    });

    $("#maintenance_image").on("change", function (event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        const preview = $("#maintenance_photo_preview");
        $("#is_remove_image").val(0);

        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                showToast("error", _l("admin.general_settings.image_5mb"));
                $(this).val("");
                return;
            }
            reader.onload = function (e) {
                const img = new Image();
                img.src = e.target.result;

                img.onload = function () {                   
                    preview.attr("src", e.target.result).show();
                    $(".frames").removeClass("d-none");                   
                };
            };

            reader.readAsDataURL(file);
        }
    });
})();

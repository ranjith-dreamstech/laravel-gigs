/* global $, loadTranslationFile, document, FormData, Image, FileReader, showToast, _l */
(async () => {
    "use strict";
    await loadTranslationFile("admin", "general_settings,common");

    $(document).ready(function () {
        $("#logoSettingForm").on("change", function (event) {
            const target = event.target;

            if (target.matches("input[type=\"file\"][accept=\"image/*\"]")) {
                let requiredWidth, requiredHeight, previewId;

                // Match preview ID to actual image IDs
                switch (target.id) {
                    case "logo_image":
                        previewId = "logo_photo_preview";
                        requiredWidth = 151;
                        requiredHeight = 26;
                        break;
                    case "favicon_image":
                        previewId = "favicon_photo_preview";
                        requiredWidth = 128;
                        requiredHeight = 128;
                        break;
                    case "small_image":
                        previewId = "small_icon_photo_preview";
                        requiredWidth = 35;
                        requiredHeight = 35;
                        break;
                    case "dark_logo":
                        previewId = "dark_logo_preview";
                        requiredWidth = 151;
                        requiredHeight = 26;
                        break;
                    default:
                        return;
                }

                previewImage(event, previewId, requiredWidth, requiredHeight);
            }
        });
        loadLogoSettings();

        $("input[type=\"file\"]").on("change", function () {
            $(this).valid();
        });

        $("#logoSettingForm").validate({
            rules: {
                logo_image: {
                    extension: "jpeg|jpg|png|svg",
                },
                favicon_image: {
                    extension: "jpeg|jpg|png|svg",
                },
                small_image: {
                    extension: "jpeg|jpg|png|svg",
                },
                dark_logo: {
                    extension: "jpeg|jpg|png|svg",
                },
            },
            messages: {
                logo_image: {
                    extension: _l(
                        "admin.general_settings.favicon_image_resolution"
                    ),
                },
                favicon_image: {
                    extension: _l(
                        "admin.general_settings.logo_image_extension_resolution"
                    ),
                },
                small_image: {
                    extension: _l(
                        "admin.general_settings.small_image_extension_resolution"
                    ),
                },
                dark_logo: {
                    extension: _l(
                        "admin.general_settings.dark_logo_extension_resolution"
                    ),
                },
            },
            errorPlacement: function (error, element) {
                const errorId = element.attr("id") + "_error";
                $("#" + errorId).text(error.text());
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
                const errorId = element.id + "_error";
                $("#" + errorId).text("");
            },
            submitHandler: function (form) {
                const formData = new FormData(form);
                $(".btn-primary")
                    .text(_l("admin.general_settings.please_wait"))
                    .prop("disabled", true);

                $.ajax({
                    type: "POST",
                    url: "/admin/settings/logo/store",
                    data: formData,
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
                            loadLogoSettings();
                            showToast("success", resp.message);
                        } else {
                            showToast("error", resp.message);
                        }
                    },
                    error: function (error) {
                        $(".error-text").text("");
                        $(".form-control").removeClass("is-invalid is-valid");

                        if (error.responseJSON?.code === 422) {
                            $.each(
                                error.responseJSON.errors,
                                function (key, val) {
                                    $("#" + key).addClass("is-invalid");
                                    $("#" + key + "_error").text(val[0]);
                                }
                            );
                        } else {
                            showToast(
                                "error",
                                error.responseJSON?.message ||
                                    _l("admin.common.default_retrieve_error")
                            );
                        }
                    },
                });
            },
        });
    });
    function loadLogoSettings() {
        $.ajax({
            url: "/admin/settings/company/list",
            type: "POST",
            data: { group_id: 16 },
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
            },
            success: function (response) {
                if (response.code === 200) {
                    const settings = response.data;

                    settings.forEach((setting) => {
                        const element = $("#" + setting.key);

                        switch (setting.key) {
                            case "logo_image":
                                $("#logo_photo_preview")
                                    .attr("src", setting.value)
                                    .show();
                                break;
                            case "favicon_image":
                                $("#favicon_photo_preview")
                                    .attr("src", setting.value)
                                    .show();
                                break;
                            case "small_image":
                                $("#small_icon_photo_preview")
                                    .attr("src", setting.value)
                                    .show();
                                break;
                            case "dark_logo":
                                $("#dark_logo_preview")
                                    .attr("src", setting.value)
                                    .show();
                                break;
                            default:
                                if (element.length) {
                                    element.val(setting.value);
                                }
                        }
                    });

                    $(".real-label").removeClass("d-none");
                    $(".label-loader, .input-loader, .image-loader").addClass(
                        "d-none"
                    );
                }
            },
            error: function (xhr) {
                showToast("error", xhr.responseJSON.message);
            },
            complete: function () {
                $(".label-loader, .input-loader, .card-loader").hide();
                $(".real-label, .real-input, .real-card").removeClass("d-none");
            },
        });
    }

    function previewImage(event, previewId, requiredWidth, requiredHeight) {
        const file = event.target.files[0];
        const reader = new FileReader();
        const preview = $("#" + previewId);

        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                showToast("error", _l("admin.general_settings.image_5mb"));
                $(event.target).val("");
                return;
            }

            reader.onload = function (e) {
                const img = new Image();
                img.src = e.target.result;

                img.onload = function () {
                    if (
                        img.width === requiredWidth &&
                        img.height === requiredHeight
                    ) {
                        preview.attr("src", e.target.result).show();
                        preview.closest(".frames").removeClass("d-none");
                    } else {
                        showToast(
                            "error",
                            `${_l(
                                "admin.general_settings.image_dimension_must_be"
                            )} ${requiredWidth}x${requiredHeight} ${_l(
                                "admin.general_settings.pixels"
                            )}.`
                        );
                        $(event.target).val("");
                    }
                };
            };

            reader.readAsDataURL(file);
        }
    }
})();

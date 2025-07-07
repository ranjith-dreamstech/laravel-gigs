/* global $, loadTranslationFile, document, FormData, showToast, _l */
(async () => {
    "use strict";
    await loadTranslationFile("admin", "cms,common");

    $(document).ready(function () {
        $("#language").on("change", function () {
            loadCopyRightSettings($(this).val());
        });
        $(".summernote").summernote({
            height: 300,
            placeholder: _l("admin.cms.enter_your_description"),
            toolbar: [
                ["style", ["bold", "italic", "underline", "clear"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["insert", ["link", "picture", "video"]],
                ["view", ["fullscreen", "codeview", "help"]],
            ],
        });

        $("#copyRightForm").validate({
            rules: {
                copy_right_description: {
                    required: true,
                    minlength: 10,
                },
                language: {
                    required: true,
                },
            },
            messages: {
                copy_right_description: {
                    required: _l("admin.cms.description_required"),
                    minlength: _l("admin.cms.description_minlength"),
                },
                language: {
                    required: _l("admin.cms.language_required"),
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
                let copyRightData = new FormData(form);

                $.ajax({
                    type: "POST",
                    url: "/admin/copyright/update",
                    data: copyRightData,
                    processData: false,
                    contentType: false,
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr(
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
                    complete: function () {
                        $(".submitbtn")
                            .attr("disabled", false)
                            .html(_l("admin.common.save_changes"));
                    },
                    success: function (resp) {
                        if (resp.code === 200) {
                            loadCopyRightSettings();
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

        loadCopyRightSettings();
    });

    function loadCopyRightSettings(languageId = null) {
        const selectedLanguageId = languageId || $("#language").val();
        $.ajax({
            url: "/admin/copyright/list",
            type: "POST",
            data: {
                group_id: 20,
                language_id: selectedLanguageId,
            },
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
            },
            success: function (response) {
                const setting = response.data;

                if (setting === null) {
                    $("#copy_right_description").summernote("code", "");
                    $("#profile_photo_preview").attr("src", "").hide();
                    return;
                }

                if (setting.key === "maintenance_image" && setting.value) {
                    const imageUrl = `/storage/${setting.value}`;
                    $("#profile_photo_preview").attr("src", imageUrl).show();
                }

                if (setting.key === `copy_right_${setting.language_id}`) {
                    $("#copy_right_description").summernote(
                        "code",
                        setting.value
                    );
                } else {
                    const element = $("#" + setting.key);
                    if (element.length) {
                        element.val(setting.value);
                    }
                }
            },
            error: function () {
                showToast("error", _l("admin.common.default_retrieve_error"));
            },
            complete: function () {
                $(".label-loader, .input-loader, .card-loader").hide();
                $(".real-label, .real-input, .real-card").removeClass("d-none");
            },
        });
    }
})();

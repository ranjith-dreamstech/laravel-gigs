/* global $, loadTranslationFile, window, FormData, setTimeout, document, showToast, _l */
"use strict";

(async () => {
    await loadTranslationFile("web", "home, common");

    $("#contactForm").validate({
        rules: {
            contact_name: {
                required: true,
                minlength: 3,
                maxlength: 30
            },
            contact_email: {
                required: true,
                email: true,
                maxlength: 50
            },
            contact_phone: {
                required: true,
                minlength: 10,
                maxlength: 15
            },
            contact_message: {
                required: true,
                minlength: 3
            }
        },
        messages: {
            contact_name: {
                required: _l("web.home.name_required"),
                minlength: _l("web.home.name_minlength_3"),
                maxlength: _l("web.home.name_maxlength_30")
            },
            contact_email: {
                required: _l("web.home.email_required"),
                email: _l("web.home.valid_email"),
                maxlength: _l("web.home.email_max_length")
            },
            contact_phone: {
                required: _l("web.home.phone_number_required"),
                minlength: _l("web.home.phone_number_minlength"),
                maxlength: _l("web.home.phone_number_maxlength")
            },
            contact_message: {
                required: _l("web.home.message_required"),
                minlength: _l("web.home.message_minlength")
            }
        },
        errorPlacement: function (error, element) {
            const errorId = `${element.attr("id")}_error`;
            $(`#${errorId}`).text(error.text());
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
            const errorId = `${element.id}_error`;
            $(`#${errorId}`).text("");
        },
        onkeyup: function (element) {
            $(element).valid();
        },
        onchange: function (element) {
            $(element).valid();
        },
        submitHandler: function () {
            const contactFormData = new FormData();
            contactFormData.append("name", $("#contact_name").val());
            contactFormData.append("email", $("#contact_email").val());
            contactFormData.append("phone_number", $("#international_phone_number").val());
            contactFormData.append("message", $("#contact_message").val());
            contactFormData.append("_token", $("meta[name='csrf-token']").attr("content"));

            const $submitBtn = $("#contactForm .submitbtn");
            $submitBtn.html(`<span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span> ${_l("web.common.saving")}..`);
            $submitBtn.prop("disabled", true);

            $.ajax({
                type: "POST",
                url: "/api/contact-message/save",
                data: contactFormData,
                processData: false,
                contentType: false,
                success: function (resp) {
                    if (resp.code === 200) {
                        showToast("success", resp.message);
                        setTimeout(() => {
                            window.location.href = "/";
                        }, 3000);
                    }
                    $("#contactForm")[0].reset();
                    $submitBtn.text(_l("web.home.send_message")).prop("disabled", false);
                },
                error: function (xhr) {
                    $(".error-text").text("");
                    $(".form-control").removeClass("is-invalid is-valid");
                    if (xhr.responseJSON?.code === 422) {
                        $.each(xhr.responseJSON.errors, function (key, val) {
                            $(`#${key}`).addClass("is-invalid");
                            $(`#${key}_error`).text(val[0]);
                        });
                    } else {
                        showToast("error", xhr.responseJSON?.message || "An unexpected error occurred.");
                    }
                    $submitBtn.text(_l("web.home.send_message")).prop("disabled", false);
                }
            });
        }
    });
})();

$(document).ready(function () {
    const $userPhoneInput = $("#contact_phone");
    const $intlPhoneInput = $("#international_phone_number");
    const $userProfileForm = $("#contactForm");

    if ($userPhoneInput.length && $userProfileForm.length) {
        const iti = window.intlTelInput($userPhoneInput[0], {
            utilsScript: `${window.location.origin}/frontend/assets/plugins/intltelinput/js/utils.js`,
            separateDialCode: true
        });

        $userPhoneInput.addClass("iti");
        $userPhoneInput.parent().addClass("intl-tel-input");

        $userPhoneInput.on("keyup countrychange", function () {
            $intlPhoneInput.val(iti.getNumber());
        });
    }
});
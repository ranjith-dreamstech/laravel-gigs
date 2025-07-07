/* global $, loadTranslationFile, intlTelInput, setTimeout, document, showToast, _l, window, FormData, FileReader, Image */
document.addEventListener("DOMContentLoaded", function () {
    const userPhoneInput = document.querySelector(".user_phone_number");
    const intlPhoneInput = document.querySelector("#international_phone_number");
    const userProfileForm = document.querySelector("#profileForm");

    if (userPhoneInput && userProfileForm) {
        const iti = intlTelInput(userPhoneInput, {
            utilsScript: `${window.location.origin}/frontend/assets/plugins/intltelinput/js/utils.js`,
            separateDialCode: true,
        });

        userPhoneInput.classList.add("iti");
        userPhoneInput.parentElement.classList.add("intl-tel-input");

        userProfileForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const intlNumber = iti.getNumber();
            if (intlNumber) {
                intlPhoneInput.value = intlNumber;
            }
        });
    }

});

(async () => {
    await loadTranslationFile("web", "user, common");

    $(document).ready(function () {
        setTimeout(function () {
            $("#country").trigger("change");
        }, 100);

        $("#country").on("change", function () {
            let id = $(this).val();
            if (id) {
                fetchStatesByCountry(id);
            } else {
                $("#state").empty();
                $("#state").append("<option value=\"\">" + _l("web.common.select") + "</option>");
                $("#city").empty();
                $("#city").append("<option value=\"\">" + _l("web.common.select") + "</option>");
            }
        });

        $("#state").on("change", function () {
            let id = $(this).val();
            if (id) {
                fetchCitiesByState(id);
            } else {
                $("#city").empty();
                $("#city").append("<option value=\"\">" + _l("web.common.select") + "</option>");
            }
        });
    });

    $("#profileForm").validate({
        rules: {
            first_name: {
                required: true,
                maxlength: 30,
                pattern: /^[A-Za-z\s]+$/
            },
            last_name: {
                required: true,
                maxlength: 30,
                pattern: /^[A-Za-z\s]+$/
            },
            image: {
                required: function () {
                    return $("#user_id").val() === "";
                },
                extension: "jpeg|jpg|png",
                filesize: 2048
            },
            phone_number: {
                required: true,
                minlength: 10,
                maxlength: 15
            },
            email: {
                required: true,
                email: true
            },
            address: {
                required: true,
                maxlength: 150
            },
            postal_code: {
                required: true,
                maxlength: 6
            },
            country: { required: true },
            state: { required: true },
            city: { required: true },
            current_email: {
                email: true,
                equalTo: {
                    param: "#email",
                    depends: function () {
                        return $("#current_email").val() !== "";
                    }
                }
            },
            new_email: {
                required: function () {
                    return $("#current_email").val() !== "";
                },
                email: true,
                notEqualTo: "#current_email"
            },
            confirm_email: {
                required: function () {
                    return $("#current_email").val() !== "";
                },
                email: true,
                equalTo: "#new_email"
            }
        },
        messages: {
            first_name: {
                required: _l("web.user.first_name_required"),
                maxlength: _l("web.user.first_name_maxlength"),
                pattern: _l("web.user.alpha_space_allowed")
            },
            last_name: {
                required: _l("web.user.last_name_required"),
                maxlength: _l("web.user.last_name_maxlength"),
                pattern: _l("web.user.alpha_space_allowed")
            },
            image: {
                required: _l("web.user.image_required"),
                extension: _l("web.user.image_format"),
                filesize: _l("web.user.image_size")
            },
            phone_number: {
                required: _l("web.user.phone_number_required"),
                minlength: _l("web.user.phone_number_minlength"),
                maxlength: _l("web.user.phone_number_maxlength")
            },
            email: {
                required: _l("web.user.email_required"),
                email: _l("web.user.email_valid")
            },
            address: {
                required: _l("web.user.address_required"),
                maxlength: _l("web.user.address_maxlength")
            },
            postal_code: {
                required: _l("web.user.postal_code_required"),
                maxlength: _l("web.user.postal_code_maxlength")
            },
            country: { required: _l("web.user.country_required") },
            state: { required: _l("web.user.state_required") },
            city: { required: _l("web.user.city_required") },
            current_email: {
                email: _l("web.user.email_valid"),
                equalTo: _l("web.user.current_email_equal_to_existing_email")
            },
            new_email: {
                required: _l("web.user.new_email_required"),
                email: _l("web.user.email_valid"),
                notEqualTo: _l("web.user.new_email_not_equal_to_current_email")
            },
            confirm_email: {
                required: _l("web.user.confirm_email_required"),
                email: _l("web.user.email_valid"),
                equalTo: _l("web.user.confirm_email_equal_to_new_email")
            }
        },
        errorPlacement: function (error, element) {
            const errorId = element.attr("id") + "_error";
            $("#" + errorId).text(error.text());
        },
        highlight: function (element) {
            if ($(element).hasClass("select2-hidden-accessible")) {
                $(element).next(".select2-container").addClass("is-invalid").removeClass("is-valid");
            }
            $(element).addClass("is-invalid").removeClass("is-valid");
            $("#" + element.id).siblings("span").addClass("me-3");
        },
        unhighlight: function (element) {
            if ($(element).hasClass("select2-hidden-accessible")) {
                $(element).next(".select2-container").removeClass("is-invalid").addClass("is-valid");
            }
            $(element).removeClass("is-invalid").addClass("is-valid");
            $("#" + element.id).siblings("span").addClass("me-3");
            $("#" + element.id + "_error").text("");
        },
        onkeyup: function (element) {
            $(element).valid();
            $("#" + element.id).siblings("span").removeClass("me-3");
        },
        onchange: function (element) {
            $(element).valid();
            $("#" + element.id).siblings("span").removeClass("me-3");
        },
        submitHandler: function (form) {
            const formData = new FormData(form);
            formData.append("phone_number", $("#international_phone_number").val());

            $.ajax({
                type: "POST",
                url: "/user/save-profile",
                data: formData,
                enctype: "multipart/form-data",
                processData: false,
                contentType: false,
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                },
                beforeSend: function () {
                    $(".profile_savebtn")
                        .attr("disabled", true)
                        .html(
                            `<span class="spinner-border spinner-border-sm align-middle me-1" role="status" aria-hidden="true"></span> ${_l("web.common.saving")}..`
                        );
                },
                success: function (resp) {
                    $(".error-text").text("");
                    $(".form-control, .select2-container").removeClass("is-invalid is-valid");
                    $(".profile_savebtn").removeAttr("disabled").html(_l("web.common.save_changes"));

                    if (resp.code === 200) {
                        showToast("success", resp.message);
                        if ($("#new_email").val() !== "") {
                            $("#email").val($("#new_email").val());
                        }
                        $("#current_email, #new_email, #confirm_email").val("");
                    }
                },
                error: function (error) {
                    $(".error-text").text("");
                    $(".form-control, .select2-container").removeClass("is-invalid is-valid");
                    $(".profile_savebtn").removeAttr("disabled").html(_l("web.common.save_changes"));

                    if (error.responseJSON.code === 422) {
                        $.each(error.responseJSON.errors, function (key, val) {
                            $("#" + key).addClass("is-invalid");
                            $("#" + key + "_error").text(val[0]);
                        });
                    } else {
                        showToast("error", error.responseJSON.message);
                    }
                }
            });
        }
    });

    $.validator.addMethod("filesize", function (value, element, param) {
        if (element.files.length === 0) return true;
        return element.files[0].size <= param * 1024;
    }, "File size must be less than {0} KB.");

    $(document).on("change", "#image", function (event) {
        const fileInput = $(this);
        const file = event.target.files[0];

        if (fileInput.val() !== "") {
            fileInput.valid();
        }

        if (!file) return;

        const reader = new FileReader();

        reader.onload = function (e) {
            const imageSrc = e.target.result;

            $("#imagePreview")
                .attr("src", imageSrc)
                .removeClass("d-none");

            const img = new Image();
            img.src = imageSrc;

            img.onload = function () {
                const width = img.width;
                const height = img.height;

                if (width <= 150 || height <= 150) {
                    $("#image_error").text(_l("web.user.image_pixel"));
                } else {
                    $("#image_error").text("");
                }
            };
        };

        reader.readAsDataURL(file);
    });

    $("#paypalForm").validate({
        rules: {
            paypal_email: {
                required: true,
                email: true
            },
            paypal_password: {
                required: true,
                maxlength: 150
            }
        },
        messages: {
            paypal_email: {
                required: _l("web.user.email_required"),
                email: _l("web.user.email_valid")
            },
            paypal_password: {
                required: _l("web.user.password_required"),
                maxlength: _l("web.user.password_maxlength")
            }
        },
        errorPlacement: function (error, element) {
            const errorId = element.attr("id") + "_error";
            $("#" + errorId).text(error.text());
        },
        highlight: function (element) {
            if ($(element).hasClass("select2-hidden-accessible")) {
                $(element).next(".select2-container").addClass("is-invalid").removeClass("is-valid");
            }
            $(element).addClass("is-invalid").removeClass("is-valid");
            $("#" + element.id).siblings("span").addClass("me-3");
        },
        unhighlight: function (element) {
            if ($(element).hasClass("select2-hidden-accessible")) {
                $(element).next(".select2-container").removeClass("is-invalid").addClass("is-valid");
            }
            $(element).removeClass("is-invalid").addClass("is-valid");
            $("#" + element.id).siblings("span").addClass("me-3");
            $("#" + element.id + "_error").text("");
        },
        onkeyup: function (element) {
            $(element).valid();
            $("#" + element.id).siblings("span").removeClass("me-3");
        },
        onchange: function (element) {
            $(element).valid();
            $("#" + element.id).siblings("span").removeClass("me-3");
        },
        submitHandler: function (form) {
            const formData = new FormData(form);
            const saveBtn = $(".paypal_savebtn");

            saveAccountSettings(formData, saveBtn);
        }
    });

    $("#stripeForm").validate({
        rules: {
            stripe_email: {
                required: true,
                email: true
            },
            stripe_password: {
                required: true,
                maxlength: 150
            }
        },
        messages: {
            stripe_email: {
                required: _l("web.user.email_required"),
                email: _l("web.user.email_valid")
            },
            stripe_password: {
                required: _l("web.user.password_required"),
                maxlength: _l("web.user.password_maxlength")
            }
        },
        errorPlacement: function (error, element) {
            const errorId = element.attr("id") + "_error";
            $("#" + errorId).text(error.text());
        },
        highlight: function (element) {
            if ($(element).hasClass("select2-hidden-accessible")) {
                $(element).next(".select2-container").addClass("is-invalid").removeClass("is-valid");
            }
            $(element).addClass("is-invalid").removeClass("is-valid");
            $("#" + element.id).siblings("span").addClass("me-3");
        },
        unhighlight: function (element) {
            if ($(element).hasClass("select2-hidden-accessible")) {
                $(element).next(".select2-container").removeClass("is-invalid").addClass("is-valid");
            }
            $(element).removeClass("is-invalid").addClass("is-valid");
            $("#" + element.id).siblings("span").addClass("me-3");
            $("#" + element.id + "_error").text("");
        },
        onkeyup: function (element) {
            $(element).valid();
            $("#" + element.id).siblings("span").removeClass("me-3");
        },
        onchange: function (element) {
            $(element).valid();
            $("#" + element.id).siblings("span").removeClass("me-3");
        },
        submitHandler: function (form) {
            const formData = new FormData(form);
            const saveBtn = $(".stripe_savebtn");

            saveAccountSettings(formData, saveBtn);
        }
    });

    $("#bankForm").validate({
        rules: {
            account_holder_name: {
                required: true,
                maxlength: 50,
                pattern: /^[A-Za-z\s]+$/
            },
            bank_name: {
                required: true,
                maxlength: 100
            },
            ifsc_code: {
                required: true,
                maxlength: 100
            },
            account_number: {
                required: true,
                maxlength: 100
            }
        },
        messages: {
            account_holder_name: {
                required: _l("web.user.account_holder_name_required"),
                maxlength: _l("web.user.account_holder_name_maxlength"),
                pattern: _l("web.user.alpha_space_allowed")
            },
            bank_name: {
                required: _l("web.user.bank_name_required"),
                maxlength: _l("web.user.bank_name_maxlength")
            },
            ifsc_code: {
                required: _l("web.user.ifsc_code_required"),
                maxlength: _l("web.user.ifsc_code_maxlength")
            },
            account_number: {
                required: _l("web.user.account_number_required"),
                maxlength: _l("web.user.account_number_maxlength")
            }
        },
        errorPlacement: function (error, element) {
            const errorId = element.attr("id") + "_error";
            $("#" + errorId).text(error.text());
        },
        highlight: function (element) {
            if ($(element).hasClass("select2-hidden-accessible")) {
                $(element).next(".select2-container").addClass("is-invalid").removeClass("is-valid");
            }
            $(element).addClass("is-invalid").removeClass("is-valid");
            $("#" + element.id).siblings("span").addClass("me-3");
        },
        unhighlight: function (element) {
            if ($(element).hasClass("select2-hidden-accessible")) {
                $(element).next(".select2-container").removeClass("is-invalid").addClass("is-valid");
            }
            $(element).removeClass("is-invalid").addClass("is-valid");
            $("#" + element.id).siblings("span").addClass("me-3");
            $("#" + element.id + "_error").text("");
        },
        onkeyup: function (element) {
            $(element).valid();
            $("#" + element.id).siblings("span").removeClass("me-3");
        },
        onchange: function (element) {
            $(element).valid();
            $("#" + element.id).siblings("span").removeClass("me-3");
        },
        submitHandler: function (form) {
            const formData = new FormData(form);
            const saveBtn = $(".bank_savebtn");

            saveAccountSettings(formData, saveBtn);
        }
    });
    
}) ();


$("#phone_number").on("input", function () {
    $(this).val($(this).val().replace(/[^0-9]/g, ""));
});

$("#postal_code").on("input", function () {
    let value = $(this).val().replace(/[^a-zA-Z0-9]/g, "");
    $(this).val(value.substring(0, 6));
});

$("#ifsc_code").on("input", function () {
    $(this).val($(this).val().replace(/[^0-9A-Za-z]/g, ""));
});

$("#account_number").on("input", function () {
    $(this).val($(this).val().replace(/[^0-9]/g, ""));
});


function saveAccountSettings(formData, saveBtn) {
    $.ajax({
        type: "POST",
        url: "/user/save-account-settings",
        data: formData,
        enctype: "multipart/form-data",
        processData: false,
        contentType: false,
        headers: {
            "Accept": "application/json",
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
        },
        beforeSend: function () {
            $(saveBtn)
                .attr("disabled", true)
                .html(
                    `<span class="spinner-border spinner-border-sm align-middle me-1" role="status" aria-hidden="true"></span> ${_l("web.common.saving")}..`
                );
        },
        complete: function () {
            $(saveBtn)
                .attr("disabled", false)
                .html(_l("web.common.save_changes"));
        },
        success: function (resp) {
            $(".error-text").text("");
            $(".form-control, .select2-container").removeClass("is-invalid is-valid");

            if (resp.code === 200) {
                showToast("success", resp.message);
            }
        },
        error: function (error) {
            $(".error-text").text("");
            $(".form-control, .select2-container").removeClass("is-invalid is-valid");

            if (error.responseJSON.code === 422) {
                $.each(error.responseJSON.errors, function (key, val) {
                    $("#" + key).addClass("is-invalid");
                    $("#" + key + "_error").text(val[0]);
                });
            } else {
                showToast("error", error.responseJSON.message);
            }
        }
    });
}

function fetchStatesByCountry(countryId) {
    $.ajax({
        type: "POST",
        url: "/api/states",
        data: { country_id: countryId },
        headers: {
            "Accept": "application/json",
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
        },
        success: function (response) {
            if (response.code === 200) {
                const states = response.data;
                const $state = $("#state");
                const $city = $("#city");
                const defaultStateId = $state.data("default-id");

                $state.empty();
                $.each(states, function (index, state) {
                    $state.append(`<option value="${state.id}">${state.name}</option>`);
                });

                setTimeout(function () {
                    if (defaultStateId) {
                        $state.val(defaultStateId).trigger("change");
                    }
                }, 100);

                $city.empty();
            }
        }
    });
}

function fetchCitiesByState(stateId) {
    $.ajax({
        type: "POST",
        url: "/api/cities",
        data: { state_id: stateId },
        headers: {
            "Accept": "application/json",
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
        },
        success: function (response) {
            if (response.code === 200) {
                const cities = response.data;
                const $city = $("#city");
                const defaultCityId = $city.data("default-id");

                $city.empty();
                $.each(cities, function (index, city) {
                    $city.append(`<option value="${city.id}">${city.name}</option>`);
                });

                setTimeout(function () {
                    if (defaultCityId) {
                        $city.val(defaultCityId).trigger("change");
                    }
                }, 100);

                $city.trigger("change");
            }
        }
    });
}
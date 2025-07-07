/* global $, alert, FormData, window, document, showToast, _l */
$(document).ready(function () {
    $("#submitCheckoutForm").validate({
        rules: {
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            address: {
                required: true,
            },
            category: {
                required: true,
            },
            city: {
                required: true,
            },
            state: {
                required: true,
            },
            pincode: {
                required: true,
                digits: true,
            },

            // Paypal
            paypal_email: {
                required: "#paypal:checked",
                email: true,
            },
            paypal_password: {
                required: "#paypal:checked",
            },

            // Stripe
            stripe_email: {
                required: "#stripe:checked",
                email: true,
            },
            stripe_password: {
                required: "#stripe:checked",
            },

            // Wallet
            wallet_email: {
                required: "#wallet:checked",
                email: true,
            },
            wallet_password: {
                required: "#wallet:checked",
            },
        },
        messages: {
            first_name: {
                required: "First Name is required.",
            },
            last_name: {
                required: "Last Name is required.",
            },
            address: {
                required: "Address is required.",
            },
            category: {
                required: "Category is required.",
            },
            city: {
                required: "City is required.",
            },
            state: {
                required: "State is required.",
            },
            pincode: {
                required: "Pincode is required.",
                digits: "Pincode must be numeric.",
            },

            // Paypal
            paypal_email: {
                required: "Paypal email is required.",
                email: "Enter a valid email.",
            },
            paypal_password: {
                required: "Paypal password is required.",
            },

            // Stripe
            stripe_email: {
                required: "Stripe email is required.",
                email: "Enter a valid email.",
            },
            stripe_password: {
                required: "Stripe password is required.",
            },

            // Wallet
            wallet_email: {
                required: "Wallet email is required.",
                email: "Enter a valid email.",
            },
            wallet_password: {
                required: "Wallet password is required.",
            },
        },
        errorPlacement: function (error, element) {
            var errorId = element.attr("id") + "_error";
            if (element.hasClass("select2-hidden-accessible")) {
                $("#" + errorId).text(error.text());
            } else {
                $("#" + errorId).text(error.text());
            }
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
            let finalFormData = new FormData(form);

            $("#order_wait").modal("show");
            $("#final_btn").text("Please Wait...").prop("disabled", true);
            $.ajax({
                url: "/create/payments",
                method: "POST",
                data: finalFormData,
                dataType: "json",
                contentType: false,
                processData: false,
                cache: false,
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr(
                        "content"
                    ),
                },
            })

                .done((response) => {
                    if (response.code === 200 && response.cod) {
                        showToast("success", response.message);
                        window.location.href = response.redirect_url;
                    }

                    if (response.paypal_url) {
                        showToast("success", response.message);
                        window.location.href = response.paypal_url;
                    }

                    if (response.stripurl) {
                        showToast("success", response.message);
                        window.location.href = response.stripurl;
                    }
                    $("#final_btn").text("Pay").prop("disabled", false);
                })
                .fail((error) => {
                    $(".error-text").text("");
                    $(".form-control").removeClass("is-invalid");
                    $("#final_btn").text("Pay").prop("disabled", false);
                    $("#order_wait").modal("hide");
                    if (error.status === 422) {
                        if (error.responseJSON.errors) {
                            // Laravel field-level validation errors
                            $.each(
                                error.responseJSON.errors,
                                function (key, val) {
                                    $("#" + key).addClass("is-invalid");
                                    $("#" + key + "_error").text(val[0]);
                                }
                            );
                        } else if (error.responseJSON.message) {
                            // Custom error message (like wallet balance)
                            showToast("error", error.responseJSON.message);
                        }
                    } else {
                        showToast(
                            "error",
                            error.responseJSON.message ||
                                _l("web.home.something_went_wrong")
                        );
                    }
                });
        },
    });
});

$(document).ready(function () {
    function loadStates(
        countryId,
        selectedStateId = null,
        selectedCityId = null
    ) {
        let $stateDropdown = $("#state_id");
        $stateDropdown
            .prop("disabled", true)
            .html("<option>Loading...</option>");

        $.ajax({
            url: "/get-states/" + countryId,
            type: "GET",
            success: function (response) {
                $stateDropdown
                    .empty()
                    .append("<option value=\"\">Select State</option>");

                if (response.length > 0) {
                    $.each(response, function (key, state) {
                        $stateDropdown.append(
                            `<option value="${state.id}" ${
                                selectedStateId == state.id ? "selected" : ""
                            }>${state.name}</option>`
                        );
                    });

                    // Only load cities if this is from edit mode
                    if (selectedStateId && selectedCityId) {
                        loadCities(selectedStateId, selectedCityId);
                    }
                }

                $stateDropdown.prop("disabled", false);
            },
            error: function () {
                alert("Failed to fetch states. Please try again.");
                $stateDropdown
                    .html("<option value=\"\">Select State</option>")
                    .prop("disabled", false);
            },
        });
    }

    function loadCities(stateId, selectedCityId = null) {
        let $cityDropdown = $("#city_id");
        $cityDropdown
            .prop("disabled", true)
            .html("<option>Loading...</option>");

        $.ajax({
            url: "/get-cities/" + stateId,
            type: "GET",
            success: function (response) {
                $cityDropdown
                    .empty()
                    .append("<option value=\"\">Select City</option>");

                if (response.length > 0) {
                    $.each(response, function (key, city) {
                        $cityDropdown.append(
                            `<option value="${city.id}" ${
                                selectedCityId == city.id ? "selected" : ""
                            }>${city.name}</option>`
                        );
                    });
                }

                $cityDropdown.prop("disabled", false);
            },
            error: function () {
                alert("Failed to fetch cities. Please try again.");
                $cityDropdown
                    .html("<option value=\"\">Select City</option>")
                    .prop("disabled", false);
            },
        });
    }

    const selectedCountryId = $("#country_id").val();
    const selectedStateId = $("#selected_state_id").val();
    const selectedCityId = $("#selected_city_id").val();

    if (selectedCountryId && selectedStateId) {
        loadStates(selectedCountryId, selectedStateId, selectedCityId);
    }

    // On country change
    // On country change
    $("#country_id").on("change", function () {
        let countryId = $(this).val();

        let $stateDropdown = $("#state_id");
        let $cityDropdown = $("#city_id");

        $cityDropdown.html("<option value=\"\">Select City</option>"); // Reset city

        if (countryId) {
            $stateDropdown
                .prop("disabled", true)
                .html("<option>Loading...</option>");
            $cityDropdown.html("<option value=\"\">Select State</option>"); // Reset city dropdown

            $.ajax({
                url: "/get-states/" + countryId,
                type: "GET",
                success: function (response) {
                    $stateDropdown
                        .empty()
                        .append("<option value=\"\">Select State</option>");

                    if (response.length > 0) {
                        $.each(response, function (key, state) {
                            $stateDropdown.append(
                                "<option value=\"" +
                                    state.id +
                                    "\">" +
                                    state.name +
                                    "</option>"
                            );
                        });
                    }

                    $stateDropdown.prop("disabled", false);
                },
                error: function () {
                    alert("Failed to fetch states. Please try again.");
                    $stateDropdown
                        .html(
                            `<option value="">${_l(
                                "web.home.select_state"
                            )}</option>`
                        )
                        .prop("disabled", false);
                },
            });

            loadStates(countryId);
        } else {
            $stateDropdown.html("<option value=\"\">Select State</option>");
        }
    });

    // On state change
    $("#state_id").on("change", function () {
        let stateId = $(this).val();
        let $cityDropdown = $("#city_id");
        if (stateId) {
            $cityDropdown
                .prop("disabled", true)
                .html("<option>Loading...</option>");

            $.ajax({
                url: "/get-cities/" + stateId,
                type: "GET",
                success: function (response) {
                    $cityDropdown
                        .empty()
                        .append("<option value=\"\">Select City</option>");

                    if (response.length > 0) {
                        $.each(response, function (key, city) {
                            $cityDropdown.append(
                                "<option value=\"" +
                                    city.id +
                                    "\">" +
                                    city.name +
                                    "</option>"
                            );
                        });
                    }

                    $cityDropdown.prop("disabled", false);
                },
                error: function () {
                    alert("Failed to fetch cities. Please try again.");
                    $cityDropdown
                        .html(
                            `<option value="">${_l(
                                "web.home.select_city"
                            )}</option>`
                        )
                        .prop("disabled", false);
                },
            });
            loadCities(stateId);
        } else {
            $("#city_id").html("<option value=\"\">Select City</option>");
        }
    });
});

/* global $, loadTranslationFile, document, FormData, showToast, _l */
(async () => {
    "use strict";

    // Load translations
    await loadTranslationFile("admin", "general_settings,common");

    // On document ready
    $(function () {
        initThemeSettings();
        initThemeEventHandlers();
    });

    /**
     * Initializes and loads saved theme settings
     */
    function initThemeSettings() {
        $.ajax({
            url: "/admin/settings/list",
            type: "POST",
            data: { group_id: 16 },
            headers: defaultHeaders(),
            success: function (response) {
                if (response.code === 200) {
                    const settings = response.data;
                    settings.forEach((setting) => {
                        if (setting.value == 1) {
                            $("#theme_01").prop("checked", true);
                        } else if (setting.value == 2) {
                            $("#theme_02").prop("checked", true);
                        } else if(setting.value == 3) {
                            $("#theme_03").prop("checked", true);
                        } else if(setting.value == 4) {
                            $("#theme_04").prop("checked", true);
                        }
                    });
                }
            },
            error: handleAjaxError,
            complete: function () {
                $(".label-loader, .input-loader, .card-loader").hide();
                $(".real-label, .real-input, .real-card").removeClass("d-none");
            },
        });
    }

    /**
     * Attaches click handler for changing theme
     */
    function initThemeEventHandlers() {
        $(document).on("click", ".default_theme, .theme-img", function () {
            const themeId = $(this).data("id");
            let theme_val = 0;
            $(`#${themeId}`).prop("checked", true);
            if(themeId === "theme_01") {
                theme_val = 1;
            }else if(themeId === "theme_02") {
                theme_val = 2;
            }else if(themeId === "theme_03") {
                theme_val = 3;
            }else if(themeId === "theme_04") {
                theme_val = 4;
            }
            if(theme_val === 0) {
                return;
            }
            const formData = new FormData();
            formData.append("group_id", 16);
            formData.append("default_theme", theme_val);

            $.ajax({
                type: "POST",
                url: "/admin/settings/update-theme-settings",
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                headers: defaultHeaders(),
                success: function (resp) {
                    if (resp.code === 200) {
                        showToast("success", resp.message);
                    }
                },
                error: handleAjaxError,
            });
        });
    }

    /**
     * Common AJAX error handler
     */
    function handleAjaxError(error) {
        $(".error-text").text("");
        $(".form-control").removeClass("is-invalid is-valid");

        if (error.responseJSON?.code === 422) {
            $.each(error.responseJSON.errors, function (key, val) {
                $(`#${key}`).addClass("is-invalid");
                $(`#${key}_error`).text(val[0]);
            });
        } else {
            const msg =
                error.responseJSON?.message ||
                _l("admin.general_settings.retrive_error");
            showToast("error", msg);
        }
    }

    /**
     * Common headers for secure AJAX requests
     */
    function defaultHeaders() {
        return {
            Accept: "application/json",
            "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
        };
    }
})();

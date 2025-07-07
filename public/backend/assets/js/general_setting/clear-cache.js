/* global $, loadTranslationFile, setTimeout, location, showToast, _l */
(async () => {
    "use strict";
    await loadTranslationFile("admin", "general_settings,common");

    $("#clear-cache").on("click", function () {
        $.ajax({
            type: "POST",
            url: "/admin/settings/clear",
            headers: {
                "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
            },
            success: function (resp) {
                showToast("success", resp.message);
                $("#clear_cache").modal("hide");
                setTimeout(function () {
                    location.reload();
                }, 500);
            },
            error: function (error) {
                showToast(
                    "error",
                    error.responseJSON.message ||
                        _l("admin.common.default_retrieve_error")
                );
            },
        });
    });
})();

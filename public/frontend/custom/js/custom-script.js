/* global $, window, setTimeout, document, showToast, bootstrap, moment, location */
(function () {
    "use strict";
    window.showToast = function (toastType, message) {
      const toastMap = {
        success: "successToast",
        error: "dangerToast",
        warning: "warningToast",
        info: "infoToast",
        secondary: "secondaryToast",
        default: "primaryToast"
      };

      const toastId = toastMap[toastType] || toastMap.default;
      const toastElement = document.getElementById(toastId);

      if (toastElement) {
        const body = toastElement.querySelector(".toast-body");
        if (body) {
          body.innerText = message;
        }

        const toast = new bootstrap.Toast(toastElement, {
          animation: true,
          autohide: true,
          delay: 2000
        });
        toast.show();
      } else {
        showToast("error", "Toast element not found");
      }
    };

    if ($(".datetimepickerVehicle").length > 0) {
        $(".datetimepickerVehicle").datetimepicker({
            format: "DD-MM-YYYY",
            minDate: moment().startOf("day"), // Disables past dates, allows only future
            icons: {
                up: "fas fa-angle-up",
                down: "fas fa-angle-down",
                next: "fas fa-angle-right",
                previous: "fas fa-angle-left",
            },
        });
    }

    if ($(".yearpickerVehicle").length > 0) {
        $(".yearpickerVehicle").datetimepicker({
            viewMode: "years",
            format: "YYYY",
            maxDate: moment().endOf("year"), // Restricts selection to past years only
            useCurrent: false, // Prevents auto-setting of the current year in input
            icons: {
                up: "fas fa-angle-up",
                down: "fas fa-angle-down",
                next: "fas fa-angle-right",
                previous: "fas fa-angle-left",
            },
        });
    }

    function initializeTooltips() {
        $("[data-bs-toggle='tooltip']").each(function () {
            let tooltipInstance = bootstrap.Tooltip.getInstance(this);
            if (tooltipInstance) {
                tooltipInstance.dispose();
            }
        });

        $("[data-bs-toggle='tooltip']").tooltip();
    }

    $(document).ready(function () {
      const $cookieBanner = $("#cookieConsentBanner");
      const $agreeButton = $("#cookieAgree");
      const $declineButton = $("#cookieDecline");

      function hasCookie(name) {
        return document.cookie.split("; ").some(function (row) {
          return row.indexOf(name + "=") === 0;
        });
      }

      if (!hasCookie("cookie_consent")) {
        setTimeout(function () {
          $cookieBanner.removeClass("d-none");
        }, 2000);
      }

      $agreeButton.on("click", function () {
        document.cookie = "cookie_consent=accepted; path=/; max-age=" + (60 * 60 * 24 * 30);
        $cookieBanner.addClass("d-none");
      });

      $declineButton.on("click", function () {
        $cookieBanner.addClass("d-none");
      });
    });

    $(document).on("click", ".fav-icon", function () {
      "use strict";

      const id = $(this).data("id");
      if (!id) return false;

      $.ajax({
        url: "/user/add-to-favourite",
        type: "POST",
        data: {
          id: id,
          _token: $("meta[name=\"csrf-token\"]").attr("content")
        },
        success: function (response) {
          if (response.status) {
            showToast("success", response.message);
          } else {
            showToast("error", response.message);
          }
        },
        error: function (xhr) {
          if (xhr.responseJSON && xhr.responseJSON.message) {
            showToast("error", xhr.responseJSON.message);
          } else {
            showToast("error", "An unexpected error occurred");
          }
        }
      });
    });

    $(document).on("click", ".change-user-language", function () {
      "use strict";

      const languageCode = $(this).data("language_code");
      const languageId = $(this).data("id");

      $.ajax({
        type: "POST",
        url: "/user/flag-change-language",
        data: {
          language_code: languageCode,
          language_id: languageId,
          _token: $("meta[name=\"csrf-token\"]").attr("content")
        },
        success: function (response) {
          if (response.status === "success") {
            location.reload();
          } else {
            showToast("error", response.message || "Language change failed");
          }
        },
        error: function () {
          showToast("error", "Failed to change language");
        }
      });
    });

    $(document).ready(function () {
        initializeTooltips();
        fetchNotifications();
    });

    function fetchNotifications() {
      $.ajax({
        type: "GET",
        url: "/user/get-notifications",
        dataType: "json",
        success: function (response) {
          $(".notification-list").html(DOMPurify.sanitize(response.html));

          if (response.count > 0) {
            $("#newNotificationBadge").removeClass("d-none");
            $(".has-notification").removeClass("d-none");
            $(".unread-count").text(response.count).removeClass("d-none");
          } else {
            $("#newNotificationBadge").addClass("d-none");
            $(".has-notification").addClass("d-none");
            $(".unread-count").text("").addClass("d-none");
          }
        },
        error: function () {
          // Silent fail — optionally handle/log here
        }
      });
    }

    $(document).on("click", "#mark-all-as-read", function () {
      $.ajax({
        type: "POST",
        url: "/user/mark-all-notifications-as-read",
        data: {
          _token: $("meta[name=\"csrf-token\"]").attr("content")
        },
        dataType: "json",
        success: function (response) {
          showToast(response.status, response.message);
          if (response.code === 200) {
            fetchNotifications();
          }
        },
        error: function () {
          // Silent fail — optionally handle/log here
        }
      });
    });

    $(document).on("change", "#user-type-switch", function () {
      const userType = $(this).val();

      if (userType === "buyer") {
        $(".buyer-menu-options").removeClass("d-none");
        $(".seller-menu-options").addClass("d-none");
      } else {
        $(".buyer-menu-options").addClass("d-none");
        $(".seller-menu-options").removeClass("d-none");
      }
    });
})();
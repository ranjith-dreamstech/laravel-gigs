/* global $, loadTranslationFile, document, showToast, _l */
"use strict";

(async () => {
    await loadTranslationFile("web", "user,common");
    loadNotifications();

    function loadNotifications(page = 1) {
        $.ajax({
            url: `/seller/notifications?page=${page}`,
            method: "GET",
            success(response) {
                const $list = $("#notification-list");
                const $pagination = $("#pagination-container");
                const $action = $("#notification_action");

                if (response.count > 0) {
                    $list.html(DOMPurify.sanitize(response.html));
                    $pagination.html(DOMPurify.sanitize(renderPagination(response)));
                    $action.removeClass("d-none");
                } else {
                    $list.html(`<p class="text-center">${_l("web.user.no_notifications_found")}</p>`);
                    $pagination.empty();
                    $action.addClass("d-none");
                }
            }
        });
    }

    function renderPagination(data) {
        let html = `
            <nav class="custom-pagination">
                <ul class="pagination justify-content-center align-items-center">
                    <li class="page-item ${data.prev_page_url ? "" : "disabled"}">
                        <a class="page-link" href="#" data-page="${data.current_page - 1}">
                            <i class="fas fa-arrow-left me-1"></i> ${_l("web.user.prev")}
                        </a>
                    </li>`;

        for (let i = 1; i <= data.last_page; i++) {
            html += `
                <li class="page-item ${i === data.current_page ? "active" : ""}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
        }

        html += `
                    <li class="page-item ${data.next_page_url ? "" : "disabled"}">
                        <a class="page-link" href="#" data-page="${data.current_page + 1}">
                            ${_l("web.user.next")} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </li>
                </ul>
            </nav>`;

        return html;
    }

    $(document).on("click", ".pagination .page-link", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        if (page) loadNotifications(page);
    });

    $(document).on("click", "#markAllAsRead", function () {
        $.post("/user/mark-all-notifications-as-read", {
            _token: $("meta[name=\"csrf-token\"]").attr("content")
        }, function (response) {
            if (response.code === 200) {
                showToast("success", response.message);
                loadNotifications();
            } else {
                showToast("error", response.message);
            }
        }).fail((response) => {
            showToast("error", response.message);
        });
    });

    $(document).on("click", ".notificationitem", function () {
        const id = $(this).data("id");
        $.post("/user/mark-notification-as-read", {
            id,
            _token: $("meta[name=\"csrf-token\"]").attr("content")
        }, function (response) {
            if (response.status === "success") {
                showToast("success", response.message);
                loadNotifications();
            } else {
                showToast("error", response.message);
            }
        }).fail((response) => {
            showToast("error", response.message);
        });
    });

    $(document).on("click", ".del_notification", function () {
        const id = $(this).data("id");
        $("#delete_notification .deletebtn").data("id", id);
        $("#delete_notification").modal("show");
    });

    $(document).on("click", "#delete_notification .deletebtn", function () {
        const id = $(this).data("id");
        if (!id) return;

        $.post("/user/delete-notification", {
            id,
            _token: $("meta[name=\"csrf-token\"]").attr("content")
        }, function (response) {
            $("#delete_notification").modal("hide");
            if (response.status === "success") {
                showToast("success", response.message);
                loadNotifications();
            } else {
                showToast("error", response.message);
            }
        }).fail((response) => {
            showToast("error", response.message);
        });
    });

    $(document).on("click", "#deleteAll", function () {
        $("#deleteAllNotifications").modal("show");
    });

    $(document).on("click", ".deleteAllNotifications", function () {
        $.post("/user/delete-all-notifications", {
            _token: $("meta[name=\"csrf-token\"]").attr("content")
        }, function (response) {
            $("#deleteAllNotifications").modal("hide");
            if (response.code === 200) {
                showToast("success", response.message);
                loadNotifications();
            } else {
                showToast("error", response.message);
            }
        }).fail((response) => {
            showToast("error", response.message);
        });
    });
})();

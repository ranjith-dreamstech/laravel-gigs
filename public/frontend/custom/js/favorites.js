/* global $, loadTranslationFile, setTimeout, document, showToast, _l */
(async () => {
    "use strict";

    await loadTranslationFile("web", "user, common");

    const isRtl = $("body").data("dir") === "rtl";

    $(document).ready(function () {
        $(document).on("click", ".remove-favorite", function () {
            const gigId = $(this).data("gig-id");
            const $button = $(this);

            $button.prop("disabled", true);

            $.ajax({
                type: "POST",
                url: "/favorites/remove",
                data: {
                    service_id: gigId,
                    _token: $("meta[name=\"csrf-token\"]").attr("content")
                },
                success: function () {
                    $button.closest(".col-xl-4").remove();

                    const favoritesList = $("#my_favorite_list");

                    if (favoritesList.children().length === 0) {
                        favoritesList.html("<p class=\"text-center\">No favorites found</p>");
                        $(".load_more_btn").addClass("d-none");
                    }

                    showToast("success", _l("web.user.favourite_removed"));
                },
                error: function () {
                    $button.prop("disabled", false);
                    showToast("error", _l("web.user.favourite_remove_failed"));
                }
            });
        });
    });
    
    $(document).ready(function () {
        const $modal = $("#remove-favourite");
        const $favoritesList = $("#my_favorite_list");
        const $loadMoreBtn = $(".load_more_btn");
        const $confirmBtn = $(".btn-danger");
        const csrfToken = $("meta[name=\"csrf-token\"]").attr("content");

        $(document).on("click", ".btn-remove-all-favorites", function () {
            $modal.modal("show");
        });

        $("#remove-all-fav-form").on("submit", function (e) {
            e.preventDefault();

            $confirmBtn.prop("disabled", true).text("Processing...");

            $.ajax({
                url: "/buyer/remove-all-favorites",
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                success: function (res) {
                    $modal.modal("hide");

                    if (res.success) {
                        $favoritesList.html(`<p class="text-center">${_l("web.user.no_favourites_found")}</p>`);
                        $loadMoreBtn.addClass("d-none");
                        showToast("success", res.message);
                    } else {
                        showToast("warning", res.message);
                    }

                    $confirmBtn.prop("disabled", false).text("Yes");
                },
                error: function (xhr) {
                    $modal.modal("hide");

                    const errorMsg = xhr.responseJSON?.message || _l("web.user.something_went_wrong");
                    showToast("error", errorMsg);

                    $confirmBtn.prop("disabled", false).text("Yes");
                }
            });
        });

        $(document).on("click", ".close-icon", function () {
            $modal.modal("hide");
        });
    });
    
  
    function renderSellerCard(item) {
        "use strict";

        let imagesHtml = "";

        if (item.service && Array.isArray(item.service_images) && item.service_images.length > 0) {
            item.service_images.forEach(function (image) {
                imagesHtml += `
                    <div class="slide-images">
                        <a href="#">
                            <img src="${image}" class="img-fluid" alt="gig image">
                        </a>
                    </div>`;
            });
        } else {
            imagesHtml = `
                <div class="slide-images">
                    <a href="#">
                        <img src="/backend/assets/img/default-gig.jpg" class="img-fluid" alt="no image">
                    </a>
                </div>`;
        }

        const service = item.service || {};
        const categoryName = service.category?.name || "Category";
        const title = service.title || "";
        const price = service.general_price || 0;
        const deliveryDays = service.days || 0;

        return `
            <div class="col-xl-4 col-md-6">
                <div class="gigs-grid">
                    <div class="gigs-img">
                        <div class="img-slider owl-carousel">
                            ${imagesHtml}
                        </div>
                        <div class="fav-selection">
                            <a href="javascript:void(0);" class="remove-favorite" data-gig-id="${service.id}">
                                <i class="ti ti-heart-filled"></i>
                            </a>
                        </div>
                    </div>
                    <div class="gigs-content">
                        <div class="gigs-info">
                            <a href="javascript:void(0);" class="badge bg-primary-light">
                                ${categoryName}
                            </a>
                        </div>
                        <div class="gigs-title">
                            <h3><a href="#">${title}</a></h3>
                        </div>
                        <div class="gigs-card-footer">
                            <h5>$${price}</h5>
                            <span class="badge">${_l("web.user.delivery_in")} ${deliveryDays} day(s)</span>
                        </div>
                    </div>
                </div>
            </div>`;
    }
    

   $(document).on("click", ".load_more_btn", function () {
        const nextPage = $(this).data("page");
        fetchMySellers(nextPage);
    });

    $(document).ready(function () {
        fetchMySellers();
    });

    function fetchMySellers(page = 1) {
        $.ajax({
            type: "GET",
            url: "/buyer/favorites?page=" + page,
            beforeSend: function () {
                $(".load_more_btn").attr("disabled", true).html(
                    "<span class=\"spinner-border spinner-border-sm align-middle me-1\" role=\"status\" aria-hidden=\"true\"></span>" +
                    _l("web.user.loading")
                );
            },
            complete: function () {
                $(".load_more_btn").removeAttr("disabled").html(
                    "<i class=\"ti ti-loader-3 me-2\"></i> " + _l("web.user.load_more")
                );
                $(".card-loader").hide();
                $(".label-loader, .input-loader").hide();
                $(".real-label, .real-table, .real-card").removeClass("d-none");
            },
            success: function (res) {
                if (Array.isArray(res.data) && res.data.length > 0) {
                    $.each(res.data, function (i, seller) {
                        $("#my_favorite_list").append(renderSellerCard(seller));
                    });

                    setTimeout(function () {
                        reintializeOwlCarousel();
                    }, 100);

                    if (res.next_page) {
                        $(".load_more_btn")
                            .data("page", res.next_page)
                            .removeClass("d-none");
                    } else {
                        $(".load_more_btn").addClass("d-none");
                    }
                } else {
                    $("#my_favorite_list").html(
                        "<p class=\"text-center\">" + _l("web.user.no_favourites_found") + "</p>"
                    );
                    $(".load_more_btn").addClass("d-none");
                }
            },
            error: function (xhr) {
                const errorMsg = xhr.responseJSON?.message || _l("web.user.something_went_wrong");
                showToast("error", errorMsg);
            }
        });
    }

    function reintializeOwlCarousel() {
        if ($(".img-slider").length > 0) {
            $(".img-slider").owlCarousel({
                loop: true,
                margin: 24,
                nav: false,
                rtl: typeof isRtl !== "undefined" ? isRtl : false,
                dots: true,
                smartSpeed: 2000,
                autoplay: false,
                navText: [
                    "<i class=\"fa-solid fa-chevron-left\"></i>",
                    "<i class=\"fa-solid fa-chevron-right\"></i>"
                ],
                responsive: {
                    0: { items: 1 },
                    550: { items: 1 },
                    768: { items: 1 },
                    1000: { items: 1 }
                }
            });
        }
    }
}) ();
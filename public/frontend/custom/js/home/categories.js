"use strict";

/* global $, loadTranslationFile, document, _l */

(async () => {
    await loadTranslationFile("web", "home, common");

    $(document).ready(function () {
        let pageLength = 12;
        let page = 1;
        let sortby;

        fetchCategories();

        // Function to fetch category data via AJAX
        function fetchCategories(reset = false) {
            $.ajax({
                url: "/home/fetch-categories",
                method: "POST",
                data: {
                    page_length: pageLength,
                    page: page,
                    sortby: sortby,
                    _token: $("meta[name='csrf-token']").attr("content")
                },
                beforeSend: function () {
                    $(".loader-container").removeClass("d-none");
                    $(".label-skeleton").removeClass("d-none");
                    $(".real-data").addClass("d-none");
                },
                success: function (response) {
                    if (reset) $(".categories-list").html("");

                    if (response.status && response.data?.length > 0) {
                        const html = response.data.map(createCategoryCard).join("");

                        const loadMoreHtml = `<a href="javascript:void(0);" 
                            data-page="${response.pagination.current_page + 1}" 
                            class="btn btn-primary d-inline-flex align-items-center load-more ${response.pagination.last_page > response.pagination.current_page ? "" : "disabled"}">
                            <i class="ti ti-loader-3 me-2"></i> ${_l("web.common.load_more")}
                        </a>`;
                        $(".search-load-btn").html(DOMPurify.sanitize(loadMoreHtml));

                        $(".categories-list").append(DOMPurify.sanitize(html));
                        $(".category-count").html(DOMPurify.sanitize(response.totalCategories));
                        $(".service-count").html(DOMPurify.sanitize(response.totalServices));
                    } else {
                        $(".search-load-btn").html("");
                        $(".categories-list").html(`<div class="col-12"><h4 class="text-center">${_l("web.home.no_categories_found")}</h4></div>`);
                        $(".category-count, .service-count").html(0);
                    }
                },
                complete: function () {
                    $(".loader-container").addClass("d-none");
                    $(".real-data").removeClass("d-none");
                    $(".label-skeleton").addClass("d-none");
                }
            });
        }

        // Event listener for "Load More"
        $(document).on("click", ".load-more", function () {
            page = $(this).data("page");
            fetchCategories();
        });

        // Generate category card HTML
        function createCategoryCard(category) {
            return `<div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="service-grid">
                    <div class="service-img">
                        <a href="/gigs?category_id=${category.id}">
                            <img src="${category.image}" class="img-fluid" alt="img">
                        </a>
                        <div class="avg-price">
                            <h6>${_l("web.home.average_price")}</h6>
                            <span>${category.currency}${category.avg_price}</span>
                        </div>
                    </div>
                    <div class="service-type d-flex justify-content-between align-items-center">
                        <div class="servive-name">
                            <h4><a href="/gigs?category_id=${category.id}">${category.name ?? ""}</a></h4>
                            <span>${category.total_services} ${_l("web.home.services")}</span>
                        </div>
                        <div class="next-arrow">
                            <a href="/gigs?category_id=${category.id}"><i class="feather-arrow-up-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>`;
        }

        // Handle sorting option selection
        $(document).on("click", ".sort-selection", function () {
            $(".seleced-sort").text($(this).data("name"));
            sortby = $(this).data("sort");
            page = 1;
            fetchCategories(true);
        });
    });
})();

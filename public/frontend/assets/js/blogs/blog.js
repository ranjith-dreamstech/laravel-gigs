/* global $, loadTranslationFile, URLSearchParams, window, document, fetch, _l, URL, showToast */
(async () => {
    "use strict";

    await loadTranslationFile("web", "blog, common");

    // Toggle view more/less categories
    if ($(".viewall-one").length > 0) {
        $(".viewall-one").hide();
        $(".viewall-button-one").on("click", function () {
            const isLess = $(this).text() === _l("web.blog.less_categories");
            $(this).text(isLess ? _l("web.blog.more_categories") : _l("web.blog.less_categories"));
            $(".viewall-one").slideToggle(900);
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        const blogContainer = document.getElementById("blogListContainer");
        let activeFilters = {};

        const fetchFilteredBlogs = (params = {}) => {
            activeFilters = { ...params };
            const query = new URLSearchParams(params).toString();

            fetch(`/blogs?${query}`, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
                .then((response) => response.json())
                .then((data) => {
                    blogContainer.innerHTML = DOMPurify.sanitize(data.html);

                    window.scrollTo({
                        top: blogContainer.offsetTop - 100,
                        behavior: "smooth"
                    });

                    bindPaginationLinks(); // re-bind after content load
                })
                .catch(() => {
                    showToast("error", "Error fetching blogs");
                });

        };

        const bindPaginationLinks = () => {
            const links = document.querySelectorAll(".pagination a");
            links.forEach((link) => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const page = url.searchParams.get("page");
                    fetchFilteredBlogs({ ...activeFilters, page });
                });
            });
        };

        // Bind category filters
        const categoryButtons = document.querySelectorAll("[data-category]");
        categoryButtons.forEach((button) => {
            button.addEventListener("click", function () {
                const category = this.dataset.category;
                fetchFilteredBlogs({ category });
            });
        });

        // Bind blog search
        const searchInput = document.getElementById("blogSearch");
        if (searchInput) {
            searchInput.addEventListener("keypress", function (e) {
                if (e.key === "Enter") {
                    const search = this.value.trim();
                    fetchFilteredBlogs({ search });
                }
            });
        }

        // Load more functionality
        const items = document.querySelectorAll(".blog-post-item");
        const loadMoreBtn = document.getElementById("load-more-btn");
        const itemsPerLoad = 6;
        let currentVisible = 0;

        const showNextItems = () => {
            for (let i = currentVisible; i < currentVisible + itemsPerLoad; i++) {
                if (items[i]) {
                    items[i].style.display = "block";
                }
            }
            currentVisible += itemsPerLoad;

            if (loadMoreBtn && currentVisible >= items.length) {
                loadMoreBtn.style.display = "none";
            }
        };

        items.forEach((item) => {
            item.style.display = "none";
        });
        showNextItems();

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener("click", showNextItems);
        }
    });
})();


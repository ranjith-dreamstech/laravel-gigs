/* global $, toastr, window, document, showToast */
$(document).ready(function () {
    initList();
});

$("#statusTabs .nav-link").on("click", function (e) {
    e.preventDefault();
    $("#statusTabs .nav-link").removeClass("active");
    $(this).addClass("active");

    currentPage = 9;
    $(".appendGigs").empty();
    initList(currentPage);
    $(".loadMore").addClass("d-none");
    $("#skeletonCard").removeClass("d-none");
});

let currentPage = 1;
let perPage = 9;

function initList(isLoadMore = false) {
    let status = $("#statusTabs .nav-link.active").data("status");

    $.ajax({
        url: "/seller/seller-gigs-lists",
        type: "GET",
        data: {
            status: status,
            page: currentPage,
            pagelist: perPage,
        },
        success: function (response) {
            if (response.code === 200 && Array.isArray(response.data)) {
                if (!isLoadMore) {
                    $(".appendGigs").empty(); // fresh reload
                }

                if (response.data.length === 0) {
                    $(".appendGigs").html(`
                        <div class="col-12 text-center py-5">
                            <h4>No Data Available</h4>
                        </div>
                    `);
                } else {
                    response.data.forEach(function (gig) {
                        let imageSlides = "";
                        gig.images.forEach(function (imageUrl) {
                            imageSlides += `
                                <div class="slide-images">
                                    <a href="/service-details/${gig.slug}">
                                        <img src="${imageUrl}" class="img-fluid" alt="img">
                                    </a>
                                </div>`;
                        });
                
                        let gigCard = `
                            <div class="col-xl-4 col-md-6">
                                <div class="gigs-grid">
                                    <div class="gigs-img">
                                        <div class="img-slider owl-carousel">
                                            ${imageSlides}
                                        </div>
                                        <div class="fav-selection">
                                            <a href="javascript:void(0);" data-id="${gig.slug}" class="edit-gig"><i class="ti ti-edit"></i></a>
                                            <a href="javascript:void(0);" data-id="${gig.id}" class="delete-gig" data-bs-toggle="modal" data-bs-target="#remove-favourite"><i class="ti ti-trash"></i></a>
                                        </div>
                                    </div>
                                    <div class="gigs-content">
                                        <div class="gigs-info">
                                            <a href="javascript:void(0);" class="badge bg-primary-light">${gig.category_name}</a>
                                        </div>
                                        <div class="gigs-title">
                                            <h3>
                                                <a href="/service-details/${gig.slug}">${gig.title}</a>
                                            </h3>
                                            <div class="gigs-card-footer">
                                                <h5>$${gig.price}</h5>
                                                <span class="badge">Delivery in ${gig.days} day(s)</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                        $(".appendGigs").append(DOMPurify.sanitize(gigCard));
                    });
                }
                

                // 🛠 Properly hide or show load more
                if (response.data.length < perPage) {
                    $(".loadMore").addClass("d-none");
                } else {
                    $(".loadMore").removeClass("d-none");
                }

                // Initialize owl carousel again
                if ($(".img-slider").length > 0) {
                    $(".img-slider").owlCarousel({
                        loop: true,
                        margin: 24,
                        nav: false,
                        dots: true,
                        smartSpeed: 2000,
                        autoplay: false,
                        navText: [
                            "<i class=\"fa-solid fa-chevron-left\"></i>",
                            "<i class=\"fa-solid fa-chevron-right\"></i>",
                        ],
                        responsive: {
                            0: { items: 1 },
                            550: { items: 1 },
                            768: { items: 1 },
                            1000: { items: 1 },
                        },
                    });
                }
            } else {
                $(".loadMore").addClass("d-none"); // error fallback
            }
        },
        error: function () {
            toastr.error("Something went wrong while loading gigs.");
            $(".loadMore").addClass("d-none");
        },
        complete: function () {
            $(".appendGigs").removeClass("d-none");
            $("#skeletonCard").addClass("d-none");
        },
    });
}

// First Load
initList();

// Load More click
$(document).on("click", "#loadMoreBtn", function (e) {
    e.preventDefault();
    currentPage++; // go next page
    $(".loadMore").addClass("d-none");
    $("#skeletonCard").removeClass("d-none");
    initList(true); // true = append mode
});

$(document).on("click", ".edit-gig", function () {
    let slug = $(this).data("id");
    window.location.href = `/seller/seller-gigs-edit/${slug}`;
});

$(document).on("click", ".delete-gig", function () {
    const gigId = $(this).data("id");
    $("#deleteGigId").val(gigId);
});

$("#deleteGigs").on("submit", function (e) {
    e.preventDefault();

    const gigId = $("#deleteGigId").val();

    $.ajax({
        url: "/gigs/delete", // Adjust to your actual route
        type: "POST",
        data: {
            gig_id: gigId,
        },
        headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
        },
        success: function () {
            $("#remove-favourite").modal("hide");
            showToast("success", "Gig deleted successfully");
            initList();
        },
        error: function () {
            showToast("error", "Failed to delete gig. Try again.");
        },
    });
});
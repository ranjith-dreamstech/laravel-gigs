/* global $, setTimeout, document */
"use strict";

$(document).ready(() => {
    const isRtl = $("body").data("dir") === "rtl";
    fetchRecentWorks();

    function fetchRecentWorks() {
        $.ajax({
            url: "/recent-list-gigs",
            type: "POST",
            dataType: "json",
            data: {
                _token: $("meta[name='csrf-token']").attr("content")
            },
            success(response) {
                if (response.status && Array.isArray(response.data) && response.data.length > 0) {
                    const $carousel = $(".gigs-slider");

                    $carousel.trigger("destroy.owl.carousel").html("");

                    const html = response.data.map(createRecentWorkCard).join("");
                    $carousel.html(DOMPurify.sanitize(html));

                    setTimeout(() => {
                        if ($(".gigs-slider").length) {
                            $(".gigs-slider").owlCarousel({
                                loop: false,
                                margin: 24,
                                nav: true,
                                rtl: isRtl,
                                dots: false,
                                smartSpeed: 2000,
                                autoplay: false,
                                navText: [
                                    "<i class='fa-solid fa-chevron-left'></i>",
                                    "<i class='fa-solid fa-chevron-right'></i>"
                                ],
                                navContainer: ".worknav",
                                responsive: {
                                    0: { items: 1 },
                                    550: { items: 1 },
                                    768: { items: 2 },
                                    1000: { items: 3 }
                                }
                            });
                        }

                        if ($(".img-slider").length) {
                            $(".img-slider").owlCarousel({
                                loop: true,
                                margin: 24,
                                nav: false,
                                dots: true,
                                rtl: isRtl,
                                smartSpeed: 2000,
                                autoplay: false,
                                navText: [
                                    "<i class='fa-solid fa-chevron-left'></i>",
                                    "<i class='fa-solid fa-chevron-right'></i>"
                                ],
                                responsive: {
                                    0: { items: 1 },
                                    550: { items: 1 },
                                    768: { items: 1 },
                                    1000: { items: 1 }
                                }
                            });
                        }
                    }, 150);
                }
            }
        });
    }

    function createRecentWorkCard(service) {
        const images = service.gig_image.map(image => `
            <div class="slide-images">
                <a href="/service-details/${service.slug}">
                    <img src="${image}" class="img-fluid" alt="img">
                </a>
            </div>`).join("");

        const badges = `
            ${service.is_hot ? `
            <div class="card-overlay-badge">
                <a href="/service-details/${service.slug}">
                    <span class="badge bg-danger"><i class="fa-solid fa-meteor"></i>Hot</span>
                </a>
            </div>` : ""}
            ${service.is_feature ? `
            <div class="card-overlay-badge">
                <a href="/service-details/${service.slug}">
                    <span class="badge bg-success"><i class="fa-solid fa-meteor"></i>Featured</span>
                </a>
            </div>` : ""}
        `;

        const imageSection = `
            <div class="gigs-img">
                <div class="img-slider">${images}</div>
                ${badges}
                <div class="fav-selection">
                    <a href="javascript:void(0);" class="video-icon"><i class="feather-video"></i></a>
                    ${service.is_authenticated ? `
                        <a href="javascript:void(0);" class="fav-icon ${service.is_wishlist ? "favourite" : ""}" data-id="${service.id}">
                            <i class="feather-heart"></i>
                        </a>` : ""}
                </div>
                <div class="user-thumb">
                    <a href="javascript:void(0);">
                        <img src="${service.provider_image}" alt="img">
                    </a>
                </div>
            </div>`;

        const serviceContent = `
            <div class="gigs-content">
                <div class="gigs-info">
                    <a href="/service-details/${service.slug}">
                        <span class="badge bg-primary-light">${service.category ?? ""}</span>
                    </a>
                    <p><i class="ti ti-map-pin-check"></i>${service.location ?? ""}</p>
                </div>
                <div class="gigs-title">
                    <h3><a href="/service-details/${service.slug}">${service.title}</a></h3>
                </div>
                <div class="star-rate">
                    <span><i class="fa-solid fa-star"></i>${service.rating} (${service.reviews} Reviews)</span>
                </div>
                <div class="gigs-card-footer">
                    <div>
                        <a href="javascript:void(0);" class="share-icon"><i class="feather-share-2"></i></a>
                        <span class="badge">Delivery in ${service.days} day</span>
                    </div>
                    <h5>${service.currency}${service.general_price}</h5>
                </div>
            </div>`;

        return `<div class="gigs-grid">${imageSection}${serviceContent}</div>`;
    }
});

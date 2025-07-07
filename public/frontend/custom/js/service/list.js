/* eslint-env browser, jquery */
/* global $, loadTranslationFile, requestAnimationFrame, setTimeout, document, showToast, _l */
"use strict";
(async () => {
    await loadTranslationFile("web", "user,common");
    const isRtl = $("body").data("dir") && $("body").data("dir") === "rtl" ? true : false;
    $(document).ready(function () {
        let category_id;
        let subcategory_id;
        let review;
        let under_budget;
        let upto_delivery_days;
        let sort_by;
        let custom_category_id = $("#custom-category").attr("data-category_id");
        let q = $("#q").val() ?? "";
        if (custom_category_id) {
            category_id = custom_category_id;
        }
        fetchServices();

        function fetchServices(page = 1) {
            let paginate = 9;
            $.ajax({
                url: "/list-gigs",
                type: "POST",
                data: {
                    paginate: paginate,
                    page: page,
                    filter_category: category_id,
                    subcategory_id: subcategory_id,
                    review: review,
                    under_budget: under_budget,
                    upto_delivery_days: upto_delivery_days,
                    sort_by: sort_by,
                    q: q,
                    _token : $("meta[name=\"csrf-token\"]").attr("content")   
                },
                beforeSend: function () {
                   $(".service-list").addClass("d-none");
                   $(".list-loader").removeClass("d-none");
                },
                success: function (response) {
                    if (response.status && response.data && response.data.length > 0) {
                        let html = response.data.map(service => createServiceCard(service)).join("");
                        $("#service-container").html(DOMPurify.sanitize(html));
                        $("#gig-pagination").html(renderPagination(response.pagination));
                        if(category_id){
                            renderCategoryBanner(response.category,response.services_count);
                            renderSubCategories(response.sub_categories);
                            if(response.sub_categories && response.sub_categories.length > 0){
                                $(".trend-section").removeClass("d-none");
                            }
                        }else{

                            $("#category_banner").addClass("d-none");
                            $("#category-breadcrumb").addClass("d-none");
                            $("#breadcrumb-text").html(DOMPurify.sanitize(`Browse Services <span class="text-primary">“ ${response.services_count ?? 0} Services ”</span>`));
                            $(".trend-section").addClass("d-none");
                        }
                        requestAnimationFrame(() => {
                            setTimeout(() => {
                                reInitializeCarousel(".img-slider");
                            }, 100);
                        });
                    }else{
                        $("#service-container").html(`<p class="text-center">${_l("web.common.no_services_found")}</p>`);
                        $("#gig-pagination").html("");
                    }
                },            
                error: function (res) {
                    if (res.responseJSON.code === 500) {
                        showToast("success", res.responseJSON.message);
                    } else {
                        showToast("error", _l("web.common.default_retrieve_error"));
                    }
                },
                complete: function () {
                    $(".list-loader").addClass("d-none");
                    $(".service-list").removeClass("d-none");
                }
            });
        }
        
        function renderCategoryBanner(category,services_count = 0) {
            const name = category?.name ?? category ?? "";
            const description = category?.description ?? "";

            $("#category_banner .category-title").html(DOMPurify.sanitize(name));
            $("#category_banner .category-description").html(DOMPurify.sanitize(description));
            $("#category-breadcrumb").html(DOMPurify.sanitize(name));
            $("#breadcrumb-text").html(DOMPurify.sanitize(`Browse ${name} Services <span class="text-primary">“ ${services_count} Services ”</span>`));
            $("#category-breadcrumb").removeClass("d-none");
            $("#category_banner").removeClass("d-none");
        }
        
        function renderSubCategories(sub_categories) {
            const $carousel = $(".trend-items");
        
            if ($carousel.hasClass("owl-loaded")) {
                $carousel.trigger("destroy.owl.carousel");
                $carousel.removeClass("owl-loaded owl-hidden");
                $carousel.find(".owl-stage-outer").children().unwrap();
                $carousel.find(".owl-stage").children().unwrap();
                $carousel.find(".owl-item").children().unwrap(); 
                $carousel.html(""); 
            }
        
            const items = sub_categories.map(sub_category =>
                `<div class="item">
                    <div class="trend-box">
                        <div class="trend-info">
                            <h6>
                                <a href="javascript:void(0);" class="subcategory-select" data-id="${sub_category.id ?? ""}">
                                    ${sub_category.name ?? ""}
                                </a>
                            </h6>
                            <p>(${sub_category.service_count ?? 0} Services)</p>
                        </div>
                        <a href="javascript:void(0);" class="subcategory-select" data-id="${sub_category.id ?? ""}">
                            <i class="feather-arrow-up-right"></i>
                        </a>
                    </div>
                </div>`
            ).join("");

            $carousel.html(DOMPurify.sanitize(items));

            setTimeout(() => {
                $carousel.owlCarousel({
                    loop: true,
                    margin: 22,
                    nav: true,
                    rtl: isRtl,
                    dots: false,
                    smartSpeed: 2000,
                    autoplay: false,
                    navText: [
                    '<button type="button" aria-label="Previous slide"><span class="visually-hidden">Previous</span><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>',
                    '<button type="button" aria-label="Next slide"><span class="visually-hidden">Next</span><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>'
                    ],
                    navContainer: ".trend-nav",
                    responsive: {
                        0: { items: 1 },
                        600: { items: 2 },
                        992: { items: 3 },
                        1200: { items: 4 }
                    }
                });
            }, 50);
        
            $(".trend-section").removeClass("d-none");
        }
        
        function createServiceCard(service){
            let images = service.gig_image.map(image => 
                `<div class="slide-images">
                    <a href="/service-details/${service.slug}">
                        <img src="${image}" class="img-fluid" alt="img">
                    </a>
                </div>`
            ).join("");
            const slider = `<div class="img-slider owl-carousel">${images}</div>`;
            let imageContainer = `<div class="gigs-img">
                                        ${slider}
                                        <div class="card-overlay-badge">
                                                ${service.is_feature ? 
                                                `<a href="/service-details/${service.slug}"><span class="badge bg-warning"><i class="feather-star"></i>Featured</span></a>`
                                                : ""}
                                                ${service.is_hot ?  
                                                `<a href="/service-details/${service.slug}"><span class="badge bg-danger"><i class="fa-solid fa-meteor"></i>Hot</span></a>`
                                                : "" }
                                        </div>
                                        <div class="fav-selection">
                                                <a href="javascript:void(0);" class="video-icon"><i class="feather-video"></i></a>
                                                ${service.is_authenticated ? 
                                                `<a href="javascript:void(0);" class="fav-icon ${service.is_wishlist ? "favourite" : ""}" data-id="${service.id ?? ""}"><i class="feather-heart"></i></a>`
                                                : "" }

                                        </div>
                                        <div class="user-thumb">
                                                <a href="buyer-profile.html"><img src="${service.provider_image}" alt="img"></a>
                                        </div>
                                  </div>`;
            let content = `<div class="gigs-content">
                                <div class="gigs-info">
                                    <a href="/service-details/${service.slug}" class="badge bg-primary-light">${service.category ?? ""}</a>
                                    <p><i class="ti ti-map-pin-check"></i>${service.location ?? ""}</p>
                                </div>
                                <div class="gigs-title">
                                    <h3>
                                        <a href="/service-details/${service.slug}">${service.title}</a>
                                    </h3>
                                </div>
                                <div class="star-rate">
                                    <span><i class="fa-solid fa-star"></i>${service.rating ?? 0} (${service.reviews ?? 0} Reviews)</span>
                                </div>
                                <div class="gigs-card-footer">
                                    <div>
                                        <a href="javascript:void(0);" class="share-icon"><i class="feather-share-2"></i></a>
                                        <span class="badge">Delivery in ${service.days} day</span>
                                    </div>
                                    <h5>${service.currency ?? "$"}${service.general_price}</h5>
                                </div>
                            </div>`;
    
    
            return `<div class="col-lg-4 col-md-6">
                        <div class="gigs-grid">
                            ${imageContainer}
                            ${content}
                        </div>
                    </div>`;
        }
    
        function renderPagination(pagination) {
            let html = "<ul>";
            if (pagination.prev_page_url) {
                html += `<li><a href="javascript:void(0);" class="previous" data-page="${pagination.current_page - 1}"><i class="fa-solid fa-chevron-left"></i></a></li>`;
            } else {
                html += "<li><a href=\"javascript:void(0);\" class=\"previous disabled\"><i class=\"fa-solid fa-chevron-left\"></i></a></li>";
            }
            for (let i = 1; i <= pagination.last_page; i++) {
                if (i == pagination.current_page) {
                    html += `<li><a href="javascript:void(0);" class="active">${i}</a></li>`;
                } else {
                    html += `<li><a href="javascript:void(0);" data-page="${i}">${i}</a></li>`;
                }
            }
            if (pagination.next_page_url) {
                html += `<li><a href="javascript:void(0);" class="next" data-page="${pagination.current_page + 1}"><i class="fa-solid fa-chevron-right"></i></a></li>`;
            } else {
                html += "<li><a href=\"javascript:void(0);\" class=\"next disabled\"><i class=\"fa-solid fa-chevron-right\"></i></a></li>";
            }
        
            html += "</ul>";

            $(".pagination").html(DOMPurify.sanitize(html));
        }
        
        $(document).on("click", ".pagination a[data-page]", function () {
            const page = $(this).data("page");
            if (!page) return;
            fetchServices(page);
        });
        
        $(".category-search").on("keyup", function () {
            let searchText = $(this).val().toLowerCase();
            $(".categories-lists li").each(function () {
                let categoryText = $(this).find(".checked-title").text().toLowerCase();
    
                if (categoryText.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    
        $(".categories-lists").on("click", "li", function () {
            let selectedText = $(this).find(".checked-title").text().trim();
            $(this).closest(".collapse-card").find(".filter-title").text(`${selectedText}`);
            category_id = $(this).data("id");
            subcategory_id = null;
            fetchServices();
        });
    
        $(document).on("click",".subcategory-select", function(){
            subcategory_id = $(this).data("id");
            fetchServices();
        });
        function reInitializeCarousel(selector){
            if($(selector).length > 0) {
                $(selector).owlCarousel({
                    loop:true,
                    margin:24,
                    nav:false,
                    dots:true,
                    rtl: isRtl,
                    smartSpeed: 2000,
                    autoplay:false,
                    navText: [
                    '<button type="button" aria-label="Previous slide"><span class="visually-hidden">Previous</span><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>',
                    '<button type="button" aria-label="Next slide"><span class="visually-hidden">Next</span><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>'
                    ],
                    responsive:{
                        0:{
                            items:1
                        },				
                        550:{
                            items:1
                        },
                        768:{
                            items:1
                        },
                        1000:{
                            items:1
                        }
                    }
                })
            }
        }

        $(document).on("click","#reset-rating", function(){
            $("input[name='reviews[]']").prop("checked", false);
            review = null;
            fetchServices();
        });

        $(document).on("click","#apply-rating", function(){
             review = [];
            $("input[name='reviews[]']").each(function(){
                if($(this).is(":checked")) {
                    review.push($(this).val());
                }
            })
            fetchServices();
        });

        $(document).on("click","#apply-budget", function(){
            under_budget = $("input[name=budget]:checked").val();
            let custom_budget = $("input[name='custom_budget']").val();
            if(custom_budget) {
              under_budget = custom_budget;
            }
            fetchServices();
        });

        $(document).on("click","#reset-budget", function(){
            under_budget = null;
            $("input[name='custom_budget']").val("");
            $("input[name=budget]").prop("checked", false);
            fetchServices();
        });

        $(document).on("click","#apply-delivery", function(){
            upto_delivery_days = null;
            upto_delivery_days = $("input[name=delivery_time]:checked").val();
            fetchServices();
        });

        $(document).on("click", "#reset-delivery", function(){
            upto_delivery_days = null;
            $("input[name=delivery_time]").prop("checked", false);
            fetchServices();
        });

        $(document).on("click", ".sortfilter", function(){
            $(".sortfilter").removeClass("active");
            $(this).addClass("active");
            sort_by = $(this).data("sort");
            $(".selected-sort").text($(this).data("name"));
            fetchServices();
        });

        $(document).on("click", ".fav-icon", function(){
            $(this).toggleClass("favourite"); 
        });
    });
    
})();
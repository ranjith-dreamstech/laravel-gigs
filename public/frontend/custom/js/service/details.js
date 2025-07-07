/* global $, loadTranslationFile, FormData, setTimeout, document, showToast, _l */
"use strict";
(async () => {
    await loadTranslationFile("web", "home,common");
    const isRtl = $("body").data("dir") && $("body").data("dir") == "rtl" ? true : false;
    $(document).ready(function () {
        fetchServiceDetails();
        
        function fetchServiceDetails() {
            const slug = $("#slug").data("slug");
            $.ajax({
                url : "/list-details-gigs",
                type : "POST", 
                data : {
                    slug : slug,
                    _token : $("meta[name=\"csrf-token\"]").attr("content")
                },
                dataType : "json",
                beforeSend : function() {
                   $(".main-content").addClass("d-none");
                   $(".service-loader").removeClass("d-none");
                },
                success : function(response) {
                    if(response.code === 200 && response.data) {
                        populateServiceDetails(response.data);
                        renderCarousel(response.data.gig_image);
                        renderFaqs(response.data.faqs);
                        renderRecentWorks(response.data.recent_works);
                        renderProviderProfile(response.data.provider_info);
                    }
                },
                complete : function() {
                    $(".main-content").removeClass("d-none");
                    $(".service-loader").addClass("d-none");
                }
            });
        }

        function populateServiceDetails(service) {
            $(".breadcrumb-title").html(DOMPurify.sanitize(service.title));
            $(".service-rating").html(DOMPurify.sanitize(service.rating ? service.rating : 0));

            let reviewCount = service.reviews ? service.reviews : 0;
            let orderCount  = service.order_in_queue ? service.order_in_queue : 0;
            let buyer = service.buyer ? service.buyer : "";

            $(".reviews-count").html(`(${reviewCount}) ${_l("web.common.reviews")}`);
            $(".order_in_queue").html(`${orderCount} ${_l("web.home.orders_in_queue")}`);
            $(".created_at").html(DOMPurify.sanitize(`${_l("web.home.created_on")} : ${service.created_at}`));
            $(".buyer").html(DOMPurify.sanitize(buyer.charAt(0).toUpperCase() + buyer.slice(1)));
            $(".location").html(DOMPurify.sanitize(service.location));
            $(".service-description").html(DOMPurify.sanitize(service.description));
            $(".why-work-with-me").html(DOMPurify.sanitize(service.why_work_with_me));
            if(service.why_work_with_me){
                $(".why-work-section").removeClass("d-none");
            }else{
                $(".why-work-section").addClass("d-none");
            }
        }
        
        function renderProviderProfile(provider) {
            if(!provider) {
                $(".member-widget").addClass("d-none");
            }
            let rating = provider.rating ? provider.rating : 0;
            let reviews = provider.reviews ? provider.reviews : 0;
            const ratingHtml = `${rating} (${reviews} Reviews)`;
            $(".provider-img").attr("src", provider.provider_image);
            $(".provider-name").html(DOMPurify.sanitize(provider.provider_name ?? "Andrew Smith"));
            $(".provider-rating").html(DOMPurify.sanitize(ratingHtml));
            $(".provider-location").html(DOMPurify.sanitize(provider.location ?? "N/A"));
            $(".provider-member_since").html(DOMPurify.sanitize(provider.member_since ?? "N/A"));
            $(".provider-speaks").html(DOMPurify.sanitize(provider.speaks ?? "N/A"));
            $(".provider-last_project_delivery").html(DOMPurify.sanitize(provider.last_project_delivery ?? "N/A"));
            $(".provider-avg_response_time").html(DOMPurify.sanitize(provider.avg_response_time ?? "N/A"));
            renderAboutMe(provider.about_me);
            $(".member-widget").removeClass("d-none");
        }
        function renderAboutMe(aboutText) {
            const visibleLimit = 100;
        
            if (!aboutText) return;
        
            const visibleText = aboutText.substring(0, visibleLimit);
            const hiddenText = aboutText.length > visibleLimit ? aboutText.substring(visibleLimit) : "";
        
            // Inject the content directly into the existing markup
            const aboutHtml = `${visibleText}${hiddenText ? `<span class="more-content" style="display:none;">${hiddenText}</span>` : ""}`;
        
            $(".about-me-container p").html(DOMPurify.sanitize(aboutHtml));
        
            if (hiddenText) {
                $(".about-me-container .read-more").show();
            } else {
                $(".about-me-container .read-more").hide();
            }
        }
        
        
        
        function renderCarousel(images) {
            const imageItems = images.map(image => {
                return `<div class="service-img-wrap">
                            <img src="${image}" class="img-fluid" alt="Slider Img">
                        </div>`;
            }).join("");
        
            const sliderHtml = `<div class="slide-part">
                                    <div class="slider service-slider">
                                        ${imageItems}
                                    </div>
                                </div>`;
        
            const thumbnailImages = images.map(image => {
                return `<div>
                            <img src="${image}" class="img-fluid" alt="Slider Img">
                        </div>`;
            }).join("");
        
            const thumbnailsHtml = `<div class="slider slider-nav-thumbnails">
                                        ${thumbnailImages}
                                    </div>`;
        
            const corouselHtml = sliderHtml + thumbnailsHtml;
        
            $("#service-carousel").html(DOMPurify.sanitize(corouselHtml));
        
            if ($(".service-slider").length > 0) {
                $(".service-slider").slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    rtl: isRtl,
                    fade: true,
                    asNavFor: ".slider-nav-thumbnails"
                });
            }
        
            if ($(".slider-nav-thumbnails").length > 0) {
                $(".slider-nav-thumbnails").slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    asNavFor: ".service-slider",
                    dots: false,
                    rtl: isRtl,
                    arrows: false,
                    centerMode: false,
                    focusOnSelect: true
                });
            }
        }

        function renderFaqs(faqs) {
            if (!faqs || faqs.length === 0) {
                $(".service-faq").addClass("d-none");
                return;
            }
            const faqItems = faqs
                .map(
                    (faq, index) => `
                            <div class="faq-card">
                                <h4 class="faq-title">
                                    <a class="collapsed" data-bs-toggle="collapse" href="#faq_${index}"
                                        aria-expanded="false">${faq.question}</a>
                                </h4>
                                <div id="faq_${index}" class="card-collapse collapse">
                                    <div class="faq-content">
                                        <p>${faq.answer}</p>
                                    </div>
                                </div>
                            </div>`
                )
                .join("");
                
             $(".faq-lists").html(DOMPurify.sanitize(faqItems));
             $(".service-faq").removeClass("d-none");
        
        }
        
        function renderRecentWorks(works) {
            if (!works || works.length === 0) {
                $("#recent-works-section").addClass("d-none");
                return;
            }
        
            const workItems = works.map(work => {
                return `<div class="recent-img">
                            <img src="${work.image}" class="img-fluid" alt="Slider Img">
                        </div>`;
            });
        
            const $carousel = $(".recent-carousel");
        
            if ($carousel.hasClass("owl-loaded")) {
                $carousel.trigger("destroy.owl.carousel");
                $carousel.html("");
            }
        
            $carousel.html(DOMPurify.sanitize(workItems.join("")));
            $("#recent-works-section").removeClass("d-none");
        
            setTimeout(function () {
                if ($carousel.length > 0) {
                    $carousel.owlCarousel({
                        loop: true,
                        margin: 24,
                        nav: true,
                        dots: false,
                        rtl: isRtl,
                        smartSpeed: 2000,
                        autoplay: false,
                        navText: [
                            "<i class=\"fa-solid fa-chevron-left\"></i>",
                            "<i class=\"fa-solid fa-chevron-right\"></i>"
                        ],
                        navContainer: ".mynav1",
                        responsive: {
                            0: {
                                items: 1
                            },
                            550: {
                                items: 1
                            },
                            768: {
                                items: 2
                            },
                            1200: {
                                items: 3
                            }
                        }
                    });
                }
            }, 100);
        }

        $("#reviewForm").validate({
            rules: {
                comments: {
                    required: true,
                    minlength: 3,
                },
            },
            messages: {
                comments: {
                    required: _l("web.home.comments_required"),
                    minlength: _l("web.home.comments_minlength"),
                },
            },
            errorPlacement: function (error, element) {
                var errorId = element.attr("id") + "_error";
                if (element.hasClass("select2-hidden-accessible")) {
                    $("#" + errorId).text(error.text());
                } else {
                    $("#" + errorId).text(error.text());
                }
            },
            highlight: function (element) {
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element).next(".select2-container").addClass("is-invalid").removeClass("is-valid");
                }
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                if ($(element).hasClass("select2-hidden-accessible")) {
                    $(element).next(".select2-container").removeClass("is-invalid").addClass("is-valid");
                }
                $(element).removeClass("is-invalid").addClass("is-valid");
                var errorId = element.id + "_error";
                $("#" + errorId).text("");
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onchange: function (element) {
                $(element).valid();
            },
            submitHandler: function () {
                let formData = new FormData();
                formData.append("comments", $("#comments").val());
                formData.append("ratings", selectedRatings);
                formData.append("gigs_id", $("#gigs_id").val());

                $.ajax({
                    type: "POST",
                    url: "/user/add-review",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
                    },
                    beforeSend: function () {
                        $(".submit-review").attr("disabled", true).html(`
                            <span class="spinner-border spinner-border-sm align-middle me-1" role="status" aria-hidden="true"></span> ${_l("web.common.submitting")}..
                        `);
                    },
                    success: function (resp) {
                        $("#reviewForm")[0].reset();
                        $(".error-text").text("");
                        $(".form-control").removeClass("is-invalid is-valid");
                        $(".submit-review").removeAttr("disabled").html(_l("web.home.submit_review"));
                        $(".gigs_ratings").prop("checked", false);
                        if (resp.code === 200) {
                            showToast("success", resp.message);
                            $("#leave_review_card").addClass("d-none");
                            listReviews();
                        }
                    },
                    error: function (error) {
                        $(".error-text").text("");
                        $(".form-control").removeClass("is-invalid is-valid");
                        $(".submit-review").removeAttr("disabled").html(_l("web.home.submit_review"));
                        if (error.responseJSON.code === 422) {
                            $.each(
                                error.responseJSON.errors,
                                function (key, val) {
                                    $("#" + key).addClass("is-invalid");
                                    $("#" + key + "_error").text(val[0]);
                                }
                            );
                        } else {
                            showToast("error", error.responseJSON.message);
                        }
                    },
                });
            },
        });

        let selectedRatings = 0;
        $(".gigs_ratings").on("click", function () {
            let selectedValue = $(this).val();
            $(".gigs_ratings").each(function () {
                $(this).prop("checked", $(this).val() <= selectedValue);
            });
            selectedRatings = selectedValue;
        });
        
        listReviews();

    });

    function listReviews(page = 1, reset = true) {
        if (reset) {
            $("#review_list_container").empty();
        }
        
        $.ajax({
            url: "/user/reviews-list",
            type: "POST",
            data: {
                gigs_id: $("#gigs_id").val(),
                page: page,
            },
            headers: {
                Accept: "application/json",
                "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
            },
            beforeSend: function () {
                $(".load-more-reviews-btn").attr("disabled", true).html(`
                    <span class="spinner-border spinner-border-sm align-middle me-1" role="status" aria-hidden="true"></span> ${_l("web.common.loading")}..
                `);
            },
            complete: function () {
                $(".load-more-reviews-btn").removeAttr("disabled").html(`${_l("web.common.load_more")}`);
            },
            success: function (response) {
                if (response.code === 200 && response.data) {
                    renderReviewsMeta(response.data.reviews_meta);
                    renderReviews(response.data.reviews);
                    if (response.data.next_page) {
                        $(".load-more-reviews-btn").data("page", response.data.next_page).removeClass("d-none");
                    } else {
                        $(".load-more-reviews-btn").addClass("d-none");
                    }
                }
            },
            error: function (res) {
                if (res.responseJSON.code === 500) {
                    showToast("success", res.responseJSON.message);
                } else {
                    showToast("error", _l("web.common.default_retrieve_error"));
                }
            },
        });
    }

    function renderReviewsMeta(reviews_meta) {
        let avgRating = reviews_meta.avg_ratings;
        let starRatingCount = reviews_meta.star_ratings_count;
        let starRatingCountPercentage = reviews_meta.star_ratings_percentage;
        $(".total_reviews").text(reviews_meta.total_reviews);
        $(".average_ratings").text(reviews_meta.avg_ratings);
        let starsHTML = "";
        for (let i = 1; i <= 5; i++) {
            if (avgRating >= i) {
                starsHTML += "<i class=\"ti ti-star-filled text-warning\"></i>";
            } else if (avgRating >= i - 0.5) {
                starsHTML += "<i class=\"ti ti-star-half-filled text-warning\"></i>";
            } else {
                starsHTML += "<i class=\"ti ti-star text-muted\"></i>";
            }
        }
        $(".star_icons").html(starsHTML);
        $("#1_star_progress").width(starRatingCountPercentage["1_star"]);
        $("#2_star_progress").width(starRatingCountPercentage["2_star"]);
        $("#3_star_progress").width(starRatingCountPercentage["3_star"]);
        $("#4_star_progress").width(starRatingCountPercentage["4_star"]);
        $("#5_star_progress").width(starRatingCountPercentage["5_star"]);

        $("#1_star_count").text(starRatingCount["1_star"]);
        $("#2_star_count").text(starRatingCount["2_star"]);
        $("#3_star_count").text(starRatingCount["3_star"]);
        $("#4_star_count").text(starRatingCount["4_star"]);
        $("#5_star_count").text(starRatingCount["5_star"]);
    }

    function renderReviews(reviews) {
        if (reviews.length > 0) {
            $("#review_list_main_card").removeClass("d-none");
            $.each(reviews, function (index, review) {
                let starsHtml = "";

                if (review.ratings >= 0.75) {
                    starsHtml = "<i class=\"fa-solid fa-star filled\"></i>";
                } else if (review.ratings >= 0.25) {
                    starsHtml = "<i class=\"fa-solid fa-star-half-stroke filled\"></i>";
                } else {
                    starsHtml = "<i class=\"fa-regular fa-star\"></i>";
                }

                $("#review_list_container").append(`
                    <li class="border-0">
                        <div class="review-wrap">
                            <div class="review-user-info">
                                <div class="review-img">
                                    <img src="${review.user.profile_image}" alt="img">
                                </div>
                                <div class="reviewer-info">
                                    <div class="reviewer-loc">
                                        <h6><a href="javascript:void(0);">${review.user.name}</a></h6>
                                    </div>
                                    <div class="reviewer-rating">
                                        <div class="star-rate">
                                            <span class="ratings">
                                                ${starsHtml}
                                            </span>
                                            <span class="rating-count">${review.ratings}</span>
                                        </div>
                                    </div>
                                    <div class="reviewer-time">
                                        <p>${review.review_date}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="review-content">
                                <p>${review.comments}</p>
                                ${
                                    ($("#auth_user_id").val()) ? 
                                        `<a class="reply-btn bg-light review_reply_btn" href="javascript:void(0);" data-id="${review.id}">
                                            <i class="feather-corner-up-left"></i>${_l("web.home.reply")}
                                        </a>` : 
                                        `<a class="reply-btn bg-light" href="/login" data-id="${review.id}">
                                            <i class="feather-corner-up-left"></i>${_l("web.home.reply")}
                                        </a>`
                                }
                            </div>
                            <div class="review_reply_box" style="display: none;">
                            </div>
                            <div class="review_reply_list" id="review_reply_list_${review.id}">

                            </div>
                        </div>
                    </li>
                `);
                if (review.replies) {
                    renderReviewReplies(review.id, review.replies);
                }
            });
        } else {
            $("#review_list_main_card").addClass("d-none");
        }
    }

    function renderReviewReplies(review_id, replies) {
        $.each(replies, function (index, reply) {
            $("#review_reply_list_" + review_id).append(`
                <div class="mt-3 ms-4">
                    <div class="review-active">
                        <div class="review-user-info">
                            <div class="review-img">
                                <img src="${reply.user.profile_image}" alt="img">
                            </div>
                            <div class="reviewer-info">
                                <div class="reviewer-loc">
                                    <h6><a href="javascript:void(0);">${reply.user.name}</a></h6>
                                </div>
                                <div class="reviewer-time">
                                    <p>${reply.reply_date}</p>
                                </div>
                            </div>
                        </div>
                        <div class="review-content">
                            <p>${reply.comments}</p>
                        </div>
                    </div>
                </div>
            `);
        });
    }

    $(document).on("click", ".review_reply_btn", function () {
        if (!$("#review_list_container").data("booking") && ($("#review_list_container").data("gig_user_id") != $("#auth_user_id").val())) {
            showToast("error", _l("web.home.reply_not_allowed"));
        } else {
            $(".review_reply_box").empty();
            let parentContainer = $(this).closest(".review-wrap");
            let replyContainer = parentContainer.find(".review_reply_box");
            let review_id = $(this).data("id");
        
            replyContainer.html(`
                <form id="reply_review_form">
                    <div class="input-group mt-3 reply-box">
                        <textarea class="form-control reply-text" id="reply_comments" name="reply_comments" rows="1" placeholder="${_l(
                            "web.home.write_a_reply"
                        )}..."></textarea>
                        <button class="btn btn-primary btn-sm send-reply">
                        <i class="fa-solid fa-paper-plane me-1"></i> ${_l("web.home.send_reply")}
                        </button>
                    </div>
                    <span class="error-text text-danger" id="reply_comments_error"></span>
                </form>
            `);
            $(".review_reply_box").not(replyContainer).slideUp();
            replyContainer.slideToggle();
        
            $("#reply_review_form").validate({
                rules: {
                    reply_comments: {
                        required: true,
                        minlength: 3,
                    },
                },
                messages: {
                    reply_comments: {
                        required: _l("web.home.reply_comments_required"),
                        minlength: _l("web.home.reply_comments_minlength"),
                    },
                },
                errorPlacement: function (error, element) {
                    var errorId = element.attr("id") + "_error";
                    if (element.hasClass("select2-hidden-accessible")) {
                        $("#" + errorId).text(error.text());
                    } else {
                        $("#" + errorId).text(error.text());
                    }
                },
                highlight: function (element) {
                    if ($(element).hasClass("select2-hidden-accessible")) {
                        $(element)
                            .next(".select2-container")
                            .addClass("is-invalid")
                            .removeClass("is-valid");
                    }
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function (element) {
                    if ($(element).hasClass("select2-hidden-accessible")) {
                        $(element)
                            .next(".select2-container")
                            .removeClass("is-invalid")
                            .addClass("is-valid");
                    }
                    $(element).removeClass("is-invalid").addClass("is-valid");
                    var errorId = element.id + "_error";
                    $("#" + errorId).text("");
                },
                onkeyup: function (element) {
                    $(element).valid();
                },
                onchange: function (element) {
                    $(element).valid();
                },
                submitHandler: function (form) {
                    let formData = new FormData(form);
                    formData.append("review_id", review_id);
                    formData.append("gigs_id", $("#gigs_id").val());
        
                    $.ajax({
                        type: "POST",
                        url: "/user/add-reply-review",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            Accept: "application/json",
                            "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr(
                                "content"
                            ),
                        },
                        beforeSend: function () {
                            $(".send-reply").attr("disabled", true).html(`
                                <span class="spinner-border spinner-border-sm align-middle me-1" role="status" aria-hidden="true"></span> ${_l(
                                    "web.common.sending"
                                )}..
                            `);
                        },
                        success: function (resp) {
                            $(".error-text").text("");
                            $(".form-control").removeClass("is-invalid is-valid");
                            $(".send-reply").removeAttr("disabled").html(_l("web.common.send_reply"));
                            $("#reply_review_form")[0].reset();
                            $(".review_reply_box").empty();
        
                            if (resp.code === 200) {
                                showToast("success", resp.message);
                                listReviews();
                            }
                        },
                        error: function (error) {
                            $(".error-text").text("");
                            $(".form-control").removeClass("is-invalid is-valid");
                            $(".send-reply")
                                .removeAttr("disabled")
                                .html(_l("web.common.send_reply"));
                            if (error.responseJSON.code === 422) {
                                $.each(error.responseJSON.errors, function (key, val) {
                                    $("#" + key).addClass("is-invalid");
                                    $("#" + key + "_error").text(val[0]);
                                });
                            } else {
                                showToast("error", error.responseJSON.message);
                            }
                        },
                    });
                },
            });
        }
    });

    $(".load-more-reviews-btn").on("click", function () {
        let page = $(this).data("page");
        listReviews(page, false);
    });
    

})();
/* global $, loadTranslationFile, document, showToast, _l */
(async () => {
    await loadTranslationFile("web", "user, common");

    $(document).on("click", ".open-delete-modal", function () {
        let reviewId = $(this).data("id");
        $("#delete_review_id").val(reviewId); 
        $("#deleteReviewModal").modal("show");
    });
    
    $("#deleteReviewForm").on("submit", function (e) {
        e.preventDefault();
    
        let reviewId = $("#delete_review_id").val();

        $(".submitbtn").prop("disabled", true).html(`
            <span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span>${ _l("web.user.deleting")}
        `);
    
        $.ajax({
            type: "POST",
            url: "/reviews/" + reviewId + "/sellerreviewdelete",
            data: $(this).serialize(), 
            success: function (response) {
            
                $(".submitbtn").prop("disabled", false).text(_l("web.user.yes_delete"));
                $("#deleteReviewModal").modal("hide");

                $("#review-row-" + reviewId).remove();
    
                showToast("success", response.message);
            },
            error: function (xhr) {

                $(".submitbtn").prop("disabled", false).text( _l("web.user.yes_delete"));
                $("#deleteReviewModal").modal("hide");
 
                showToast("error", xhr.responseJSON.message || _l("web.user.something_went_wrong"));
            }
        });
    });
    
    

    function fetchMyReviews(page = 1) {
        $.ajax({
            type: "GET",
            url: "/seller/reviews?page=" + page,
            beforeSend: function () {
                $(".load_more_btn").attr("disabled", true).html(`
                    <span class="spinner-border spinner-border-sm align-middle me-1" role="status" aria-hidden="true"></span> ${_l("web.user.loading")}
                `);
            },
            complete: function () {
                $(".load_more_btn").removeAttr("disabled").html(`<i class="ti ti-loader-3 me-2"></i> ${_l("web.user.load_more")}`);
                $(".card-loader").hide();
                $(".label-loader, .input-loader").hide();
                $(".real-label, .real-table, .real-card").removeClass("d-none");
            },
            success: function (res) {
                if (res.data.length) {

                    if (page === 1) {
                        $("#review_list").html("");
                    }

                    $.each(res.data, function (i, review) {
                        $("#review_list").append(renderReviewHTML(review));
                    });

                    if (res.next_page) {
                        $(".load_more_btn").data("page", res.next_page).removeClass("d-none");
                    } else {
                        $(".load_more_btn").addClass("d-none");
                    }
                } else {
                    $("#review_list").html(`<p class="text-center"> ${_l("web.user.no_reviews_found")}</p>`);
                    $(".load_more_btn").addClass("d-none");
                }
            },
            error: function (xhr) {
                showToast("error", xhr.responseJSON?.message || _l("web.user.something_went_wrong"));
            }
        });
    }

    $(document).on("click", ".load_more_btn", function () {
        const nextPage = $(this).data("page") || 2;
        fetchMyReviews(nextPage); 
    });
    $(document).ready(function () {
        fetchMyReviews();
    });
    function renderReviewHTML(review) {
        let stars = "";
        
        // Create star rating based on the review rating
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="fa-solid fa-star ${i <= review.ratings ? "filled" : ""}"></i>`;
        }

        // Set profile image for the reviewer
        let profileImg = review.user?.user_detail?.profile_image
            ? `${review.user.user_detail.profile_image}`
            : "/assets/img/default-avatar.png";

        // Function to show time ago in human-readable format
        function timeAgo(date) {
            const now = new Date();
            const diffInSeconds = Math.floor((now - new Date(date)) / 1000);
            const diffInMinutes = Math.floor(diffInSeconds / 60);
            const diffInHours = Math.floor(diffInMinutes / 60);
            const diffInDays = Math.floor(diffInHours / 24);
            const diffInMonths = Math.floor(diffInDays / 30);
            const diffInYears = Math.floor(diffInDays / 365);

            if (diffInYears > 0) {
                return `${diffInYears} year${diffInYears > 1 ? "s" : ""} ago`;
            } else if (diffInMonths > 0) {
                return `${diffInMonths} month${diffInMonths > 1 ? "s" : ""} ago`;
            } else if (diffInDays > 0) {
                return `${diffInDays} day${diffInDays > 1 ? "s" : ""} ago`;
            } else if (diffInHours > 0) {
                return `${diffInHours} hour${diffInHours > 1 ? "s" : ""} ago`;
            } else if (diffInMinutes > 0) {
                return `${diffInMinutes} minute${diffInMinutes > 1 ? "s" : ""} ago`;
            } else {
                return `${diffInSeconds} second${diffInSeconds > 1 ? "s" : ""} ago`;
            }
        }


        const createdAtDiff = review.created_at ? timeAgo(review.created_at) : "";

        return `
            <li id="review-row-${review.id}" class="review-item">
                <div class="review-wrap">
                    <div class="review-user-info">
                        <div class="review-img">
                            <img src="${profileImg}" alt="img">
                        </div>
                        <div class="reviewer-info">
                            <div class="reviewer-loc">
                                <h6><a href="javascript:void(0);">${review.user?.name || "User"}</a></h6>
                            </div>
                            <div class="reviewer-rating">
                                <div class="star-rate">
                                    <span class="ratings">
                                        ${stars}
                                    </span>
                                    <span class="rating-count">${parseFloat(review.ratings).toFixed(1)}</span>
                                </div>
                                <p>${createdAtDiff}</p>
                            </div>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>${review.comments}</p>
                    </div>
                    <div class="table-action">
                        <a href="javascript:void(0);" class="open-delete-modal" data-id="${review.id}" data-url="/reviews/${review.id}">
                            <i class="feather-trash-2 text-error"></i>
                        </a>
                    </div>
                </div>
            </li>
        `;
    }

})();
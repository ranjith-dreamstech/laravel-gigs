/* global $, loadTranslationFile, setTimeout, document, _l */
(async () => {
    await loadTranslationFile("web", "user, common");

    $(document).on("click", ".open-delete-modal", function () {
        const reviewId = $(this).data("id");
        $("#delete_review_id").val(reviewId);
        $("#deleteReviewModal").modal("show");
    });

    $("#deleteReviewForm").on("submit", function (e) {
        e.preventDefault();

        const reviewId = $("#delete_review_id").val();

        const $submitBtn = $(".submitbtn");
        $submitBtn.prop("disabled", true).html(
            `<span class="spinner-border spinner-border-sm align-middle" role="status" aria-hidden="true"></span>${_l("web.user.deleting")}`
        );

        $.ajax({
            type: "POST",
            url: `/reviews/${reviewId}/delete`,
            data: $(this).serialize(),
            success: function (response) {
                $submitBtn.prop("disabled", false).text(_l("web.common.yes_delete"));
                $("#deleteReviewModal").modal("hide");
                $(`#review-row-${reviewId}`).remove();
                showToast("success", response.message);
            },
            error: function (xhr) {
                $submitBtn.prop("disabled", false).text(_l("web.common.yes_delete"));
                $("#deleteReviewModal").modal("hide");
                const errorMessage = xhr.responseJSON?.message || _l("web.user.something_went_wrong");
                showToast("error", errorMessage);
            }
        });
    });
  
    function showToast(type, message) {
        const toast = $(`<div class="toast ${type}">${message}</div>`);

        $("body").append(DOMPurify.sanitize(toast));

        setTimeout(() => {
            toast.fadeOut(() => {
                toast.remove();
            });
        }, 3000);
    }

    function fetchMyReviews(page = 1) {
        $.ajax({
            type: "GET",
            url: `/buyer/reviews?page=${page}`,
            beforeSend: function () {
                $(".load_more_btn")
                    .attr("disabled", true)
                    .html(
                        `<span class="spinner-border spinner-border-sm align-middle me-1" role="status" aria-hidden="true"></span>${_l("web.user.loading")}`
                    );
            },
            complete: function () {
                $(".load_more_btn")
                    .removeAttr("disabled")
                    .html(`<i class="ti ti-loader-3 me-2"></i>${_l("web.user.load_more")}`);
                $(".card-loader").hide();
                $(".label-loader, .input-loader").hide();
                $(".real-label, .real-table, .real-card").removeClass("d-none");
            },
            success: function (res) {
                if (res.data.length) {
                    if (page === 1) {
                        $("#review_list").html("");
                    }

                    res.data.forEach(function (review) {
                        $("#review_list").append(DOMPurify.sanitize(renderReviewHTML(review)));
                    });

                    if (res.next_page) {
                        $(".load_more_btn")
                            .data("page", res.next_page)
                            .removeClass("d-none");
                    } else {
                        $(".load_more_btn").addClass("d-none");
                    }
                } else {
                    $("#review_list").html(
                        `<p class="text-center">${_l("web.user.no_reviews_found")}</p>`
                    );
                    $(".load_more_btn").addClass("d-none");
                }
            },
            error: function (xhr) {
                const errorMessage = xhr.responseJSON?.message || _l("web.user.something_went_wrong");
                showToast("error", errorMessage);
            }
        });
    }

    $(document).ready(function () {
        fetchMyReviews();
    });

    $(document).on("click", ".load_more_btn", function () {
        const nextPage = $(this).data("page") || 2;
        fetchMyReviews(nextPage); 
    });

    function renderReviewHTML(review) {
        let stars = "";
        for (let i = 1; i <= 5; i++) {
            stars += `<i class="fa-solid fa-star ${i <= review.ratings ? "filled" : ""}"></i>`;
        }

        const profileImg = review.user?.user_detail?.profile_image
            ? review.user.user_detail.profile_image
            : "/assets/img/default-avatar.png";

        const timeAgo = (date) => {
            const now = new Date();
            const past = new Date(date);
            const diffInSeconds = Math.floor((now - past) / 1000);

            const minutes = Math.floor(diffInSeconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);
            const months = Math.floor(days / 30);
            const years = Math.floor(days / 365);

            if (years > 0) return `${years} year${years > 1 ? "s" : ""} ago`;
            if (months > 0) return `${months} month${months > 1 ? "s" : ""} ago`;
            if (days > 0) return `${days} day${days > 1 ? "s" : ""} ago`;
            if (hours > 0) return `${hours} hour${hours > 1 ? "s" : ""} ago`;
            if (minutes > 0) return `${minutes} minute${minutes > 1 ? "s" : ""} ago`;
            return `${diffInSeconds} second${diffInSeconds > 1 ? "s" : ""} ago`;
        };

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
                                    <span class="ratings">${stars}</span>
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
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_review"
                            class="open-delete-modal" data-id="${review.id}" data-url="/reviews/${review.id}">
                            <i class="feather-trash-2 text-error"></i>
                        </a>
                    </div>
                </div>
            </li>
        `;
    }
})();
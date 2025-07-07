/* global $, loadTranslationFile, document, showToast, _l */
(async () => {
    await loadTranslationFile("web", "user, common");

    $(document).ready(function () {
        fetchMySellers();
    });

})();

function renderSellerCard(seller) {
    return `
        <div class="col-xl-4 col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <span class="avatar"><a href="#"><img class="rounded-pill" src="${seller.profile_image}" alt="img"></a></span>
                    <h6 class="mb-1"><a href="#">${seller.name}</a></h6>
                    <p>${seller.job_title || ""}</p>
                    <p class="mb-0 location-text d-inline-flex align-items-center">
                        ${seller.country ? `<img src="${seller.country.flag_image}" alt="flag" class="me-1"> ${seller.country.name}` : ""}
                        <i class="ti ti-point-filled mx-1"></i> ${_l("web.user.total_gigs")} : ${seller.total_gigs}
                    </p>
                </div>
            </div>
        </div>`;
}

function fetchMySellers(page = 1) {
    $.ajax({
        type: "GET",
        url: "/buyer/my-sellers-list?page=" + page,
        beforeSend: function () {
            $(".load_more_btn").attr("disabled", true).html(`
                <span class="spinner-border spinner-border-sm align-middle me-1" role="status" aria-hidden="true"></span> ${_l("web.common.loading")}..
            `);
        },
        complete: function () {
            $(".load_more_btn").removeAttr("disabled").html(`<i class="ti ti-loader-3 me-2"></i> ${_l("web.common.load_more")}`);
            $(".card-loader").hide();
            $(".label-loader, .input-loader").hide();
            $(".real-label, .real-table, .real-card").removeClass("d-none");
        },
        success: function (res) {
            if (res.data.length) {
                $.each(res.data, function (i, seller) {
                    $("#my_sellers_list").append(renderSellerCard(seller));
                });

                if (res.next_page) {
                    $(".load_more_btn").data("page", res.next_page).removeClass("d-none");
                } else {
                    $(".load_more_btn").addClass("d-none");
                }
            } else {
                $("#my_sellers_list").html(`<p class="text-center">${_l("web.user.no_sellers_found")}</p>`);
                $(".load_more_btn").addClass("d-none");
            }
        },
        error: function (xhr) {
            showToast("error", xhr.responseJSON.message);
        }
    });
}

$(document).on("click", ".load_more_btn", function () {
    const nextPage = $(this).data("page");
    fetchMySellers(nextPage);
});
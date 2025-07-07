/* global $, FormData, document, showToast */
$("#sumbit_btn").on("click", function (e) {
    e.preventDefault(); // prevent default anchor behavior

    let finalFormData = new FormData();

    // Basic gig info
    let gigsId = $("#gigs_id").val();
    let gigsPrice = $("#gigs_price").val();
    finalFormData.append("gigs_id", gigsId);
    finalFormData.append("gigs_price", gigsPrice);

    // Quantity
    let quantity = $(".quantity").val();
    finalFormData.append("quantity", quantity ?? 1);

    // Extra services (checkboxes - multiple values)
    $("input[name=\"extra_service[]\"]:checked").each(function () {
        finalFormData.append("extra_service[]", $(this).val());
    });

    // Fast service
    let isFastService = $("input[name=\"fast_service\"]").is(":checked");
    finalFormData.append("fast_service", isFastService ? "yes" : "no");

    // UI feedback
    $("#sumbit_btn").text("Please Wait").prop("disabled", true);
    $(".backUserInfo").prop("disabled", true);

    $.ajax({
        url: "/create/booking",
        method: "POST",
        data: finalFormData,
        dataType: "json",
        contentType: false,
        processData: false,
        cache: false,
        headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
        },
    })
        .done((response) => {
            if (!response.status || !response.data) return;

            // Open the modal first
            const data = response.data;
            const currency = data.currency ?? "$";

            $("#gig_id").val(data.gig?.id ?? "");
            $("#extra_service_total").val(data.extra_services_total ?? 0);
            $("#extra_service_ids").val(
                data.extra_services?.map((s) => s.id).join(",") ?? ""
            );
            $("#fast_service_total").val(data.fast_service?.total ?? 0);
            $("#total_price").val(data.base_price_total ?? 0);
            $("#quantity").val(data.quantity ?? 1);
            $("#final_price").val(data.final_price ?? 0);

            // Set main gig image
            $("#gigs_image").attr(
                "src",
                data.gig.image ?? "/backend/assets/img/service/service-slide-01.jpg"
            );

            // Set gig title
            $("#gigs_title").text(data.gig.title ?? "N/A");

            // Remove Order ID line (if present)
            $("li:contains('ID :')").remove();

            // Calculate delivery date
            let totalDays = parseInt(data.gig.days);
            if (data.extra_services) {
                totalDays += data.extra_services.reduce(
                    (sum, e) => sum + parseInt(e.days),
                    0
                );
            }
            if (data.fast_service) {
                totalDays += parseInt(data.fast_service.days);
            }

            const deliveryDate = new Date();
            deliveryDate.setDate(deliveryDate.getDate() + totalDays);
            const deliveryString = deliveryDate.toLocaleString("en-US", {
                month: "short",
                day: "numeric",
                year: "numeric",
                hour: "numeric",
                minute: "numeric",
                hour12: true,
            });
            $("ul li:contains('Delivery')").text(
                `Delivery : ${deliveryString}`
            );

            // Seller info
            $("#gigs_owner").text(
                `${data.provide_info.first_name} ${data.provide_info.last_name}`
            );
            $("#gigs_rating").html(
                `<i class="fa-solid fa-star"></i>Ratings ${data.provide_info.rating} <span id="gigs_review">(${data.provide_info.review_count} Reviews)</span>`
            );
            $("#gigs_location").html(`${data.provide_info.address}`);
            $("#providerImg").attr(
                "src",
                data.provide_info.profile_image ?? "/backend/assets/img/user/user-05.jpg"
            );

            // Reset previous rows except base
            $("#gigs_service_price").closest("tbody").find("tr:gt(0)").remove();

            // Base service row
            $("#gigs_service_title").text(data.gig.title);
            $("#gigs_service_qut").text(data.quantity);
            $("#gigs_service_price").text(
                `${currency}${parseFloat(data.base_price_total).toFixed(2)}`
            );

            // Extra service rows
            if (data.extra_services && data.extra_services.length > 0) {
                let extraHtml = "";
                data.extra_services.forEach((extra, i) => {
                    extraHtml += `
                    <tr>
                        <td id="gigs_extra_title">Additional ${i + 1} : ${
                        extra.name
                    }</td>
                        <td id="gigs_extra_qut">1</td>
                        <td class="text-primary" id="gigs_extra_price">${currency}${parseFloat(
                        extra.price
                    ).toFixed(2)}</td>
                    </tr>`;
                });
                $("#gigs_service_price").closest("tbody").append(extraHtml);
            }

            // Fast service row
            if (data.fast_service) {
                const fastHtml = `
                <tr>
                    <td id="gigs_fast_title">Super Fast : ${
                        data.fast_service.title
                    }</td>
                    <td id="gigs_fast_qut">1</td>
                    <td class="text-primary" id="gigs_fast_price">${currency}${parseFloat(
                    data.fast_service.price
                ).toFixed(2)}</td>
                </tr>`;
                $("#gigs_service_price").closest("tbody").append(fastHtml);
            }

            // Set final total
            $(".detail-table tfoot th.text-primary").text(
                `${currency}${parseFloat(data.final_price).toFixed(2)}`
            );

            // Final modal show and reset button
            $("#order_details").modal("show");
            $("#sumbit_btn").text("Buy this gig").prop("disabled", false);
        })
        .fail((error) => {
            $("#serviceLoader").hide();
            $(".error-text").text("");
            $(".form-control").removeClass("is-invalid");
            $(".add_btn").removeAttr("disabled").html("Submit");

            $("#sumbit_btn").text("Buy this gig").prop("disabled", false);
            $(".backUserInfo").prop("disabled", false);

            if (error.status === 422) {
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (key, val) {
                        $("#" + key).addClass("is-invalid");
                        $("#" + key + "_error").text(val[0]);
                    });
                } else if (error.responseJSON.message) {
                    showToast("error", error.responseJSON.message);
                }
            } else {
                showToast(
                    "error",
                    error.responseJSON.message ||
                        "Something went wrong. Please try again."
                );
            }
        });
});

$(document).ready(function () {
    $("#validate_btn").on("click", function () {
        $("#validateVehicleBook").submit();
    });
});
$(document).ready(function () {
    initTable();
});

$("#search").on("input", function () {
    let searchQuery = $(this).val().trim();
    initTable(searchQuery); // Call initTable with search query
});

function initTable(search = "") {
    $(".table-loader").show();
    $(".input-loader").show();
    $(".real-table, .real-data").addClass("d-none");

    $.ajax({
        url: "/admin/gigs/datatable",
        type: "GET",
        data: { search: search },
        success: function (response) {
            let tableBody = "";
            if ($.fn.DataTable.isDataTable("#gigsTable")) {
                $("#gigsTable").DataTable().destroy();
            }

            if (response.code === 200 && response.data.length > 0) {
                let data = response.data;

                $.each(data, function (index, value) {
                    // Trim title to first 30 characters + "..."
                    let trimmedTitle =
                        value.title.length > 30
                            ? value.title.substring(0, 30) + "..."
                            : value.title;

                    // Format buyer
                    let buyer =
                        value.buyer === "buyer"
                            ? "On-Site"
                            : value.buyer.charAt(0).toUpperCase() +
                              value.buyer.slice(1);

                    tableBody += `<tr>
                        <td>${trimmedTitle}</td>
                        <td>${value.category.name}</td>
                        <td>${value.sub_category.name}</td>
                        <td>${value.general_price}</td>
                        <td>${value.days}</td>
                        <td>${value.no_revisions}</td>
                        <td>${buyer}</td>
                        <td>
                            <span class="badge ${
                                value.status == 1
                                    ? "badge-success-transparent"
                                    : "badge-danger-transparent"
                            } d-inline-flex align-items-center badge-sm">
                                <i class="ti ti-point-filled me-1"></i>${
                                    value.status == 1 ? "Active" : "Inactive"
                                }
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end p-2">
                                    <li>
                                        <a class="dropdown-item rounded-1" href="javascript:void(0);" onclick="detailsGigsSection('${
                                            value.id
                                        }')">
                                            <i class="ti ti-edit me-1"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item rounded-1" href="javascript:void(0);" onclick="detailGigsSection('${
                                            value.slug
                                        }')">
                                            <i class="ti ti-view me-1"></i>View Details
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>                                
                    </tr>`;
                });
            } else {
                tableBody += `
                            <tr>
                                <td colspan="9" class="text-center">No Data Available</td>
                            </tr>`;
                $(".table-footer").empty();
            }

            $("#gigsTable tbody").html(tableBody);
            if (response.data.length > 0) {
                $("#gigsTable").DataTable({
                    ordering: true,
                    searching: false,
                    pageLength: 10,
                    lengthChange: false,
                    drawCallback: function () {
                        $(".dataTables_info").addClass("d-none");
                        $(".dataTables_wrapper .dataTables_paginate").addClass(
                            "d-none"
                        );

                        let tableWrapper = $(this).closest(
                            ".dataTables_wrapper"
                        );
                        let info = tableWrapper.find(".dataTables_info");
                        let pagination = tableWrapper.find(
                            ".dataTables_paginate"
                        );

                        $(".table-footer")
                            .empty()
                            .append(
                                $(
                                    '<div class="d-flex justify-content-between align-items-center w-100"></div>'
                                )
                                    .append(
                                        $(
                                            '<div class="datatable-info"></div>'
                                        ).append(info.clone(true))
                                    )
                                    .append(
                                        $(
                                            '<div class="datatable-pagination"></div>'
                                        ).append(pagination.clone(true))
                                    )
                            );
                        $(".table-footer")
                            .find(".dataTables_paginate")
                            .removeClass("d-none");
                    },
                });
            }
        },
        error: function (error) {
            if (error.responseJSON.code === 500) {
                toastr.error(error.responseJSON.message);
            } else {
                toastr.error("An error occurred while retrieving door type.");
            }
        },
        complete: function () {
            $(".table-loader").hide();
            $(".label-loader, .input-loader").hide();
            $(".real-label, .real-table, .real-data").removeClass("d-none");
        },
    });
}

function editGigsSection(gigId) {
    // Set the value of 'id' in the modal
    $("#id").val(gigId);

    // Open the modal (assuming it's already defined in your HTML)
    $("#gigs_modal").modal("show");
}

$("#gigsStatusForm").on("submit", function (e) {
    e.preventDefault();

    const status = $("#status").val();
    const gigs_id = $("#id").val(); // You need to set this dynamically when opening the modal

    if (!status) {
        showToast("error", "Please select a status.");
        return;
    }

    if (!gigs_id) {
        showToast("error", "ID is missing.");
        return;
    }

    $.ajax({
        url: "/admin/update-status",
        method: "POST",
        data: {
            id: gigs_id,
            status: status,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            Accept: "application/json",
        },
        success: function (response) {
            if (response.code === 200) {
                $("#gigs_modal").modal("hide"); // Adjust ID if different
                showToast("success", "Gigs ststus updated successfully.");
                initTable(); // Refresh table
            } else {
                toastr.error(response.message ?? "Failed to update status.");
            }
        },
        error: function (xhr) {
            toastr.error(
                xhr.responseJSON?.message ?? "Server error. Try again."
            );
        },
    });
});

function detailsGigsSection(id) {
    $("#id").val(id);

    $.ajax({
        type: "GET",
        url: "/admin/gigs-details/" + id,
        success: function (response) {
            if (response.code === 200) {
                const data = response.data;
    
                const safe = (val) => val && val !== "null" && val !== "$null";
    
                let faqs = [];
                let extraServices = [];
                try {
                    faqs = JSON.parse(data.faqs || "[]");
                } catch {}
                try {
                    extraServices = JSON.parse(data.extra_service || "[]");
                } catch {}
    
                let rows = "";
    
                const addRow = (label, value) => {
                    if (safe(value)) {
                        rows += `
                            <tr>
                                <th class="text-nowrap">${label}</th>
                                <td>${value}</td>
                            </tr>
                        `;
                    }
                };
    
                addRow("Title", data.title);
                addRow("Price", `$${data.general_price}`);
                addRow("Days", data.days);
                addRow("No. of Revisions", data.no_revisions);
                addRow("Tags", data.tags);
                addRow("Fast Service Title", data.fast_service_tile);
                addRow("Fast Service Price", `$${data.fast_service_price}`);
                addRow("Fast Service Days", data.fast_service_days);
                addRow("Buyer", data.buyer);
                addRow("Status", data.status == 1 ? "Active" : "Inactive");
    
                if (extraServices.length) {
                    rows += `<tr><th colspan="2" class="pt-4 pb-2">Extra Services</th></tr>`;
                    extraServices.forEach((extra, i) => {
                        if (safe(extra.title) || safe(extra.price) || safe(extra.days)) {
                            rows += `
                                <tr>
                                    <th class="text-nowrap">Service ${i + 1}</th>
                                    <td>
                                        ${safe(extra.title) ? extra.title : ""}
                                        ${safe(extra.price) ? `- $${extra.price}` : ""}
                                        ${safe(extra.days) ? `, ${extra.days} day(s)` : ""}
                                    </td>
                                </tr>
                            `;
                        }
                    });
                }
    
                if (faqs.length) {
                    rows += `<tr><th colspan="2" class="pt-4 pb-2">FAQs</th></tr>`;
                    faqs.forEach((faq, i) => {
                        if (safe(faq.question)) {
                            rows += `
                                <tr>
                                    <th class="text-nowrap">Q${i + 1}</th>
                                    <td>${faq.question}</td>
                                </tr>
                            `;
                        }
                        if (safe(faq.answer)) {
                            rows += `
                                <tr>
                                    <th class="text-nowrap">A${i + 1}</th>
                                    <td>${faq.answer}</td>
                                </tr>
                            `;
                        }
                    });
                }
    
                const modalContent = `
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <tbody>
                                ${rows}
                            </tbody>
                        </table>
                    </div>
                `;

                $("#append_details").html(DOMPurify.sanitize(modalContent));

                // Open modal
                $("#gigs_modal").modal("show");
            }
        }
    });
    

    
}

function detailGigsSection(slug) {
    window.location.href = `/admin/gigs-detail/${slug}`;
}
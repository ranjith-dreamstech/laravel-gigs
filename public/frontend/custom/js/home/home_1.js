/* global $, document, setTimeout, clearTimeout, showToast */
"use strict";

$(document).ready(function () {
    const $input = $("#keyword");
    const $suggestions = $("#keyword-suggestions");
    const cache = {};
    let searchTimeout;

    $input.on("keyup", function () {
        const query = $(this).val().trim().toLowerCase();

        clearTimeout(searchTimeout);

        if (query.length < 2) {
            $suggestions.hide();
            return;
        }

        if (cache[query]) {
            renderSuggestions(cache[query]);
            return;
        }

        searchTimeout = setTimeout(() => {
            $.ajax({
                url: "/search-gigs",
                method: "GET",
                data: { query },
                dataType: "json",
                success: function (response) {
                    if (response && Array.isArray(response.data)) {
                        cache[query] = response.data;
                        renderSuggestions(response.data);
                    } else {
                        renderSuggestions([]);
                    }
                },
                error: function () {
                    renderSuggestions([]);
                }
            });
        }, 300);
    });

    function renderSuggestions(data) {
        $suggestions.empty();

        if (data.length > 0) {
            data.forEach(gig => {
                $suggestions.append(
                    $("<li>")
                        .attr("data-id", gig.id)
                        .text(gig.title)
                );
            });
        } else {
            $suggestions.append($("<li>").text("No results found"));
        }

        $suggestions.show();
    }

    $(document).on("click", "#keyword-suggestions li", function () {
        $input.val($(this).text());
        $suggestions.hide();
    });

    $(document).on("click", function (event) {
        if (!$(event.target).closest(".group-img").length) {
            $suggestions.hide();
        }
    });
});

$(document).on("click", ".wishlist-icon", function () {
    const id = $(this).data("id");

    if (!id) return;

    $.ajax({
        type: "POST",
        url: "/user/add-to-wishlist",
        data: {
            id,
            _token: $("meta[name='csrf-token']").attr("content")
        },
        dataType: "json",
        success: function (response) {
            const type = response.status === "success" ? "success" : "error";
            showToast(type, response.message);
        },
        error: function () {
            showToast("error", "An unexpected error occurred.");
        }
    });
});

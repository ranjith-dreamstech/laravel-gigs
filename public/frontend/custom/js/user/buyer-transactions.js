"use strict";

/**
 * Buyer Transaction Module
 * - Loads translations
 * - Fetches and displays transaction data
 * - Handles filters and modal view
 */

/* global $, loadTranslationFile, showToast, _l, document, console */

(async () => {
  await loadTranslationFile("web", "user,common");

  $(document).ready(function () {
    buyerTransaction();
  });

  // Toggle collapse arrow
  $(document).on("click", ".toggle-collapse", function () {
    const target = $(this).data("target");
    $(target).slideToggle(200);
    $(this).find("i").toggleClass("ti-transition-top ti-transition-bottom");
  });

  // Re-fetch on filter change
  $(document).on("change", ".payment-filter", function () {
    buyerTransaction();
  });

  // Show modal details
  $(document).on("click", ".view-eye", function () {
    try {
      const txn = $(this).data("txn");
      if (txn) {
        viewTransactions(txn);
      }
    } catch (error) {
        console.error("Transaction view error:", error);
    showToast("error", _l("web.common.error_loading_transaction_details"));
}
  });

  function getSelectedPaymentTypes() {
    return $(".payment-filter:checked")
      .map(function () {
        return $(this).val();
      })
      .get();
  }

  function buyerTransaction() {
    const paymentTypes = getSelectedPaymentTypes();

    $.ajax({
      url: "/buyer/transaction/list",
      type: "GET",
      data: { payment_types: paymentTypes },
      success: function (response) {
        const currency = response.currency_symbol || "$";
        const data = response.data || [];

        $(".total_credit").text(`${currency}${parseFloat(response.total_credit).toFixed(2)}`);
        $(".total_debit").text(`${currency}${parseFloat(response.total_debit).toFixed(2)}`);
        $(".available_balance").text(`${currency}${parseFloat(response.total_balance).toFixed(2)}`);
        $(".total-transactions").text(data.length);

        let tableBody = "";

        if ($.fn.DataTable.isDataTable("#buyerTransaction")) {
          $("#buyerTransaction").DataTable().destroy();
        }

        if (data.length > 0) {
          $.each(data, function (index, txn) {
            const imgUrl = txn.gig?.image_meta?.gigs_image_url || "/assets/img/default-placeholder.jpg";
            const gigTitle = txn.gig?.title || "N/A";
            const formattedAmount = `${currency}${parseFloat(txn.final_price.replace("$", "")).toFixed(2)}`;

            const transactionPayload = {
              transaction_id: txn.order_id || txn.id,
              transaction_type: txn.payment_type,
              total_gross_amount: txn.final_price,
              total_commission_amount: txn.transactions?.total_commission_amount || 0,
              currency: currency,
              payment_method: txn.payment_type,
              name: txn.user?.name || "N/A",
              receiver_name: txn.seller?.name || "N/A"
            };

            tableBody += `
              <tr>
                <td>#${transactionPayload.transaction_id}</td>
                <td>
                  <h2 class="table-avatar d-flex align-items-center">
                    <a href="javascript:void(0);" class="avatar avatar-md">
                      <img src="${imgUrl}" alt="Gig Image">
                    </a>
                    <a href="javascript:void(0);" class="text-dark">${gigTitle}</a>
                  </h2>
                </td>
                <td>${new Date(txn.booking_date).toLocaleDateString()}</td>
                <td>${txn.payment_type}</td>
                <td class="text-start">${formattedAmount}</td>
                <td>
                  <div class="table-action">
                    <a href="javascript:void(0);"
                       class="border-rounded view-eye"
                       data-bs-toggle="modal"
                       data-bs-target="#transaction_details"
                       data-txn='${JSON.stringify(transactionPayload)}'>
                       <i class="feather-eye"></i>
                    </a>
                  </div>
                </td>
              </tr>`;
          });
        } else {
          tableBody = `<tr><td colspan="6" class="text-center">${_l("web.user.no_buyer_transaction_available")}</td></tr>`;
        }

        $("#buyerTransaction tbody").html(DOMPurify.sanitize(tableBody));

        if (data.length > 0) {
          $("#buyerTransaction").DataTable({
            ordering: true,
            searching: false,
            pageLength: 10,
            lengthChange: false
          });
        }
      },
      error: function () {
        showToast("error", _l("web.user.error_occured_while_retrieving_buyer_transaction"));
      },
      complete: function () {
        $(".table-loader, .input-loader, .label-loader").hide();
        $(".real-table, .real-label, .real-input").removeClass("d-none");
      }
    });
  }
})();

// Populate modal with transaction details
function viewTransactions(txn) {
  "use strict";

  const modal = $("#transaction_details");
  const truncatedId = txn.transaction_id ? txn.transaction_id.slice(0, 25) : "N/A";

  const fields = {
    "Transaction ID": truncatedId,
    "Transaction Type": txn.transaction_type || "Purchase",
    "Amount": txn.total_gross_amount,
    "Payment Method": txn.payment_method || "N/A",
    "Sender": txn.name || "N/A",
    "Receiver": txn.receiver_name || "N/A"
  };

  modal.find(".modal-title").text("Transaction Details");
  modal.find(".badge").text("Completed");

  const $summary = modal.find(".sumary-widget");
  $summary.html("");

  $.each(fields, function (label, value) {
    $summary.append(`
      <div class="summary-info">
        <h6 class="mb-1">${label}</h6>
        <p>${value}</p>
      </div>
    `);
  });
}

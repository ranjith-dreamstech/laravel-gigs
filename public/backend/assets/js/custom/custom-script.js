(function () {
    "use strict";
	window.showToast = function (toastType, message) {
		let toastId = '';
		if (toastType == 'success') {
			toastId = 'successToast'
		} else if (toastType == 'error') {
			toastId ='dangerToast'
		} else if (toastType == 'warning') {
			toastId ='warningToast'
		} else if (toastType == 'info') {
			toastId ='infoToast'
		} else if (toastType == 'secondary') {
			toastId ='secondaryToast'
		} else {
			toastId ='primaryToast'
		}
		let toastElement = document.getElementById(toastId);
		if (toastElement) {
			toastElement.querySelector(".toast-body").innerText = message;

			let toast = new bootstrap.Toast(toastElement, {
				animation: true,
				autohide: true,
				delay: 2000,
			});
			toast.show();
		}else{
			console.log('toast not found');
		}
	}

	if ($(".income-year-picker").length > 0) {
		$(".income-year-picker").datetimepicker({
			viewMode: "years",
			format: "YYYY",
			maxDate: moment().endOf("year"),
			icons: {
				up: "fas fa-angle-up",
				down: "fas fa-angle-down",
				next: "fas fa-angle-right",
				previous: "fas fa-angle-left",
			},
		});
	}

	window.initializeTooltips = function () {
		$('[data-bs-toggle="tooltip"]').each(function () {
			let tooltipInstance = bootstrap.Tooltip.getInstance(this);
			if (tooltipInstance) {
				tooltipInstance.dispose();
			}
		});

		$('[data-bs-toggle="tooltip"]').tooltip();
	}

	$(document).on("click", ".change-language", function () {
		let languageCode = $(this).data("language_code");

		$.ajax({
			url: "/admin/flag-change-language",
			type: "POST",
			data: { language_code: languageCode },
			headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
			success: function (response) {
				if (response.status === "success") {
					location.reload();
				}
			}
		});
	});

	let permission_error = $('body').data('permission_error');
	if (permission_error) {
		showToast('error', permission_error);
	}
	$(document).ready(function () {
		fetchAdminNotifications();
	});

	$(document).on("click", "#mark-all-as-read", function () {
		markAllAsRead();
	})
	function fetchAdminNotifications() {
		$.ajax({
			url: "/admin/fetch-notifications",
			type: "GET",
			headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
			success: function (response) {
				if (response.status === "success") {
					if(response.count > 0){
						$(".notify-action").removeClass("d-none");
					}
					$('#notification_count').html(response.notification_count);
					$(".noti-content").html(response.html);
				}
			}
		});
	}

	function markAllAsRead()
	{
		$.ajax({
			url: "/admin/mark-all-as-read",
			type: "POST",
			headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
			success: function (response) {
				fetchAdminNotifications();
				if (response.status === "success") {
					showToast('success', response.message);
				}else{
					showToast('error', response.message);
				}
			}
		});
	}
})();

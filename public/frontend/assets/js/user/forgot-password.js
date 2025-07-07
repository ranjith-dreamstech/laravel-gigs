/* global $, localStorage, setTimeout, document, showToast, window, setInterval, clearInterval */
let emailTimerTime = 0;
$(document).ready(function () {
    $(document).on("click", "#forgot_otp, .resendEmailOtpForgot", function (event) {
        event.preventDefault(); // Prevent default anchor behavior

        const username = $("[name=\"email\"]").val().trim();

        if (!username || !isValidEmail(username)) {
            showToast("error", "Please provide a valid email address.");
            return;
        }

        // showLoader();

        $.ajax({
            url: "/otp-settings",
            type: "POST",
            data: { email: username,
                type: "forgot"
             },
            headers: {
                "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
            },
            success: function (data) {
                // hideLoader();

                const userName = data.name;
                const otpExpireTime = parseInt(data.otp_expire_time.split(" ")[0]); // Extract expiration time
                const otpDigitLimit = parseInt(data.otp_digit_limit); // Extract OTP digit limit
                const otp = data.otp; // Extract OTP
                const otpType = data.otp_type; // Extract OTP type
                const emailSubject = data.email_subject; // Extract email subject
                const emailContent = data.email_content; // Extract email content
                const username = $("[name=\"email\"]").val().trim(); // Get email from input field

                const inputContainer = $(".inputcontainer");
                inputContainer.empty();

                let inputsHtml = "<div class=\"d-flex align-items-center mb-3\">";
                for (let i = 1; i <= otpDigitLimit; i++) {
                    const nextId = `digit-${i + 1}`;
                    const prevId = `digit-${i - 1}`;
                    inputsHtml += `
                        <input type="text"
                            class="rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3 digit-${i}"
                            id="digit-${i}"
                            name="digit-${i}"
                            data-next="${nextId}"
                            data-previous="${prevId}"
                            maxlength="1">
                    `;
                }
                inputsHtml += "</div>";
                inputContainer.append(inputsHtml);

                // OTP Input Auto-Focus Logic
                $(".inputcontainer").off("input").on("input", "input", function () {
                    if (this.value.length >= 1) {
                        const next = $(this).data("next");
                        if (next) {
                            $("#" + next).focus();
                        }
                    }
                });

                $(".inputcontainer").off("keydown").on("keydown", "input", function (e) {
                    if (e.key === "Backspace" && this.value === "") {
                        const prev = $(this).data("previous");
                        if (prev) {
                            $("#" + prev).focus();
                        }
                    }
                });

                $(".inputcontainer").off("click").on("click", "input", function () {
                    $(this).select();
                });


                if (otpType === "email") {
                    const emailData = {
                        subject: emailSubject,
                        content: `${emailContent}: ${otp}`
                    };

                    sendEmail(username, emailData, "email", userName, otp)
                        .then(() => {
                            $("#otp-email-message").text(`OTP sent to your Email Address ${username}`);
                            $("#otp-email-modal").modal("show");
                            startTimer(otpExpireTime);
                        })
                        .catch((error) => {
                            showToast("error", error);
                        });
                } else {
                    $("#otp-email-message").text(`OTP sent to your Email Address ${username}`);
                    $("#otp-email-modal").modal("show");
                    startTimer(otpExpireTime);
                }
            },
            error: function (xhr) {
                // hideLoader();
                const errorMessage = xhr.responseJSON?.error || "Failed to fetch OTP settings. Please try again.";
                showToast("error", errorMessage);
            }
        });
    });

    $("#verify-email-forgot-otp-btn").on("click", function () {
        const email = $("[name=\"email\"]").val();
        const otpDigitLimit = $(".inputcontainer input").length;
        const forgot_email = $("[name=\"forgot_email\"]").val();
        const login_type = "forgot_email";

        const otp = [];
        for (let i = 1; i <= otpDigitLimit; i++) {
            const digit = $(`#digit-${i}`).val();
            otp.push(digit);
        }
        const otpString = otp.join("");

        let requestData = { otp: otpString };

        if (email) {
            requestData.forgot_email = email;
            requestData.login_type = login_type;
        } else if (forgot_email) {
            requestData.forgot_email = forgot_email;
            requestData.login_type = login_type;
        }


        $.ajax({
            url: "/verify-otp",
            type: "POST",
            data: requestData,
            headers: {
                "X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content"),
            },
            beforeSend: function () {
                $(".verify-email-otp-btn").attr("disabled", true).html(
                    "<div class=\"spinner-border text-light\" role=\"status\"></div>"
                );
            },
            success: function (response) {
                if (response.data === "done") {
                    $("#otp-email-modal").modal("hide");
                    $("#reset-password").modal("show");
                    let email = response.email;
                    localStorage.setItem("email", email);
                    window.location.href = "/user/reset-password";

                    $("#email_id").val(email);
                } else {
                    $("#otp-email-modal").modal("hide");
                    $("#success_modal").modal("show");
                    window.location.href = "/";
                }
            },
            error: function (xhr) {
                const errorMessage = xhr.responseJSON?.error || "OTP Required";
                showToast("error", errorMessage);
            },
            complete: function () {
                $(".verify-email-otp-btn").attr("disabled", false).html("Verify OTP");
            },
        });
    });
});

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

let emailTimerInterval;

function startTimer(expireTime) {
    clearInterval(emailTimerInterval); // Clear any existing timer
    emailTimerTime = expireTime * 60; // Convert minutes to seconds

    setTimeout(() => {
        let otpTimerDisplay = document.getElementById("otp-timer");

        if (!otpTimerDisplay) {
            showToast("error", "OTP Timer element not found!");
            return;
        }

        emailTimerInterval = setInterval(() => {
            let minutes = Math.floor(emailTimerTime / 60);
            let seconds = emailTimerTime % 60;

            otpTimerDisplay.textContent = `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;

            if (emailTimerTime <= 0) {
                clearInterval(emailTimerInterval);
                otpTimerDisplay.textContent = "00:00"; // Timer finished
            } else {
                emailTimerTime--;
            }
        }, 1000);
    }, 500); // Ensures modal and elements are visible
}

function sendEmail(email, emailData, userName, otp) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: "/api/mail/sendmail",
            type: "POST",
            dataType: "json",
            data: {
                otp_type: "email",
                to_email: email,
                notification_type: 2,
                type: 1,
                user_name: userName,
                otp: otp,
                subject: emailData.subject,
                content: emailData.content,
            },
            headers: {
                Authorization: "Bearer " + localStorage.getItem("admin_token"),
                Accept: "application/json",
            },
            success: function (response) {
                if (response.code === 200) {
                    resolve(response);
                } else {
                    reject("Failed to send email OTP.");
                }
            },
            error: function (error) {
                reject(error);
            },
        });
    });
}
/* global $, loadTranslationFile, FormData, toastr, window, FileReader, Image, URL, setTimeout, document, showToast, _l */
(async () => {
    await loadTranslationFile("web", "gigs,common");

    $(document).ready(function () {
        $(".summernote").summernote({
            height: 250,
            placeholder: _l("web.gigs.description_placeholder"),
            toolbar: [
                ["style", ["bold", "italic", "underline", "clear"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["insert", ["link", "picture", "video"]],
                ["view", ["fullscreen", "codeview", "help"]],
            ],
        });

        $(document).on("input", ".NumOnly", function () {
            this.value = this.value.replace(/[^0-9]/g, "");
        });

        $("#createGigsForm").validate({
            rules: {
                title: {
                    required: true,
                },
                general_price: {
                    required: true,
                },
                days: {
                    required: true,
                },
                category_id: {
                    required: true,
                },
                sub_category_id: {
                    required: true,
                },
                no_revisions: {
                    required: true,
                },
                description: {
                    required: true,
                },
                fast_service_tile: {
                    required: true,
                },
                fast_service_price: {
                    required: true,
                },
                fast_service_days: {
                    required: true,
                },
                video_platform: {
                    required: true,
                },
                video_link: {
                    required: true,
                },
                "gigs_image[]": {
                    required: false,
                },
                "gigs_video[]": {
                    required: false,
                },
                "addon_title[]": {
                    required: true,
                },
                "addon_price[]": {
                    required: true,
                },
                "addon_days[]": {
                    required: true,
                },
            },
            messages: {
                title: {
                    required: _l("web.gigs.title_required"),
                },
                general_price: {
                    required: _l("web.gigs.general_price_required"),
                },
                days: {
                    required: _l("web.gigs.days_required"),
                },
                category_id: {
                    required: _l("web.gigs.category_id_required"),
                },
                sub_category_id: {
                    required: _l("web.gigs.sub_category_id_required"),
                },
                no_revisions: {
                    required: _l("web.gigs.no_revisions_required"),
                },
                description: {
                    required: _l("web.gigs.description_required"),
                },
                fast_service_tile: {
                    required: _l("web.gigs.fast_service_title_required"),
                },
                fast_service_price: {
                    required: _l("web.gigs.fast_service_price_required"),
                },
                fast_service_days: {
                    required: _l("web.gigs.fast_service_days_required"),
                },
                video_platform: {
                    required: _l("web.gigs.video_platform_required"),
                },
                video_link: {
                    required: _l("web.gigs.video_link_required"),
                },
                "gigs_image[]": {
                    required: _l("web.gigs.addon_title_required"),
                },
                "gigs_video[]": {
                    required: _l("web.gigs.addon_title_required"),
                },
                "addon_title[]": {
                    required: _l("web.gigs.addon_title_required"),
                },
                "addon_price[]": {
                    required: _l("web.gigs.addon_price_required"),
                },
                "addon_days[]": {
                    required: _l("web.gigs.addon_days_required"),
                },
            },
            errorPlacement: function (error, element) {
                var errorId = element.attr("id") + "_error";

                // Handle Select2 separately
                if (element.hasClass("select2-hidden-accessible")) {
                    $("#" + errorId).text(error.text());
                } else {
                    // If a specific error container exists, use it
                    if ($("#" + errorId).length) {
                        $("#" + errorId).text(error.text());
                    } else {
                        // Otherwise, just insert after the element
                        error.insertAfter(element);
                    }
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

                selectedImages.forEach((item) => {
                    formData.append("gigs_image[]", item.file);
                });

                selectedVideos.forEach((item) => {
                    formData.append("gigs_video[]", item.file);
                });

                formData.append("status", $("#status").is(":checked") ? 1 : 1);

                let faqList = [];
                $("[name='faq_question[]']").each(function (index) {
                    let question = $(this).val();
                    let answer = $("[name='faq_answer[]']").eq(index).val();

                    faqList.push({
                        id: index + 1,
                        question: question,
                        answer: answer,
                    });
                });

                formData.append("faqs", JSON.stringify(faqList));

                let extraServices = [];
                $("[name='addon_title[]']").each(function (index) {
                    let title = $(this).val();
                    let price = $("[name='addon_price[]']").eq(index).val();
                    let days = $("[name='addon_days[]']").eq(index).val();

                    extraServices.push({
                        id: index + 1,
                        title: title,
                        price: price,
                        days: days,
                    });
                });

                formData.append("extra_service", JSON.stringify(extraServices));

                $.ajax({
                    type: "POST",
                    url: "/store-gigs",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    beforeSend: function () {
                        $(".validateBtn .btn-text").text("Publishing...");
                        $(".validateBtn").prop("disabled", true);
                    },
                    success: function (resp) {
                        $(".error-text").text("");
                        $(".form-control").removeClass("is-invalid is-valid");
                        $(".validateBtn .btn-text").text("Publish");
                        $(".validateBtn").prop("disabled", false);
                        if (resp.code === 200) {
                            // showToast("success", resp.message);

                            $("#success_gigs").modal("show");

                            setTimeout(function () {
                                $("#success_gigs").modal("hide");
                                window.location.href = route('seller.seller-gigs');
                            }, 1500);
                        }
                    },
                    error: function (error) {
                        $(".error-text").text("");
                        $(".form-control").removeClass("is-invalid is-valid");
                        $(".validateBtn .btn-text").text("Publish");
                        $(".validateBtn").prop("disabled", false);
                        if (error.responseJSON.code === 422) {
                            $.each(
                                error.responseJSON.errors,
                                function (key, val) {
                                    $("#" + key).addClass("is-invalid");
                                    $("#" + key + "_error").text(val[0]);
                                }
                            );
                        } else {
                            toastr.error(error.responseJSON.message);
                        }
                    },
                });
            },
        });

        $(".select").on("change", function () {
            $(this).valid();
        });
        $(".select2").on("change", function () {
            $(this).valid();
        });
    });

    // -------------------------------------------------------------------------------------------------------------------------

    let currencySymbol = $("#currency").val() || "$";

    function getSignContent(index) {
        return `<div class="row sign-cont">
        <div class="col-md-6">
            <div class="form-wrap">
                <label class="col-form-label">${_l(
                    "web.gigs.addon_title_label"
                )}<span class="text-danger ms-1">*</span></label>
                <input type="text" class="form-control" maxlength="50" name="addon_title[]" id="addon_title_${index}">
                <span class="invalid-feedback" id="addon_title_${index}_error"></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-wrap">
                <label class="col-form-label">${_l(
                    "web.gigs.addon_price_label"
                )} (${currencySymbol})</label>
                <input type="text" class="form-control NumOnly" maxlength="6" name="addon_price[]" id="addon_price_${index}">
                <span class="invalid-feedback" id="addon_price_${index}_error"></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-wrap">
                <label class="col-form-label">${_l(
                    "web.gigs.addon_days_label"
                )}</label>
                <div class="d-flex align-items-center">
                    <div>
                        <input type="text" class="form-control NumOnly" maxlength="3" name="addon_days[]" id="addon_days_${index}">
                        <span class="invalid-feedback" id="addon_days_${index}_error"></span>
                    </div>
                    <a href="javascript:void(0);" class="trash-sign ms-2 text-danger"><i class="feather-trash-2"></i></a>
                </div>
            </div>
        </div>
    </div>`;
    }

    let addonIndex = 0; // to track number of addons

    // Append initial one on load
    $(document).ready(function () {
        $(".add-content").append(getSignContent(addonIndex++));
    });

    // On click of Add New
    $(document).on("click", ".multiple-amount-add", function () {
        $(".add-content").append(getSignContent(addonIndex++));
        return false;
    });

    // Optional: Handle delete click
    $(document).on("click", ".trash-sign", function () {
        $(this).closest(".sign-cont").remove();
    });

    let faqIndex = 0;
    let editingIndex = null;

    // Add / Update FAQ on modal save
    $(document).on("click", "#addFaqBtn", function () {
        const question = $("#question").val().trim();
        const answer = $("#answer").val().trim();

        if (!question) {
            $("#question_error").text("Question is required.");
            return;
        } else {
            $("#question_error").text("");
        }

        if (!answer) {
            $("#answer_error").text("Answer is required.");
            return;
        } else {
            $("#answer_error").text("");
        }

        const collapseId = `faq_${faqIndex}`;
        const cardHtml = `
        <div class="faq-card" data-index="${faqIndex}" id="faq_card_${faqIndex}">
            <h4 class="faq-title d-flex justify-content-between align-items-center">
                <a class="collapsed" data-bs-toggle="collapse" href="#${collapseId}" aria-expanded="false">${question}</a>
                <div>
                    <i class="ti ti-edit text-primary me-2 edit-faq cursor-pointer" data-index="${faqIndex}" title="Edit"></i>
                    <i class="ti ti-trash text-danger delete-faq cursor-pointer" data-index="${faqIndex}" title="Delete"></i>
                </div>
            </h4>
            <div id="${collapseId}" class="card-collapse collapse">
                <div class="faq-content">
                    <p>${answer}</p>
                    <input type="hidden" name="faq_question[]" maxlength="100" id="faq_question_${faqIndex}" value="${question}">
                    <input type="hidden" name="faq_answer[]" maxlength="200" id="faq_answer_${faqIndex}" value="${answer}">
                </div>
            </div>
        </div>
    `;

        if (editingIndex !== null) {
            $(`#faq_card_${editingIndex}`).replaceWith(cardHtml);
            editingIndex = null;
        } else {
            $("#faqContainer").append(cardHtml);
            faqIndex++;
        }

        $("#faq_add").modal("hide");
        $("#question").val("");
        $("#answer").val("");
    });

    // Delete FAQ
    $(document).on("click", ".delete-faq", function () {
        const index = $(this).data("index");
        $(`#faq_card_${index}`).remove();
    });

    // Edit FAQ
    $(document).on("click", ".edit-faq", function () {
        const index = $(this).data("index");
        const question = $(`#faq_question_${index}`).val();
        const answer = $(`#faq_answer_${index}`).val();

        $("#question").val(question);
        $("#answer").val(answer);
        editingIndex = index;

        $("#faq_add").modal("show");
    });

    let selectedImages = [];
    let imageId = 0;

    $("#gigs_image").on("change", function (e) {
        const files = Array.from(e.target.files);

        files.forEach((file) => {
            const reader = new FileReader();
            reader.onload = function (event) {
                const currentId = imageId++;
                selectedImages.push({ id: currentId, file: file });

                const html = `
                <div class="upload-file-item rounded-2 position-relative" data-id="${currentId}">
                    <img class="rounded-2" src="${event.target.result}" alt="img" width="100" height="100">
                    <span class="icon-delete bg-light delete-image" data-id="${currentId}">
                        <i class="ti ti-trash text-danger"></i>
                    </span>
                </div>
            `;
                $("#imagePreview").append(html);
            };
            reader.readAsDataURL(file);
        });

        // Reset input so same file can be selected again
        $(this).val("");
    });

    $(document).on("click", ".delete-image", function () {
        const id = $(this).data("id");
        selectedImages = selectedImages.filter((item) => item.id !== id);
        $(`.upload-file-item[data-id="${id}"]`).remove();
    });

    let selectedVideos = [];
    let videoId = 0;

    $(document).on("change", "#gigs_video", function (e) {
        const files = Array.from(e.target.files);

        files.forEach((file) => {
            // Check file type first
            const isMP4 =
                file.type === "video/mp4" ||
                file.name.toLowerCase().endsWith(".mp4");

            if (!isMP4) {
                showToast("error", "Only MP4 format can be uploaded.");
                return;
            }

            // Check file size
            if (file.size > 5 * 1024 * 1024) {
                showToast("error", "Video must be less than 5MB.");
                return;
            }

            const currentId = videoId++;
            selectedVideos.push({ id: currentId, file: file });

            const videoUrl = URL.createObjectURL(file);

            // Create a video element to grab thumbnail
            const video = document.createElement("video");
            video.src = videoUrl;
            video.preload = "metadata";
            video.muted = true;
            video.playsInline = true;

            video.addEventListener("loadeddata", function () {
                const canvas = document.createElement("canvas");
                canvas.width = 150;
                canvas.height = 100;
                const ctx = canvas.getContext("2d");
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                const thumbnailUrl = canvas.toDataURL("image/jpeg");

                const html = `
                    <div class="upload-file-item rounded-2 position-relative" data-id="${currentId}">
                        <img class="rounded-2" src="${thumbnailUrl}" alt="Video Thumbnail" width="100" height="100">
                        <span class="icon-delete bg-light delete-video" data-id="${currentId}">
                            <i class="ti ti-trash text-danger"></i>
                        </span>
                    </div>
                `;
                $("#VideoPreview").append(html);

                // Release memory
                URL.revokeObjectURL(videoUrl);
            });
        });

        // Reset input
        $(this).val("");
    });

    // Delete video
    $(document).on("click", ".delete-video", function () {
        const id = $(this).data("id");
        selectedVideos = selectedVideos.filter((item) => item.id !== id);
        $(`.upload-file-item[data-id="${id}"]`).remove();
    });

    //Get Sub - category
    $(document).ready(function () {
        $("#category_id").on("change", function () {
            let categoryId = $(this).val();
            let subCategoryDropdown = $("#sub_category_id");

            subCategoryDropdown.html('<option value="">Loading...</option>');

            if (categoryId) {
                $.ajax({
                    url: "/get-sub_category",
                    type: "GET",
                    data: { category_id: categoryId },
                    success: function (response) {
                        subCategoryDropdown.html(
                            '<option value="">Select Sub Category</option>'
                        );

                        if (response.length > 0) {
                            $.each(response, function (index, sub) {
                                subCategoryDropdown.append(
                                    `<option value="${sub.id}">${sub.name}</option>`
                                );
                            });
                        } else {
                            subCategoryDropdown.html(
                                '<option value="">No Subcategory Found</option>'
                            );
                        }
                    },
                    error: function () {
                        subCategoryDropdown.html(
                            '<option value="">Error loading subcategories</option>'
                        );
                    },
                });
            } else {
                subCategoryDropdown.html(
                    '<option value="">Select Sub Category</option>'
                );
            }
        });
    });
})();

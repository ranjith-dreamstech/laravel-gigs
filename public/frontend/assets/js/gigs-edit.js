/* global $, loadTranslationFile, FormData, toastr, FileReader, Image, document, showToast, _l */
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

        $("#editGigsForm").validate({
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
                    required: false,
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
                    required: "Title field is required",
                },
                general_price: {
                    required: "Price field is required",
                },
                days: {
                    required: "Delivery days field is required",
                },
                category_id: {
                    required: "Category field is required",
                },
                sub_category_id: {
                    required: "Sub Category field is required",
                },
                no_revisions: {
                    required: "No of Revisions field is required",
                },
                description: {
                    required: "Description field is required",
                },
                fast_service_tile: {
                    required: "Service title field is required",
                },
                fast_service_price: {
                    required: "Service price field is required",
                },
                fast_service_days: {
                    required: "Service days field is required",
                },
                video_platform: {
                    required: "Video platfrom field is required",
                },
                video_link: {
                    required: "Video link field is required",
                },
                "addon_title[]": {
                    required: "Addon Title field is required",
                },
                "addon_price[]": {
                    required: "Addon Price required",
                },
                "addon_days[]": {
                    required: "Addon Days required",
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

                formData.append("status", $("#status").is(":checked") ? 1 : 0);

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
                    url: "/edit-gigs",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function (resp) {
                        $(".error-text").text("");
                        $(".form-control").removeClass("is-invalid is-valid");
                        if (resp.code === 200) {
                            // showToast("success", resp.message);

                            $("#success_gigs").modal("show");
                        }
                    },
                    error: function (error) {
                        $(".error-text").text("");
                        $(".form-control").removeClass("is-invalid is-valid");
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

    $(document).ready(function () {
        function loadSubCategories(selectedSubCategoryId = null) {
            let categoryId = $("#category_id").val();
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
                                const isSelected =
                                    selectedSubCategoryId == sub.id
                                        ? "selected"
                                        : "";
                                subCategoryDropdown.append(
                                    `<option value="${sub.id}" ${isSelected}>${sub.name}</option>`
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
        }

        // On category change
        $("#category_id").on("change", function () {
            loadSubCategories(); // no selected subcategory on change
        });

        // On initial load — edit mode
        const initialSubCategoryId = $("#selected_sub").val(); // Get from hidden input
        if ($("#category_id").val()) {
            loadSubCategories(initialSubCategoryId);
        }
    });

    let addonIndex = 0;
    $(document).ready(function () {
        function loadSubCategories(selectedSubCategoryId = null) {
            let categoryId = $("#category_id").val();
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
                                const isSelected =
                                    selectedSubCategoryId == sub.id
                                        ? "selected"
                                        : "";
                                subCategoryDropdown.append(
                                    `<option value="${sub.id}" ${isSelected}>${sub.name}</option>`
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
        }

        // On category change
        $("#category_id").on("change", function () {
            loadSubCategories(); // no selected subcategory on change
        });

        // On initial load — edit mode
        const initialSubCategoryId = $("#selected_sub").val(); // Get from hidden input
        if ($("#category_id").val()) {
            loadSubCategories(initialSubCategoryId);
        }
    });

    function getSignContent(index, data = {}) {
        return `<div class="row sign-cont">
        <input type="hidden" name="addon_id[]" value="${data.id ?? ""}">
        <div class="col-md-6">
            <div class="form-wrap">
                <label class="col-form-label">${_l(
                    "web.gigs.addon_title_label"
                )} <span class="text-danger ms-1">*</span></label>
                <input type="text" class="form-control" maxlength="50" name="addon_name[]" id="addon_name_${index}" value="${
            data.name ?? ""
        }">
                <span class="invalid-feedback" id="addon_name_${index}_error"></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-wrap">
                <label class="col-form-label">${_l(
                    "web.gigs.addon_price_label"
                )} </label>
                <input type="text" class="form-control NumOnly" maxlength="6" name="addon_price[]" id="addon_price_${index}" value="${
            data.price ?? ""
        }">
                <span class="invalid-feedback" id="addon_price_${index}_error"></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-wrap">
                <label class="col-form-label">${_l(
                    "web.gigs.addon_days_label"
                )} </label>
                <div class="d-flex align-items-center">
                    <div>
                        <input type="text" class="form-control NumOnly" maxlength="3" name="addon_days[]" id="addon_days_${index}" value="${
            data.days ?? ""
        }">
                        <span class="invalid-feedback" id="addon_days_${index}_error"></span>
                    </div>
                    <a href="javascript:void(0);" class="trash-sign ms-2 text-danger"><i class="feather-trash-2"></i></a>
                </div>
            </div>
        </div>
    </div>`;
    }

    // On click of Add New
    $(document).on("click", ".multiple-amount-add", function () {
        $(".add-content").append(getSignContent(addonIndex++));
        return false;
    });

    // Optional: Handle delete click
    $(document).on("click", ".trash-sign", function () {
        $(this).closest(".sign-cont").remove();
    });

    $(document).ready(function () {
        const gigId = $("#gig_id").val();
        if (gigId) {
            $.ajax({
                url: "/gigs/get-addons", // Replace with actual route
                method: "GET",
                data: { gig_id: gigId },
                success: function (res) {
                    if (res.data && res.data.length > 0) {
                        res.data.forEach((item) => {
                            $(".add-content").append(
                                DOMPurify.sanitize(getSignContent(addonIndex++, item))
                            );
                        });
                    }
                },
            });
        }
    });

    let selectedImages = [];
    let imageId = 0;

    function renderImage(id, src, type = "file") {
        const html = `
        <div class="upload-file-item rounded-2 position-relative" data-id="${id}" data-type="${type}">
            <img class="rounded-2" src="${src}" alt="img" width="100" height="100">
            <span class="icon-delete bg-light delete-image" data-id="${id}">
                <i class="ti ti-trash text-danger"></i>
            </span>
        </div>
    `;
        $("#imagePreview").append(DOMPurify.sanitize(html));
    }

    $(document).ready(function () {
        const gigId = $("#gig_id").val();

        if (gigId) {
            $.ajax({
                url: "/gigs/get-images",
                method: "GET",
                data: { gig_id: gigId },
                success: function (res) {
                    if (
                        res.data &&
                        res.data.value &&
                        res.data.value.length > 0
                    ) {
                        res.data.value.forEach((url) => {
                            const currentId = imageId++;
                            selectedImages.push({
                                id: currentId,
                                url: url,
                                type: "url",
                            });
                            renderImage(currentId, url, "url");
                        });
                    }
                },
            });
        }

        $("#gigs_image").on("change", function (e) {
            const files = Array.from(e.target.files);

            files.forEach((file) => {
                const reader = new FileReader();

                reader.onload = function (event) {
                    const currentId = imageId++;
                    selectedImages.push({
                        id: currentId,
                        file: file,
                        type: "file",
                    });
                    renderImage(currentId, event.target.result, "file");
                };

                reader.readAsDataURL(file);
            });

            $(this).val("");
        });

        $(document).on("click", ".delete-image", function () {
            const id = $(this).data("id");
            const image = selectedImages.find((img) => img.id === id);

            if (image?.type === "url") {
                // Call server to delete from DB
                $.ajax({
                    url: "/gigs/delete-image",
                    method: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr("content"),
                        gig_id: $("#gig_id").val(),
                        path: image.url.replace(
                            `${window.location.origin}/storage/`,
                            ""
                        ),
                    },
                    success: function (res) {
                        showToast("success", res.message);
                    },
                    error: function () {
                        showToast(
                            "error",
                            "Failed to delete image from server."
                        );
                    },
                });
            }

            selectedImages = selectedImages.filter((img) => img.id !== id);
            $(`.upload-file-item[data-id="${id}"]`).remove();
        });
    });

    $(document).ready(function () {
        const gigId = $("#gig_id").val();
        if (gigId) {
            $.ajax({
                url: "/gigs/get-faq", // Replace with actual route
                method: "GET",
                data: { gig_id: gigId },
                success: function (res) {
                    if (res.data && res.data.length > 0) {
                        const faqs = JSON.parse(res.data[0].faqs); // Parse the JSON string

                        faqs.forEach((faq) => {
                            const collapseId = `faq_${faqIndex}`;
                            const cardHtml = `
                            <div class="faq-card" data-index="${faqIndex}" id="faq_card_${faqIndex}">
                                <h4 class="faq-title d-flex justify-content-between align-items-center">
                                    <a class="collapsed" data-bs-toggle="collapse" href="#${collapseId}" aria-expanded="false">${faq.question}</a>
                                    <div>
                                        <i class="ti ti-edit text-primary me-2 edit-faq cursor-pointer" data-index="${faqIndex}" title="Edit"></i>
                                        <i class="ti ti-trash text-danger delete-faq cursor-pointer" data-index="${faqIndex}" title="Delete"></i>
                                    </div>
                                </h4>
                                <div id="${collapseId}" class="card-collapse collapse">
                                    <div class="faq-content">
                                        <p>${faq.answer}</p>
                                        <input type="hidden" name="faq_question[]" maxlength="100" id="faq_question_${faqIndex}" value="${faq.question}">
                                        <input type="hidden" name="faq_answer[]" maxlength="200" id="faq_answer_${faqIndex}" value="${faq.answer}">
                                    </div>
                                </div>
                            </div>
                        `;

                            $("#faqContainer").append(DOMPurify.sanitize(cardHtml));
                            faqIndex++; // Increment for next
                        });
                    }
                },
            });
        }
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
})();

/* global $, loadTranslationFile, mqtt, setTimeout, document, showToast, _l, FormData, window, console */
"use strict";
(async () => {
    await loadTranslationFile("web", "user,common");
    $(document).ready(function () {
        let offset = "";
        let isLoading = false;
        let last_offset = "";
        const authUserId = $("#messageinput").attr("data-senderid");
        listenMqttForNewMessages();
        autoloadFirstChat();

        function autoloadFirstChat(){
            let user_id = $(".user-list .user-list-item").first().data("userid");
            fetchMessages(user_id);
        }
    
        /**
 * Listens to MQTT topic for incoming chat messages and routes them accordingly.
 */
function listenMqttForNewMessages() {
    const topic = `dreamsgigs/to_user/${authUserId}`;

    if (!mqtt) {
        showToast("error", "MQTT not connected! Please refresh the page.");
        return;
    }

    const client = mqtt.connect("wss://broker.emqx.io:8084/mqtt", {
        clientId:  'client_' + crypto.randomUUID(),
        clean: true,
        reconnectPeriod: 1000,
        connectTimeout: 5000
    });

    client.on("connect", () =>
        client.subscribe(topic, { qos: 1 }, (err) =>
            showToast(err ? "error" : "info", err ? `Subscription Error: ${err.message}` : `Subscribed to topic: ${topic}`)
        )
    );

    client.on("message", (_, msg) => {
        try {
            const { sender_id } = JSON.parse(msg.toString());
            const activeUserId = $("#chat_avatar").data("userid");

            if (activeUserId !== sender_id) {
                const $profile = $(`.chat-user-list[data-userid="${sender_id}"]`);
                if ($profile.length) $profile.trigger("click");
            }

            offset = last_offset = "";
            fetchMessages(sender_id, true, true);
        } catch (e) {
            console.error("MQTT Parsing Error:", e);
            showToast("error", "Failed to parse MQTT message.");
        }
    });

    client.on("error", (err) =>
        showToast("error", `MQTT Error: ${err.message || "Unknown error"}`)
    );

    client.on("close", () =>
        showToast("info", "MQTT connection closed.")
    );
}
        /**
 * Fetch chat messages for the specified user and render them appropriately.
 * 
 * @param {number|string} userId - ID of the user whose messages are being fetched.
 * @param {boolean} initial - Whether to replace or prepend messages.
 * @param {boolean} reset - Whether to reset pagination offsets.
 */
function fetchMessages(userId, initial = true, reset = false) {
    $("#messageinput").data("receiverid", userId);

    $.ajax({
        url: "/seller/fetch-messages",
        type: "POST",
        dataType: "json",
        data: {
            user_id: userId,
            _token: $("meta[name=\"csrf-token\"]").attr("content"),
            offset,
            last_offset,
            reset
        },
        success: (response) => {
            updateChatProfile(response.profile);

            const $messageContainer = $("#messagebody");
            const $messageArea = $("#messageArea");

            if (response.status && Array.isArray(response.messages) && response.messages.length) {
                const existingMsgIds = new Set();
                $(".chats").each(function () {
                    existingMsgIds.add($(this).data("message-id"));
                });

                const newMessages = response.messages.filter(msg => !existingMsgIds.has(msg.id));
                const html = newMessages.map(createMessageCard).join("");

                if (initial) {
                    $messageArea.html(DOMPurify.sanitize(html));
                    setTimeout(() => {
                        $messageContainer.scrollTop($messageContainer[0].scrollHeight);
                    }, 10);
                } else {
                    const oldScrollHeight = $messageContainer[0].scrollHeight;
                    $messageArea.prepend(DOMPurify.sanitize(html));
                    setTimeout(() => {
                        const newScrollHeight = $messageContainer[0].scrollHeight;
                        $messageContainer.scrollTop(newScrollHeight - oldScrollHeight);
                    }, 50);
                }

                offset = response.next_offset;
                last_offset = response.last_offset;
            } else {
                offset = null;
                last_offset = null;
                $messageContainer.off("scroll");
                $messageArea.html(`
                    <div><p class="text-center">${_l("web.user.no_messages")}</p></div>
                `);
            }

            setTimeout(() => $(".chats").addClass("loaded"), 10);

            if (response.last_message) {
                updateLastMessage(response.last_message);
            }

            if (offset === null) {
                $messageContainer.off("scroll");
            }

            isLoading = false;
        }
    });
}
        $("#messagebody").on("scroll", function () {
            if ($(this).scrollTop() === 0 && !isLoading) {
                let user_id = $("#chat_avatar").data("userid");
                if(user_id) fetchMessages(user_id, false);
            }
        });
    
        $(document).on("keydown", "#messageinput", function (e) {
            if (e.keyCode === 13) {
                $("#sendmsg").trigger("click");
            }
        });
    
        $(document).on("click", "#sendmsg", () => {
    const $messageInput = $("#messageinput");
    const message = $messageInput.val().trim();
    const senderId = $messageInput.data("senderid");
    const receiverId = $messageInput.data("receiverid");
    const topic = `dreamsgigs/to_user/${receiverId}`;
    const fileInput = $("#fileupload")[0];
    const file = fileInput?.files?.[0];

    if (!message && !file) {
        showToast("error", _l("web.user.type_message_or_file"));
        return;
    }

    const formData = new FormData();
    formData.append("message", message);
    formData.append("sender_id", senderId);
    formData.append("receiver_id", receiverId);
    formData.append("topic", topic);
    formData.append("messageType", file ? "file" : "text");
    if (file) formData.append("file", file);
    formData.append("_token", $("meta[name=\"csrf-token\"]").attr("content"));

    const $sendButton = $("#sendmsg");

    $.ajax({
        url: "/seller/send-message",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: () => {
            $messageInput.val("").prop("disabled", true);
            $sendButton.prop("disabled", true);
        },
        success: () => {
            offset = null;
            last_offset = null;
            fetchMessages(receiverId, true, true);
        },
        complete: () => {
            $messageInput.prop("disabled", false);
            $sendButton.prop("disabled", false);
            $("#fileupload").val("");
            $messageInput.val("");
            $(".selected-file").text("");
            $(".select-section").addClass("d-none");
        },
        error: () => {
            offset = null;
            last_offset = null;
            fetchMessages(receiverId, true, true);
        }
    });
});
     
        function updateLastMessage(last_message) {
            if(last_message){
                const chatProfile = $(".chat-user-list[data-userid='" + last_message.partner_id + "']");
                if (chatProfile.length > 0) {   
                    let text = last_message.message;
                    if(text.length > 15){
                        text = text.substring(0, 15) + "...";
                    }
                    chatProfile.find(".last-message").text(text);
                    chatProfile.find(".last_message_time").text(last_message.created_at);
                }
            }
        }
        function updateChatProfile(profile) {
            if(profile){
                let firstname = profile.user_detail ? profile.user_detail.first_name : "";
                let lastname = profile.user_detail ? profile.user_detail.last_name : "";
                let fullname = firstname + " " + lastname;
                let avatar = profile.user_detail ? profile.user_detail.profile_image : "";
                $(".chat-username").text(fullname);
                $("#chat_avatar").attr("src", avatar);
                $("#chat_avatar").data("userid", profile.id);
            }
        }
        function createMessageCard(messages) {
            let html = "";
            let messageDate = new Date(messages.created_at);
            let formattedDate = messageDate.toLocaleDateString("en-US", { weekday: "long", month: "long", day: "numeric" });
        
            if (window.lastMessageDate !== formattedDate) {
                window.lastMessageDate = formattedDate;
                html += `<div class="chat-line">
                            <span class="chat-date">${formattedDate}</span>
                          </div>`;
            }
        
            if (messages.alignment === "right") {
                html += `<div class="chats chats-right">
                            <div class="chat-content">
                                <div class="chat-profile-name text-end">
                                    <h6>${messages.sender_username}<span>${messageDate.getHours()}:${messageDate.getMinutes()} ${messageDate.getHours() >= 12 ? "PM" : "AM"}</span><span class="check-star msg-star-one d-none"><i class="bx bxs-star"></i></span></h6>
                                </div>
                                <div class="message-content">
                                    ${messages.message_type === "file" ? `<a href="${messages.file_path}" target="_blank">${messages.message}</a>` : messages.message}
                                </div>
                            </div>
                            <div class="chat-avatar">
                                <img src="${messages.sender_avatar}" class="rounded-circle dreams_chat" alt="image">
                            </div>
                        </div>`;
            } else {
                html += `<div class="chats">
                            <div class="chat-avatar">
                                <img src="${messages.sender_avatar}" class="rounded-circle dreams_chat" alt="image">
                            </div>
                            <div class="chat-content">
                                <div class="chat-profile-name">
                                    <h6>${messages.sender_username}<span>${messageDate.getHours()}:${messageDate.getMinutes()} ${messageDate.getHours() >= 12 ? "PM" : "AM"}</span><span class="check-star msg-star d-none"><i class="bx bxs-star"></i></span></h6>
                                </div>
                                <div class="message-content">
                                    ${messages.message_type === "file" ? `<a href="${messages.file_path}" target="_blank">${messages.message}</a>` : messages.message}
                                </div>
                            </div>
                        </div>`;
            }
        
            return html;
        }

        $(document).on("click",".chat-user-list",function(){
            let receiverId = $(this).data("userid");
            offset = null;
            last_offset = null;
            fetchMessages(receiverId,true,true); 
        });

        $(document).on("click", "#openFile", function(){
            $("#fileupload").click();
        });

        $(document).on("change", "#fileupload", function(){
            var fileName = this.files[0].name;
            $(".selected-file").text(fileName);
            $(".select-section").removeClass("d-none");
        });

        $("#chatSearch").on("input", function () {
            let q = $(this).val();
                setTimeout(function () {
                     fetchUsers(q);
                },100);
        });

        function fetchUsers(q) {
            $.ajax({
                url: "/user/search-users",
                type: "POST",
                data: {
                    q: q,
                    _token: $("meta[name=\"csrf-token\"]").attr("content")
                },
                dataType: "JSON",
                success: function (response) {
                    if(response.status && response.users && response.users.length > 0){
                        let html = response.users.map(user => createUserCard(user)).join("");
                        $("#chatsidebar .user-list").html(DOMPurify.sanitize(html));
                    }else{
                        $("#chatsidebar .user-list").html(`
                            <li class="user-list-item">
                                <a href="javascript:void(0);">
                                    <div class="users-list-body">
                                        <div>
                                            <h5>${_l("web.user.no_user_found")}</h5>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            `);
                    }
                },
                error: function () {
                    
                }
            });
        }

        function createUserCard(user) {
            let profile_image = user.avatar;
            let user_id       = user.id;
            let first_name    = user.user_detail ? user.user_detail.first_name : "";
            let last_name     = user.user_detail ? user.user_detail.last_name : "";
            let fullname      = user.name;
            if(first_name && last_name){
                fullname = first_name + " " + last_name;
            }
            let messageText   = user.lastMessage ? user.lastMessage.message : "";
            if(messageText.length > 15){
                messageText = messageText.substring(0, 15) + "...";
            }
            let lastMessageTime = user.lastMessageTime;
            let html = ` <li class="user-list-item chat-user-list" data-userid="${user_id}">
                            <a href="javascript:void(0);">
                                <div class="avatar avatar-online">
                                    <img src="${profile_image}" class="rounded-circle" alt="image">
                                </div>
                                <div class="users-list-body">
                                    <div>
                                        <h5>${fullname}</h5>
                                        <p class="last-message">${messageText}</p>
                                    </div>
                                    <div class="last-chat-time">
                                        <small class="text-muted last_message_time">${lastMessageTime}</small>
                                    </div>
                                </div>
                            </a>
                        </li> `;
            return html;
        }
    });
})();
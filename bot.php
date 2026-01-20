<style>
    /* Chat toggle button */
    #chatbot-toggle {
        position: fixed;
        bottom: 20px;
        right: 25px;
        width: 70px;
        cursor: pointer;
        z-index: 999999;
    }

    /* Chat container */
    #chatbot-container {
        position: fixed;
        bottom: 100px;
        right: 25px;
        width: 330px;
        height: 420px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
        z-index: 999999;
        overflow: hidden;
        display: none;
        flex-direction: column;
    }

    /* Chat header */
    #chatbot-header {
        background: #011432;
        color: white;
        padding: 10px;
        font-size: 1em;
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    #chatbot-header img {
        width: 40px;
        margin-right: 10px;
        border-radius: 50%;
        background: white;
    }

    /* Messages area */
    #chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
        background: #f5f5f5;
    }

    .msg {
        margin: 8px 0;
        max-width: 80%;
        padding: 8px 12px;
        border-radius: 18px;
        font-size: 0.9em;
        line-height: 1.3em;
        word-wrap: break-word;
    }

    .user-msg {
        background: #011432;
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 4px;
    }

    .bot-msg {
        background: #e4e6eb;
        color: #111;
        margin-right: auto;
        border-bottom-left-radius: 4px;
    }

    /* Input area */
    #user-input-area {
        padding: 10px;
        background: white;
        display: flex;
        border-top: 1px solid #ddd;
    }

    #user-input {
        flex: 1;
        padding: 8px;
        border-radius: 20px;
        border: 1px solid #ccc;
        outline: none;
        font-size: 0.9em;
    }

    #send-button {
        padding: 8px 15px;
        margin-left: 8px;
        background: #011432;
        color: white;
        border: none;
        border-radius: 20px;
        cursor: pointer;
    }
</style>


<!-- Chat toggle button -->
<img src="assets/img/faceBot.png" id="chatbot-toggle" alt="Chatbot">


<!-- Chat window -->
<div id="chatbot-container">
    <div id="chatbot-header">
        <img src="assets/img/faceBot.png" alt="">
        AI Assistant
    </div>

    <div id="chat-messages"></div>

    <div id="user-input-area">
        <input type="text" id="user-input" placeholder="Type your message...">
        <button id="send-button" onclick="sendMessage()">Send</button>
    </div>
</div>


<script>
    const chatBox = document.getElementById("chatbot-container");
    const toggle = document.getElementById("chatbot-toggle");

    // Open/close chat
    toggle.onclick = () => {
        chatBox.style.display = chatBox.style.display === "flex" ? "none" : "flex";
    };

    function sendMessage() {
        var userInput = $("#user-input").val().trim();
        if (userInput === "") return;

        var chatMessages = $("#chat-messages");

        // User message
        chatMessages.append('<div class="msg user-msg">' + userInput + '</div>');
        chatMessages.scrollTop(chatMessages[0].scrollHeight);

        var pass_data = new FormData();
        pass_data.append("received_txt", userInput);
        pass_data.append("seller_user_id", '<?php echo $_GET["user_id"];?>');

        // AJAX
        $.ajax({
            type: "POST",
            url: "chat_bot_NLP_AI.php",
            data: pass_data,
            contentType: false,
            processData: false,
            success: function(response) {
                try {
                    var res = JSON.parse(response.trim());
                    var botReply = res.contentMsg || "I couldn't understand your message.";

                    chatMessages.append('<div class="msg bot-msg">' + botReply + '</div>');
                    chatMessages.scrollTop(chatMessages[0].scrollHeight);
                } catch {
                    chatMessages.append('<div class="msg bot-msg">Data processing error.</div>');
                }
            },
            error: function() {
                chatMessages.append('<div class="msg bot-msg">Server connection failed.</div>');
            }
        });

        $("#user-input").val("");
    }

    // Enter to send
    $("#user-input").keyup(function(event) {
        if (event.key === "Enter") sendMessage();
    });
</script>


<?php
include "header.php";

// database connection assumed in $conn
?>

<style>
    /* Chat container */
    #chatbot-container {
        width: 100%;
        max-width: 800px;
        height: 90vh;
        max-height: 700px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        margin: 190px auto 0px auto;
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Chat header with toggle */
    #chatbot-header {
        background: linear-gradient(135deg, #0084ff 0%, #0059ff 100%);
        color: white;
        padding: 15px 20px;
        font-size: 1.1em;
        font-weight: bold;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    #chatbot-header img {
        width: 36px;
        height: 36px;
        margin-right: 12px;
        border-radius: 50%;
        background: white;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    /* toggle switch */
    .switch {
      position: relative;
      display: inline-block;
      width: 52px;
      height: 26px;
      margin-left:auto;
    }
    .switch input { display:none; }
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0; left: 0;
      right: 0; bottom: 0;
      background-color: #ccc;
      transition: .4s;
      border-radius: 26px;
    }
    .slider:before {
      position: absolute;
      content: "";
      height: 20px; width: 20px;
      left: 3px; bottom: 3px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }
    input:checked + .slider {
      background-color: #0084ff;
    }
    input:checked + .slider:before {
      transform: translateX(26px);
    }

    /* Chat messages */
    #chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .msg {
        max-width: 80%;
        padding: 12px 16px;
        border-radius: 18px;
        font-size: 0.95em;
        line-height: 1.4;
        word-wrap: break-word;
        position: relative;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .user-msg {
        background: linear-gradient(135deg, #0084ff 0%, #0072e0 100%);
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 132, 255, 0.3);
    }

    .bot-msg {
        background: white;
        color: #333;
        margin-right: auto;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        border: 1px solid #eaeaea;
    }

    /* Input area */
    #user-input-area {
        padding: 15px 20px;
        background: white;
        display: flex;
        border-top: 1px solid #eaeaea;
        align-items: center;
    }
    #user-input {
        flex: 1;
        padding: 12px 18px;
        border-radius: 24px;
        border: 1px solid #ddd;
        outline: none;
        font-size: 0.95em;
        transition: all 0.3s;
        background: #f8f9fa;
    }
    #user-input:focus {
        border-color: #0084ff;
        background: white;
        box-shadow: 0 0 0 2px rgba(0, 132, 255, 0.1);
    }
    #send-button {
        padding: 12px 20px;
        margin-left: 10px;
        background: linear-gradient(135deg, #0084ff 0%, #0072e0 100%);
        color: white;
        border: none;
        border-radius: 24px;
        cursor: pointer;
        font-size: 0.95em;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #send-button:hover {
        background: linear-gradient(135deg, #0072e0 0%, #0060c0 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 132, 255, 0.3);
    }
    #send-button:active {
        transform: translateY(0);
    }

    /* Typing indicator */
    .typing-indicator { display: flex; align-items: center; margin: 8px 0; max-width: 80%; margin-right: auto; }
    .typing-dots { display: flex; background: white; padding: 12px 16px; border-radius: 18px; border-bottom-left-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border:1px solid #eaeaea;}
    .typing-dot { width: 8px; height: 8px; border-radius: 50%; background-color: #999; margin: 0 2px; animation: typing 1.4s infinite ease-in-out;}
    .typing-dot:nth-child(1) { animation-delay: 0s;}
    .typing-dot:nth-child(2) { animation-delay: 0.2s;}
    .typing-dot:nth-child(3) { animation-delay: 0.4s;}
    @keyframes typing {0%,60%,100%{transform:translateY(0);}30%{transform:translateY(-5px);}}

    /* Scrollbar */
    #chat-messages::-webkit-scrollbar { width: 6px; }
    #chat-messages::-webkit-scrollbar-track { background: #f1f1f1; }
    #chat-messages::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 3px; }
    #chat-messages::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }

    /* Responsive */
    @media (max-width: 768px) {
        #chatbot-container { width:95%; height:85vh; margin:10px auto; border-radius:8px; }
        .msg { max-width: 90%; }
        #chatbot-header { padding:12px 15px; }
        #user-input-area { padding:12px 15px; }
    }
</style>

<div id="chatbot-container">
    <div id="chatbot-header">
        <img src="assets/img/faceBot.png" alt="Bot Image">
        <div id="chat_title">AI Assistant</div> 

        <label class="switch">
            <input type="checkbox" id="ai-toggle" onchange="$(this).is(':checked') ? $('#chat_title').html('AI Assistant') : $('#chat_title').html('Human Chat');" checked>
            <span class="slider round"></span>
        </label>
    </div>

    <div id="chat-messages">
        <?php
            $session_user = $_SESSION["user_id"];
            $other_user = $_GET["user_id"];

            $sql = "SELECT * FROM messenger 
                    WHERE (client1_user_id='$session_user' AND client2_user_id='$other_user')
                       OR (client1_user_id='$other_user' AND client2_user_id='$session_user')
                    ORDER BY id ASC";

            $q = $connect->query($sql);
            while($row = $q->fetch_assoc()){
                $is_from_me = ($row["client1_user_id"] == $session_user);
                if($row["human_ai"] == "ai"){
                    echo '<div class="msg bot-msg">'.$row["msg_content"].'</div>';
                } else if($is_from_me){
                    echo '<div class="msg user-msg">'.$row["msg_content"].'</div>';
                } else {
                    echo '<div class="msg bot-msg">'.$row["msg_content"].'</div>';
                }
            }
        ?>
    </div>

    <div id="user-input-area">
        <input type="text" id="user-input" placeholder="Type your message..." autocomplete="off">
        <button id="send-button" onclick="sendMessage()"><span>Send</span></button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function sendMessage() {
    var userInput = $("#user-input").val().trim();
    if(userInput === "") return;

    var chatMessages = $("#chat-messages");
    chatMessages.append('<div class="msg user-msg">' + userInput + '</div>');
    chatMessages.scrollTop(chatMessages[0].scrollHeight);

    chatMessages.append('<div class="typing-indicator"><div class="typing-dots"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div></div>');
    chatMessages.scrollTop(chatMessages[0].scrollHeight);

    var aiMode = $("#ai-toggle").is(":checked") ? "2" : "1";
    var pass_data = new FormData();
    pass_data.append("received_txt", userInput);
    pass_data.append("seller_user_id", '<?php echo $other_user; ?>');
    pass_data.append("human_ai", aiMode);

    if(aiMode=="2")
    {
    $.ajax({
        type: "POST",
        url: "chat_bot_NLP_AI.php",
        data: pass_data,
        contentType: false,
        processData: false,
        success: function(response){
            $(".typing-indicator").remove();
            try {
                var res = JSON.parse(response.trim());
                var botReply = res.contentMsg || "No reply";

                chatMessages.append('<div class="msg bot-msg">' + botReply + '</div>');
                chatMessages.scrollTop(chatMessages[0].scrollHeight);

                // store messages after getting reply
                $.ajax({
                    type: "POST",
                    url: "store_message.php",
                    data: {
                        user_msg: userInput,
                        bot_msg: botReply,
                        human_ai: aiMode,
                        receiver_id: '<?php echo $other_user; ?>'
                    },
                    success: function(storeRes){ console.log("Messages stored"); },
                    error: function(){ console.log("Failed to store messages"); }
                });

            } catch {
                chatMessages.append('<div class="msg bot-msg">Error processing request.</div>');
            }
        },
        error: function(){
            $(".typing-indicator").remove();
            chatMessages.append('<div class="msg bot-msg">Failed to connect to server.</div>');
        }
    });
    }
    else if(aiMode=="1")
    {
        $.ajax({
            type: "POST",
            url: "store_message.php",
            data: {
                user_msg: userInput,
                bot_msg: "",
                human_ai: "1",
                receiver_id: '<?php echo $other_user; ?>'
            },
            success: function(storeRes){ console.log("Messages stored"); },
            error: function(){ console.log("Failed to store messages"); }
        });
    }

    $("#user-input").val("");
}

$("#user-input").keyup(function(event){
    if(event.key==="Enter") sendMessage();
});
</script>

<?php include "footer.php"; ?>


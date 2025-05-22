<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chat Assistant</title>
    <link rel="stylesheet" href="./src/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Fira+Code&display=swap"
        rel="stylesheet">
</head>

<body>
    <!-- Sidebar for chat history -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2>Chat History</h2>
            <button class="new-chat-btn" id="newChatBtn">
                <i class="fas fa-plus"></i> New
            </button>
        </div>
        <div class="chat-history" id="chatHistory">
            <!-- Chat items will be added here dynamically -->
        </div>
    </div>

    <!-- Main chat area -->
    <div class="main-content">
        <div class="chat-header">
            <button class="menu-btn" id="menuBtn">
                <i class="fas fa-bars"></i>
            </button>
            <div class="chat-title">AI Assistant</div>
            <div style="width: 40px;"></div> <!-- Spacer for alignment -->
        </div>

        <div class="chat-body" id="chatBody">
            <div class="message-container" id="messageContainer"></div>
            <div class="typing-indicator" id="typingIndicator">
                <div class="message-avatar ai">AI</div>
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        </div>

        <div class="chat-footer">
            <div class="chat-form-container">
                <form id="chatForm" class="chat-form">
                    <textarea class="chat-input" id="prompt" placeholder="Message AI Assistant..." rows="1"
                        autocomplete="off"></textarea>
                    <button type="submit" class="chat-submit">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
                <div class="suggestions" id="suggestions">
                    <button class="suggestion-btn">Explain quantum computing</button>
                    <button class="suggestion-btn">Write a poem about AI</button>
                    <button class="suggestion-btn">How to make pizza dough?</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="./src/js/script.js"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chat Assistant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-bg: #343541;
            --sidebar-bg: #202123;
            --message-ai-bg: #444654;
            --message-user-bg: #343541;
            --text-primary: #ececf1;
            --text-secondary: #acacbe;
            --border-color: #565869;
            --primary-color: #10a37f;
            --primary-hover: #1a7f64;
            --input-bg: #40414f;
            --input-border: #565869;
            --button-hover: #2b2c3a;
            --button-active: #3e3f4d;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            background: var(--primary-bg);
            color: var(--text-primary);
            line-height: 1.6;
            height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* Sidebar styles */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            height: 100vh;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            position: relative;
            z-index: 10;
        }

        .sidebar-header {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .new-chat-btn {
            width: 100%;
            padding: 10px 12px;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .new-chat-btn:hover {
            background: var(--button-hover);
        }

        .new-chat-btn i {
            font-size: 14px;
        }

        .chat-history {
            flex: 1;
            overflow-y: auto;
            padding: 8px 0;
        }

        .chat-item {
            padding: 10px 16px;
            margin: 0 8px;
            border-radius: 4px;
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .chat-item:hover {
            background: var(--button-hover);
        }

        .chat-item i {
            font-size: 16px;
            color: var(--text-secondary);
        }

        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid var(--border-color);
        }

        .user-profile {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .user-profile:hover {
            background: var(--button-hover);
        }

        .user-avatar {
            width: 24px;
            height: 24px;
            border-radius: 2px;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        /* Main chat area */
        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: relative;
        }

        .chat-header {
            padding: 16px;
            text-align: center;
            background: var(--primary-bg);
            position: sticky;
            top: 0;
            z-index: 5;
            border-bottom: 1px solid var(--border-color);
        }

        .chat-header h1 {
            font-size: 1rem;
            font-weight: 600;
        }

        .chat-body {
            flex: 1;
            overflow-y: auto;
            scroll-behavior: smooth;
            padding-bottom: 100px;
        }

        .message-container {
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
            padding: 0 16px;
        }

        .message-row {
            padding: 24px 0;
        }

        .message-row.user {
            background: var(--message-user-bg);
        }

        .message-row.ai {
            background: var(--message-ai-bg);
        }

        .message-content {
            display: flex;
            gap: 24px;
            max-width: 800px;
            margin: 0 auto;
        }

        .message-avatar {
            width: 36px;
            height: 36px;
            border-radius: 2px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }

        .message-avatar.user {
            background: var(--primary-color);
        }

        .message-avatar.ai {
            background: #6e6e80;
        }

        .message-text {
            flex: 1;
            padding-top: 4px;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.7;
            font-size: 16px;
        }

        .message-text code {
            background: rgba(0, 0, 0, 0.3);
            padding: 0.2em 0.4em;
            border-radius: 4px;
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 0.9em;
            color: #eb5f5f;
        }

        .typing-indicator {
            display: flex;
            padding: 16px 0;
            align-items: center;
            background: var(--message-ai-bg);
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s, transform 0.3s;
            position: fixed;
            bottom: 100px;
            width: 100%;
            max-width: 800px;
        }

        .typing-indicator.active {
            opacity: 1;
            transform: translateY(0);
        }

        .typing-dots {
            display: flex;
            margin-left: 8px;
            gap: 4px;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--text-secondary);
            animation: typingAnimation 1.4s infinite ease-in-out;
        }

        .typing-dot:nth-child(1) {
            animation-delay: 0s;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        .chat-footer {
            padding: 16px;
            background: var(--primary-bg);
            position: fixed;
            bottom: 0;
            width: 100%;
            border-top: 1px solid var(--border-color);
        }

        .chat-form-container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }

        .chat-form {
            display: flex;
            border-radius: 8px;
            background: var(--input-bg);
            position: relative;
        }

        .chat-input {
            flex: 1;
            padding: 12px 48px 12px 16px;
            border: none;
            background: transparent;
            color: var(--text-primary);
            font-size: 1rem;
            resize: none;
            max-height: 200px;
            min-height: 60px;
            outline: none;
            line-height: 1.5;
        }

        .chat-submit {
            position: absolute;
            right: 12px;
            bottom: 12px;
            background: transparent;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            transition: color 0.2s;
        }

        .chat-submit:hover {
            color: var(--primary-color);
        }

        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 16px;
            justify-content: center;
        }

        .suggestion-btn {
            background: rgba(64, 65, 79, 0.5);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 8px 16px;
            font-size: 0.9rem;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.2s;
        }

        .suggestion-btn:hover {
            background: var(--input-bg);
        }

        .error-message {
            color: #ef4146;
            padding: 12px;
            background: rgba(239, 65, 70, 0.1);
            border-radius: 8px;
            margin-top: 8px;
        }

        /* Mobile styles */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                transform: translateX(-100%);
            }

            .sidebar.visible {
                transform: translateX(0);
            }

            .sidebar-toggle {
                position: fixed;
                left: 16px;
                top: 16px;
                z-index: 20;
                background: var(--input-bg);
                border: none;
                color: var(--text-primary);
                width: 36px;
                height: 36px;
                border-radius: 4px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            }

            .message-content {
                gap: 12px;
                padding: 0 12px;
            }

            .message-avatar {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }

            .message-text {
                font-size: 15px;
            }

            .chat-input {
                min-height: 50px;
                font-size: 0.95rem;
            }
        }

        @keyframes typingAnimation {

            0%,
            60%,
            100% {
                transform: translateY(0);
                opacity: 0.6;
            }

            30% {
                transform: translateY(-4px);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <button class="new-chat-btn">
                <i class="fas fa-plus"></i>
                <span>New chat</span>
            </button>
        </div>
        <div class="chat-history" id="chatHistory">
            <!-- Chat history items will be added here -->
        </div>
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">U</div>
                <span>User Name</span>
            </div>
        </div>
    </div>

    <button class="sidebar-toggle" id="sidebarToggle" style="display: none;">
        <i class="fas fa-bars"></i>
    </button>

    <div class="chat-container">
        <div class="chat-header">
            <h1>ChatGPT</h1>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="message-container" id="messageContainer">
                <!-- Messages will be added here -->
            </div>
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
                    <textarea class="chat-input" id="prompt" placeholder="Message ChatGPT..." rows="1"
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

    <script>
        // DOM elements
        const elements = {
            sidebar: document.querySelector('.sidebar'),
            sidebarToggle: document.getElementById('sidebarToggle'),
            chatHistory: document.getElementById('chatHistory'),
            messageContainer: document.getElementById('messageContainer'),
            chatForm: document.getElementById('chatForm'),
            promptInput: document.getElementById('prompt'),
            typingIndicator: document.getElementById('typingIndicator'),
            suggestions: document.getElementById('suggestions'),
            chatBody: document.getElementById('chatBody')
        };

        // Chat history
        let chats = [];
        let currentChatId = null;

        // Initialize a new chat
        function initNewChat() {
            const chatId = Date.now().toString();
            currentChatId = chatId;
            chats.push({
                id: chatId,
                title: 'New chat',
                messages: []
            });
            updateChatHistory();
            elements.messageContainer.innerHTML = '';
            return chatId;
        }

        // Update chat history sidebar
        function updateChatHistory() {
            elements.chatHistory.innerHTML = '';
            chats.forEach(chat => {
                const chatItem = document.createElement('div');
                chatItem.className = 'chat-item';
                chatItem.innerHTML = `
                    <i class="fas fa-message"></i>
                    <span>${chat.title}</span>
                `;
                chatItem.addEventListener('click', () => loadChat(chat.id));
                elements.chatHistory.appendChild(chatItem);
            });
        }

        // Load a chat
        function loadChat(chatId) {
            currentChatId = chatId;
            const chat = chats.find(c => c.id === chatId);
            if (!chat) return;

            elements.messageContainer.innerHTML = '';
            chat.messages.forEach(msg => {
                addMessage(msg.content, msg.role === 'user', false, false);
            });
            scrollToBottom();
        }

        // Add a message to the chat
        function addMessage(content, isUser = false, isError = false, saveToHistory = true) {
            if (saveToHistory && currentChatId) {
                const chat = chats.find(c => c.id === currentChatId);
                if (chat) {
                    chat.messages.push({
                        role: isUser ? 'user' : 'assistant',
                        content: content
                    });
                    
                    // Update chat title with first message if it's the first message
                    if (chat.messages.length === 1 && isUser) {
                        chat.title = content.length > 30 ? content.substring(0, 30) + '...' : content;
                        updateChatHistory();
                    }
                }
            }

            const messageRow = document.createElement('div');
            messageRow.className = `message-row ${isUser ? 'user' : 'ai'}`;

            const messageContent = document.createElement('div');
            messageContent.className = 'message-content';

            const avatar = document.createElement('div');
            avatar.className = `message-avatar ${isUser ? 'user' : 'ai'}`;
            avatar.textContent = isUser ? 'You' : 'AI';

            const textDiv = document.createElement('div');
            textDiv.className = `message-text ${isError ? 'error-message' : ''}`;
            textDiv.textContent = content;

            messageContent.appendChild(avatar);
            messageContent.appendChild(textDiv);
            messageRow.appendChild(messageContent);
            elements.messageContainer.appendChild(messageRow);

            scrollToBottom();
            return textDiv;
        }

        // Scroll to bottom of chat
        function scrollToBottom() {
            elements.chatBody.scrollTop = elements.chatBody.scrollHeight;
        }

        // Handle form submission
        async function handleSubmit(e) {
            e.preventDefault();
            const prompt = elements.promptInput.value.trim();
            if (!prompt) return;

            // Initialize new chat if none exists
            if (!currentChatId || chats.length === 0) {
                initNewChat();
            }

            addMessage(prompt, true);
            elements.promptInput.value = '';
            elements.promptInput.style.height = 'auto';
            elements.typingIndicator.classList.add('active');
            scrollToBottom();

            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                // Generate response (in a real app, this would come from your API)
                const responseText = getAIResponse(prompt);
                
                elements.typingIndicator.classList.remove('active');
                addMessage(responseText, false);
            } catch (err) {
                elements.typingIndicator.classList.remove('active');
                addMessage('Failed to get response. Please try again.', false, true);
                console.error('Error:', err);
            }
        }

        // Simple AI response generator for demo
        function getAIResponse(prompt) {
            const responses = [
                "I'm an AI assistant designed to help with a variety of tasks. How can I assist you further?",
                "That's an interesting question. Here's what I know about that topic...",
                "I can certainly help with that. Let me provide some information that might be useful.",
                "Thanks for your message! I'm happy to help with your request.",
                "I've processed your input and here's the response you requested."
            ];
            return responses[Math.floor(Math.random() * responses.length)];
        }

        // Initialize the app
        function init() {
            // Auto-resize textarea
            elements.promptInput.addEventListener('input', () => {
                elements.promptInput.style.height = 'auto';
                elements.promptInput.style.height = `${elements.promptInput.scrollHeight}px`;
            });

            // Form submission
            elements.chatForm.addEventListener('submit', handleSubmit);

            // New chat button
            document.querySelector('.new-chat-btn').addEventListener('click', initNewChat);

            // Suggestion buttons
            document.querySelectorAll('.suggestion-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    elements.promptInput.value = btn.textContent;
                    elements.promptInput.focus();
                    elements.promptInput.dispatchEvent(new Event('input'));
                });
            });

            // Mobile sidebar toggle
            elements.sidebarToggle.addEventListener('click', () => {
                elements.sidebar.classList.toggle('visible');
            });

            // Check for mobile view
            function checkMobileView() {
                if (window.innerWidth <= 768) {
                    elements.sidebarToggle.style.display = 'flex';
                    elements.sidebar.classList.remove('visible');
                } else {
                    elements.sidebarToggle.style.display = 'none';
                    elements.sidebar.classList.add('visible');
                }
            }

            window.addEventListener('resize', checkMobileView);
            checkMobileView();

            // Initialize first chat
            initNewChat();
            elements.promptInput.focus();
        }

        // Start the app
        init();
    </script>
</body>

</html>
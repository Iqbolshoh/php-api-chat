<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chat Assistant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Fira+Code&display=swap"
        rel="stylesheet">
    <style>
        :root {
            /* Color palette */
            --primary-bg: #1a1a2e;
            --sidebar-bg: #0f0f1a;
            --message-ai-bg: #16213e;
            --message-user-bg: #1a1a2e;
            --text-primary: #f1f1f1;
            --text-secondary: #b8b8d1;
            --border-color: #2a2a3a;
            --primary-color: #4cc9f0;
            --primary-hover: #3aa8d8;
            --error-color: #f72585;
            --success-color: #4ad66d;
            --warning-color: #f8961e;
            --sidebar-width: 280px;

            /* Shadows */
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);

            /* Inputs */
            --input-bg: #2a2a3a;
            --input-border: #3a3a4a;
            --input-focus: rgba(76, 201, 240, 0.3);

            /* Typography */
            --font-base: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            --font-mono: 'Fira Code', 'Courier New', monospace;

            /* Spacing */
            --space-xs: 0.25rem;
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;

            /* Border radius */
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-full: 9999px;

            /* Transitions */
            --transition-fast: 0.15s ease;
            --transition-normal: 0.3s ease;
            --transition-slow: 0.5s ease;
        }

        /* Base styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-base);
            background-color: var(--primary-bg);
            color: var(--text-primary);
            line-height: 1.6;
            height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            height: 100vh;
            overflow-y: auto;
            transition: transform var(--transition-normal);
            z-index: 100;
            position: relative;
        }

        .sidebar-header {
            padding: var(--space-md);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-header h2 {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .new-chat-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: var(--space-xs) var(--space-sm);
            font-size: 0.875rem;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            gap: var(--space-xs);
        }

        .new-chat-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .chat-history {
            padding: var(--space-sm);
        }

        .chat-item {
            padding: var(--space-sm);
            border-radius: var(--radius-md);
            margin-bottom: var(--space-xs);
            cursor: pointer;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            font-size: 0.875rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .chat-item.active {
            background: var(--primary-bg);
            font-weight: 500;
        }

        .chat-item i {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Main chat area */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: relative;
        }

        /* Header */
        .chat-header {
            padding: var(--space-md);
            background: var(--primary-bg);
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            backdrop-filter: blur(8px);
        }

        .menu-btn {
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.2rem;
            cursor: pointer;
            display: none;
        }

        .chat-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }

        .chat-title::before {
            content: "";
            display: block;
            width: 10px;
            height: 10px;
            background-color: var(--primary-color);
            border-radius: var(--radius-full);
            animation: pulse 2s infinite;
        }

        /* Chat body */
        .chat-body {
            flex: 1;
            overflow-y: auto;
            scroll-behavior: smooth;
            padding-bottom: 120px;
            position: relative;
        }

        /* Message container */
        .message-container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        /* Message row */
        .message-row {
            display: flex;
            width: 100%;
            padding: var(--space-lg) 0;
            animation: messageAppear 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        }

        .message-row.user {
            background: var(--message-user-bg);
        }

        .message-row.ai {
            background: var(--message-ai-bg);
        }

        .message-row-center {
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            gap: var(--space-md);
            padding: 0 var(--space-md);
        }

        /* Avatar */
        .message-avatar {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
            margin-top: 2px;
            font-size: 0.875rem;
            transition: transform 0.3s ease;
        }

        .message-avatar.user {
            background: linear-gradient(135deg, var(--primary-color), #4361ee);
        }

        .message-avatar.ai {
            background: linear-gradient(135deg, #7209b7, #f72585);
        }

        .message-row:hover .message-avatar {
            transform: scale(1.1) rotate(5deg);
        }

        /* Message content */
        .message-content {
            flex: 1;
            min-width: 0;
        }

        .message-text {
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.7;
            font-size: 1rem;
            padding-right: var(--space-xl);
            animation: textFadeIn 0.5s ease-out;
        }

        /* Typing indicator */
        .typing-indicator {
            display: flex;
            padding: var(--space-md);
            align-items: center;
            background: var(--message-ai-bg);
            opacity: 0;
            transform: translateY(10px);
            transition: all var(--transition-normal);
            position: fixed;
            bottom: 100px;
            width: calc(100% - var(--sidebar-width));
            border-top: 1px solid var(--border-color);
        }

        .typing-indicator.active {
            opacity: 1;
            transform: translateY(0);
        }

        .typing-dots {
            display: flex;
            margin-left: var(--space-sm);
            gap: var(--space-xs);
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            border-radius: var(--radius-full);
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

        /* Footer */
        .chat-footer {
            padding: var(--space-md);
            background: var(--primary-bg);
            position: fixed;
            bottom: 0;
            width: calc(100% - var(--sidebar-width));
            border-top: 1px solid var(--border-color);
            backdrop-filter: blur(8px);
        }

        .chat-form-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .chat-form {
            display: flex;
            border-radius: var(--radius-lg);
            border: 1px solid var(--input-border);
            background: var(--input-bg);
            box-shadow: var(--shadow-lg);
            transition: all var(--transition-fast);
        }

        .chat-form:focus-within {
            box-shadow: 0 0 0 2px var(--input-focus);
            border-color: var(--primary-color);
        }

        .chat-input {
            flex: 1;
            padding: var(--space-md);
            border: none;
            background: transparent;
            color: var(--text-primary);
            font-size: 1rem;
            resize: none;
            max-height: 200px;
            min-height: 60px;
            outline: none;
            line-height: 1.5;
            font-family: var(--font-base);
        }

        .chat-input::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }

        .chat-submit {
            background: transparent;
            border: none;
            padding: 0 var(--space-md);
            color: var(--text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-fast);
        }

        .chat-submit:hover {
            color: var(--primary-color);
            transform: rotate(15deg) scale(1.1);
        }

        .chat-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Suggestions */
        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: var(--space-sm);
            margin-top: var(--space-md);
            justify-content: center;
        }

        .suggestion-btn {
            background: rgba(42, 42, 58, 0.7);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-full);
            padding: var(--space-sm) var(--space-md);
            font-size: 0.875rem;
            color: var(--text-primary);
            cursor: pointer;
            transition: all var(--transition-fast);
            backdrop-filter: blur(4px);
        }

        .suggestion-btn:hover {
            background: var(--input-bg);
            transform: translateY(-2px);
        }

        /* Error message */
        .error-message {
            color: var(--error-color);
            padding: var(--space-sm);
            background: rgba(247, 37, 133, 0.1);
            border-radius: var(--radius-sm);
            margin-top: var(--space-sm);
            animation: shake 0.5s ease-in-out;
            border: 1px solid rgba(247, 37, 133, 0.2);
            font-size: 0.875rem;
        }

        /* Text formatting */
        .message-text strong {
            font-weight: 600;
            color: #fff;
        }

        .message-text em {
            font-style: italic;
        }

        .message-text a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all var(--transition-fast);
            border-bottom: 1px dotted currentColor;
        }

        .message-text a:hover {
            color: var(--primary-hover);
            border-bottom-style: solid;
        }

        .message-text ul,
        .message-text ol {
            margin: 0.75em 0;
            padding-left: 1.5em;
        }

        .message-text li {
            margin-bottom: 0.5em;
            position: relative;
        }

        .message-text li::before {
            content: "•";
            color: var(--primary-color);
            position: absolute;
            left: -1em;
        }

        .message-text blockquote {
            border-left: 3px solid var(--primary-color);
            padding-left: var(--space-md);
            margin: var(--space-md) 0;
            color: var(--text-secondary);
            font-style: italic;
            animation: fadeIn 0.5s ease-out;
        }

        .message-text table {
            width: 100%;
            border-collapse: collapse;
            margin: var(--space-md) 0;
            animation: fadeIn 0.5s ease-out;
        }

        .message-text th,
        .message-text td {
            padding: var(--space-sm) var(--space-md);
            border: 1px solid var(--border-color);
            text-align: left;
        }

        .message-text th {
            font-weight: 600;
        }

        .code-box {
            position: relative;
            background: #1e1e1e;
            border-radius: 6px;
            overflow: auto;
            box-shadow: 0 0 8px #0008;
        }

        pre {
            margin: 0;
            padding: 1.2rem 1rem 1.2rem 3.5rem;
            white-space: pre-wrap;
        }

        .lines {
            position: absolute;
            top: 0;
            left: 0;
            width: 2.5rem;
            background: #252526;
            color: #888;
            text-align: right;
            padding: 1.2rem 0.3rem;
            font-size: 0.9rem;
            line-height: 1.5;
            user-select: none;
        }

        /* Animations */
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

        @keyframes messageAppear {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes textFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-2px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(2px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        /* Tooltip */
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 120px;
            color: var(--text-primary);
            text-align: center;
            border-radius: var(--radius-sm);
            padding: var(--space-xs) 0;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity var(--transition-fast);
            font-size: 0.75rem;
            border: 1px solid var(--border-color);
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* Responsive design */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .menu-btn {
                display: block;
            }

            .chat-footer,
            .typing-indicator {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            :root {
                --sidebar-width: 240px;
            }

            .message-row-center {
                padding: 0 var(--space-sm);
                gap: var(--space-sm);
            }

            .message-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.75rem;
            }

            .message-text {
                font-size: 0.9375rem;
                padding-right: var(--space-md);
            }

            .chat-input {
                min-height: 56px;
                font-size: 0.9375rem;
                padding: var(--space-sm);
            }

            .chat-submit {
                padding: 0 var(--space-sm);
            }

            .suggestion-btn {
                padding: var(--space-xs) var(--space-sm);
                font-size: 0.8125rem;
            }
        }

        @media (max-width: 480px) {
            .chat-header h1 {
                font-size: 1rem;
            }

            .message-text {
                font-size: 0.875rem;
            }

            .suggestions {
                flex-direction: column;
                align-items: stretch;
            }

            .suggestion-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
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
    <script>
        // DOM Elements (cached to avoid repeated queries)
        const els = {
            sidebar: document.getElementById('sidebar'),
            menuBtn: document.getElementById('menuBtn'),
            newChatBtn: document.getElementById('newChatBtn'),
            chatHistory: document.getElementById('chatHistory'),
            messageContainer: document.getElementById('messageContainer'),
            chatForm: document.getElementById('chatForm'),
            promptInput: document.getElementById('prompt'),
            typingIndicator: document.getElementById('typingIndicator'),
            suggestions: document.getElementById('suggestions'),
            chatBody: document.getElementById('chatBody')
        };

        // State
        let currentChatId = null;
        let chats = JSON.parse(localStorage.getItem('chats') || '[]');

        // Debounce utility for input resize
        const debounce = (fn, delay) => {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn(...args), delay);
            };
        };

        // Initialize app
        function init() {
            renderChatHistory();
            setupEventListeners();
            if (chats.length) {
                currentChatId = chats[0].id;
                loadChat(currentChatId);
            } else {
                els.suggestions.style.display = 'block';
            }
            highlightCode();
        }

        // Event listeners
        function setupEventListeners() {
            els.chatForm.addEventListener('submit', handleSubmit);

            // Debounced input resize
            const resizeInput = debounce(() => {
                els.promptInput.style.height = 'auto';
                els.promptInput.style.height = `${els.promptInput.scrollHeight}px`;
            }, 50);
            els.promptInput.addEventListener('input', resizeInput);

            // Event delegation for suggestion buttons
            els.suggestions.addEventListener('click', e => {
                const btn = e.target.closest('.suggestion-btn');
                if (btn) {
                    els.promptInput.value = btn.textContent;
                    els.promptInput.focus();
                    resizeInput();
                }
            });

            // Menu toggle
            els.menuBtn.addEventListener('click', () => {
                els.sidebar.classList.toggle('open');
            });

            // New chat
            els.newChatBtn.addEventListener('click', createNewChat);

            // Event delegation for chat history
            els.chatHistory.addEventListener('click', e => {
                const chatItem = e.target.closest('.chat-item');
                if (!chatItem) return;
                const chatId = chatItem.dataset.id;
                if (e.target.closest('.delete-chat')) {
                    deleteChat(chatId);
                } else {
                    loadChat(chatId);
                }
            });

            // Close sidebar on outside click
            document.addEventListener('click', e => {
                if (window.innerWidth <= 1024 && !els.sidebar.contains(e.target) && e.target !== els.menuBtn) {
                    els.sidebar.classList.remove('open');
                }
            });
        }

        // Create new chat
        function createNewChat() {
            currentChatId = Date.now().toString();
            chats.unshift({
                id: currentChatId,
                title: 'New Chat',
                messages: [],
                createdAt: new Date().toISOString()
            });
            saveChats();
            renderChatHistory();
            renderMessages([]);
            setTimeout(() => addMessage("Hello! I'm your AI assistant. How can I help you today?", false), 800);
            if (window.innerWidth <= 1024) els.sidebar.classList.remove('open');
            els.promptInput.value = '';
            els.suggestions.style.display = 'block';
        }

        // Load chat
        function loadChat(chatId) {
            currentChatId = chatId;
            const chat = chats.find(c => c.id === chatId);
            if (chat) {
                renderMessages(chat.messages);
                if (window.innerWidth <= 1024) els.sidebar.classList.remove('open');
            }
        }

        // Save chats
        function saveChats() {
            localStorage.setItem('chats', JSON.stringify(chats));
        }

        // Render chat history
        function renderChatHistory() {
            const fragment = document.createDocumentFragment();
            chats.forEach(chat => {
                const chatItem = document.createElement('div');
                chatItem.className = `chat-item ${chat.id === currentChatId ? 'active' : ''}`;
                chatItem.dataset.id = chat.id;
                chatItem.innerHTML = `
      <i class="fas fa-comment"></i>
      <span>${chat.title}</span>
      <button class="delete-chat" title="Delete chat">
        <i class="fas fa-trash"></i>
      </button>
    `;
                fragment.appendChild(chatItem);
            });
            els.chatHistory.innerHTML = '';
            els.chatHistory.appendChild(fragment);
        }

        // Delete chat
        function deleteChat(chatId) {
            chats = chats.filter(c => c.id !== chatId);
            saveChats();
            renderChatHistory();
            if (currentChatId === chatId) {
                if (chats.length) {
                    loadChat(chats[0].id);
                } else {
                    currentChatId = null;
                    renderMessages([]);
                    els.suggestions.style.display = 'block';
                }
            }
        }

        // Render messages
        function renderMessages(messages) {
            const fragment = document.createDocumentFragment();
            messages.forEach(msg => {
                fragment.appendChild(createMessageElement(msg.content, msg.isUser, msg.isError));
            });
            els.messageContainer.innerHTML = '';
            els.messageContainer.appendChild(fragment);
            scrollToBottom();
            highlightCode();
        }

        // Escape HTML
        const escapeHtml = text => text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");

        // Format message
        const formatMessage = text => text
            .replace(/```(\w*)\n([\s\S]*?)```/g, (match, lang, code) => `
    <div class="code-box">
      <div class="lines"></div>
      <pre><code class="language-${lang || 'plaintext'}">${escapeHtml(code)}</code></pre>
    </div>`)
            .replace(/`([^`]+)`/g, '<code>$1</code>')
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank" rel="noopener">$1</a>')
            .replace(/\n/g, '<br>');

        // Scroll to bottom
        const scrollToBottom = () => {
            els.chatBody.scrollTop = els.chatBody.scrollHeight;
        };

        // Highlight code
        const highlightCode = () => {
            hljs.highlightAll();
            const codeEl = document.getElementById("code");
            if (codeEl) {
                const lines = codeEl.innerText.trim().split('\n');
                const linesEl = document.getElementById("lines");
                if (linesEl) linesEl.innerHTML = lines.map((_, i) => i + 1).join('<br>');
            }
        };

        // Simplified typing animation
        const simulateTyping = (element, text) => {
            element.innerHTML = formatMessage(text);
            scrollToBottom();
            highlightCode();
        };

        // Create message element
        function createMessageElement(content, isUser = false, isError = false) {
            const messageRow = document.createElement('div');
            messageRow.className = `message-row ${isUser ? 'user' : 'ai'}`;
            const center = document.createElement('div');
            center.className = 'message-row-center';
            const avatar = document.createElement('div');
            avatar.className = `message-avatar ${isUser ? 'user' : 'ai'}`;
            avatar.textContent = isUser ? 'You' : 'AI';
            const contentDiv = document.createElement('div');
            contentDiv.className = 'message-content';
            const textDiv = document.createElement('div');
            textDiv.className = `message-text ${isError ? 'error-message' : ''}`;
            textDiv[isError ? 'textContent' : 'innerHTML'] = isError ? content : formatMessage(content);
            contentDiv.appendChild(textDiv);
            center.appendChild(avatar);
            center.appendChild(contentDiv);
            messageRow.appendChild(center);
            return messageRow;
        }

        // Add message
        function addMessage(content, isUser = false, isError = false, saveToHistory = true) {
            if (saveToHistory && currentChatId) {
                const chat = chats.find(c => c.id === currentChatId);
                if (chat) {
                    chat.messages.push({ content, isUser, isError });
                    if (isUser && chat.title === 'New Chat') {
                        chat.title = content.length > 30 ? content.substring(0, 30) + '...' : content;
                        renderChatHistory();
                    }
                    saveChats();
                }
            }
            const messageEl = createMessageElement(content, isUser, isError);
            els.messageContainer.appendChild(messageEl);
            scrollToBottom();
            if (!isError && !isUser) {
                simulateTyping(messageEl.querySelector('.message-text'), content);
            } else {
                highlightCode();
            }
        }

        // Handle form submission
        async function handleSubmit(e) {
            e.preventDefault();
            const prompt = els.promptInput.value.trim();
            if (!prompt) return;
            if (!currentChatId) createNewChat();
            addMessage(prompt, true);
            els.promptInput.value = '';
            els.promptInput.style.height = 'auto';
            els.typingIndicator.classList.add('active');
            els.suggestions.style.display = 'none';
            scrollToBottom();

            try {
                const response = await fetch('process.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `prompt=${encodeURIComponent(prompt)}`
                });
                const data = await response.json();
                els.typingIndicator.classList.remove('active');
                if (data.error) {
                    addMessage(data.error, false, true);
                } else {
                    const responseText = prompt.toLowerCase().includes('code')
                        ? `\`\`\`javascript\n${data.text}\n\`\`\``
                        : data.text;
                    addMessage(responseText, false);
                }
            } catch (err) {
                els.typingIndicator.classList.remove('active');
                addMessage('Failed to connect to the server. Please check your network.', false, true);
                console.error('Fetch error:', err);
            }
        }

        // Start app
        init();
    </script>
</body>

</html>
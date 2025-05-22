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
            --message-ai-bg: linear-gradient(135deg, #16213e, #1a2a44);
            --message-user-bg: linear-gradient(135deg, #1a2a44, #16213e);
            --text-primary: #f1f1f1;
            --text-secondary: #b8b8d1;
            --border-color: #2a2a3a;
            --primary-color: #4cc9f0;
            --primary-hover: #3aa8d8;
            --error-color: #f72585;
            --success-color: #4ad66d;
            --warning-color: #f8961e;
            --input-bg: #2a2a3a;
            --input-border: #3a3a4a;
            --input-focus: rgba(76, 201, 240, 0.3);
            --code-bg: #0f0f1a;
            --code-header-bg: #1a1a2a;
            --code-text: #f8f8f2;
            --code-border: #3a3a4a;
            --sidebar-bg: #16213e;

            /* Typography */
            --font-base: 'Inter', sans-serif;
            --font-mono: 'Fira Code', monospace;

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
            --transition-fast: 0.2s ease;
            --transition-normal: 0.4s ease;
            --transition-slow: 0.6s ease;
        }

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
            overflow: hidden;
        }

        /* Main layout */
        .app-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 300px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            overflow-y: auto;
            transition: transform var(--transition-normal);
            transform: translateX(0);
        }

        .sidebar.hidden {
            transform: translateX(-100%);
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

        .toggle-sidebar {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1rem;
            transition: color var(--transition-fast);
        }

        .toggle-sidebar:hover {
            color: var(--primary-color);
        }

        .history-list {
            list-style: none;
            padding: var(--space-md);
        }

        .history-item {
            padding: var(--space-sm);
            margin-bottom: var(--space-sm);
            background: rgba(255, 255, 255, 0.05);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all var(--transition-fast);
            animation: fadeIn 0.5s ease;
        }

        .history-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .history-item span {
            display: block;
            font-size: 0.875rem;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Chat container */
        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            max-width: 900px;
            margin: 0 auto;
        }

        .chat-header {
            padding: var(--space-md);
            background: var(--primary-bg);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-header h1 {
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }

        .chat-header h1::before {
            content: "";
            width: 10px;
            height: 10px;
            background-color: var(--primary-color);
            border-radius: var(--radius-full);
            animation: pulse 2s infinite;
        }

        .chat-body {
            flex: 1;
            overflow-y: auto;
            scroll-behavior: smooth;
            padding-bottom: 120px;
        }

        .message-container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .message-row {
            display: flex;
            width: 100%;
            padding: var(--space-lg) 0;
            animation: slideIn 0.5s ease-out;
        }

        .message-row.user {
            background: var(--message-user-bg);
        }

        .message-row.ai {
            background: var(--message-ai-bg);
        }

        .message-row-center {
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            gap: var(--space-md);
            padding: 0 var(--space-md);
        }

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
            font-size: 0.875rem;
        }

        .message-avatar.user {
            background: linear-gradient(135deg, var(--primary-color), #4361ee);
        }

        .message-avatar.ai {
            background: linear-gradient(135deg, #7209b7, #f72585);
        }

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
            margin-bottom: var(--space-md);
        }

        /* Code blocks */
        .code-block {
            position: relative;
            margin: var(--space-lg) 0;
            background: var(--code-bg);
            border-radius: var(--radius-md);
            border: 1px solid var(--code-border);
            transition: transform var(--transition-fast);
        }

        .code-block:hover {
            transform: scale(1.02);
        }

        .code-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--space-sm) var(--space-md);
            background: var(--code-header-bg);
            color: var(--text-secondary);
            font-family: var(--font-mono);
            font-size: 0.75rem;
        }

        .code-language {
            font-weight: 600;
            text-transform: uppercase;
        }

        .code-copy {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: var(--space-xs);
            transition: color var(--transition-fast);
        }

        .code-copy:hover {
            color: var(--primary-color);
        }

        .code-copy.copied::after {
            content: 'Copied!';
            position: absolute;
            right: var(--space-md);
            color: var(--success-color);
            font-size: 0.75rem;
        }

        .code-content {
            overflow-x: auto;
            padding: var(--space-md);
        }

        .code-content pre {
            margin: 0;
            font-family: var(--font-mono);
            font-size: 0.875rem;
            line-height: 1.5;
            color: var(--code-text);
        }

        .message-text code:not(.hljs) {
            background: rgba(76, 201, 240, 0.1);
            padding: 0.2em 0.4em;
            border-radius: var(--radius-sm);
            font-family: var(--font-mono);
            font-size: 0.9em;
            color: var(--primary-color);
        }

        .typing-indicator {
            display: flex;
            padding: var(--space-md);
            align-items: center;
            background: var(--message-ai-bg);
            opacity: 0;
            transform: translateY(20px);
            transition: all var(--transition-normal);
            position: fixed;
            bottom: 100px;
            width: 100%;
            max-width: 900px;
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
            background-color: var(--primary-color);
            animation: bounce 1.2s infinite ease-in-out;
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
            padding: var(--space-md);
            background: var(--primary-bg);
            position: fixed;
            bottom: 0;
            width: 100%;
            max-width: 900px;
            border-top: 1px solid var(--border-color);
        }

        .chat-form-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .chat-form {
            display: flex;
            border-radius: var(--radius-lg);
            border: 1px solid var(--input-border);
            background: var(--input-bg);
            transition: all var(--transition-fast);
        }

        .chat-form:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--input-focus);
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
            transition: all var(--transition-fast);
        }

        .chat-submit:hover {
            color: var(--primary-color);
            transform: scale(1.1);
        }

        .chat-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

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
        }

        .suggestion-btn:hover {
            background: var(--input-bg);
            transform: scale(1.05);
        }

        .error-message {
            color: var(--error-color);
            padding: var(--space-sm);
            background: rgba(247, 37, 133, 0.1);
            border-radius: var(--radius-sm);
            margin-top: var(--space-sm);
            animation: shake 0.5s ease-in-out;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
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

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
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

        /* Responsive design */
        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                position: absolute;
                z-index: 100;
                height: 100%;
            }

            .chat-container {
                margin-left: 0;
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
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 200px;
            }

            .chat-header h1 {
                font-size: 1.1rem;
            }

            .message-text {
                font-size: 0.875rem;
            }

            .code-content pre {
                font-size: 0.8125rem;
            }
        }
    </style>
</head>

<body>
    <div class="app-container">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>Chat History</h2>
                <button class="toggle-sidebar" id="toggleSidebar"><i class="fas fa-times"></i></button>
            </div>
            <ul class="history-list" id="historyList"></ul>
        </div>
        <div class="chat-container">
            <div class="chat-header">
                <button class="toggle-sidebar" id="toggleSidebarBtn"><i class="fas fa-bars"></i></button>
                <h1>AI Assistant</h1>
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
                        <button type="submit" class="chat-submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                    <div class="suggestions" id="suggestions">
                        <button class="suggestion-btn">Explain quantum computing</button>
                        <button class="suggestion-btn">Write a poem about AI</button>
                        <button class="suggestion-btn">How to make pizza dough?</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
        const elements = {
            messageContainer: document.getElementById('messageContainer'),
            chatForm: document.getElementById('chatForm'),
            promptInput: document.getElementById('prompt'),
            typingIndicator: document.getElementById('typingIndicator'),
            suggestions: document.getElementById('suggestions'),
            chatBody: document.getElementById('chatBody'),
            sidebar: document.getElementById('sidebar'),
            toggleSidebarBtn: document.getElementById('toggleSidebarBtn'),
            toggleSidebar: document.getElementById('toggleSidebar'),
            historyList: document.getElementById('historyList')
        };

        // Utility Functions
        const escapeHtml = text => text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");

        const formatMessage = text => {
            return text
                .replace(/```(\w*)\n([\s\S]*?)```/g, (match, lang, code) => {
                    const language = lang || 'plaintext';
                    return `
                        <div class="code-block">
                            <div class="code-header">
                                <span class="code-language">${language}</span>
                                <button class="code-copy" onclick="copyCode(this, \`${escapeHtml(code)}\`)">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                            <div class="code-content">
                                <pre><code class="language-${language}">${escapeHtml(code)}</code></pre>
                            </div>
                        </div>`;
                })
                .replace(/`([^`]+)`/g, '<code>$1</code>')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank" rel="noopener">$1</a>');
        };

        const scrollToBottom = () => {
            elements.chatBody.scrollTop = elements.chatBody.scrollHeight;
        };

        // Chat History Management
        const loadChatHistory = () => {
            const history = JSON.parse(localStorage.getItem('chatHistory') || '[]');
            elements.historyList.innerHTML = '';
            history.forEach((item, index) => {
                const li = document.createElement('li');
                li.className = 'history-item';
                li.innerHTML = `<span>${item.preview}</span>`;
                li.addEventListener('click', () => loadConversation(index));
                elements.historyList.appendChild(li);
            });
        };

        const saveChatHistory = (prompt, response) => {
            const history = JSON.parse(localStorage.getItem('chatHistory') || '[]');
            const preview = prompt.substring(0, 50) + (prompt.length > 50 ? '...' : '');
            history.push({ preview, messages: [{ role: 'user', content: prompt }, { role: 'ai', content: response }] });
            localStorage.setItem('chatHistory', JSON.stringify(history));
            loadChatHistory();
        };

        const loadConversation = index => {
            const history = JSON.parse(localStorage.getItem('chatHistory') || '[]');
            const conversation = history[index];
            if (conversation) {
                elements.messageContainer.innerHTML = '';
                conversation.messages.forEach(msg => addMessage(msg.content, msg.role === 'user'));
                scrollToBottom();
            }
        };

        // Message Handling
        const addMessage = (content, isUser = false, isError = false) => {
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
            elements.messageContainer.appendChild(messageRow);

            scrollToBottom();
            if (!isError && !isUser) simulateTyping(textDiv, content);
            setTimeout(() => hljs.highlightAll(), 100);
        };

        const simulateTyping = (element, text) => {
            const formatted = formatMessage(text);
            element.innerHTML = '';
            let i = 0;
            const type = () => {
                if (i < formatted.length) {
                    element.innerHTML += formatted[i++];
                    scrollToBottom();
                    setTimeout(type, 5);
                } else {
                    hljs.highlightAll();
                }
            };
            type();
        };

        // Event Handlers
        const handleSubmit = async e => {
            e.preventDefault();
            const prompt = elements.promptInput.value.trim();
            if (!prompt) return;

            addMessage(prompt, true);
            elements.promptInput.value = '';
            elements.promptInput.style.height = 'auto';
            elements.typingIndicator.classList.add('active');
            elements.suggestions.style.display = 'none';

            try {
                const response = await fetch('process.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `prompt=${encodeURIComponent(prompt)}`
                });
                const data = await response.json();
                elements.typingIndicator.classList.remove('active');

                addMessage(data.error || data.text, false, !!data.error);
                if (!data.error) saveChatHistory(prompt, data.text);
            } catch (err) {
                elements.typingIndicator.classList.remove('active');
                addMessage('Failed to connect to the server. Please check your network.', false, true);
                console.error('Fetch error:', err);
            }
        };

        const copyCode = (button, code) => {
            navigator.clipboard.writeText(code).then(() => {
                button.classList.add('copied');
                setTimeout(() => button.classList.remove('copied'), 2000);
            });
        };

        // Initialization
        const init = () => {
            elements.promptInput.addEventListener('input', () => {
                elements.promptInput.style.height = 'auto';
                elements.promptInput.style.height = `${elements.promptInput.scrollHeight}px`;
            });

            elements.chatForm.addEventListener('submit', handleSubmit);

            elements.toggleSidebarBtn.addEventListener('click', () => {
                elements.sidebar.classList.toggle('hidden');
            });

            elements.toggleSidebar.addEventListener('click', () => {
                elements.sidebar.classList.toggle('hidden');
            });

            document.querySelectorAll('.suggestion-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    elements.promptInput.value = btn.textContent;
                    elements.promptInput.focus();
                    elements.promptInput.dispatchEvent(new Event('input'));
                });
            });

            elements.promptInput.focus();
            loadChatHistory();
            setTimeout(() => addMessage("Hello! I'm your AI assistant. How can I help you today?"), 800);
        };

        init();
    </script>
</body>

</html>
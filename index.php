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

            /* Shadows */
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);

            /* Inputs */
            --input-bg: #2a2a3a;
            --input-border: #3a3a4a;
            --input-focus: rgba(76, 201, 240, 0.3);

            /* Code blocks */
            --code-bg: #0f0f1a;
            --code-header-bg: #1a1a2a;
            --code-text: #f8f8f2;
            --code-border: #3a3a4a;

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
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Chat container */
        .chat-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 900px;
            height: 100vh;
            margin: 0 auto;
            position: relative;
            background: var(--primary-bg);
        }

        /* Header */
        .chat-header {
            padding: var(--space-md);
            background: var(--primary-bg);
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid var(--border-color);
            backdrop-filter: blur(8px);
        }

        .chat-header h1 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }

        .chat-header h1::before {
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
            animation: messageAppear 0.3s ease-out;
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
        }

        .message-avatar.user {
            background: linear-gradient(135deg, var(--primary-color), #4361ee);
        }

        .message-avatar.ai {
            background: linear-gradient(135deg, #7209b7, #f72585);
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
        }

        /* Code blocks */
        .code-block {
            position: relative;
            margin: var(--space-md) 0;
            background: var(--code-bg);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--code-border);
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
            border-bottom: 1px solid var(--code-border);
        }

        .code-language {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.6875rem;
            letter-spacing: 0.5px;
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

        .code-content {
            overflow-x: auto;
        }

        .code-content pre {
            margin: 0;
            padding: var(--space-md);
            font-family: var(--font-mono);
            font-size: 0.875rem;
            line-height: 1.5;
            color: var(--code-text);
            tab-size: 2;
        }

        .code-content code {
            display: block;
            white-space: pre;
        }

        /* Inline code */
        .message-text code:not(.hljs) {
            background: rgba(76, 201, 240, 0.1);
            padding: 0.2em 0.4em;
            border-radius: var(--radius-sm);
            font-family: var(--font-mono);
            font-size: 0.9em;
            color: var(--primary-color);
            border: 1px solid rgba(76, 201, 240, 0.2);
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
            width: 100%;
            max-width: 900px;
            border-top: 1px solid var(--border-color);
            backdrop-filter: blur(8px);
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
        }

        .chat-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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
        }

        .message-text blockquote {
            border-left: 3px solid var(--primary-color);
            padding-left: var(--space-md);
            margin: var(--space-md) 0;
            color: var(--text-secondary);
            font-style: italic;
        }

        .message-text table {
            width: 100%;
            border-collapse: collapse;
            margin: var(--space-md) 0;
        }

        .message-text th,
        .message-text td {
            padding: var(--space-sm) var(--space-md);
            border: 1px solid var(--border-color);
            text-align: left;
        }

        .message-text th {
            background-color: var(--code-header-bg);
            font-weight: 600;
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
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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
            background-color: var(--code-header-bg);
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
        @media (max-width: 768px) {
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
                font-size: 1.1rem;
            }

            .message-text {
                font-size: 0.875rem;
            }

            .code-content pre {
                padding: var(--space-sm);
                font-size: 0.8125rem;
            }
        }
    </style>
</head>

<body>
    <div class="chat-container">
        <div class="chat-header">
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
        const elements = {
            messageContainer: document.getElementById('messageContainer'),
            chatForm: document.getElementById('chatForm'),
            promptInput: document.getElementById('prompt'),
            typingIndicator: document.getElementById('typingIndicator'),
            suggestions: document.getElementById('suggestions'),
            chatBody: document.getElementById('chatBody')
        };

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
                    </div>
                    <pre><code class="language-${language}">${escapeHtml(code)}</code></pre>
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

        const simulateTyping = (element, text) => {
            const formatted = formatMessage(text);
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = formatted;
            const nodes = Array.from(tempDiv.childNodes);
            let nodeIndex = 0, charIndex = 0;

            element.innerHTML = '';
            const typeChar = () => {
                if (nodeIndex >= nodes.length) return;
                const node = nodes[nodeIndex];

                if (node.nodeType === Node.TEXT_NODE) {
                    if (charIndex < node.textContent.length) {
                        element.innerHTML += node.textContent[charIndex++];
                        scrollToBottom();
                        setTimeout(typeChar, 10 + Math.random() * 10);
                    } else {
                        nodeIndex++;
                        charIndex = 0;
                        typeChar();
                    }
                } else {
                    element.appendChild(node.cloneNode(true));
                    nodeIndex++;
                    charIndex = 0;
                    setTimeout(() => {
                        hljs.highlightAll();
                        scrollToBottom();
                        typeChar();
                    }, 20);
                }
            };
            typeChar();
        };

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
        };

        const handleSubmit = async e => {
            e.preventDefault();
            const prompt = elements.promptInput.value.trim();
            if (!prompt) return;

            addMessage(prompt, true);
            elements.promptInput.value = '';
            elements.promptInput.style.height = 'auto';
            elements.typingIndicator.classList.add('active');
            elements.suggestions.style.display = 'none';
            scrollToBottom();

            try {
                const response = await fetch('process.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `prompt=${encodeURIComponent(prompt)}`
                });
                const data = await response.json();
                elements.typingIndicator.classList.remove('active');

                addMessage(data.error || data.text, false, !!data.error);
                if (!data.error) {
                    setTimeout(() => document.querySelectorAll('.code-content code').forEach(hljs.highlightElement), 100);
                }
            } catch (err) {
                elements.typingIndicator.classList.remove('active');
                addMessage('Failed to connect to the server. Please check your network.', false, true);
                console.error('Fetch error:', err);
            }
        };

        const init = () => {
            elements.promptInput.addEventListener('input', () => {
                elements.promptInput.style.height = 'auto';
                elements.promptInput.style.height = `${elements.promptInput.scrollHeight}px`;
            });

            elements.chatForm.addEventListener('submit', handleSubmit);

            document.querySelectorAll('.suggestion-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    elements.promptInput.value = btn.textContent;
                    elements.promptInput.focus();
                    elements.promptInput.dispatchEvent(new Event('input'));
                });
            });

            elements.promptInput.focus();
            setTimeout(() => addMessage("Hello! I'm your AI assistant. How can I help you today?"), 800);
            hljs.highlightAll();
        };

        init();
    </script>
</body>

</html>
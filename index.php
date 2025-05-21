<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chat Assistant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <style>
        :root {
            --primary-bg: #343541;
            --message-ai-bg: #444654;
            --message-user-bg: #343541;
            --text-primary: #ececf1;
            --text-secondary: #acacbe;
            --border-color: #565869;
            --primary-color: #10a37f;
            --primary-hover: #0d8a6d;
            --error-color: #ef4146;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.2);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.2);
            --input-bg: #40414f;
            --input-border: #565869;
            --code-bg: #2b2b3a;
            --code-header-bg: #363642;
            --code-text: #f8f8f2;
            --code-copy-hover: #4d4d5a;
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
            flex-direction: column;
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin: 0 auto;
            height: 100vh;
            max-width: 900px;
            position: relative;
        }

        .chat-header {
            padding: 16px;
            text-align: center;
            background: var(--primary-bg);
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid var(--border-color);
        }

        .chat-header h1 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .chat-body {
            flex: 1;
            overflow-y: auto;
            scroll-behavior: smooth;
            padding-bottom: 100px;
        }

        .message-container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .message-row {
            display: flex;
            width: 100%;
            padding: 24px 0;
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
            gap: 20px;
            padding: 0 16px;
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
            margin-top: 4px;
        }

        .message-avatar.user {
            background: #10a37f;
        }

        .message-avatar.ai {
            background: #6e6e80;
        }

        .message-content {
            flex: 1;
        }

        .message-text {
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.7;
            font-size: 16px;
            padding-right: 40px;
        }

        .code-block {
            position: relative;
            margin: 16px 0;
            background: var(--code-bg);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .code-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 16px;
            background: var(--code-header-bg);
            color: var(--text-secondary);
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 14px;
        }

        .code-language {
            font-weight: 500;
            text-transform: uppercase;
            font-size: 12px;
        }

        .copy-btn {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .copy-btn:hover {
            background: var(--code-copy-hover);
        }

        .code-content {
            overflow-x: auto;
        }

        .code-content pre {
            margin: 0;
            padding: 16px;
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.5;
            color: var(--code-text);
        }

        .code-content code {
            display: block;
            white-space: pre;
        }

        .message-text code:not(.hljs) {
            background: rgba(0, 0, 0, 0.3);
            padding: 0.2em 0.4em;
            border-radius: 4px;
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 0.9em;
            color: #eb5f5f;
        }

        .typing-indicator {
            display: flex;
            padding: 16px;
            align-items: center;
            background: var(--message-ai-bg);
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s, transform 0.3s;
            position: fixed;
            bottom: 100px;
            width: 100%;
            max-width: 900px;
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
            max-width: 900px;
            border-top: 1px solid var(--border-color);
        }

        .chat-form-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .chat-form {
            display: flex;
            border-radius: 8px;
            border: 1px solid var(--input-border);
            background: var(--input-bg);
            box-shadow: var(--shadow-md);
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .chat-form:focus-within {
            box-shadow: 0 0 0 2px rgba(16, 163, 127, 0.3);
            border-color: var(--primary-color);
        }

        .chat-input {
            flex: 1;
            padding: 12px 16px;
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
            background: transparent;
            border: none;
            padding: 0 16px;
            color: var(--text-secondary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
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
            color: var(--error-color);
            padding: 12px;
            background: rgba(239, 65, 70, 0.1);
            border-radius: 8px;
            margin-top: 8px;
            animation: shake 0.5s ease-in-out;
        }

        .message-text strong {
            font-weight: 600;
        }

        .message-text em {
            font-style: italic;
        }

        .message-text a {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .message-text ul,
        .message-text ol {
            margin: 0.5em 0;
            padding-left: 1.5em;
        }

        .message-text li {
            margin-bottom: 0.5em;
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

        @media (max-width: 768px) {
            .message-row-center {
                padding: 0 12px;
                gap: 12px;
            }

            .message-avatar {
                width: 32px;
                height: 32px;
            }

            .message-text {
                font-size: 15px;
                padding-right: 16px;
            }

            .chat-input {
                min-height: 50px;
                font-size: 0.95rem;
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
                                <button class="copy-btn" onclick="copyCode(event)">
                                    <i class="far fa-clipboard"></i> Copy
                                </button>
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

        function copyCode(event) {
            const button = event.target;
            const code = button.closest(".code-container").querySelector("pre");

            navigator.clipboard
                .writeText(code.innerText)
                .then(() => {
                    button.innerHTML = '<i class="fas fa-clipboard-check"></i> Copied!';
                    setTimeout(
                        () => (button.innerHTML = '<i class="far fa-clipboard"></i> Copy'),
                        2000
                    );
                })
                .catch((err) => console.error("Copy failed:", err));
        }

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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chat Interface</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Highlight.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/vs2015.min.css">
    <style>
        /* ===== BASE STYLES ===== */
        :root {
            --user-bg: #ffffff;
            --ai-bg: #f7f7f8;
            --text-primary: #343541;
            --text-secondary: #6e6e80;
            --border-color: #d9d9e3;
            --primary-color: #10a37f;
            --primary-hover: #0d8a6d;
            --error-color: #ef4146;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
            --code-bg: #1e1e1e;
            --code-text: #d4d4d4;
            --code-border: #3c3c3c;
            --line-numbers: #858585;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --user-bg: #343541;
                --ai-bg: #444654;
                --text-primary: #ececf1;
                --text-secondary: #acacbe;
                --border-color: #565869;
                --primary-color: #10a37f;
                --primary-hover: #0d8a6d;
                --code-bg: #1e1e1e;
                --code-text: #d4d4d4;
                --code-border: #3c3c3c;
                --line-numbers: #858585;
            }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            background: var(--ai-bg);
            color: var(--text-primary);
            line-height: 1.6;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== CHAT CONTAINER ===== */
        .chat-container {
            display: flex;
            flex-direction: column;
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            height: 100vh;
            background: var(--ai-bg);
        }

        /* ===== HEADER ===== */
        .chat-header {
            padding: 16px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
            background: var(--user-bg);
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-header h1 {
            font-size: 1.2rem;
            font-weight: 600;
        }

        /* ===== CHAT BODY ===== */
        .chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 16px 0;
            scroll-behavior: smooth;
        }

        .message-container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        /* ===== MESSAGE STYLES ===== */
        .message-row {
            display: flex;
            width: 100%;
            padding: 16px 0;
            animation: messageAppear 0.3s ease-out;
        }

        .message-row.user {
            background: var(--user-bg);
        }

        .message-row.ai {
            background: var(--ai-bg);
        }

        .message-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 16px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }

        .message-avatar.user {
            background: #6e6e80;
        }

        .message-avatar.ai {
            background: var(--primary-color);
        }

        .message-content {
            flex: 1;
            padding-right: 16px;
            max-width: calc(100% - 72px);
        }

        .message-text {
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.7;
        }

        /* User message specific styling */
        .message-row.user .message-text {
            color: var(--text-primary);
        }

        /* AI message specific styling */
        .message-row.ai .message-text {
            color: var(--text-primary);
        }

        /* ===== CODE BLOCK STYLES ===== */
        .code-block {
            position: relative;
            margin: 12px 0;
            background: var(--code-bg);
            border: 1px solid var(--code-border);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .code-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: rgba(0, 0, 0, 0.2);
            color: var(--code-text);
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 0.85em;
            border-bottom: 1px solid var(--code-border);
        }

        .code-language {
            font-weight: bold;
        }

        .copy-btn {
            background: transparent;
            border: none;
            color: var(--code-text);
            cursor: pointer;
            font-size: 0.8em;
            display: flex;
            align-items: center;
            gap: 4px;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .copy-btn:hover {
            opacity: 1;
        }

        .code-content {
            overflow-x: auto;
            padding: 12px;
        }

        .code-content pre {
            margin: 0;
            font-family: 'Consolas', 'Courier New', monospace;
            font-size: 0.9em;
            line-height: 1.5;
            color: var(--code-text);
        }

        .code-content code {
            display: block;
            white-space: pre;
        }

        /* Inline code styling */
        .message-text code:not(.hljs) {
            background: rgba(0, 0, 0, 0.1);
            padding: 0.2em 0.4em;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            color: var(--primary-color);
        }

        /* ===== TYPING INDICATOR ===== */
        .typing-indicator {
            display: flex;
            padding: 12px 16px;
            align-items: center;
            background: var(--ai-bg);
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s, transform 0.3s;
        }

        .typing-indicator.active {
            opacity: 1;
            transform: translateY(0);
        }

        .typing-dots {
            display: flex;
            margin-left: 8px;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--text-secondary);
            margin: 0 2px;
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

        /* ===== CHAT FOOTER ===== */
        .chat-footer {
            padding: 16px;
            border-top: 1px solid var(--border-color);
            background: var(--user-bg);
            position: sticky;
            bottom: 0;
        }

        .chat-form {
            display: flex;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background: var(--user-bg);
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s, border-color 0.2s;
        }

        .chat-form:focus-within {
            box-shadow: var(--shadow-md);
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
            line-height: 1.6;
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

        /* ===== SUGGESTIONS ===== */
        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 16px;
        }

        .suggestion-btn {
            background: var(--ai-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 8px 16px;
            font-size: 0.9rem;
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.2s;
        }

        .suggestion-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* ===== ERROR MESSAGE ===== */
        .error-message {
            color: var(--error-color);
            padding: 12px;
            background: rgba(239, 65, 70, 0.1);
            border-radius: 8px;
            margin-top: 8px;
            animation: shake 0.5s ease-in-out;
        }

        /* ===== TEXT FORMATTING ===== */
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
            margin-bottom: 0.25em;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes typingAnimation {

            0%,
            60%,
            100% {
                transform: translateY(0);
                opacity: 0.6;
            }

            30% {
                transform: translateY(-5px);
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

        /* ===== RESPONSIVE ADJUSTMENTS ===== */
        @media (max-width: 768px) {
            .chat-container {
                height: 100vh;
                max-width: 100%;
            }

            .message-avatar {
                width: 32px;
                height: 32px;
                margin: 0 12px;
            }

            .message-content {
                max-width: calc(100% - 56px);
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
            <div class="message-container" id="messageContainer">
                <!-- Messages will be added here dynamically -->
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
            <form id="chatForm" class="chat-form">
                <textarea class="chat-input" id="prompt" placeholder="Type your message..." rows="1"
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

    <!-- Highlight.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/html.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/cpp.min.js"></script>

    <script>
        // DOM Elements
        const messageContainer = document.getElementById('messageContainer');
        const chatForm = document.getElementById('chatForm');
        const promptInput = document.getElementById('prompt');
        const typingIndicator = document.getElementById('typingIndicator');
        const suggestions = document.getElementById('suggestions');
        const suggestionBtns = document.querySelectorAll('.suggestion-btn');

        // Auto-resize textarea
        promptInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Scroll to bottom of chat
        function scrollToBottom() {
            const chatBody = document.getElementById('chatBody');
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        // Add message to chat
        function addMessage(content, isUser = false, isError = false) {
            const messageRow = document.createElement('div');
            messageRow.className = `message-row ${isUser ? 'user' : 'ai'}`;

            const avatarDiv = document.createElement('div');
            avatarDiv.className = `message-avatar ${isUser ? 'user' : 'ai'}`;
            avatarDiv.textContent = isUser ? 'You' : 'AI';

            const contentDiv = document.createElement('div');
            contentDiv.className = 'message-content';

            const textDiv = document.createElement('div');
            textDiv.className = 'message-text';

            if (isError) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.textContent = content;
                contentDiv.appendChild(errorDiv);
            } else {
                textDiv.innerHTML = formatMessage(content);
                contentDiv.appendChild(textDiv);
            }

            messageRow.appendChild(avatarDiv);
            messageRow.appendChild(contentDiv);
            messageContainer.appendChild(messageRow);

            scrollToBottom();

            if (!isError && !isUser) {
                simulateTyping(textDiv, content);
            }
        }

        // Format message with markdown support including code blocks
        function formatMessage(text) {
            // First process code blocks
            let processedText = text.replace(/```(\w*)\n([\s\S]*?)```/g, function(match, language, code) {
                // Default to plaintext if no language specified
                if (!language) language = 'plaintext';
                
                // Create code block HTML
                return `
                <div class="code-block">
                    <div class="code-header">
                        <span class="code-language">${language}</span>
                        <button class="copy-btn" onclick="copyCode(this)">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                    <div class="code-content">
                        <pre><code class="language-${language}">${escapeHtml(code)}</code></pre>
                    </div>
                </div>
                `;
            });
            
            // Then process inline code
            processedText = processedText.replace(/`([^`]+)`/g, '<code>$1</code>');
            
            // Process other markdown
            processedText = processedText
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // bold
                .replace(/\*(.*?)\*/g, '<em>$1</em>') // italic
                .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank">$1</a>') // links
                .replace(/\n/g, '<br>'); // line breaks
                
            return processedText;
        }

        // Helper function to escape HTML
        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Copy code to clipboard
        function copyCode(button) {
            const codeBlock = button.closest('.code-block').querySelector('code');
            const textToCopy = codeBlock.textContent;
            
            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i> Copied!';
                setTimeout(() => {
                    button.innerHTML = originalText;
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        }

        // Simulate typing animation
        function simulateTyping(element, fullText) {
            element.innerHTML = '';
            let i = 0;
            const speed = 10 + Math.random() * 10;

            function typeWriter() {
                if (i < fullText.length) {
                    const char = fullText.charAt(i);

                    // Handle code blocks
                    if (char === '`' && fullText.substring(i, i+3) === '```') {
                        const endPos = fullText.indexOf('```', i+3);
                        if (endPos !== -1) {
                            const codeBlock = fullText.substring(i, endPos+3);
                            element.innerHTML += formatMessage(codeBlock);
                            i = endPos + 3;
                            
                            // Highlight the code after it's inserted
                            setTimeout(() => {
                                document.querySelectorAll('.code-content code').forEach(block => {
                                    hljs.highlightElement(block);
                                });
                            }, 0);
                        } else {
                            element.innerHTML += char;
                            i++;
                        }
                    } 
                    // Handle other characters
                    else {
                        element.innerHTML += char;
                        i++;
                    }

                    scrollToBottom();
                    setTimeout(typeWriter, speed);
                }
            }

            typeWriter();
        }

        // Handle form submission
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const prompt = promptInput.value.trim();
            if (!prompt) return;

            // Add user message
            addMessage(prompt, true);
            promptInput.value = '';
            promptInput.style.height = 'auto';

            // Show typing indicator
            typingIndicator.classList.add('active');
            scrollToBottom();

            // Hide suggestions after first message
            suggestions.style.display = 'none';

            try {
                const response = await fetch('process.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `prompt=${encodeURIComponent(prompt)}`
                });

                const data = await response.json();
                typingIndicator.classList.remove('active');

                if (data.error) {
                    addMessage(`Error: ${data.error}`, false, true);
                } else {
                    addMessage(data.text);
                    
                    // Highlight any code blocks in the response
                    setTimeout(() => {
                        document.querySelectorAll('.code-content code').forEach(block => {
                            hljs.highlightElement(block);
                        });
                    }, 100);
                }
            } catch (error) {
                typingIndicator.classList.remove('active');
                addMessage('Failed to connect to the server. Please check your network connection.', false, true);
                console.error('Error:', error);
            }
        });

        // Add click handlers for suggestion buttons
        suggestionBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                promptInput.value = btn.textContent;
                promptInput.focus();
                promptInput.dispatchEvent(new Event('input'));
            });
        });

        // Auto-focus input on page load
        promptInput.focus();

        // Initial greeting
        setTimeout(() => {
            addMessage("Hello! I'm your AI assistant. How can I help you today?");
        }, 800);
    </script>
</body>

</html>
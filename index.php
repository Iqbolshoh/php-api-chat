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
    <script>
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

        let currentChatId = null;
        let chats = JSON.parse(localStorage.getItem('chats') || '[]');

        const debounce = (fn, delay) => {
            let timeout;
            return (...args) => {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn(...args), delay);
            };
        };

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

        function setupEventListeners() {
            els.chatForm.addEventListener('submit', handleSubmit);

            const resizeInput = debounce(() => {
                els.promptInput.style.height = 'auto';
                els.promptInput.style.height = `${els.promptInput.scrollHeight}px`;
            }, 50);
            els.promptInput.addEventListener('input', resizeInput);

            els.suggestions.addEventListener('click', e => {
                const btn = e.target.closest('.suggestion-btn');
                if (btn) {
                    els.promptInput.value = btn.textContent;
                    els.promptInput.focus();
                    resizeInput();
                }
            });

            els.menuBtn.addEventListener('click', () => {
                els.sidebar.classList.toggle('open');
            });

            els.newChatBtn.addEventListener('click', createNewChat);

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

            document.addEventListener('click', e => {
                if (window.innerWidth <= 1024 && !els.sidebar.contains(e.target) && e.target !== els.menuBtn) {
                    els.sidebar.classList.remove('open');
                }
            });

            if (chats.length === 0) {
                createDefaultChat();
            }
        }

        function createDefaultChat() {
            currentChatId = Date.now().toString();
            chats = [{
                id: currentChatId,
                title: 'Welcome Chat',
                messages: [
                    {
                        content: "Hello! I'm your AI assistant. How can I help you today?",
                        isUser: false,
                        isError: false
                    }
                ],
                createdAt: new Date().toISOString()
            }];
            saveChats();
            renderChatHistory();
            renderMessages(chats[0].messages);
            els.suggestions.style.display = 'block';
        }

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

        function loadChat(chatId) {
            currentChatId = chatId;
            const chat = chats.find(c => c.id === chatId);
            if (chat) {
                renderMessages(chat.messages);
                if (window.innerWidth <= 1024) els.sidebar.classList.remove('open');
            }
        }

        function saveChats() {
            localStorage.setItem('chats', JSON.stringify(chats));
        }

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

        const escapeHtml = text => text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");

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

        const scrollToBottom = () => {
            els.chatBody.scrollTop = els.chatBody.scrollHeight;
        };

        const highlightCode = () => {
            hljs.highlightAll();
            const codeEl = document.getElementById("code");
            if (codeEl) {
                const lines = codeEl.innerText.trim().split('\n');
                const linesEl = document.getElementById("lines");
                if (linesEl) linesEl.innerHTML = lines.map((_, i) => i + 1).join('<br>');
            }
        };

        const simulateTyping = (element, text) => {
            element.innerHTML = formatMessage(text);
            scrollToBottom();
            highlightCode();
        };

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
        init();
    </script>
</body>

</html>
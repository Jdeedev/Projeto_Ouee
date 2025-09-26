<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Agente Ouee</title>
  <style>
    :root {
      --bg: #0d0d0d;
      --sidebar: #111;
      --panel: rgba(20, 20, 20, 0.85);
      --text: #e5e5e5;
      --muted: #9ca3af;
      --accent: #ffffffff;
      --border: #2a2a2a;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      background: var(--bg);
      color: var(--text);
      font-family: "Segoe UI", "Inter", sans-serif;
      height: 100vh;
      display: flex;
    }

    .layout {
      display: grid;
      grid-template-columns: 280px 1fr;
      width: 100%;
    }

    /* Sidebar */
    aside {
      background: var(--sidebar);
      border-right: 1px solid var(--border);
      padding: 16px;
      display: flex;
      flex-direction: column;
    }
    .btn {
      background: var(--accent);
      color: #000;
      border: none;
      border-radius: 6px;
      padding: 12px;
      font-weight: bold;
      cursor: pointer;
      margin-bottom: 20px;
      transition: 0.2s ease;
    }
    .btn:hover { filter: brightness(1.15); }

    .conversation-list {
      flex: 1;
      overflow-y: auto;
    }
    .conversation-item {
      display: block;
      padding: 12px;
      margin-bottom: 8px;
      border-radius: 6px;
      color: var(--muted);
      text-decoration: none;
      transition: background 0.2s, color 0.2s;
    }
    .conversation-item:hover { background: #1e1e1e; color: var(--text); }
    .conversation-item.active { background: #1f1f1f; color: var(--accent); }

    /* Main */
    .main {
      display: flex;
      flex-direction: column;
      height: 100vh;
    }
    header {
      padding: 18px 22px;
      border-bottom: 1px solid var(--border);
      font-size: 17px;
      font-weight: bold;
      backdrop-filter: blur(12px);
      background: var(--panel);
    }

    /* Chat area */
    .chat-area {
      flex: 1;
      overflow-y: auto;
      padding: 22px;
      background: var(--bg);
    }
    .message {
      max-width: 75%;
      margin-bottom: 18px;
      padding: 14px 18px;
      border-radius: 10px;
      font-size: 15px;
      line-height: 1.5;
      box-shadow: 0 2px 8px rgba(0,0,0,0.25);
      animation: fadeIn 0.25s ease;
    }
    .user {
      margin-left: auto;
      background: #262626;
      color: var(--text);
    }
    .assistant {
      margin-right: auto;
      background: #1a1a1a;
      border: 1px solid var(--border);
    }

    /* Input */
    .input-area {
      display: flex;
      gap: 12px;
      padding: 18px;
      border-top: 1px solid var(--border);
      backdrop-filter: blur(10px);
      background: var(--panel);
    }
    .message-input {
      flex: 1;
      background: #111;
      border: 1px solid #333;
      border-radius: 8px;
      padding: 14px;
      color: var(--text);
      resize: none;
      font-size: 15px;
    }
    .message-input:focus {
      border-color: var(--accent);
      outline: none;
    }
    .send-btn {
      background: var(--accent);
      border: none;
      border-radius: 8px;
      padding: 0 20px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.2s ease;
    }
    .send-btn:hover { filter: brightness(1.15); }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(5px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
<div class="layout">
  <!-- Sidebar -->
  <aside>
    <button class="btn" onclick="window.location.href='{{ route('new.conversation') }}'">+ Nova conversa</button>
    <div class="conversation-list">
      @foreach($conversations as $conv)
        <a href="{{ url('/c/'.$conv->id) }}" 
           class="conversation-item {{ $conversation->id ?? '' == $conv->id ? 'active' : '' }}">
          {{ $conv->title }}
        </a>
      @endforeach
    </div>
  </aside>

  <!-- Main -->
  <div class="main">
    <header>🔗Agente Ouee</header>
    <div class="chat-area">
      @isset($conversation)
        @foreach($conversation->messages as $m)
          <div class="message {{ $m->role }}">
            <strong>{{ $m->role === 'user' ? 'Você' : 'Agente Ouee' }}:</strong>
            {{ $m->content }}
          </div>
        @endforeach
      @else
        <div class="message assistant">👋 Bem-vindo ao Agente Ouee! Clique em "Nova conversa".</div>
      @endisset
    </div>
    <form class="input-area" method="post" action="{{ route('message.send', $conversation->id ?? 0) }}">
      @csrf
      <textarea name="content" class="message-input" placeholder="Digite sua mensagem..."></textarea>
      <button class="send-btn" type="submit">Enviar</button>
    </form>
  </div>
</div>
</body>
</html>

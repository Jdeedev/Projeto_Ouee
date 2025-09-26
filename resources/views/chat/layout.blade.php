<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Agente Ouee</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root{--bg:#0d0d0d;--sidebar:#111;--panel:rgba(20,20,20,.85);--text:#eaeaea;--muted:#9ca3af;--accent:#ff6a00;--border:#2a2a2a}
    *{margin:0;padding:0;box-sizing:border-box}
    body{background:var(--bg);color:var(--text);font-family:Inter,system-ui,Segoe UI,Roboto,Arial,sans-serif;height:100vh;display:flex}
    .layout{display:grid;grid-template-columns:280px 1fr;width:100%}
    aside{background:var(--sidebar);border-right:1px solid var(--border);padding:16px;display:flex;flex-direction:column}
    .btn{background:var(--accent);color:#000;border:none;border-radius:10px;padding:12px;font-weight:700;cursor:pointer;margin-bottom:14px}
    .btn:hover{filter:brightness(1.12)}
    .conversation-list{flex:1;overflow-y:auto}
    .conversation-item{display:block;padding:12px;margin-bottom:8px;border-radius:10px;color:var(--muted);text-decoration:none;border:1px solid #202020;background:#141414}
    .conversation-item:hover{background:#1a1a1a;color:var(--text)}
    .conversation-item.active{outline:1px solid var(--accent);color:var(--accent)}
    .main{display:flex;flex-direction:column;height:100vh}
    header{padding:16px 20px;border-bottom:1px solid var(--border);font-weight:700;background:var(--panel);backdrop-filter:blur(12px);display:flex;justify-content:space-between;align-items:center}
    .chat-area{flex:1;overflow-y:auto;padding:20px;background:var(--bg);display:flex;flex-direction:column;gap:12px}
    .message{max-width:75%;margin-bottom:6px;padding:14px 18px;border-radius:12px;font-size:15px;line-height:1.55;box-shadow:0 2px 8px rgba(0,0,0,.25);animation:fadeIn .25s ease}
    .user{margin-left:auto;background:#262626;color:var(--text)}
    .assistant{margin-right:auto;background:#1a1a1a;border:1px solid var(--border)}
    .input-area{display:flex;gap:12px;padding:16px;border-top:1px solid var(--border);background:var(--panel);backdrop-filter:blur(10px)}
    .message-input{flex:1;background:#111;border:1px solid #333;border-radius:10px;padding:14px;color:var(--text);resize:none;font-size:15px;min-height:44px;max-height:220px}
    .message-input:focus{border-color:var(--accent);outline:none}
    .send-btn{background:var(--accent);border:none;border-radius:10px;padding:0 20px;font-weight:700;cursor:pointer}
    .send-btn:hover{filter:brightness(1.12)}
    .code{position:relative;margin-top:10px;border:1px solid #2a2a2a;background:#0c0c0c;border-radius:12px;overflow:hidden}
    .code pre{margin:0;padding:12px;white-space:pre;overflow:auto}
    .copy{position:absolute;top:6px;right:6px;border:1px solid #2a2a2a;background:#151515;color:#dcdcdc;border-radius:8px;padding:6px 10px;cursor:pointer}
    @keyframes fadeIn{from{opacity:0;transform:translateY(5px)}to{opacity:1;transform:translateY(0)}}
  </style>
</head>
<body>
<div class="layout">
  <aside>
    <button class="btn" onclick="window.location.href='{{ route('new.conversation') }}'">+ Nova conversa</button>
    <div class="conversation-list">
      @foreach($conversations as $conv)
        <a href="{{ route('conversation.show',$conv->id) }}"
           class="conversation-item {{ ($conversation->id ?? null) == $conv->id ? 'active' : '' }}">
          {{ $conv->title }}
        </a>
      @endforeach
    </div>
  </aside>

  <div class="main">
    <header>
      <div>⚡ Agente Ouee</div>
      <div style="opacity:.9;font-weight:600">Clone estilo Blackbox</div>
    </header>

    <div class="chat-area">
      @yield('content')
    </div>

    <form class="input-area" method="post" action="{{ isset($conversation) ? route('message.send',$conversation->id) : '#' }}">
      @csrf
      <textarea name="content" class="message-input" placeholder="Digite sua mensagem…" onkeydown="return handleKey(event)"></textarea>
      <button class="send-btn" type="submit">Enviar</button>
    </form>
  </div>
</div>

<script>
function handleKey(e){
  if(e.key==='Enter' && !e.shiftKey){
    e.preventDefault();
    e.target.form.submit();
    return false;
  }
  // auto-resize
  setTimeout(()=>{
    e.target.style.height='auto';
    e.target.style.height=Math.min(220,e.target.scrollHeight)+'px';
  },0);
}
document.addEventListener('click', (ev)=>{
  if(ev.target.matches('.copy')){
    const pre = ev.target.nextElementSibling;
    navigator.clipboard.writeText(pre?.innerText||'');
    ev.target.textContent='Copiado!'; setTimeout(()=>ev.target.textContent='Copiar',1200);
  }
});
</script>
</body>
</html>

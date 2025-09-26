// Demo-only state (in-memory)
const convList = document.getElementById('convList');
const chat = document.getElementById('chatArea');
const prompt = document.getElementById('prompt');
const sendBtn = document.getElementById('send');
const newConv = document.getElementById('newConv');
const settingsBtn = document.getElementById('settingsBtn');
const loginBtn = document.getElementById('loginBtn');
const settingsModal = document.getElementById('settingsModal');
const loginModal = document.getElementById('loginModal');
const themeSel = document.getElementById('theme');

let state = {
  theme: localStorage.getItem('agente.theme') || 'dark',
  conversations: [],
  activeId: null,
};

document.documentElement.setAttribute('data-theme', state.theme);
themeSel && (themeSel.value = state.theme);
themeSel?.addEventListener('change', e => {
  document.documentElement.setAttribute('data-theme', e.target.value);
  localStorage.setItem('agente.theme', e.target.value);
});

function uid(){ return Math.random().toString(36).slice(2,10); }

function renderConvs(){
  convList.innerHTML = '';
  state.conversations.forEach(c => {
    const el = document.createElement('div');
    el.className = 'conv';
    el.textContent = c.title || 'Nova conversa';
    el.onclick = () => { state.activeId = c.id; renderChat(); highlight(el); };
    convList.appendChild(el);
  });
}
function highlight(el){
  [...convList.children].forEach(x => x.style.outline = '');
  el.style.outline = '1px solid var(--accent)';
}
function activeConv(){
  return state.conversations.find(c => c.id === state.activeId);
}
function renderChat(){
  chat.innerHTML = '';
  const c = activeConv();
  if(!c){ return; }
  c.messages.forEach(m => addMessage(m.role, m.content, false));
  chat.scrollTop = chat.scrollHeight;
}
function addMessage(role, content, push=true){
  const wrp = document.createElement('div');
  wrp.className = 'message ' + role;
  const bubble = document.createElement('div');
  bubble.className = 'bubble';
  bubble.innerHTML = content;
  wrp.appendChild(bubble);

  // Code block detection
  if(content.includes('```')){
    const pre = document.createElement('pre');
    pre.textContent = content.replace(/```[\s\S]*?```/g, m => m.replace(/```/g,''));
    const codeBox = document.createElement('div');
    codeBox.className = 'code';
    const copy = document.createElement('button');
    copy.className = 'copy'; copy.textContent = 'Copiar';
    copy.onclick = () => navigator.clipboard.writeText(pre.textContent);
    codeBox.appendChild(copy); codeBox.appendChild(pre);
    bubble.appendChild(codeBox);
  }

  chat.appendChild(wrp);
  chat.scrollTop = chat.scrollHeight;

  if(push){
    const c = activeConv();
    c.messages.push({role, content});
  }
}

newConv.onclick = () => {
  const id = uid();
  state.conversations.unshift({id, title:'Nova conversa', messages:[]});
  state.activeId = id;
  renderConvs(); renderChat();
};

sendBtn.onclick = () => send();
prompt.addEventListener('keydown', (e) => {
  if(e.key === 'Enter' && !e.shiftKey){
    e.preventDefault();
    send();
  }
  // autoresize
  setTimeout(() => {
    prompt.style.height = 'auto';
    prompt.style.height = Math.min(220, prompt.scrollHeight) + 'px';
  }, 0);
});

function fakeAssistantReply(text){
  // Simple "assistant" typing effect (demo)
  const content = '🤖 <b>Agente Ouee</b>: ' + text.split('').reverse().join('');
  addMessage('assistant', content, true);
}
function send(){
  const val = prompt.value.trim();
  if(!val){ return; }
  addMessage('user', val, true);
  prompt.value=''; prompt.style.height='44px';
  // fake stream
  setTimeout(() => fakeAssistantReply(val), 250);
}

// Modals
function openModal(m){ m.hidden = false; }
function closeModal(m){ m.hidden = true; }
settingsBtn.onclick = () => openModal(settingsModal);
loginBtn.onclick = () => openModal(loginModal);
document.querySelectorAll('[data-close]').forEach(btn => btn.onclick = (e)=> closeModal(e.target.closest('.modal')));
[settingsModal, loginModal].forEach(m => m.addEventListener('click', e => { if(e.target===m) closeModal(m); }));

// Seed
newConv.click();
addMessage('assistant', 'Diga olá 👋 e eu respondo (demo sem servidor).', true);

@extends('chat.layout')

@section('content')
  @isset($conversation)
    @foreach($conversation->messages as $m)
      <div class="message {{ $m->role }}">
        <div><strong>{{ $m->role === 'user' ? 'Você' : 'Agente Ouee' }}:</strong></div>
        <div style="margin-top:6px">{!! nl2br(e($m->content)) !!}</div>
      </div>
    @endforeach
  @else
    <div class="message assistant">👋 Bem-vindo ao Agente Ouee! Clique em <b>Nova conversa</b> para começar.</div>
    <div class="message assistant">
      <div><b>Dica:</b> cole um código que eu formatto e adiciono botão <i>Copiar</i>.</div>
      <div class="code">
        <button class="copy">Copiar</button>
        <pre>console.log("Olá do Agente Ouee!");</pre>
      </div>
    </div>
  @endisset
@endsection

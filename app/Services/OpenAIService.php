<?php

namespace App\Services;

class OpenAIService
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY', '');
        $this->model = env('AGENTE_OUEE_MODEL', 'gpt-4.1');
    }

    public function chat(array $history): string
    {
        if (!$this->apiKey) {
            return 'Configure sua OPENAI_API_KEY no .env';
        }

        $messages = array_map(function($m){
            return [
                'role' => $m['role'],
                'content' => $m['content'],
            ];
        }, $history);

        $payload = json_encode([
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => 0.7,
        ]);

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey,
            ],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 60,
        ]);

        $res = curl_exec($ch);
        if ($res === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new \Exception("cURL error: $err");
        }
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($res, true);
        if ($code >= 400) {
            $msg = $data['error']['message'] ?? 'Erro desconhecido';
            throw new \Exception("API error ($code): $msg");
        }

        return $data['choices'][0]['message']['content'] ?? '[sem resposta]';
    }
}

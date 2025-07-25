<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZenzivaService
{
    protected $userKey;
    protected $apiKey;
    protected $url;

    public function __construct()
    {
        $this->userKey = env('ZENZIVA_USER_KEY');
        $this->apiKey = env('ZENZIVA_API_KEY');
        $this->url = 'https://console.zenziva.net/wareguler/api/sendWA/';
    }

    public function send($phone, $message)
    {
        $response = Http::asForm()->post($this->url, [
            'userkey' => $this->userKey,
            'passkey' => $this->apiKey,
            'to'      => $phone,
            'message' => $message,
        ]);

        return $response->json();
    }
}

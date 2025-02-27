<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    protected $apiUrl;
    protected $username;
    protected $password;
    protected $from;

    public function __construct()
    {
        $this->apiUrl = 'http://api.messaging-service.com';
        $this->username = env('SMS_API_USER', 'user');
        $this->password = env('SMS_API_PASSWORD', 'password');
        $this->from = 'COOPSERPROG';
    }

    public function sendSimpleSms($to, $text)
    {
        $authHeader = "Basic " . base64_encode("$this->username:$this->password");

        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->apiUrl, [
            'from' => $this->from,
            'to' => is_array($to) ? $to : [$to],
            'text' => $text,
        ]);

        return $response->json();
    }
}

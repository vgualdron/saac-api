<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController {
    use AuthorizesRequests, ValidatesRequests;

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
        ])->post($this->apiUrl . '/sms/1/text/single', [
            'from' => $this->from,
            'to' => is_array($to) ? $to : [$to],
            'text' => $text,
        ]);

        return $response->json();
    }

    public function logAction($action, $model = null, $modelId = null) {
        // Registrar el log en la base de datos
        Log::create([
            'user_id' => Auth::id(), // Si el usuario está autenticado
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController {
    use AuthorizesRequests, ValidatesRequests;

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

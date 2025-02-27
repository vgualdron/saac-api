<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Implementations\CategoryServiceImplement;
use App\Services\SmsService;

class CategoryController extends Controller
{
    private $service;
    private $request;
    protected $smsService;

    public function __construct(Request $request, CategoryServiceImplement $service, SmsService $smsService) {
        $this->request = $request;
        $this->service = $service;
        $this->smsService = $smsService;
    }

    function list() {
        $response = $this->smsService->sendSimpleSms(['3043427319', '3104653638'], 'Hola es una prueba.');
        return response()->json($response);
        // return $this->service->list();
    }
}

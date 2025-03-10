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
        $response = $this->smsService->sendSimpleSms(['573043427319', '573104653638', '573046607622'], 'Hola es una prueba de COOPSERPROG <3.');
        // return response()->json($response);
        return $this->service->list();
    }
}

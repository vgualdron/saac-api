<?php

namespace App\Http\Controllers;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Implementations\CategoryServiceImplement;

class CategoryController extends Controller
{
    private $service;
    private $request;

    public function __construct(Request $request, CategoryServiceImplement $service) {
        $this->request = $request;
        $this->service = $service;
    }

    function list() {
        // $this->sendSimpleSms(['573043427319'], 'Alguna persona está revisando los comercios.');
        $this->logAction('list', 'category', null);
        return $this->service->list();
    }
}

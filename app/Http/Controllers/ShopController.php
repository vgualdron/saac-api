<?php

namespace App\Http\Controllers;
use App\Models\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Implementations\ShopServiceImplement;

class ShopController extends Controller
{
    private $service;
    private $request;

    public function __construct(Request $request, ShopServiceImplement $service) {
        $this->request = $request;
        $this->service = $service;
    }

    function list(){
        return $this->service->list();
    }
    function listByStatus(string $status){
        return $this->service->listByStatus($status);
    }

    function create(){
        $data = $this->request->all();
        $userSesion = $this->request->user();
        $idUserSesion = $userSesion->id;
        $data["registered_by"] = $idUserSesion;
        return $this->service->create($data);
    }

    function update(int $id){
        return $this->service->update($this->request->all(), $id);
    }

    function delete(int $id){
        return $this->service->delete($id);
    }
}

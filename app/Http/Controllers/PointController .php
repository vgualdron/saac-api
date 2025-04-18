<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Implementations\CreditServiceImplement;

class CreditController extends Controller
{
    private $service;
    private $request;

    public function __construct(Request $request, CreditServiceImplement $service) {
            $this->request = $request;
            $this->service = $service;
    }

    function list(string $status){
        return $this->service->list($status);
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

    function get(int $id){
        return $this->service->get($id);
    }
}

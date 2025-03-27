<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Implementations\UserServiceImplement;

class UserController extends Controller
{
    private $service;
    private $request;

    public function __construct(Request $request, UserServiceImplement $service) {
            $this->request = $request;
            $this->service = $service;
    }

    function list(int $displayAll){
        return $this->service->list($displayAll);
    }

    function listByRoleName(int $displayAll, string $name, int $city){
        return $this->service->listByRoleName($displayAll, $name, $city);
    }

    function listByArea(int $area){
        return $this->service->listByArea($area);
    }

    function create(){
        return $this->service->create($this->request->all());
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

    function updateProfile(int $id){
        return $this->service->updateProfile($this->request->all(), $id);
    }

    function updatePushToken() {
        $user = $this->request->all();
        $userSesion = $this->request->user();
        $idUserSesion = $userSesion->id;
        return $this->service->updatePushToken($user['pushToken'], $idUserSesion);
    }

    function updateLocation() {
        $user = $this->request->all();
        $userSesion = $this->request->user();
        $idUserSesion = $userSesion->id;
        return $this->service->updateLocation($user, $idUserSesion);
    }

    function completeData(int $id) {

        try {
            $item = User::find($id)->update($this->request->all());
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $item,
            'message' => 'Succeed'
        ], Response::HTTP_OK);
    }
}

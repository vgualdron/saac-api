<?php

namespace App\Http\Controllers;
use App\Models\Novel;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Implementations\NovelServiceImplement;
use Illuminate\Support\Facades\DB;

class NovelController extends Controller
{
    private $service;
    private $request;

    public function __construct(Request $request, NovelServiceImplement $service) {
        $this->request = $request;
        $this->service = $service;
    }

    function list(string $status){
        return $this->service->list($status);
    }

    function create(){
        return $this->service->create($this->request->all());
    }

    function update(int $id){
        return $this->service->update($this->request->all(), $id);
    }

    function completeData(int $id) {

        try {
            $item = Novel::find($id)->update($this->request->all());
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $item,
            'message' => 'Succeed'
        ], JsonResponse::HTTP_OK);
    }

    function delete(int $id){
        return $this->service->delete($id);
    }

    function get(int $id){
        return $this->service->get($id);
    }

    function getByPhone(string $phone){
        return $this->service->getByPhone($phone);
    }

    function getNewsMigrate() {
        try {
            DB::beginTransaction(); // Inicia la transacción

            $associates = DB::table('saacccgq_dbsaac.associates')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('saacccgq_mobile_database.users')
                        ->whereColumn('saacccgq_mobile_database.users.document_number', 'saacccgq_dbsaac.associates.id_number');
                })
                ->get();

            $createdUsers = [];

            foreach ($associates as $associate) {
                $fullName = trim("{$associate->nombre} {$associate->primer_apellido} {$associate->segundo_apellido}");

                $newUser = [
                    'type_document'   => $associate->tipo_documento,  // Tipo de documento
                    'document_number' => $associate->cedula,          // Número de documento
                    'name'            => $fullName,                   // Nombre completo
                    'phone'           => $associate->celular,         // Celular
                    'password'        => Hash::make($associate->cedula), // Hash de la cédula
                    'completedFields' => true                         // Campo completado
                ];

                // Insertar usuario en la BD
                $userId = DB::table('saacccgq_mobile_database.users')->insertGetId($newUser);
                $createdUsers[] = $userId;
            }

            DB::commit(); // Confirma la transacción

            return response()->json([
                'data' => $createdUsers,
                'message' => 'Users successfully created'
            ], JsonResponse::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack(); // Revierte la transacción si hay error

            return response()->json([
                'data' => [],
                'message' => 'Error: ' . $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

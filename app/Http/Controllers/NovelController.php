<?php

namespace App\Http\Controllers;
use App\Models\Novel;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\Implementations\NovelServiceImplement;

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
            $items = DB::table('saacccgq_dbsaac.asociados')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('saacccgq_mobile_database.users')
                        ->whereColumn('saacccgq_mobile_database.users.document_number', 'saacccgq_dbsaac.asociados.cedula');
                })
            ->get();

            return response()->json([
                'data' => $items,
                'message' => 'Succeed'
            ], JsonResponse::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

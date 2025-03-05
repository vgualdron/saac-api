<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\ShopServiceInterface;
    use Symfony\Component\HttpFoundation\Response;
    use App\Models\Shop;
    use App\Validator\{ProfileValidator};
    use App\Traits\Commons;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\DB;

    class ShopServiceImplement implements ShopServiceInterface {

        use Commons;

        private $shop;
        private $profileValidator;

        function __construct(ProfileValidator $profileValidator){
            $this->shop = new Shop;
            $this->profileValidator = $profileValidator;
        }

        function list() {
            try {
                $sql = $this->shop->from('shops as s')
                    ->select(
                        's.*',
                        'c.name as category_name',
                        'fa.url as url_logo',
                    )
                    ->join('categories as c', 'c.id', 's.category_id')
                    ->leftJoin('files as fa', function($join) {
                        $join->where('fa.model_name', '=', 'shops')
                             ->on('fa.model_id', '=', 's.id')
                             ->where('fa.name', '=', 'LOGO_SHOP');
                    })
                    ->orderBy('s.order', 'ASC')
                    ->get();

                if (count($sql) > 0){
                    return response()->json([
                        'data' => $sql
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'data' => []
                    ], Response::HTTP_OK);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error al cargar los registros',
                            'detail' => $e->getMessage()
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function listByStatus(string $status) {
            try {
                $sql = $this->shop->from('shops as s')
                    ->select(
                        's.*',
                        'c.name as category_name',
                        'fa.url as url_logo',
                    )
                    ->join('categories as c', 'c.id', 's.category_id')
                    ->leftJoin('files as fa', function($join) {
                        $join->where('fa.model_name', '=', 'shops')
                             ->on('fa.model_id', '=', 's.id')
                             ->where('fa.name', '=', 'LOGO_SHOP');
                    })
                    ->where('s.status', $status)
                    ->orderBy('s.order', 'ASC')
                    ->get();

                if (count($sql) > 0){
                    return response()->json([
                        'data' => $sql
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'data' => []
                    ], Response::HTTP_OK);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error al cargar los registros',
                            'detail' => $e->getMessage()
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }


        function create(array $shop){ // NOT WORK;
            try {
                DB::transaction(function () use ($point) {
                    $sql = $this->point::create($point);

                    $idUserSesion = $point["registered_by"];
                    $name = 'FOTO_PUNTOS';
                    $modelName = 'points';
                    $modelId = $sql->id;
                    $type = 'image';
                    $file = base64_decode($point["photo"]);
                    $extension = 'jpg';
                    $storage = 'points';
                    $state = 'aprobado';
                    $latitude = null;
                    $longitude = null;
                    $item = null;

                    // Crear un nombre aleatorio para la imagen
                    $time = strtotime("now");
                    $nameComplete = $name."-".$time.".".$extension;
                    $path = "$modelId/$nameComplete";
                    $url = "/storage/app/public/$storage/$path";

                    Storage::disk($storage)->makeDirectory($modelId);
                    $status = Storage::disk($storage)->put($path, $file);

                    $item = File::create([
                        'name' => $name,
                        'model_name' => $modelName,
                        'model_id' => $modelId,
                        'type' => $type,
                        'extension' => $extension,
                        'url' => $url,
                        'registered_by' => $idUserSesion,
                        'registered_date' => date('Y-m-d H:i:s'),
                        'status' => $state,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                    ]);

                });
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Registrado con éxito',
                            'detail' => null
                        ]
                    ]
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                $d = $e->getMessage();
                if (strpos($e->getMessage(), 'SQLSTATE[23000]') !== false) {
                    $d = 'Ya se encuentra registrado ese número de factura para ese mismo convenio, revisa la información por favor.';
                }
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al registrar',
                            'detail' => $d,
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function update(array $shop, int $id){
            try {
                $sql = $this->shop::find($id)->update($shop);

                return response()->json([
                    'message' => [
                        [
                            'text' => 'Actualizado con éxito',
                            'detail' => null
                        ]
                    ]
                ], Response::HTTP_OK);

            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al actualizar el usuario',
                            'detail' => $e->getMessage(),
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function delete(int $id){
            try {
                $sql = $this->shop::find($id);
                if(!empty($sql)) {
                    $sql->delete();
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Registro eliminado con éxito',
                                'detail' => null
                            ]
                        ]
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al eliminar',
                                'detail' => 'El registro no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al eliminar el registro',
                            'detail' => $e->getMessage()
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
?>

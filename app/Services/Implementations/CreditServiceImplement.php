<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\CreditServiceInterface;
    use Symfony\Component\HttpFoundation\Response;
    use App\Models\Credit;
    use App\Models\File;
    use App\Validator\{UserValidator, ProfileValidator};
    use App\Traits\Commons;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Storage;

    class CreditServiceImplement implements CreditServiceInterface {

        use Commons;

        private $credit;
        private $validator;
        private $profileValidator;

        function __construct(ProfileValidator $profileValidator){
            $this->credit = new Credit;
            $this->profileValidator = $profileValidator;
        }

        function list(string $status) {
            try {
                $explodeStatus = explode(',', $status);
                $sql = $this->credit->from('credits as c')
                            ->select(
                                'c.*',
                            )
                            ->when($status !== 'all', function ($q) use ($explodeStatus) {
                                return $q->whereIn('p.status', $explodeStatus);
                            })
                            ->get();

                return response()->json([
                    'data' => $sql
                ], Response::HTTP_OK);

            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error al cargar los usuarios',
                            'detail' => $e->getMessage()
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function create(array $point){
            try {
                $sql = $this->point::create($point);
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

        function update(array $point, int $id){
            try {
                $sql = $this->point::find($id)->update($point);

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
                $sql = $this->point::find($id);
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

        function get(int $id){
            try {
                $sql = $this->point::select(
                    'amount',
                    'status',
                    'observation',
                    'description',
                    'user_id',
                )
                ->where('id', $id)
                ->first();

                return response()->json([
                    'data' => $sql
                ], Response::HTTP_OK);

            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error al buscar el usuario',
                            'detail' => $e->getMessage(),
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
?>

<?php
    namespace App\Services\Implementations;
    use App\Services\Interfaces\NovelServiceInterface;
    use Symfony\Component\HttpFoundation\Response;
    use App\Models\Novel;
    use App\Models\User;
    use App\Models\Asociado;
    use App\Validator\{NovelValidator, ProfileValidator};
    use App\Traits\Commons;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\DB;

    class NovelServiceImplement implements NovelServiceInterface {

        use Commons;

        private $novel;
        private $validator;
        private $profileValidator;

        function __construct(NovelValidator $validator, ProfileValidator $profileValidator){
            $this->novel = new Novel;
            $this->validator = $validator;
            $this->profileValidator = $profileValidator;
        }

        function list(string $status) {
            try {
                $explodeStatus = explode(',', $status);
                $sql = $this->novel->from('news as n')
                    ->select(
                        'n.*',
                        'u.id as user_id',
                        'u.completedFields as user_completed_fields',
                        'u.payment_date as user_payment_date',
                    )
                    ->leftJoin('users as u', 'u.id', 'n.user_id')
                    ->when($status !== 'all', function ($q) use ($explodeStatus) {
                        return $q->whereIn('n.status', $explodeStatus);
                    })
                    ->orderBy('n.name', 'ASC')
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

        function create(array $novel) {
            try {
                $newNovel = null;
                $validation = $this->validate($this->validator, $novel, null, 'registrar', 'asociado', null);
                if ($validation['success'] === false) {
                    return response()->json([
                        'message' => $validation['message']
                    ], Response::HTTP_BAD_REQUEST);
                }

                if (User::where('document_number', $novel['document_number'])->exists()) {
                    return response()->json([
                        'message' => [
                            ['text' => 'El número de documento ya está registrado como cuenta de asociado']
                        ]
                    ], Response::HTTP_BAD_REQUEST);
                }

                DB::transaction(function () use ($novel) {
                    unset($novel['department_house']);
                    unset($novel['department_id']);
                    unset($novel['department_issue']);

                    $newUser = User::create([
                        'type_document' => $novel['type_document'],
                        'document_number' => $novel['document_number'],
                        'name' => $novel['name'] . ' ' . $novel['first_lastname'] . ' ' . $novel['second_lastname'],
                        'phone' => $novel['phone'],
                        'active' => $novel['active'],
                        'password' => empty($novel['password']) ? Hash::make($novel['document_number']) : Hash::make($novel['password'])
                    ]);

                    $newUser->assignRole(['Asociado']);

                    $novel["user_id"] = $newUser->id;
                    $newNovel = $this->novel::create($novel);

                });
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Registrado con exito',
                            'detail' => $newNovel
                        ]
                    ]
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al registrar',
                            'detail' => $e->getMessage()
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function update(array $novel, int $id){
            try {
                $sql = $this->novel::find($id);
                if(!empty($sql)) {
                    DB::transaction(function () use ($sql, $novel) {
                        $sql->document_number = $novel['documentNumber'];
                        $sql->name = $novel['name'];
                        $sql->phone = $novel['phone'];
                        $sql->address = $novel['address'];
                        $sql->sector = $novel['sector'];
                        $sql->status = $novel['status'];
                        $sql->district = $novel['district'];
                        $sql->occupation = $novel['occupation'];
                        $sql->observation = $novel['observation'];
                        $sql->user_send = $novel['userSend'] ? $novel['userSend'] : null;
                        $sql->save();
                    });
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Actualizado con éxito',
                                'detail' => null
                            ]
                        ]
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al actualizar',
                                'detail' => 'El registro no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Advertencia al actualizar',
                            'detail' => $e->getMessage()
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function delete(int $id){
            try {
                $sql = $this->novel::find($id);
                if(!empty($sql)) {
                    $userId = $sql->user_id;
                    $sql->delete();

                    if ($userId) {
                        $user = User::find($userId);
                        if ($user) {
                            $user->delete();
                        }
                    }

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
                                'text' => 'Advertencia al eliminar el registro',
                                'detail' => 'El registro no existe'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                if ($e->getCode() !== "23000") {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'Advertencia al eliminar el registro',
                                'detail' => $e->getMessage()
                            ]
                        ]
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'No se permite eliminar',
                                'detail' => $e->getMessage()
                            ]
                        ]
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        }

        function get(int $id){
            try {
                $sql = $this->novel->from('news as n')
                    ->select(
                        'n.id',
                        'n.document_number as documentNumber',
                        'n.name as name',
                        'n.phone as phone',
                        'n.address as address',
                        'n.address_house',
                        'n.address_house_district',
                        'dh.name as districtHouseName',
                        'n.address_work',
                        'n.address_work_district',
                        'dw.name as districtWorkName',
                        'n.site_visit',
                        'n.type_house',
                        'n.type_work',
                        'n.quantity',
                        'n.district as district',
                        'n.occupation as occupation',
                        'n.attempts as attempts',
                        'n.observation as observation',
                        'n.status as status',
                        'n.created_at as date',
                        'n.visit_start_date',
                        DB::Raw('IF(y.zone IS NOT NULL, z.name, "Sin ciudad") as cityName'),
                        DB::Raw('IF(y.zone IS NOT NULL, z.id, null) as city'),
                        DB::Raw('IF(n.sector IS NOT NULL, y.name, "Sin sector") as sectorName'),
                        DB::Raw('IF(n.sector IS NOT NULL, y.id, null) as sector'),
                        DB::Raw('IF(n.user_send IS NOT NULL, u.name, "Ninguno") as userSendName'),
                        DB::Raw('IF(n.user_send IS NOT NULL, u.id, null) as userSend'),
                        DB::Raw('IF(us.id IS NOT NULL, us.id, null) as userVisit'),
                        DB::Raw('IF(us.id IS NOT NULL, us.push_token, null) as userVisitToken'),
                        'n.family_reference_district',
                        'drf.name as family_reference_district_name',
                        'n.family_reference_name',
                        'n.family_reference_address',
                        'n.family_reference_phone',
                        'n.family_reference_relationship',
                        'n.family2_reference_district',
                        'drf2.name as family2_reference_district_name',
                        'n.family2_reference_name',
                        'n.family2_reference_address',
                        'n.family2_reference_phone',
                        'n.family2_reference_relationship',
                        'n.guarantor_district',
                        'dg.name as guarantor_district_name',
                        'n.guarantor_document_number',
                        'n.guarantor_name',
                        'n.guarantor_address',
                        'n.guarantor_phone',
                        'n.guarantor_occupation',
                        'n.guarantor_relationship',
                        'n.extra_reference',
                        'n.period',
                        'n.quantity',
                        'd.id as diary_id',
                        'd.status as diary_status',
                        'y.id as sector',
                        'z.id as city',
                        'yh.id as sectorHouse',
                        'zh.id as cityHouse',
                        'yw.id as sectorWork',
                        'zw.id as cityWork',
                        'yrf.id as sectorRef1',
                        'zrf.id as cityRef1',
                        'yrf2.id as sectorRef2',
                        'zrf2.id as cityRef2',
                        'yg.id as sectorGuarantor',
                        'zg.id as cityGuarantor',
                        'n.visit_end_date',
                        'n.account_type',
                        'n.account_number',
                        'n.account_type_third',
                        'n.account_number_third',
                        'n.account_name_third',
                        'n.type_cv',
                        'n.lent_by',
                        'ul.name as lent_by_name',
                        'ua.name as approved_by_name',
                        'n.has_letter',
                        'n.who_received_letter',
                        'n.date_received_letter',
                        'n.who_returned_letter',
                        'n.date_returned_letter',
                        'n.score',
                        'n.score_observation',
                        'n.account_active',
                        'li.name as list_name',
                        'li.id as list_id',
                    )
                    ->leftJoin('yards as y', 'n.sector', 'y.id')
                    ->leftJoin('zones as z', 'y.zone', 'z.id')
                    ->leftJoin('users as u', 'n.user_send', 'u.id')
                    ->leftJoin('diaries as d', 'd.new_id', 'n.id')
                    ->leftJoin('users as us', 'us.id', 'd.user_id')
                    ->leftJoin('districts as dh', 'n.address_house_district', 'dh.id')
                    ->leftJoin('yards as yh', 'dh.sector', 'yh.id')
                    ->leftJoin('zones as zh', 'yh.zone', 'zh.id')
                    ->leftJoin('districts as dw', 'n.address_work_district', 'dw.id')
                    ->leftJoin('yards as yw', 'dw.sector', 'yw.id')
                    ->leftJoin('zones as zw', 'yw.zone', 'zw.id')
                    ->leftJoin('districts as drf', 'n.family_reference_district', 'drf.id')
                    ->leftJoin('yards as yrf', 'drf.sector', 'yrf.id')
                    ->leftJoin('zones as zrf', 'yrf.zone', 'zrf.id')
                    ->leftJoin('districts as drf2', 'n.family2_reference_district', 'drf2.id')
                    ->leftJoin('yards as yrf2', 'drf2.sector', 'yrf2.id')
                    ->leftJoin('zones as zrf2', 'yrf2.zone', 'zrf2.id')
                    ->leftJoin('districts as dg', 'n.guarantor_district', 'dg.id')
                    ->leftJoin('yards as yg', 'dg.sector', 'yg.id')
                    ->leftJoin('zones as zg', 'yg.zone', 'zg.id')
                    ->leftJoin('lendings as l', 'l.new_id', 'n.id')
                    ->leftJoin('listings as li', 'li.id', 'l.listing_id')
                    ->leftJoin('users as ul', 'n.lent_by', 'ul.id')
                    ->leftJoin('users as ua', 'n.approved_by', 'ua.id')
                    ->where('n.id', $id)
                    ->first();
                if(!empty($sql)) {
                    return response()->json([
                        'data' => $sql
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        'message' => [
                            [
                                'text' => 'El registro no existe',
                                'detail' => 'por favor recargue la página'
                            ]
                        ]
                    ], Response::HTTP_NOT_FOUND);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error al buscar',
                            'detail' => $e->getMessage()
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function getByPhone(string $phone){
            try {
                $new = $this->novel->from('news as n')->select('n.*')->where('n.phone', $phone)->first();
                return response()->json([
                    'data' => $new
                ], Response::HTTP_OK);

            } catch (\Throwable $e) {
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Se ha presentado un error al buscar',
                            'detail' => $e->getMessage()
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        function completeDataSaac(Request $request) {
            try {
                // Excluir datos no necesarios para la tabla principal
                // $datosAsociado = $request->except(['economicas', 'activos', 'conocimientos', 'referencias', 'aportes']);
                $datosAsociado = $request->asociado;

                // Iniciar transacción
                DB::beginTransaction();

                // Crear el Asociado
                $asociado = Asociado::create($datosAsociado);

                // Guardar relaciones si existen en la solicitud
                $relaciones = ['economicas', 'activos', 'conocimientos', 'referencias'];

                foreach ($relaciones as $relacion) {
                    if ($request->has($relacion) && !empty($request->$relacion)) {
                        $asociado->$relacion()->create($request->$relacion);
                    }
                }

                // Guardar los aportes
                if ($request->aportes) {
                    foreach ($request->aportes as $aporte) {
                        if (!empty($aporte['valor_aporte']) && $aporte['valor_aporte'] > 0) {
                            AsociadoAporte::create([
                                'asociado_id' => $asociado->id,
                                'lineaaporte_id' => $aporte['linea_aporte_id'],
                                'valor_aporte' => $aporte['valor_aporte'],
                            ]);
                        }
                    }
                }

                // Confirmar la transacción
                DB::commit();

                return response()->json([
                    'message' => [
                        [
                            'text' => 'Registrado con exito',
                            'detail' => $novel
                        ]
                    ]
                ], Response::HTTP_OK);
            } catch (\Throwable $e) {
                DB::rollBack();
                return response()->json([
                    'message' => [
                        [
                            'text' => 'Error al registrar',
                            'detail' => $e->getMessage()
                        ]
                    ]
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

    }
?>

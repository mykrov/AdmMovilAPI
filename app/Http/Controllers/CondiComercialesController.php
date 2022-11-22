<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CondiComercialesController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/condicomer",
    *     tags={"Datos"},
    *     summary="Listado de Condiciones Comerciales",
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMCONDICOMER."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetCondiComer(){
        $fecha = Carbon::now();
        $condiciones = \App\ADMCONDICOMER::get();

        return response()->json($condiciones);
    }

    /**
    * @OA\Get(
    *     path="/api/condicomerxi/{codigo}",
    *     tags={"Datos"},
    *     summary="Listado de Condiciones Comerciales de un Item",
    * @OA\Parameter(
    *          name="codigo",
    *          description="Codigo del Item",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMCONDICOMER segun el codigo del item dado."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetCondiComerPorProducto($item){
        $fecha = Carbon::now();
        $condicionesi = \App\ADMCONDICOMER::where('ITEM','=',$item)->get();
        //->where('FECDESDE','>=','01-01-'.$fecha->Format('Y'))
        //->where('FECHASTA','>=',$fecha->Format('d-m-Y'))
        

        return response()->json($condicionesi);
    }
}

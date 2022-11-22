<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\ADMDEUDACUOTADET;
use Illuminate\Support\Facades\DB;

class DeudasCoutasDetController extends Controller
{

    /**
    * @OA\Get(
    *     path="/api/deudacuotasdet/{pago}",
    *     tags={"Deudas"},
    *     summary="Retorna registros de ADMDEUDACUOTADET afectado por el pago indicado.",
    * @OA\Parameter(
    *          name="pago",
    *          description="pago",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="number")),
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMDEUDACUOTADET con pago indicado."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function CuotasAfectadas($pago)
    {
        $cuotas = ADMDEUDACUOTADET::where('NUMPAGO',$pago)->get();

        return response()->json($cuotas);
    }

     /**
    * @OA\Get(
    *     path="/api/detguiacob/{guia}/{secuencial}",
    *     tags={"Guia Cobro"},
    *     summary="Retorna el 'detalle' de la Guia de Cobro indicada dependiendo el secuencial",
    * @OA\Parameter(
    *          name="guia",
    *          description="guia",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="number")),
    * @OA\Parameter(
    *          name="secuencial",
    *          description="secuencial",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="number")),
    *     @OA\Response(
    *         response=200,
    *         description="Retorna el registro de ADMDETGUIACOB con el SECUENCIAL indicado perteneciente al numero de guia."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function ItemDetGuia($guia,$secuencial)
    {
        $detalle = \App\ADMDETGUIACOB::where([['NUMGUIA','=',$guia],['SECUENCIAL','=',$secuencial]])->get();
        return response()->json($detalle);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeudaCuotasController extends Controller
{
     /**
    * @OA\Get(
    *     path="/api/deudacuotas/{secuencial}",
    *     tags={"Deudas"},
    *     summary="Retorna registros la DEUDA y CUOTAS del secuencial dado.",
    * @OA\Parameter(
    *          name="secuencial",
    *          description="secuencial",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="number")),
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros: cabecera => ADMDEUDA, detalles => ADMDEUDACUOTA donde el saldo > 0."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function GetDeudaCuotas(int $sec)
    {
        $cabecera = \App\ADMDEUDA::where('SECUENCIAL','=',$sec)->get();

        $detalles= \App\ADMDEUDACUOTA::where('SECDEUDA','=',$sec)
        ->where('SALDO','>',0.001)
        ->get();

        return response()->json(["deuda"=>$cabecera,'cuotas'=>$detalles]);
    }
}

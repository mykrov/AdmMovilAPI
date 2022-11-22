<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreditoController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/credito/{numero}",
    *     tags={"Credito"},
    *     summary="Información de un credito segun el NUMCRE, donde TIPOCR = 'PAG' y TIPOCRE != 'ANT'",
    * @OA\Parameter(
    *          name="NUMCRE",
    *          description="numero",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="int")),
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMCREDITO segun el numero."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetCredito(int $numero)
    {
        $creditos = \App\ADMCREDITO::where('TIPOCR','=','PAG')
        ->where('TIPO','<>','ANT')
        ->where('NUMCRE','=',$numero)
        ->select('NUMERO','TIPO','SERIE','MONTO','SALDO')
        ->get();

        return response()->json($creditos);
    }

     /**
    * @OA\Get(
    *     path="/api/creditopos/{numero}",
    *     tags={"Credito"},
    *     summary="Información de un creditoPos segun el NUMCRE, donde TIPOCR = 'PAG' y TIPOCRE != 'ANT'",
    * @OA\Parameter(
    *          name="NUMCRE",
    *          description="numero",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="int")),
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMCREDITOPOS segun el numero."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function GetCreditoPos(int $numero){

        $creditos = \App\ADMCREDITOPOS::where('TIPOCR','=','PAG')
        ->where('TIPO','<>','ANT')
        ->where('NUMCRE','=',$numero)
        ->select('NUMERO','TIPO','SERIE','MONTO')
        ->get();

        return response()->json($creditos);
    }
}

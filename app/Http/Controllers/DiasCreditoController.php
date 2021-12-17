<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiasCreditoController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/diascredito",
    *     tags={"Datos"},
    *     summary="Retorna registros de ADMDIASCREDITO.",
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMDIASCREDITO."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function GetDiasCredito(){
        $dias =  \App\ADMDIASCREDITO::get();
        return response()->json($dias);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BancoCiaController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/bacoscia",
    *     tags={"Datos"},
    *     summary="Listado de BancosCia",
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMBANCOCIA con ESTADO = 'A'."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function GetBancoCia(){
        $Bancos = \App\ADMBANCOCIA::where('ESTADO','=','A')->get();
        return response()->json($Bancos);
    }
}

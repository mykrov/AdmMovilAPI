<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CantonController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/canton",
    *     tags={"Datos"},
    *     summary="Listado de Cantones",
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCANTON con ESTADO = 'A'."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function GetCantones(){
        $cantones = \App\ADMCANTON::where('estado','=','A')->get();

        return response()->json($cantones);
    }
}

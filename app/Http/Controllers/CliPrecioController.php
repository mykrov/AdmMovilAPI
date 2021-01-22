<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CliPrecioController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/cliprecio",
    *     tags={"Datos"},
    *     summary="Listado de Precios",
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMCLIPRECIO."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
   public function GetCliPrecio()
   {
       $cprecio = \App\ADMCLIPRECIO::get();
       return response()->json($cprecio);
   }
}

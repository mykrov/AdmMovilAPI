<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/categoria",
    *     tags={"Datos"},
    *     summary="Listado de Categoria de Items",
    *     @OA\Response(
    *         response=200,
    *         description="Retorna todos los registros de la tabla ADMCATEGORIA con ESTADO = 'A'."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetCategorias(){
        $cate = \App\ADMCATEGORIA::where('ESTADO','=','A')->get();
        return response()->json($cate);
    }
}

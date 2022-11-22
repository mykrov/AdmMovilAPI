<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
* @OA\Info(
*  title="API ADM GO", 
*  version="1.0",
*  description= " API de aplicación ADM GO.",
*  @OA\Contact(email="basesinteligentes@birobid.com")
* )
* @OA\Server(url="http://181.198.213.18:8000",description="Server Pro")
* @OA\Server(url="http://aplicacionmovil.test:8000",description="Server Local")
*/
class Banco extends Controller
{
   /**
    * @OA\Get(
    *     path="/api/bancos",
    *     tags={"Datos"},
    *     summary="Listado de Bancos",
    *     @OA\Response(
    *         response=200,
    *         description="Retorna codigo,nombre y estado de los bancos."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */


   // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
   public function GetBancos(){

      $bancos = \App\ADMBANCO::where('estado','=','A')->get();
      return response()->json($bancos);

   }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\ADMDEUDAPOS as Deudapos;
use Illuminate\Support\Facades\DB;

class DeudaPosController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/deudapos",
    *     tags={"Deudas"},
    *     summary="Retorna registros de ADMDEUDAPOS, donde el saldo > 0, el ESTADO != null, y el TIPO in ('FAC','NVT','NDB','CHP')",
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMDEUDA."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function GetDeudas()
    {
        $deudas = DB::table('ADMDEUDAPOS')
                ->select(DB::raw("TIPO, NUMERO, BODEGA, SERIE, FECHAEMI, FECHAVEN, CLIENTE, MONTO - IVA AS SUBTOTAL, '0' AS DESCTO, IVA, MONTO, CREDITO, SALDO"))
                ->where('SALDO', '>', 0.01)
                ->whereNull('ESTADO')
                ->whereIn('TIPO',array('FAC','NVT','NDB','CHP'))
                ->get();
        return response()->json($deudas);
    }


    /**
    * @OA\Get(
    *     path="/api/deudapos/{cliente}",
    *     tags={"Deudas"},
    *     summary="Retorna registros de ADMDEUDAPOS por Cliente, donde el saldo > 0, el ESTADO != null, y el TIPO in ('FAC','NVT','NDB','CHP')",
    * @OA\Parameter(
    *          name="cliente",
    *          description="cliente",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMDEUDAPOS y los Cheques de ADMDEUDA por cliente."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function GetDeudaXCliente($codigo)
    {
        $deudas = DB::table('ADMDEUDAPOS')
                ->select(DB::raw("TIPO, NUMERO, SECUENCIAL, BODEGA, SERIE, FECHAEMI, FECHAVEN, CLIENTE, MONTO - IVA AS SUBTOTAL, '0' AS DESCTO, IVA, MONTO, CREDITO, SALDO"))
                ->where('SALDO', '>', 0.01)
                ->where('CLIENTE','=',$codigo)
                ->whereIn('TIPO',array('FAC','NVT','NDB'))
                ->whereNull('ESTADO')
                ->get();

        $deudasCHP = DB::table('ADMDEUDA')
                    ->select(DB::raw("TIPO, NUMERO, SECUENCIAL, BODEGA, SERIE, FECHAEMI, FECHAVEN, CLIENTE, MONTO - IVA AS SUBTOTAL, '0' AS DESCTO, IVA, MONTO, CREDITO, SALDO"))
                    ->where('SALDO', '>', 0.01)
                    ->where('CLIENTE','=',$codigo)
                    ->whereIn('TIPO',array('CHP'))
                    ->whereNull('ESTADO')
                    ->get();
        
        //return response()->json($deudas);
        return response()->json($deudas->merge($deudasCHP));
    }
}

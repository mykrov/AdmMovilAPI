<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\ADMDEUDA as Deuda;
use Illuminate\Support\Facades\DB;

class DeudaController extends Controller
{
    /**
    * @OA\Get(
    *     path="/api/deuda",
    *     tags={"Deudas"},
    *     summary="Retorna registros de ADMDEUDA donde el saldo > 0, el ESTADO != null, y el TIPO in ('FAC','NVT','NDB')",
   
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMDEUDA"
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function GetDeudas()
    {
        $deudas = DB::table('ADMDEUDA')
                ->select(DB::raw("SECUENCIAL,TIPO, NUMERO, BODEGA, SERIE, FECHAEMI, FECHAVEN, CLIENTE, MONTO - IVA AS SUBTOTAL, '0' AS DESCTO, IVA, MONTO, CREDITO, SALDO, NUMEROFAC, SERIEFAC"))
                ->where('SALDO', '>', 0.01)
                ->whereNull('ESTADO')
                ->whereIn('TIPO',array('FAC','NVT','NDB'))
                ->get();
        return response()->json($deudas);
    }

    /**
    * @OA\Get(
    *     path="/api/deudaxv/{vendedor}",
    *     tags={"Deudas"},
    *     summary="Retorna registros de ADMDEUDA por vendedor, donde el saldo > 0, el ESTADO != null, y el TIPO in ('FAC','NVT','NDB')",
    * @OA\Parameter(
    *          name="vendedor",
    *          description="vendedor",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMDEUDA por vendedor."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    public function GetDeudasXVendedor($vendedor)
    {
        $deudas = DB::table('ADMDEUDA')
                ->select(DB::raw("SECUENCIAL,TIPO, NUMERO, BODEGA, SERIE, FECHAEMI, FECHAVEN, CLIENTE, MONTO - IVA AS SUBTOTAL, '0' AS DESCTO, IVA, MONTO, CREDITO, SALDO"))
                ->where('SALDO', '>', 0.01)
                ->whereNull('ESTADO')
                ->where('VENDEDOR','=',$vendedor)
                ->whereIn('TIPO',array('FAC','NVT','NDB'))
                ->get();
        return response()->json($deudas);
    }

    /**
    * @OA\Get(
    *     path="/api/deuda/{cliente}",
    *     tags={"Deudas"},
    *     summary="Retorna registros de ADMDEUDA por cliente, donde el saldo > 0, el ESTADO != null, y el TIPO in ('FAC','NVT','NDB')",
    * @OA\Parameter(
    *          name="cliente",
    *          description="cliente",
    *          required=true,
    *          in="path",
    *          @OA\Schema(type="string")),
    *     @OA\Response(
    *         response=200,
    *         description="Retorna los registros de ADMDEUDA por cliente."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function GetDeudaXCliente($codigo)
    {
        $deudas = DB::table('ADMDEUDA')
                ->select(DB::raw("SECUENCIAL,TIPO, NUMERO, BODEGA, SERIE, FECHAEMI, FECHAVEN, CLIENTE, MONTO - IVA AS SUBTOTAL, '0' AS DESCTO, IVA, MONTO, CREDITO, SALDO,VENDEDOR"))
                ->where('SALDO', '>', 0.01)
                ->where('CLIENTE','=',$codigo)
                ->whereIn('TIPO',array('FAC','NVT','NDB'))
                ->whereNull('ESTADO')
                ->get();
        return response()->json($deudas);
    }
}

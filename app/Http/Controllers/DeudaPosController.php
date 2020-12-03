<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\ADMDEUDAPOS as Deudapos;
use Illuminate\Support\Facades\DB;

class DeudaPosController extends Controller
{
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
        return response()->json($deudas);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\ADMDEUDA as Deuda;
use Illuminate\Support\Facades\DB;

class DeudaController extends Controller
{
    public function GetDeudas()
    {
        $deudas = DB::table('ADMDEUDA')
                ->select(DB::raw("SECUENCIAL,TIPO, NUMERO, BODEGA, SERIE, FECHAEMI, FECHAVEN, CLIENTE, MONTO - IVA AS SUBTOTAL, '0' AS DESCTO, IVA, MONTO, CREDITO, SALDO"))
                ->where('SALDO', '>', 0.01)
                ->whereNull('ESTADO')
                ->whereIn('TIPO',array('FAC','NVT','NDB'))
                ->get();
        return response()->json($deudas);
    }

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

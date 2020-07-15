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
                ->select(DB::raw("TIPO, NUMERO, BODEGA, SERIE, FECHAEMI, FECHAVEN, CLIENTE, MONTO - IVA AS SUBTOTAL, '0' AS DESCTO, IVA, MONTO, CREDITO, SALDO"))
                ->where('SALDO', '>', 0)
                ->whereNull('ESTADO')
                ->get();
        return response()->json($deudas);
    }

    public function GetDeudaXCliente($codigo)
    {
        $deudas = DB::table('ADMDEUDA')
                ->select(DB::raw("TIPO, NUMERO, BODEGA, SERIE, FECHAEMI, FECHAVEN, CLIENTE, MONTO - IVA AS SUBTOTAL, '0' AS DESCTO, IVA, MONTO, CREDITO, SALDO"))
                ->where('SALDO', '>', 0)
                ->where('CLIENTE','=',$codigo)
                ->whereNull('ESTADO')
                ->get();
        return response()->json($deudas);
    }
}

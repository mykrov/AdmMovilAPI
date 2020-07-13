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
                ->where('SALDO', '>', 0)
                ->get();
        return response()->json($deudas);
    }

    public function GetDeudaXCliente($codigo)
    {
        $deudas = DB::table('ADMDEUDAPOS')
                ->select(DB::raw("TIPO, NUMERO, BODEGA, SERIE, FECHAEMI, FECHAVEN, CLIENTE, MONTO - IVA AS SUBTOTAL, '0' AS DESCTO, IVA, MONTO, CREDITO, SALDO"))
                ->where('SALDO', '>', 0)
                ->where('CLIENTE','=',$codigo)
                ->get();
        return response()->json($deudas);
    }
}

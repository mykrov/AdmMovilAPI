<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeudaCuotasController extends Controller
{
    public function GetDeudaCuotas(int $sec)
    {
        $cabecera = \App\ADMDEUDA::where('SECUENCIAL','=',$sec)->get();

        $detalles= \App\ADMDEUDACUOTA::where('SECDEUDA','=',$sec)
        ->where('SALDO','>',0.001)
        ->get();

        return response()->json(["deuda"=>$cabecera,'cuotas'=>$detalles]);
    }
}

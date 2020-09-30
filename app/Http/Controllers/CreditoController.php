<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreditoController extends Controller
{
    public function GetCredito(int $numero)
    {
        $creditos = \App\ADMCREDITO::where('TIPOCR','=','PAG')
        ->where('TIPO','<>','ANT')
        ->where('NUMCRE','=',$numero)
        ->select('NUMERO','TIPO','SERIE','MONTO')
        ->get();

        return response()->json($creditos);
    }

    public function GetCreditoPos(int $numero){

        $creditos = \App\ADMCREDITOPOS::where('TIPOCR','=','PAG')
        ->where('TIPO','<>','ANT')
        ->where('NUMCRE','=',$numero)
        ->select('NUMERO','TIPO','SERIE','MONTO')
        ->get();

        return response()->json($creditos);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuiaCobroController extends Controller
{
    public function GetGuiaCobro(int $numero)
    {
        $cabecera = \App\ADMCABGUIACOB::where('NUMGUIA','=',$numero)
        ->get();

        $detalles2 = DB::table("ADMDETGUIACOB")
        ->where('SALDO','>',0)
        ->where('NUMGUIA','=',$numero)
        ->select('NUMGUIA','SECUENCIAL',DB::raw('RTRIM(CLIENTE) as CLIENTE'),'TIPO','NUMERO','SERIE','FECHAEMI','FECHAVEN','MONTO','SALDO','EFECTIVO','CHEQUE','FUENTE','IVA','DESCUENTO','OTRO','NOCOBRO','ESTADO','NRECIBO','DEPOSITO','ARTICULO','VALORCUOTA','FECULTPAG','VALORULTPAG')
        ->get();
        
        return response()->json(['cabecera'=>$cabecera,'detalles'=>$detalles2]);
    }
}

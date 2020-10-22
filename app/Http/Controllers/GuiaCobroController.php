<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuiaCobroController extends Controller
{
    public function GetGuiaCobro(int $numero)
    {
        $cabecera = \App\ADMCABGUIACOB::where('NUMGUIA','=',$numero)
        ->where('ESTADO','=','P')
        ->get();
        
        $detalles = \App\ADMDETGUIACOB::where('NUMGUIA','=',$numero)
        ->where('ESTADO','=','P')
        ->get();
        
        return response()->json(['cabecera'=>$cabecera,'detalles'=>$detalles]);
    }
}

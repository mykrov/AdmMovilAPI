<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Cliente;

class UbicacionController extends Controller
{
    public function UpdateCoordinates(Request $r)
    {
        $cliente = trim($r['CLIENTE']);
        $lat = trim($r['LATITUD']);
        $lon = trim($r['LONGITUD']);
        $cli = Cliente::where('CODIGO','=',$cliente)->first();

        if ($cli != null) {
            $cli->latitud = $lat;
            $cli->longuitud = $lon;
            $cli->save();
            return response()->json(['estado'=>'actualizado']);
        } else {
            return response()->json(['estado'=>'noEncontrado']);
        }
    }
}
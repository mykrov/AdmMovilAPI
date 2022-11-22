<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZonaController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetZonas(){
        $zonas = \App\ADMZONA::where('estado','=','A')->get();
        return response()->json($zonas);
    }
}

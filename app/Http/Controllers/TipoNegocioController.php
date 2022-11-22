<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipoNegocioController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda.
    public function GetTipoNegocios(){
        $tipos = \App\ADMNEGOCIO::where('estado','=','A')->get();
        return response()->json($tipos);
    }
}

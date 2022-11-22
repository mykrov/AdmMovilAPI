<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrupoProductoController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetGrupoProductos(){
        $grupos = \App\ADMGRUPOPRO::where('ESTADO','=','A')->get();
        return response()->json($grupos);
    }
}

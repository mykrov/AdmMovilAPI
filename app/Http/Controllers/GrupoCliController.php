<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrupoCliController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetGrupoCli(){
        $grucl = \App\ADMGRUPOCLI::where('ESTADO','=','A')->get();
        return response()->json($grucl);
    }
}

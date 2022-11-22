<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrecuenciaController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetFrecuencias(){
        $frecuencias = \App\ADMFRECUENCIA::All();
        return response()->json($frecuencias);
    }
}

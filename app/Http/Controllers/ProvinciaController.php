<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProvinciaController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetProvincias(){
        $provincias = \App\ADMPROVINCIA::where('estado','=','A')->get();
        return response()->json($provincias);
    }
}

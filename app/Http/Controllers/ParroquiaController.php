<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParroquiaController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetParroquias(){
        $parroquias = \App\ADMPARROQUIA::where('estado','=','A')->get();
        return response()->json($parroquias);
    }
}

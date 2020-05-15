<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function GetRutas(){
        $rutas = \App\ADMRUTA::where('ESTADO','=','A')->get();
        return response()->json($rutas);
    }
}

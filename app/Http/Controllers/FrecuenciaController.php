<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrecuenciaController extends Controller
{
    public function GetFrecuencias(){
        $frecuencias = \App\ADMFRECUENCIA::All();
        return response()->json($frecuencias);
    }
}

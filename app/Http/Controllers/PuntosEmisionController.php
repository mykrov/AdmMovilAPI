<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PuntosEmisionController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetPuntos(){
        $puntos = DB::table('ADMPUNTOEMISION')->get();
        return response()->json($puntos);
    }
}

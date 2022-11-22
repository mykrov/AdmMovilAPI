<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedioPagoController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetMedioPago()
    {
        $medios = \App\ADMMEDIOPAGO::where('estado','=','A')->get();
        return response()->json($medios);
    }
}

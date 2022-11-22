<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SucursalController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda.
    public function GetSucursales($cliente){
        $sucursales = \App\ADMSUCURSAL::where('CLIENTE','=',$cliente)
        ->get();
        return response()->json($sucursales);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function GetSucursales($cliente){
        $sucursales = \App\ADMSUCURSAL::where('CLIENTE','=',$cliente)
        ->get();
        return response()->json($sucursales);
    }
}

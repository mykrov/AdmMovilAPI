<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZonaController extends Controller
{
    public function GetZonas(){
        $zonas = \App\ADMZONA::where('estado','=','A')->get();
        return response()->json($zonas);
    }
}

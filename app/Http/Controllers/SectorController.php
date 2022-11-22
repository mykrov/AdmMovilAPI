<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SectorController extends Controller
{
     // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda.
    public function GetSector(){
        $sectores = \App\ADMSECTOR::where('estado','=','A')->get();
        return response()->json($sectores);
    }
}

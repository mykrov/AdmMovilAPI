<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SectorController extends Controller
{
    public function GetSector(){
        $sectores = \App\ADMSECTOR::where('estado','=','A')->get();
        return response()->json($sectores);
    }
}

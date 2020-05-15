<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProvinciaController extends Controller
{
    public function GetProvincias(){
        $provincias = \App\ADMPROVINCIA::where('estado','=','A')->get();
        return response()->json($provincias);
    }
}

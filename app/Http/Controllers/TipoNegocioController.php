<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipoNegocioController extends Controller
{
    public function GetTipoNegocios(){
        $tipos = \App\ADMNEGOCIO::where('estado','=','A')->get();
        return response()->json($tipos);
    }
}

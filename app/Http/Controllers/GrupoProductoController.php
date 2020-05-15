<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrupoProductoController extends Controller
{
    public function GetGrupoProductos(){
        $grupos = \App\ADMGRUPOPRO::where('ESTADO','=','A')->get();
        return response()->json($grupos);
    }
}

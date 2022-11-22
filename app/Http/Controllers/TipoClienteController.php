<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipoClienteController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda.
    public function GetTipoClientes(){
        $tipos = \App\ADMTIPOCLIENTE::where('estado','=','A')->get();
        return response()->json($tipos);
    }
}

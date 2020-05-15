<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipoClienteController extends Controller
{
    public function GetTipoClientes(){
        $tipos = \App\ADMTIPOCLIENTE::where('estado','=','A')->get();
        return response()->json($tipos);
    }
}

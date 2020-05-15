<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Cliente extends Controller
{
    public function listado(){

        $clientes = \App\Cliente::all();
        return response()->json($clientes);

    }

    public function byID($id){

        $cliente = \App\Cliente::where('codigo',$id)->get();
        return response()->json($cliente);

    }

    public function CreateClient(Request $request){

        return response($request);
    }
}

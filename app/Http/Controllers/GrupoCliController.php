<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrupoCliController extends Controller
{
    public function GetGrupoCli(){
        $grucl = \App\ADMGRUPOCLI::where('ESTADO','=','A')->get();
        return response()->json($grucl);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BancoCiaController extends Controller
{
    public function GetBancoCia(){
        $Bancos = \App\ADMBANCOCIA::where('ESTADO','=','A')->get();
        return response()->json($Bancos);
    }
}

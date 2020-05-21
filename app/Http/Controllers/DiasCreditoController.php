<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiasCreditoController extends Controller
{
    public function GetDiasCredito(){
        $dias =  \App\ADMDIASCREDITO::get();
        return response()->json($dias);
    }
}

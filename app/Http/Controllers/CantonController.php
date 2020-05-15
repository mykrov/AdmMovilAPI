<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CantonController extends Controller
{
    public function GetCantones(){
        $cantones = \App\ADMCANTON::where('estado','=','A')->get();

        return response()->json($cantones);
    }
}

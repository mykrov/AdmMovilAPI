<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedioPagoController extends Controller
{
    public function GetMedioPago()
    {
        $medios = \App\ADMMEDIOPAGO::where('estado','=','A')->get();
        return response()->json($medios);
    }
}

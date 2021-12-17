<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PuntosEmisionController extends Controller
{
    public function GetPuntos(){
        $puntos = DB::table('ADMPUNTOEMISION')->get();
        return response()->json($puntos);
    }
}

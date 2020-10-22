<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RutaController extends Controller
{
    public function GetRutas(){
        //Rutas Cacheadas 5min.
        $rutas = Cache::remember('rutasLista',300, function () {
            return DB::table('ADMRUTA')->where('ESTADO','=','A')->get();
        });

        return response()->json($rutas);
    }
}

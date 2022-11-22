<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RutaController extends Controller
{
     // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda.
    public function GetRutas(){
        //Rutas Cacheadas 5min. para evitar saturacion de peticiones
        $rutas = Cache::remember('rutasLista',300, function () {
            return DB::table('ADMRUTA')->where('ESTADO','=','A')->get();
        });

        return response()->json($rutas);
    }
}

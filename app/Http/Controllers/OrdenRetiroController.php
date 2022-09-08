<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrdenRetiroController extends Controller
{
    public function getCabOrders(Request $r){

        Log::info($r);
        $f1 = Carbon::createFromFormat('d-m-Y',$r['fecha1'])->Format('d-m-Y');
        $f2 =  Carbon::createFromFormat('d-m-Y',$r['fecha2'])->Format('d-m-Y');
        $vend = $r['vendedor'];

        $cabeceras = DB::table('ADMCABRETIRO')
        ->whereBetween('FECHA',array($f1,$f2))
        ->where('VENDEDOR',$vend)
        ->get();

        return response()->json($cabeceras);

    }

    public function getDetOrders(Request $r){

        $numero = $r['numero'];        
        $detalles = DB::table('ADMDETRETIRO')
        ->where('NUMERO',$numero )
        ->get();

        return response()->json($detalles);

    }
}

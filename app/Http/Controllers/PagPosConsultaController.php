<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagPosConsultaController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetCabeceras(Request $r){
        $vendedor = $r['VENDEDOR'];
        $f1 = Carbon::createFromFormat('d-m-Y',$r['FECHAINI']);
        $f2 = Carbon::createFromFormat('d-m-Y',$r["FECHAFIN"]);
        $fecha1 = $f1->Format('Y-d-m');
        $fecha2 = $f2->Format('Y-d-m');

        $cabeceras = DB::table('ADMPAGOPOS')
        ->whereBetween('ADMPAGOPOS.fecha',[$fecha1, $fecha2])
        ->whereIn('ADMPAGOPOS.tipo',array('PAG'))
        ->where('ADMPAGOPOS.vendedor','=',$vendedor)
        ->join('ADMCLIENTE','ADMPAGOPOS.cliente','=','ADMCLIENTE.CODIGO' )
        ->select('ADMCLIENTE.RAZONSOCIAL','ADMPAGOPOS.tipo',
        'ADMPAGOPOS.numero','ADMPAGOPOS.CODIGOCAJA','ADMPAGOPOS.fecha','ADMPAGOPOS.cliente','ADMPAGOPOS.secuencial',
        'ADMPAGOPOS.monto')->get();

        return response()->json($cabeceras);
    }

// Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetDetalles(Request $r){

        $secuencial = $r['SECUENCIAL'];

        $detalle = DB::table('ADMDETPAGOPOS')
        ->where('secuencial','=',$secuencial)
        ->get();
        return response()->json($detalle);

    }   
}

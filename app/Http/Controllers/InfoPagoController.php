<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InfoPagoController extends Controller
{
    public function GetPagoCab(Request $r){
        
        $vendedor = $r['VENDEDOR'];
        $f1 = Carbon::createFromFormat('d-m-Y',$r['FECHAINI']);
        $f2 = Carbon::createFromFormat('d-m-Y',$r["FECHAFIN"]);
        $fecha1 = $f1->Format('Y-d-m');
        $fecha2 = $f2->Format('Y-d-m');

        $pagos = DB::table('ADMPAGO')
        ->whereBetween('ADMPAGO.fecha',[$fecha1, $fecha2])
        ->where('ADMPAGO.vendedor','=',$vendedor)
        ->join('ADMCLIENTE','ADMPAGO.cliente','=','ADMCLIENTE.CODIGO')
        ->select('ADMCLIENTE.RAZONSOCIAL','ADMPAGO.tipo','ADMPAGO.numero',
        'ADMPAGO.fecha','ADMPAGO.cliente','ADMPAGO.secuencial','ADMPAGO.monto','ADMPAGO.observacion')->get();

        return response()->json($pagos);
    }

    public function GetDetallesPagos(Request $r)
    {
        $secuencial = $r['SECUENCIAL'];

        $detallesPago = DB::table('ADMDETPAGO')
        ->where('SECUENCIAL','=',$secuencial)
        ->select('ADMDETPAGO.secuencial','ADMDETPAGO.tipo',
        'ADMDETPAGO.numero','ADMDETPAGO.monto','ADMDETPAGO.tipopag',
        'ADMDETPAGO.banco','ADMDETPAGO.cuenta','ADMDETPAGO.numchq')
        ->get();
        return response()->json($detallesPago);
    }
}

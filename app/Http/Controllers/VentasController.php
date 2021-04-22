<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\ADMCABEGRESOPOS;
use \App\ADMDETEGRESOPOS;
use \App\ADMCLIENTE;
use Carbon\Carbon;


class VentasController extends Controller
{
   public function GetCabeceras(Request $r)
    {
        $vendedor = $r['VENDEDOR'];
        $f1 = Carbon::createFromFormat('d-m-Y',$r['FECHAINI']);
        $f2 = Carbon::createFromFormat('d-m-Y',$r["FECHAFIN"]);
        $fecha1 = $f1->Format('Y-d-m');
        $fecha2 = $f2->Format('Y-d-m');

        $cabeceras = DB::table('ADMCABEGRESOPOS')
        ->whereBetween('ADMCABEGRESOPOS.FECHA',[$fecha1, $fecha2])
        ->whereIn('ADMCABEGRESOPOS.TIPO',array('FAC','NVT'))
        ->where('ADMCABEGRESOPOS.VENDEDOR','=',$vendedor)
        ->join('ADMCLIENTE','ADMCABEGRESOPOS.CLIENTE','=','ADMCLIENTE.CODIGO' )
        ->select('ADMCLIENTE.RAZONSOCIAL','ADMCABEGRESOPOS.TIPO','ADMCABEGRESOPOS.SERIE',
        'ADMCABEGRESOPOS.NUMERO','ADMCABEGRESOPOS.FECHA','ADMCABEGRESOPOS.CLIENTE','ADMCABEGRESOPOS.SECUENCIAL',
        'ADMCABEGRESOPOS.SUBTOTAL','ADMCABEGRESOPOS.DESCUENTO','ADMCABEGRESOPOS.IVA','ADMCABEGRESOPOS.NETO')->get();

        return response()->json($cabeceras);
    }
    
    public function GetDetalles(Request $r)
    {
        $secuencial = $r['SECUENCIAL'];

        $detalles = DB::table('ADMDETEGRESOPOS')
        ->where('SECUENCIAL','=',$secuencial)
        ->join('ADMITEM','ADMDETEGRESOPOS.ITEM','=','ADMITEM.ITEM')
        ->select('ADMITEM.NOMBRE','ADMDETEGRESOPOS.ITEM','ADMDETEGRESOPOS.PRECIO','ADMDETEGRESOPOS.CANTIU',
        'ADMDETEGRESOPOS.CANTIC','ADMDETEGRESOPOS.CANTFUN','ADMDETEGRESOPOS.SUBTOTAL','ADMDETEGRESOPOS.DESCUENTO',
        'ADMDETEGRESOPOS.IVA','ADMDETEGRESOPOS.NETO','ADMDETEGRESOPOS.PORDES')
        ->get();

        return response()->json($detalles);
    }

    public function GetCabeceras2(Request $r)
    {
        $vendedor1 = $r['VENDEDOR1'];
        $vendedor2 = $r['VENDEDOR2'];
        $f1 = Carbon::createFromFormat('d-m-Y',$r['FECHAINI']);
        $f2 = Carbon::createFromFormat('d-m-Y',$r["FECHAFIN"]);
        $fecha1 = $f1->Format('Y-d-m');
        $fecha2 = $f2->Format('Y-d-m');

        $cabeceras = DB::table('ADMCABEGRESOPOS')
        ->whereBetween('ADMCABEGRESOPOS.FECHA',[$fecha1, $fecha2])
        ->whereIn('ADMCABEGRESOPOS.TIPO',array('FAC','NVT'))
        ->whereBetween('ADMCABEGRESOPOS.VENDEDOR',[$vendedor1,$vendedor2])
        ->join('ADMCLIENTE','ADMCABEGRESOPOS.CLIENTE','=','ADMCLIENTE.CODIGO' )
        ->select('ADMCLIENTE.RAZONSOCIAL','ADMCABEGRESOPOS.TIPO','ADMCABEGRESOPOS.SERIE',
        'ADMCABEGRESOPOS.NUMERO','ADMCABEGRESOPOS.FECHA','ADMCABEGRESOPOS.CLIENTE','ADMCABEGRESOPOS.SECUENCIAL',
        'ADMCABEGRESOPOS.SUBTOTAL','ADMCABEGRESOPOS.DESCUENTO','ADMCABEGRESOPOS.IVA','ADMCABEGRESOPOS.NETO','ADMCABEGRESOPOS.VENDEDOR')->get();

        return response()->json($cabeceras);
    }
}

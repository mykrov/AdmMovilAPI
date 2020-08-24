<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProformasController extends Controller
{
    public function GetCabecera(Request $r){
        $vendedor = $r['VENDEDOR'];
        $f1 = Carbon::createFromFormat('d-m-Y',$r['FECHAINI']);
        $f2 = Carbon::createFromFormat('d-m-Y',$r["FECHAFIN"]);
        $fecha1 = $f1->Format('Y-d-m');
        $fecha2 = $f2->Format('Y-d-m');

        //return response()->json(['f1'=>$fecha1,'f2'=>$fecha2,'vende'=>$vendedor]);

        $cabeceras = DB::table('ADMCABPEDIDO')
        ->where('ADMCABPEDIDO.TIPO','=','PED')
        ->whereBetween('ADMCABPEDIDO.FECHA',[$fecha1, $fecha2])
        ->where('ADMCABPEDIDO.VENDEDOR','=',$vendedor)
        ->join('ADMCLIENTE','ADMCABPEDIDO.CLIENTE','=','ADMCLIENTE.CODIGO' )
        ->select('ADMCLIENTE.RAZONSOCIAL','ADMCABPEDIDO.TIPO','ADMCABPEDIDO.SECAUTO',
        'ADMCABPEDIDO.NUMERO','ADMCABPEDIDO.FECHA','ADMCABPEDIDO.CLIENTE','ADMCABPEDIDO.SECUENCIAL',
        'ADMCABPEDIDO.SUBTOTAL','ADMCABPEDIDO.DESCUENTO','ADMCABPEDIDO.IVA','ADMCABPEDIDO.NETO')->get();

        return response()->json($cabeceras);
    }   

    public function GetDetalles(Request $r)
    {
        $secuencial = $r['SECUENCIAL'];

        $detalles = DB::table('ADMDETPEDIDO')
        ->where('SECUENCIAL','=',$secuencial)
        ->join('ADMITEM','ADMDETPEDIDO.ITEM','=','ADMITEM.ITEM')
        ->select('ADMITEM.NOMBRE','ADMDETPEDIDO.ITEM','ADMDETPEDIDO.PRECIO','ADMDETPEDIDO.CANTIU',
        'ADMDETPEDIDO.CANTIC','ADMDETPEDIDO.CANTFUN','ADMDETPEDIDO.SUBTOTAL','ADMDETPEDIDO.DESCUENTO',
        'ADMDETPEDIDO.IVA','ADMDETPEDIDO.NETO','ADMDETPEDIDO.PORDES')
        ->get();

        return response()->json($detalles);
    }
}
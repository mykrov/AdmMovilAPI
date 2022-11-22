<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use \App\ADMCABEGRESOPOS;
use \App\ADMDETEGRESOPOS;
use \App\ADMDETEGRESO;
use \App\ADMCABEGRESO;

class HistPrecioController extends Controller
{
    // Retorna las entidades del modelo consultado de acuerdo al criterio de busqueda."
    public function GetHistorico(Request $r)
    {
        $cliente = $r['CLIENTE'];
        $f1 = Carbon::createFromFormat('d-m-Y',$r['FECHAINI']);
        $f2 = Carbon::createFromFormat('d-m-Y',$r["FECHAFIN"]);
        $fecha1 = $f1->Format('Y-d-m');
        $fecha2 = $f2->Format('Y-d-m');
        $item = $r["ITEM"];
        $tipo = $r["TIPOBUSQUEDA"];//ITEM y FECH

        if ($tipo == 'FECH') {

            $detalles = DB::table('ADMDETEGRESO')
            ->select(DB::raw('ADMCABEGRESO.TIPO,
                                ADMCABEGRESO.NUMERO,
                                ADMCABEGRESO.FECHA,
                                ADMITEM.NOMBRE,
                                ADMDETEGRESO.ITEM,
                                ADMDETEGRESO.CANTIC as CAJAS,
                                ADMDETEGRESO.CANTFUN as UNIDADES,
                                ADMDETEGRESO.PRECIO,
                                ADMDETEGRESO.SUBTOTAL,
                                ADMDETEGRESO.DESCUENTO,
                                ADMDETEGRESO.PORDES,
                                ADMDETEGRESO.IVA,
                                ADMDETEGRESO.NETO'))
            ->join('ADMCABEGRESO','ADMDETEGRESO.SECUENCIAL','ADMCABEGRESO.SECUENCIAL')
            ->where([['ADMCABEGRESO.CLIENTE','=',$cliente],['ADMCABEGRESO.FECHA','>=',$fecha1],
            ['ADMCABEGRESO.FECHA ','<=',$fecha2]])
            ->join('ADMITEM','ADMDETEGRESO.ITEM','ADMITEM.ITEM')
            ->get();

            $detallesPos = DB::table('ADMDETEGRESOPOS')
            ->select(DB::raw('ADMCABEGRESOPOS.TIPO,
                                ADMCABEGRESOPOS.NUMERO,
                                ADMCABEGRESOPOS.FECHA,
                                ADMITEM.NOMBRE,
                                ADMDETEGRESOPOS.ITEM,
                                ADMDETEGRESOPOS.CANTIC as CAJAS,
                                ADMDETEGRESOPOS.CANTFUN as UNIDADES,
                                ADMDETEGRESOPOS.PRECIO,
                                ADMDETEGRESOPOS.SUBTOTAL,
                                ADMDETEGRESOPOS.DESCUENTO,
                                ADMDETEGRESOPOS.PORDES,
                                ADMDETEGRESOPOS.IVA,
                                ADMDETEGRESOPOS.NETO'))
            ->join('ADMCABEGRESOPOS','ADMDETEGRESOPOS.SECUENCIAL','ADMCABEGRESOPOS.SECUENCIAL')
            ->where([['ADMCABEGRESOPOS.CLIENTE','=',$cliente],['ADMCABEGRESOPOS.FECHA','>',$fecha1],
            ['ADMCABEGRESOPOS.FECHA ','<=',$fecha2]])
            ->join('ADMITEM','ADMDETEGRESOPOS.ITEM','ADMITEM.ITEM')
            ->get();

            return response()->json($detalles->merge($detallesPos));
        
        }else{

            $detalles = DB::table('ADMDETEGRESO')
            ->select(DB::raw('ADMCABEGRESO.TIPO,
                                ADMCABEGRESO.NUMERO,
                                ADMCABEGRESO.FECHA,
                                ADMITEM.NOMBRE,
                                ADMDETEGRESO.ITEM,
                                ADMDETEGRESO.CANTIC as CAJAS,
                                ADMDETEGRESO.CANTFUN as UNIDADES,
                                ADMDETEGRESO.PRECIO,
                                ADMDETEGRESO.SUBTOTAL,
                                ADMDETEGRESO.DESCUENTO,
                                ADMDETEGRESO.PORDES,
                                ADMDETEGRESO.IVA,
                                ADMDETEGRESO.NETO'))
            ->join('ADMCABEGRESO','ADMDETEGRESO.SECUENCIAL','ADMCABEGRESO.SECUENCIAL')
            ->where('ADMCABEGRESO.CLIENTE','=',$cliente)
            ->join('ADMITEM','ADMDETEGRESO.ITEM','ADMITEM.ITEM')
            ->where('ADMDETEGRESO.ITEM','=',$item)
            ->get();

            $detallesPos = DB::table('ADMDETEGRESOPOS')
            ->select(DB::raw('ADMCABEGRESOPOS.TIPO,
                                ADMCABEGRESOPOS.NUMERO,
                                ADMCABEGRESOPOS.FECHA,
                                ADMITEM.NOMBRE,
                                ADMDETEGRESOPOS.ITEM,
                                ADMDETEGRESOPOS.CANTIC as CAJAS,
                                ADMDETEGRESOPOS.CANTFUN as UNIDADES,
                                ADMDETEGRESOPOS.PRECIO,
                                ADMDETEGRESOPOS.SUBTOTAL,
                                ADMDETEGRESOPOS.DESCUENTO,
                                ADMDETEGRESOPOS.PORDES,
                                ADMDETEGRESOPOS.IVA,
                                ADMDETEGRESOPOS.NETO'))
            ->join('ADMCABEGRESOPOS','ADMDETEGRESOPOS.SECUENCIAL','ADMCABEGRESOPOS.SECUENCIAL')
            ->where('ADMCABEGRESOPOS.CLIENTE','=',$cliente)
            ->join('ADMITEM','ADMDETEGRESOPOS.ITEM','ADMITEM.ITEM')
            ->where('ADMDETEGRESOPOS.ITEM','=',$item)
            ->get();

            return response()->json($detalles->merge($detallesPos));
        }
        

    }
    
}

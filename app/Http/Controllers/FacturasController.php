<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use \App\ADMCABEGRESO;
use \App\ADMDETEGRESO;
use \App\ADMCLIENTE;
use Carbon\Carbon;

class FacturasController extends Controller
{
    /**
    * @OA\Post(
    *     path="/api/factxvende",
    *     tags={"Facturas"},
    *     summary="Retorna datos de Facturas y Notas de Ventas del perido de fechas y vendedor indicado.",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"VENDEDOR","FECHAINI","FECHAFIN"},
     *              @OA\Property(property="VENDEDOR", type="string", format="string", example="VEN009"),
    *               @OA\Property(property="FECHAINI", type="string", format="string", example="22-01-2020"),
    *               @OA\Property(property="FECHAFIN", type="string", format="string", example="22-12-2020")
     *         )
     *      ),
    *     @OA\Response(
    *         response=200,
    *         description="Retorna datos de Facturas y Notas de Ventas del perido de fechas y vendedor indicado.."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function GetCabeceras(Request $r)
    {
        $vendedor = $r['VENDEDOR'];
        $f1 = Carbon::createFromFormat('d-m-Y',$r['FECHAINI']);
        $f2 = Carbon::createFromFormat('d-m-Y',$r["FECHAFIN"]);
        $fecha1 = $f1->Format('Y-d-m');
        $fecha2 = $f2->Format('Y-d-m');

        $cabeceras = DB::table('ADMCABEGRESO')
        ->whereIn('ADMCABEGRESO.TIPO',['FAC','NVT'])
        ->whereBetween('ADMCABEGRESO.FECHA',[$fecha1, $fecha2])
        ->where('ADMCABEGRESO.VENDEDOR','=',$vendedor)
        ->join('ADMCLIENTE','ADMCABEGRESO.CLIENTE','=','ADMCLIENTE.CODIGO' )
        ->select('ADMCLIENTE.RAZONSOCIAL','ADMCABEGRESO.TIPO','ADMCABEGRESO.SERIE',
        'ADMCABEGRESO.NUMERO','ADMCABEGRESO.FECHA','ADMCABEGRESO.CLIENTE','ADMCABEGRESO.SECUENCIAL',
        'ADMCABEGRESO.SUBTOTAL','ADMCABEGRESO.DESCUENTO','ADMCABEGRESO.IVA','ADMCABEGRESO.NETO')->get();

        return response()->json($cabeceras);
    }
    
    public function GetDetalles(Request $r)
    {
        $secuencial = $r['SECUENCIAL'];

        $detalles = DB::table('ADMDETEGRESO')
        ->where('SECUENCIAL','=',$secuencial)
        ->join('ADMITEM','ADMDETEGRESO.ITEM','=','ADMITEM.ITEM')
        ->select('ADMITEM.NOMBRE','ADMDETEGRESO.ITEM','ADMDETEGRESO.PRECIO','ADMDETEGRESO.CANTIU',
        'ADMDETEGRESO.CANTIC','ADMDETEGRESO.CANTFUN','ADMDETEGRESO.SUBTOTAL','ADMDETEGRESO.DESCUENTO',
        'ADMDETEGRESO.IVA','ADMDETEGRESO.NETO','ADMDETEGRESO.PORDES')
        ->get();

        return response()->json($detalles);
    }
}

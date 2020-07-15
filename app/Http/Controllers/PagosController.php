<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use \App\ADMPARAMETROV;
use Illuminate\Support\Facades\DB;

class PagosController extends Controller
{
    public function Pago(Request $r)
    {
        $vendedor = $r->vendedor;
        $facs = $r->facturas;
        $tipoPago = $r->medioPago;
        $cliente = $r->cliente;
        $fechaChq = $r->fecha;
        $montoPagar = $r->total;
        $parametrov = ADMPARAMETROV::first();
        $NumCre = \App\ADMTIPODOC::where('TIPO','=','PAG')->first();
        $date = Carbon::now('-5');
        
        $fcChq = Carbon::createFromFormat('Y-m-d',$fechaChq)->Format('d-m-Y');
        //return response()->json( $fcChq);

        DB::beginTransaction();
        try {
             //ADMPAGO
            $pago = new \App\ADMPAGO();
            $pago->secuencial = $parametrov->SECUENCIAL + 1;
            $pago->cliente = $cliente;
            $pago->tipo = 'PAG';
            $pago->numero = $NumCre->CONTADOR + 1;
            $pago->monto = $montoPagar;
            $pago->operador = "ADM";
            $pago->observacion = "Pago ".$tipoPago. " por ADMGO Nro:".$pago->numero;
            $pago->numpapel = "";
            $pago->fecha = $date->Format('d-m-Y');
            $pago->vendedor = $vendedor;
            $pago->oripago = "C";
            $pago->hora = $date->Format('H:i:s');
            $pago->nombrepc = 'Servidor Laravel';
            $pago->cajac = "10"; //preguntar a Ricardo
            $pago->fechaeli = "";
            $pago->horaeli = "";
            $pago->maquinaeli = "";
            $pago->operadoreli = "";
            $pago->save();

            //ADMDETPAGO
            $detPago = new \App\ADMDETPAGO();
            $detPago->secuencial = $pago->secuencial;
            $detPago->tipo = $pago->tipo;
            $detPago->numero = $pago->numero;
            $detPago->tipopag = $tipoPago;
            $detPago->monto = $montoPagar;
            $detPago->banco = "";
            $detPago->cuenta = "";
            $detPago->numchq = "";
            //$detPago->fechaven = $fcChq;
            $detPago->estchq = "";
            
            //Pago con Cheque
            if ($tipoPago == "CHQ") {
                $detPago->banco = $r->banco;
                $detPago->cuenta = $r->cuentaChq;
                $detPago->numchq = $r->numCheque;
                $detPago->estchq = "P";
                $detPago->fechaven = $fcChq;
            }
            //$detPago->nogui = "";
            $detPago->vendedor = $vendedor;
            $detPago->intregrado = "N";
            $detPago->save();

            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;
            $NumCre->CONTADOR = $NumCre->CONTADOR + 1;
            $parametrov->save();
            $NumCre->save();

            foreach ($facs as $pos => $val) {
                $numFac = $val['numero'];
                $montoPagar = round($val['monto'],2);
    
                $factura = \App\ADMCABEGRESO::where('SECUENCIAL','=',$numFac)
                ->where('TIPO','=','FAC')
                ->first();
                //return response()->json($factura);
    
                if ($factura != null){
                    $deuda = \App\ADMDEUDA::where('SECINV','=',$factura->SECUENCIAL)
                    ->where('TIPO','=','FAC')
                    ->first();
    
                    //Reducción de Saldo.
                    $saldo = $deuda->SALDO;
                    $deuda->CREDITO = $deuda->CREDITO + $montoPagar;
                    $deuda->SALDO = $deuda->SALDO - $montoPagar;

                    $deuda->save();
                    
                    $credito = \App\ADMCREDITO::where('SECINV','=',$factura->SECUENCIAL)
                    ->where('TIPO','=','FAC')
                    ->first();
                    
                    //Nueva Linea ADMCREDITO
                    $creditoLinea = new \App\ADMCREDITO();
                    $creditoLinea->SECUENCIAL = $pago->secuencial;
                    $creditoLinea->BODEGA = $credito->BODEGA;
                    $creditoLinea->CLIENTE = $credito->CLIENTE;
                    $creditoLinea->TIPO = $credito->TIPO;
                    $creditoLinea->NUMERO = $credito->NUMERO;
                    $creditoLinea->SERIE = $credito->SERIE;
                    $creditoLinea->SECINV = $credito->SECINV;
                    $creditoLinea->TIPOCR = 'PAG';
                    $creditoLinea->NUMCRE = $pago->numero;
                    $creditoLinea->SERIECRE = '';
                    $creditoLinea->NOAUTOR = '';
                    $creditoLinea->FECHA = $date->Format('Y-d-m');
                    $creditoLinea->IVA = 0;
                    $creditoLinea->MONTO = $montoPagar;
                    $creditoLinea->SALDO = $saldo - $montoPagar;
                    if($creditoLinea->SALDO < 0){
                        $creditoLinea->SALDO = 0;
                    }
                    $creditoLinea->OPERADOR = 'ADM';
                    $creditoLinea->OBSERVACION = $pago->observacion;
                    $creditoLinea->VENDEDOR = $vendedor;
                    $creditoLinea->HORA = $date->Format('H:i:s');
                    $creditoLinea->NOMBREPC = 'Servidor Laravel';
                    $creditoLinea->estafirmado = 'N';
                    $creditoLinea->ACT_SCT = 'N';
                    $creditoLinea->seccreditogen = 0;
                    $creditoLinea->save();
                    
                }else{
                    DB::rollback();
                    return response()->json(['error'=>'Factura no encontrada: Secuencial '.$numFac]);
                }  
            }
            DB::commit();
            return response()->json(['estado'=>'ok','numPago'=> $pago->numero]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error"=>["ADMPAGO"=>$e->getMessage()]]);
        }
    }
}
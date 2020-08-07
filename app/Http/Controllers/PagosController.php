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
        $deudas = $r->facturas;
        $tipoPago = $r->medioPago;
        $cliente = $r->cliente;
        $fechaChq = $r->fecha;
        $montoPagar = $r->total;
        $parametrov = ADMPARAMETROV::first();
        $NumCre = \App\ADMTIPODOC::where('TIPO','=','PAG')->first();
        $date = Carbon::now('-5');
        
        $fcChq = Carbon::createFromFormat('Y-m-d',$fechaChq)->Format('d-m-Y');
        $observacionCre = "";
        $bodegaDeuda = 10;

            
        try {
             //ADMPAGO
            $cajaAbierta = DB::table('ADMCAJACOB')->where([['estadocaja','=','A'],['estado','=','A']])
            ->select('codigo')
            ->get();

            if($cajaAbierta == null){
                return response()->json(['estado'=>'error','info'=>'NO HAY CAJA']);
            }

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
            $pago->cajac = $cajaAbierta[0]->codigo;
            $pago->fechaeli = "";
            $pago->horaeli = "";
            $pago->maquinaeli = "";
            $pago->operadoreli = "";
            $pago->save();

            $observacionCre = $pago->observacion;

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
            $detPago->estchq = "";
            
            //Pago con Cheque
            if ($tipoPago == "CHQ") {
                $detPago->banco = $r->banco;
                $detPago->cuenta = $r->cuentaChq;
                $detPago->numchq = $r->numCheque;
                $detPago->estchq = "P";
                $detPago->fechaven = $fcChq;
                $observacionCre = "Cheque ".$r->banco." - Cuenta No ".$r->cuentaChq."- No Chq ".$r->numCheque;
            }
            $detPago->vendedor = $vendedor;
            $detPago->intregrado = "N";
            $detPago->save();

            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;

            $NumCre->CONTADOR = $NumCre->CONTADOR + 1;
            $NumCre->save();

            //DEUDA y CREDITO si es Cheque.
            if ($r->medioPago == 'CHQ') {
                
                $numeroCHP =  \App\ADMTIPODOC::where('TIPO','=','CHP')->first();
                $deudaChq = new \App\ADMDEUDA();
                $deudaChq->SECUENCIAL = $parametrov->SECUENCIAL + 1;
                $deudaChq->BODEGA = $bodegaDeuda;
                $deudaChq->CLIENTE = $cliente;
                $deudaChq->TIPO = "CHP";
                $deudaChq->NUMERO = $numeroCHP->CONTADOR + 1;
                $deudaChq->IVA = 0;
                $deudaChq->MONTO = $montoPagar;
                $deudaChq->CREDITO = 0;
                $deudaChq->SALDO = $montoPagar;
                $deudaChq->FECHAEMI = $date->Format('Y-d-m');
                $deudaChq->FECHAVEN = $fcChq;
                $deudaChq->FECHADES = $fcChq;
                $deudaChq->BANCO = trim($r->banco);
                $deudaChq->CUENTA = trim($r->cuentaChq);
                $deudaChq->NUMCHQ = trim($r->numCheque);
                $deudaChq->ESTCHQ = "P";
                $deudaChq->OPERADOR = "ADM";
                $deudaChq->VENDEDOR = $vendedor;
                $deudaChq->OBSERVACION = $observacionCre;
                $deudaChq->NUMAUTO = "";
                $deudaChq->BODEGAFAC = 0;
                $deudaChq->SERIEFAC = "";
                $deudaChq->NUMEROFAC = 0;
                $deudaChq->ACT_SCT = "N";
                $deudaChq->montodocumento = 0;
                $deudaChq->tipoventa = "";
                $deudaChq->mesescredito = 0;
                $deudaChq->tipopago = "";
                $deudaChq->usuarioeli = "";
                $deudaChq->EWEB = "N";
                $deudaChq->save();

                $creditoLinea2 = new \App\ADMCREDITO();
                $creditoLinea2->SECUENCIAL = $deudaChq->SECUENCIAL;
                $creditoLinea2->BODEGA = $deudaChq->BODEGA;
                $creditoLinea2->CLIENTE = $deudaChq->CLIENTE;
                $creditoLinea2->TIPO = $deudaChq->TIPO;
                $creditoLinea2->NUMERO = $deudaChq->NUMERO;
                $creditoLinea2->FECHA = $date->Format('Y-d-m');
                $creditoLinea2->MONTO = $montoPagar;
                $creditoLinea2->SALDO = $montoPagar;
                $creditoLinea2->OPERADOR = 'ADM';
                $creditoLinea2->OBSERVACION = $observacionCre;
                $creditoLinea2->VENDEDOR = $vendedor;
                $creditoLinea2->HORA = $date->Format('H:i:s');
                $creditoLinea2->NOMBREPC = 'Servidor Laravel';
                $creditoLinea2->estafirmado = 'N';
                $creditoLinea2->ACT_SCT = 'N';
                $creditoLinea2->seccreditogen = 0;
                $creditoLinea2->save();

                $numeroCHP->CONTADOR = $numeroCHP->CONTADOR + 1;
                $numeroCHP->save();

                $detPago = \App\ADMDETPAGO::where('secuencial','=',$detPago->secuencial)
                ->where('numero','=',$detPago->numero)->first();

                $detPago->docrel = 'CHP';
                $detPago->numerorel = $deudaChq->NUMERO;
                $detPago->save();

            }

            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;
            $parametrov->save();

            //Cabecera Comprobante Contable
            $cabCompro = new \App\ADMCABCOMPROBANTE();
            $parametroBO = \App\ADMPARAMETROBO::first();

            $cabCompro->secuencial = $parametroBO->secuencial + 1;
            $cabCompro->fecha = $date->Format('Y-d-m');
            $cabCompro->tipoComprobante = 18;
            $cabCompro->numero = -1;
            $cabCompro->cliente = "";
            $cabCompro->detalle = $observacionCre;
            $cabCompro->debito = $montoPagar;
            $cabCompro->credito = $montoPagar;
            $cabCompro->estado = "C";
            $cabCompro->fechao = $date->Format('Y-d-m');
            $cabCompro->retencion = "N";
            $cabCompro->operador = "ADM";
            $cabCompro->modulo = "CXC";
            $cabCompro->nocuenta= "";
            $cabCompro->banco = "";
            $cabCompro->cheque = "0";
            $cabCompro->save();

            $parametroBO->secuencial = $parametroBO->secuencial + 1;
            $parametroBO->save();

            //Detalles comprobante contable
            $anioActual  = intval($date->Format('Y'));
            $cuentaCli1 = DB::table('ADMASIENTOS')
            ->where([['ASIENTO','=','PAG'],['ANIO','=',$anioActual],['TIPO','=','CLI']])
            ->first();
            $cuentaEfe1 = DB::table('ADMASIENTOS')
            ->where([['ASIENTO','=','PAG'],['ANIO','=',$anioActual],['TIPO','=','EFE']])
            ->first();
            $cuentaChq1 = DB::table('ADMASIENTOS')
            ->where([['ASIENTO','=','PAG'],['ANIO','=',$anioActual],['TIPO','=','CHQ']])
            ->first();
            
            $detCompro = new \App\ADMDETCOMPROBANTE();
            $detCompro->SECUENCIAL = $cabCompro->secuencial;
            $detCompro->LINEA = 1;
            $detCompro->CUENTA = trim($cuentaCli1->CUENTA);
            $detCompro->DETALLE = $observacionCre;
            $detCompro->DBCR = "DB";
            $detCompro->MONTO = $montoPagar;
            $detCompro->ESTADO = "C";
            $detCompro->save();

            $detCompro2 = new \App\ADMDETCOMPROBANTE();
            $detCompro2->SECUENCIAL = $cabCompro->secuencial;
            $detCompro2->LINEA = 2;
            if (trim($r->medioPago) == 'CHQ') {
                $detCompro2->CUENTA = trim($cuentaChq1->CUENTA);
            } else {
                $detCompro2->CUENTA = trim($cuentaEfe1->CUENTA);
            }
            $detCompro2->DETALLE = $observacionCre;
            $detCompro2->DBCR = "CR";
            $detCompro2->MONTO = $montoPagar;
            $detCompro2->ESTADO = "C";
            $detCompro2->save();

            //Proceso de las Facturas a pagar
            foreach ($deudas as $pos => $val) {
                $numDeuda = $val['numero'];
                $montoPagar = round($val['monto'],2);
                
                $deuda = \App\ADMDEUDA::where('SECUENCIAL','=',$numDeuda)
                ->whereIn('TIPO',['NVT','FAC'])
                ->first();
                //return response()->json($deuda);
                
                if ($deuda != null){
                        
                    //Reducción de Saldo.
                    $saldo = $deuda->SALDO;
                    $deuda->CREDITO = $deuda->CREDITO + $montoPagar;
                    $deuda->SALDO = round(($deuda->SALDO - $montoPagar),2);
                    
                    $bodegaDeuda = $deuda->BODEGA;

                    $deuda->save();
                    
                    $credito = \App\ADMCREDITO::where('SECUENCIAL','=',$numDeuda)
                    ->whereIn('TIPO',['NVT','FAC'])
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
                    $creditoLinea->SALDO = round(($saldo - $montoPagar),2);
                    if($creditoLinea->SALDO < 0){
                        $creditoLinea->SALDO = 0;
                    }
                    $creditoLinea->OPERADOR = 'ADM';
                    $creditoLinea->OBSERVACION = $observacionCre;
                    $creditoLinea->VENDEDOR = $vendedor;
                    $creditoLinea->HORA = $date->Format('H:i:s');
                    $creditoLinea->NOMBREPC = 'Servidor Laravel';
                    $creditoLinea->estafirmado = 'N';
                    $creditoLinea->ACT_SCT = 'N';
                    $creditoLinea->seccreditogen = 0;
                    $creditoLinea->save();
                    
                }else{
                    DB::rollback();
                    return response()->json(['error'=>'Deuda no encontrada: Secuencial '.$numDeuda]);
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
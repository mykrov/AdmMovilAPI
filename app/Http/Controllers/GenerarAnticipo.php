<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerarAnticipo extends Controller
{
    public function PagoAnticipo(Request $r)
    {
        //return response()->json($r);
        Log::info("request",["body"=>$r]);
        $operador = $r->operador;
        $deudas = $r->facturas;
        $tipoPago = $r->medioPago;
        $cliente = $r->cliente;
        $fechaPago = $r->fechaPago;
        $fechaChq = $r->fechaVen;
        $montoPagar = $r->total;
        $parametrov = \App\ADMPARAMETROV::first();
        $NumCre = \App\ADMTIPODOC::where('TIPO','=','PAG')->first();
        $date = Carbon::now()->subHours(5);
        $observacionReq = $r->observacion;
        
        $fcChq = Carbon::createFromFormat('Y-m-d',$fechaChq)->Format('d-m-Y');
        $fcPag = Carbon::createFromFormat('Y-m-d',$fechaPago)->Format('d-m-Y');
        $fcPagFormated = Carbon::createFromFormat('Y-m-d',$fechaPago)->Format('Y-d-m');
        //return response()->json($fcPagFormated);
        $observacionCre = "";
        $bodegaDeuda = 10;
        $secDelPago = 0;

        $clienteData = \App\Cliente::where('CODIGO',$cliente)->first();

        //para actualizar al final el seccon
        $numComproContable = 0;
        $numeroPago = 0;
        $numeroMoviBancoCIa = 0;

        DB::beginTransaction();  
        try {
             //ADMPAGO
            $cajaAbierta = DB::table('ADMCAJACOB')
            ->where([['estadocaja','=','A'],['estado','=','A'],['fechaini','<=',$fcPag],['fechafin','>=',$fcPag]])
            ->select('codigo')
            ->get();

            //Log::info("caja abierta",['caja'=>$cajaAbierta]);

            if($cajaAbierta == null or COUNT($cajaAbierta) == 0){
                return response()->json(['estado'=>'error','info'=>'NO HAY CAJA']);
            }

            $opeR = \App\ADMVENDEDOR::where('operadormovil','=',$operador)->first();
            $vendedor= '';

            if($opeR == null || trim($opeR ->CODIGO) == ''){
                $vendedor= null;
            }else{
                $vendedor = $opeR->CODIGO;
            }

            
            $operador1 = $operador;
           
            $pago = new \App\ADMPAGO();
            $pago->secuencial = $parametrov->SECUENCIAL + 1;

            $secDelPago = $pago->secuencial;

            $pago->cliente = $cliente;
            $pago->tipo = 'PAG';
            $pago->numero = $NumCre->CONTADOR + 1;

            $numeroPago = $pago->numero;

            $pago->monto = $montoPagar;
            $pago->operador = $operador1;

            if ($observacionReq == 'NA'){
                $pago->observacion = "Pago ".$tipoPago. " por ADMGO Nro:".$pago->numero;
            }else{
                $pago->observacion = $observacionReq;
            }

            $pago->numpapel = "";
            $pago->fecha = $fcPagFormated;
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
            if ($tipoPago != "EFE") {
                $detPago->banco = $r->banco;
                $detPago->cuenta = $r->cuentaChq;
                $detPago->numchq = $r->numCheque;
                $detPago->estchq = "P";
                $detPago->fechaven = $fcChq;

                $tipoOperacionN = "Cheque";

                if ($tipoPago == 'TRA'){
                    $tipoOperacionN = 'Transferencia';
                }elseif ($tipoPago == 'DEP'){
                    $tipoOperacionN = 'Deposito';
                }

                $observacionCre = $tipoOperacionN." ".$r->banco." - Cuenta No ".$r->cuentaChq."- No ".$tipoPago." ".$r->numCheque;
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
                $deudaChq->FECHAEMI = $fcPagFormated;
                $deudaChq->FECHAVEN = $fcChq;
                $deudaChq->FECHADES = $fcChq;
                $deudaChq->BANCO = trim($r->banco);
                $deudaChq->CUENTA = trim($r->cuentaChq);
                $deudaChq->NUMCHQ = trim($r->numCheque);
                $deudaChq->ESTCHQ = "P";
                $deudaChq->OPERADOR = $operador1;
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
                $creditoLinea2->FECHA = $fcPagFormated;
                $creditoLinea2->MONTO = $montoPagar;
                $creditoLinea2->SALDO = $montoPagar;
                $creditoLinea2->OPERADOR = $operador1;
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

            //Si el Pago es por Transferecia o Deposito
            if ($r->medioPago == 'DEP' || $r->medioPago == 'TRA') {
                $tipoDocccb = \App\ADMTIPODOCPROV::where('TIPO','=','CCB')->first(); 
                $paramC = \App\ADMPARAMETROC::first();
                
                
                $numeroCCB = $tipoDocccb->NUMERO + 1;
                $beneficiario =  $paramC->BENEDEFAULT;
                
                $numeroMoviBancoCIa = $numeroCCB;

                
                $movibanco = new \App\ADMMOVIBANCOCIA();                
                $movibanco->secuencial =  $secDelPago;
                $movibanco->tipomovimiento = "CRE";
                $movibanco->numeromovimiento = $numeroCCB;
                $movibanco->tipodocumento = $r->medioPago;
                $movibanco->motivo = $r->medioPago."CLI";
                $movibanco->numdocumento = $r->numCheque;
                $movibanco->banco = $r->banco;
                $movibanco->cuenta = $r->cuentaChq;
                $movibanco->fecha = $fcPagFormated;
                
                $movibanco->fechavence = $fcPagFormated;
                $movibanco->monto = $montoPagar;
                $movibanco->beneficiario = $beneficiario;
                $movibanco->numpapel = "";
                $movibanco->origen = "CXC";
                $movibanco->observacion = $observacionCre;
                $movibanco->hora = $date->Format('H:i:s');
                $movibanco->operador = $operador1;
                $movibanco->nombrepc = "Servidor Laravel";
                $movibanco->cajac = $cajaAbierta[0]->codigo;
                $movibanco->conciliado = "N";
                $movibanco->save();

                $tipoDocccb->NUMERO = $numeroCCB;
                $tipoDocccb->save();

            }


            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;
            $parametrov->save();

            //Cabecera Comprobante Contable
            $cabCompro = new \App\ADMCABCOMPROBANTE();
            $parametroBO = \App\ADMPARAMETROBO::first();

            $cabCompro->secuencial = $parametroBO->secuencial + 1;

            $numComproContable = $cabCompro->secuencial;
            $cabCompro->fecha = $fcPagFormated;
            $cabCompro->tipoComprobante = 18;
            $cabCompro->numero = -1;
            $cabCompro->cliente = trim($clienteData->RAZONSOCIAL);
            $cabCompro->detalle = $observacionCre;
            $cabCompro->debito = $montoPagar;
            $cabCompro->credito = $montoPagar;
            $cabCompro->estado = "C";
            $cabCompro->fechao = $fcPagFormated;
            $cabCompro->retencion = "N";
            $cabCompro->operador = $operador1;
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
             
            $cuentaDEP = DB::table('ADMBANCOCIA')
            ->where('CODIGO','=',trim($r->banco))
            ->first();
            
            $detCompro = new \App\ADMDETCOMPROBANTE();
            $detCompro->SECUENCIAL = $cabCompro->secuencial;
            $detCompro->LINEA = 1;
            $detCompro->CUENTA = trim($cuentaCli1->CUENTA);
            $detCompro->DETALLE = $observacionCre;
            $detCompro->DBCR = "CR";
            $detCompro->MONTO = $montoPagar;
            $detCompro->ESTADO = "C";
            $detCompro->save();

            $detCompro2 = new \App\ADMDETCOMPROBANTE();
            $detCompro2->SECUENCIAL = $cabCompro->secuencial;
            $detCompro2->LINEA = 2;

            if (trim($r->medioPago) == 'CHQ') {
                $detCompro2->CUENTA = trim($cuentaChq1->CUENTA);
            }elseif (trim($r->medioPago) == 'EFE') {
                $detCompro2->CUENTA = trim($cuentaEfe1->CUENTA);
            }elseif(trim($r->medioPago) == 'DEP' || trim($r->medioPago) == 'TRA'){ 
                $detCompro2->CUENTA = trim($cuentaDEP->cuentachq);
            }


            $detCompro2->DETALLE = $observacionCre;
            $detCompro2->DBCR = "DB";
            $detCompro2->MONTO = $montoPagar;
            $detCompro2->ESTADO = "C";
            $detCompro2->save();


            //actualizar SECCON en el ADMPAGO y ADMMoviBancoCia
            $pagoActualizar = \App\ADMPAGO::where('NUMERO','=', $numeroPago)->first();
            $pagoActualizar->seccon = $numComproContable;
            $pagoActualizar->save();
            
            if ($r->medioPago == 'DEP' || $r->medioPago == 'TRA'){

                $result = DB::table('ADMMOVIBANCOCIA')
                            ->where('secuencial',$secDelPago)
                            ->update([
                                'seccon' =>$numComproContable,
                            ]);
            }

            //CREAR DEUDA NUEVA DE TIPO ANTICIPO
            $paramV = \App\ADMPARAMETROV::first();
            $tipoDocANT = \App\ADMTIPODOC::where('TIPO','=','ANT')->first();
            $dataBodega = \App\ADMBODEGA::where('CODIGO','=',$bodegaDeuda)->first();

            

            $nDeuda = new \App\ADMDEUDA();
            $nDeuda->SECUENCIAL = $paramV->SECUENCIAL + 1; //Actualizar
            $nDeuda->BODEGA = $bodegaDeuda;
            $nDeuda->CLIENTE = $cliente;
            $nDeuda->TIPO = 'ANT';
            $nDeuda->NUMERO =  $tipoDocANT->CONTADOR + 1; //ACTUALIZAR
            $nDeuda->IVA = 0;
            $nDeuda->MONTO = $montoPagar;
            $nDeuda->CREDITO = $montoPagar;
            $nDeuda->SALDO = $montoPagar * -1;
            $nDeuda->FECHAEMI = $fcPagFormated;
            $nDeuda->FECHAVEN = $fcPagFormated;
            $nDeuda->FECHADES =  $fcPagFormated;
            $nDeuda->OPERADOR =  $operador1;
            $nDeuda->VENDEDOR = $vendedor;
            $nDeuda->OBSERVACION = $observacionCre;
            $nDeuda->HORA =  $date->Format('H:s:i');
            $nDeuda->save();

            $paramV->SECUENCIAL = $paramV->SECUENCIAL + 1;
            $paramV->save();
            $tipoDocANT = \App\ADMTIPODOC::where('TIPO','=','ANT')->first();

            $tipoDocANT->CONTADOR = $tipoDocANT->CONTADOR + 1;
            $tipoDocANT->save();


            //Crear nuevo CREDITO del ANTICIPO
            $creditoLinea = new \App\ADMCREDITO();
            $creditoLinea->SECUENCIAL = $nDeuda->SECUENCIAL;
            $creditoLinea->BODEGA = $bodegaDeuda;
            $creditoLinea->CLIENTE = $cliente;
            $creditoLinea->TIPO = 'ANT';
            $creditoLinea->NUMERO = $nDeuda->NUMERO;
            $creditoLinea->TIPOCR = 'PAG';
            $creditoLinea->NUMCRE = $pago->numero;
            $creditoLinea->SERIE = $dataBodega->SERIE;
            $creditoLinea->SECINV = 0;
            $creditoLinea->NOAUTOR = '';
            $creditoLinea->FECHA = $fcPagFormated;
            $creditoLinea->IVA = 0;
            $creditoLinea->MONTO = $montoPagar;
            $creditoLinea->SALDO = $montoPagar * -1;
            $creditoLinea->OPERADOR = $operador1;
            $creditoLinea->OBSERVACION = $nDeuda->OBSERVACION;
            $creditoLinea->VENDEDOR = $vendedor;
            $creditoLinea->HORA = $date->Format('H:i:s');
            $creditoLinea->NOMBREPC = 'Servidor Laravel';
            $creditoLinea->estafirmado = 'N';
            $creditoLinea->ACT_SCT = 'N';
            $creditoLinea->seccreditogen = 0;
            $creditoLinea->save();

            DB::commit();
            return response()->json(['estado'=>'ok','numPago'=> $pago->numero,'numAnticipo'=>$nDeuda->NUMERO]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error"=>["ADMPAGO"=>$e->getMessage()]]);
        }
    }

}

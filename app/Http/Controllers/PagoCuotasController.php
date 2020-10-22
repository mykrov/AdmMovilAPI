<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use \App\ADMPARAMETROV;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagoCuotasController extends Controller
{
    public function PagoCuota(Request $r)
    {
        $operador1 = $r->operador;
        $deudas = $r->facturas;
        $tipoPago = $r->medioPago;
        $cliente = $r->cliente;
        $fechaChq = $r->fecha;
        $montoPagar = $r->total;
        $parametrov = ADMPARAMETROV::first();
        $NumCre = \App\ADMTIPODOC::where('TIPO','=','PAG')->first();
        $date = Carbon::now()->subHours(5);
        
        $fcChq = Carbon::createFromFormat('Y-m-d',$fechaChq)->Format('d-m-Y');
        $observacionCre = "";
        $bodegaDeuda = 10;

        $secDelPago = 0;

        //Vendedor de la deuda a pagar
        $secDeuda = $deudas[0]['numero'];
        $vendedor = DB::table('ADMDEUDA')->where('SECUENCIAL',$secDeuda)
        ->select('VENDEDOR')->first();

        $clienteData = \App\ADMCLIENTE::where('CODIGO',$cliente)->first();

        //para actualizar al final el seccon
        $numComproContable = 0;
        $numeroPago = 0;
        $numeroMoviBancoCIa = 0;

        DB::beginTransaction();  
        try {
             //ADMPAGO
            $cajaAbierta = DB::table('ADMCAJACOB')->where([['estadocaja','=','A'],['estado','=','A']])
            ->select('codigo')
            ->get();

            if($cajaAbierta == null){
                return response()->json(['estado'=>'error','info'=>'NO HAY CAJA']);
            }

            //Datos del Cobrador segun operador
            $operadorData = \App\ADMOPERADOR::where('CODIGO','=',$operador1)->first();
            $cobrador = '';
            if($operadorData == null || $operadorData->COBRADOR == null || trim($operadorData->COBRADOR) == ''){
                $cobrador = 'ADM';
            }else{
                $cobrador = trim($operadorData->COBRADOR);
            }

            $pago = new \App\ADMPAGO();
            $pago->secuencial = $parametrov->SECUENCIAL + 1;

            $secDelPago = $pago->secuencial;

            $pago->cliente = $cliente;
            $pago->tipo = 'PAG';
            $pago->numero = $NumCre->CONTADOR + 1;

            $numeroPago = $pago->numero;

            $pago->monto = $montoPagar;
            $pago->operador = $operador1;
            $pago->observacion = "Pago ".$tipoPago. " por ADMGO Nro:".$pago->numero;
            $pago->numpapel = "";
            $pago->fecha = $date->Format('d-m-Y');
            $pago->vendedor = $vendedor->VENDEDOR;
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
            $detPago->vendedor = $vendedor->VENDEDOR;
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
                $deudaChq->OPERADOR = $operador1;
                $deudaChq->VENDEDOR = $vendedor->VENDEDOR;
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
                $creditoLinea2->OPERADOR = $operador1;
                $creditoLinea2->OBSERVACION = $observacionCre;
                $creditoLinea2->VENDEDOR = $vendedor->VENDEDOR;
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
                $movibanco->fecha = $date->Format('d-m-Y');
                
                $movibanco->fechavence = $fcChq;
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
            $cabCompro->fecha = $date->Format('Y-d-m');
            $cabCompro->tipoComprobante = 18;
            $cabCompro->numero = -1;
            $cabCompro->cliente = trim($clienteData->RAZONSOCIAL);
            $cabCompro->detalle = $observacionCre;
            $cabCompro->debito = $montoPagar;
            $cabCompro->credito = $montoPagar;
            $cabCompro->estado = "C";
            $cabCompro->fechao = $date->Format('Y-d-m');
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

            //Proceso de las deudas a pagar
            foreach ($deudas as $pos => $val) {
                $numDeuda = $val['numero'];
                $montoPagar = round($val['monto'],2);
                
                $deuda = \App\ADMDEUDA::where('SECUENCIAL','=',$numDeuda)
                ->whereIn('TIPO',['NVT','FAC','NDB'])
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
                    ->whereIn('TIPO',['NVT','FAC','NDB'])
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
                    $creditoLinea->OPERADOR = $operador1;
                    $creditoLinea->OBSERVACION = $observacionCre;
                    $creditoLinea->VENDEDOR = $vendedor->VENDEDOR;
                    $creditoLinea->HORA = $date->Format('H:i:s');
                    $creditoLinea->NOMBREPC = 'Servidor Laravel';
                    $creditoLinea->estafirmado = 'N';
                    $creditoLinea->ACT_SCT = 'N';
                    $creditoLinea->seccreditogen = 0;
                    $creditoLinea->save();

                    if($this->pagarCuotas($numDeuda,$montoPagar,$pago->numero,$operador1,$observacionCre)){
                        
                    }else{
                        DB::rollback();
                        return response()->json(['error'=>'Pagando Deuda en DEUDACUOTA , DEUDACUOTADET secuencial: '.$numDeuda]);
                    }
                    
                }else{
                    DB::rollback();
                    return response()->json(['error'=>'Deuda Credito no encontrada: Secuencial '.$numDeuda]);
                }  
            }
            
            DB::commit();
            return response()->json(['estado'=>'ok','numPago'=> $pago->numero]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error"=>["ADMPAGO"=>$e->getMessage()]]);
        }
    }

    public function pagarCuotas(int $secuencial, float $mon, int $pago,string $ope,string $observa)
    {
        $monto = $mon;
        $cuotas = \App\ADMDEUDACUOTA::where('SECDEUDA','=',$secuencial)
        ->where('SALDO','>',0.001)
        ->orderBy('NUMCUOTA','ASC')
        ->get();

        $fecha = Carbon::now()->subHours(5);

        DB::beginTransaction();
        try {

            foreach ($cuotas as $index => $val){
                
                $saldoCuota = $val['SALDO'];
                $numCuota = $val['NUMCUOTA'];
    
                if ($saldoCuota < $monto) {
                    # Pago de cuota completa.
                    $deCuo = \App\ADMDEUDACUOTA::where('SECDEUDA','=',$secuencial)
                    ->where('NUMCUOTA','=',$numCuota)
                    ->first();
                   
                    $monto = round($monto - $deCuo->SALDO,2);
                    
                    //Guardado alternativo por problemas con PK compuestos;
                    $result = DB::table('ADMDEUDACUOTA')
                    ->where('SECDEUDA',$secuencial)
                    ->where ('NUMCUOTA',$numCuota)
                    ->update([
                        'SALDO' => 0,
                        'CREDITO'=>$deCuo->MONTO,
                        'FECHACANCELACUOTA'=>$fecha->format('Y-d-m'),
                        'NUMPAGO'=>$pago
                    ]);

                    $admdeuda = \App\ADMDEUDA::where('SECUENCIAL',$secuencial)
                    ->first();

                    $deCuoDet = new \App\ADMDEUDACUOTADET();
                    $deCuoDet->SECDEUDA = $secuencial;
                    $deCuoDet->SECINV = $admdeuda->SECINV;
                    $deCuoDet->NUMCUOTA = $numCuota;
                    $deCuoDet->VALORCUOTA = $deCuo->MONTO;
                    $deCuoDet->MONTO = $deCuo->SALDO;
                    $deCuoDet->SALDO = 0;
                    $deCuoDet->NUMPAGO = $pago;
                    $deCuoDet->FECHACANCELA = $fecha->format('Y-d-m');
                    $deCuoDet->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s',$deCuo->FECHAVEN)->Format('Y-d-m');
                    $deCuoDet->OBSERVACION = $observa;
                    $deCuoDet->OPERADOR = $ope;
                    $deCuoDet->MAQUINA = "SERVER LARAVEL";
                    $deCuoDet->HORA = $fecha->format('H:i:s');
                    $deCuoDet->save();

                } else {
                    # Abono a Cuota.
                    $deCuo = \App\ADMDEUDACUOTA::where('SECDEUDA','=',$secuencial)
                    ->where('NUMCUOTA','=',$numCuota)
                    ->first();
                    
                    //Guardado alternativo por problemas con PK compuestos;
                    $saldoFinal =  $deCuo->SALDO - $monto;
                    $creditoFinal = $deCuo->CREDITO + $monto;
                    $result = DB::table('ADMDEUDACUOTA')
                            ->where('SECDEUDA',$secuencial)
                            ->where ('NUMCUOTA',$numCuota)
                            ->update([
                                'SALDO' => round($saldoFinal,2),
                                'CREDITO' => round($creditoFinal,2),
                                'FECHACANCELACUOTA'=>$fecha->format('Y-d-m'),
                                'NUMPAGO'=>$pago
                            ]);

                    $admdeuda = \App\ADMDEUDA::where('SECUENCIAL',$secuencial)
                    ->first();

                    $deCuoDet = new \App\ADMDEUDACUOTADET();
                    $deCuoDet->SECDEUDA = $secuencial;
                    $deCuoDet->SECINV = $admdeuda->SECINV;
                    $deCuoDet->NUMCUOTA = $numCuota; 
                    $deCuoDet->VALORCUOTA = $deCuo->MONTO;
                    $deCuoDet->MONTO = $monto;
                    $deCuoDet->SALDO = round($deCuo->SALDO - $monto,2);
                    $deCuoDet->NUMPAGO = $pago;
                    $deCuoDet->FECHACANCELA = $fecha->format('Y-d-m');
                    $deCuoDet->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s',$deCuo->FECHAVEN)->Format('Y-d-m');
                    $deCuoDet->OBSERVACION = $observa;
                    $deCuoDet->OPERADOR = $ope;
                    $deCuoDet->MAQUINA = "SERVER LARAVEL";
                    $deCuoDet->HORA = $fecha->format('H:i:s');
                    $deCuoDet->save();
                    break;
                } 
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error pagoCuota:",['Mensaje'=> $e->getMessage()]);
            return false;
        }
    }
}

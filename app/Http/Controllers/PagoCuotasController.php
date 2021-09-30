<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use \App\ADMPARAMETROV;
use \App\ADMPUNTOASIENTOS;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagoCuotasController extends Controller
{
    public function PagoCuota(Request $r)
    {
        
        Log::info("Peticion de PagoCuota",["Request"=>$r]);
        $operador1 = $r->operador;
        $deudas = $r->facturas;
        $tipoPago = $r->medioPago;
        $cliente = $r->cliente;
        $fechaChq = $r->fechaVen;
        $fechaPago = $r->fechaPago;
        $montoPagar = $r->total;
        $interes = $r->interes;
        $parametrov = ADMPARAMETROV::first();
        $NumCre = \App\ADMTIPODOC::where('TIPO','=','PAG')->first();
        $date = Carbon::now()->subHours(5);
        $numeroGuia = $r->numguia;
        $fcChq = Carbon::createFromFormat('Y-m-d',$fechaChq)->Format('d-m-Y');
        $fcPag = Carbon::createFromFormat('Y-m-d',$fechaPago)->Format('d-m-Y');
       
        $observacionCre = "";
        $bodegaDeuda = 10;
        $secDelPago = 0;

        $secDeuda = $deudas[0]['numero'];

        $coutasDetPag = $deudas[0]['cuotas'];

        //return response()->json($coutasDetPag);
        
        $serieDeuda = DB::table('ADMDEUDA')->where('SECUENCIAL',$secDeuda)
        ->first();

        //return response()->json($deudas[0]['numero']);

        $codigoPunto = substr($serieDeuda->SERIE, 0, 3);

        $cajaDeuda = $serieDeuda->CAJAC;

        $vendedor = DB::table('ADMDEUDA')->where('SECUENCIAL',$secDeuda)
        ->select('VENDEDOR')->first();
        
        //Log::info("Deuda a Procesar:",['deudas'=>$deudas]);
        //Log::info("Vendedor de la deuda:",['vendedor'=>$vendedor]);

        $clienteData = \App\Cliente::where('CODIGO',$cliente)->first();

        //para actualizar al final el seccon
        $numComproContable = 0;
        $numeroPago = 0;
        $numeroMoviBancoCIa = 0;

        DB::beginTransaction();  
        try {

            //ADMPAGO
            $cajaAbierta = array(['codigo'=> null,'DIRECCION'=>'MatrizDefault']);

            if(strlen($operador1 == 3)){
                Log::info('Es un código de operador.' .$operador1);
            }

            //Datos del Cobrador segun operador
            $operadorData = \App\ADMOPERADOR::where('CODIGO','=',$operador1)->first();
            $cobrador = '';

            Log::info('operadorData',['data'=> $operadorData]);
           
            if($operadorData == null || $operadorData->COBRADOR == null || trim($operadorData->COBRADOR) == ''){
                $cobrador = 'ADM';
                
                $cajaAbierta = DB::table('ADMCAJACOB')->where([['estadocaja','=','A'],['estado','=','A'],['codigo','=',$operadorData->caja]])
                ->select('codigo','DIRECCION')
                ->get();

                Log::info('objeto de caja',['caja'=>$cajaAbierta]);

                if($cajaAbierta == null or COUNT($cajaAbierta) == 0){
                    return response()->json(['estado'=>'error','info'=>'NO HAY CAJA']);
                }

                if($cajaAbierta[0]->codigo != $operadorData->caja){
                    return response()->json(['estado'=>'error','info'=>'Caja'. $operadorData->caja .' de operador cerrada']);
                }
            }else{
                
                $cobrador = trim($operadorData->COBRADOR);
                
                if(trim($operadorData->COBRADOR) == ""){
                    return response()->json(['estado'=>'error','info'=>'Operador no tiene asignado Cobrador.']);
                }
                Log::info('Cobrador',['cobrador'=>$cobrador]);
                
                $cajaAbierta = DB::table('ADMCAJACOB')->where([['estadocaja','=','A'],['estado','=','A'],['codigo','=',$operadorData->caja]])
                ->select('codigo','DIRECCION')
                ->get();
                
                Log::info('objeto de caja',['caja'=>$cajaAbierta]);

                if($cajaAbierta == null or COUNT($cajaAbierta) == 0){
                    return response()->json(['estado'=>'error','info'=>'NO HAY CAJA']);
                }

                if($cajaAbierta[0]->codigo != $operadorData->caja){
                    return response()->json(['estado'=>'error','info'=>'Caja'. $operadorData->caja .' de operador cerrada']);
                }
            }

            $pago = new \App\ADMPAGO();
            $pago->secuencial = $parametrov->SECUENCIAL + 1;

            $secDelPago = $pago->secuencial;

            $pago->cliente = $cliente;
            $pago->tipo = 'PAG';
            $pago->numero = $NumCre->CONTADOR + 1;

            $numeroPago = $pago->numero;

            $pago->monto = $montoPagar;

            if($interes > 0){
                $pago->monto = $montoPagar - $interes;
            }

            $pago->operador = $operador1;
            $pago->observacion = "Pago ".$tipoPago. " por ADMGO Nro:".$pago->numero;
            $pago->numpapel = "";
            $pago->fecha = $fcPag;
            $pago->vendedor = $vendedor->VENDEDOR;
            $pago->oripago = "C";
            $pago->hora =  $date->Format('H:i:s');
            $pago->nombrepc = 'Servidor Laravel';
            $pago->cajac = $cajaAbierta[0]->codigo;
            $pago->fechaeli = "";
            $pago->horaeli = "";
            $pago->maquinaeli = "";
            $pago->operadoreli = "";
           
            

            $observacionCre = $pago->observacion;
            Log::info('Pasa el Pago en la caja: '.$cajaAbierta[0]->codigo);
            //ADMDETPAGO
            $detPago = new \App\ADMDETPAGO();
            $detPago->secuencial = $pago->secuencial;
            $detPago->tipo = $pago->tipo;
            $detPago->numero = $pago->numero;
            $detPago->tipopag = $tipoPago;
            $detPago->monto = $montoPagar;

            if($interes > 0){
                $detPago->monto = $montoPagar + $interes;
            }

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
                $deudaChq->FECHAEMI = $fcPag;
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
                $creditoLinea2->FECHA = $fcPag;
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
                $movibanco->fecha = $fcPag;
                
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
            $cabCompro->fecha = $fcPag;
            $cabCompro->tipoComprobante = 18;
            $cabCompro->numero = -1;
            $cabCompro->cliente = trim($clienteData->RAZONSOCIAL);
            $cabCompro->detalle = $observacionCre;
            $cabCompro->debito = $montoPagar;
            $cabCompro->credito = $montoPagar;
            $cabCompro->estado = "C";
            $cabCompro->fechao = $fcPag;
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
            $cuentaCli1 = DB::table('ADMPUNTOASIENTOS')
            ->where([['ASIENTO','=','PAG'],['ANIO','=',$anioActual],['TIPO','=','CLI'],['PUNTO','=',$codigoPunto]])
            ->first();
            $cuentaEfe1 = DB::table('ADMPUNTOASIENTOS')
            ->where([['ASIENTO','=','PAG'],['ANIO','=',$anioActual],['TIPO','=','EFE'],['PUNTO','=',$codigoPunto]])
            ->first();
            $cuentaChq1 = DB::table('ADMPUNTOASIENTOS')
            ->where([['ASIENTO','=','PAG'],['ANIO','=',$anioActual],['TIPO','=','CHQ'],['PUNTO','=',$codigoPunto]])
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

            //Generar Nota de Debito cuando existe interes de mora.
            if($interes > 0){
                
                foreach($coutasDetPag as $key => $val){
                    $montoInteres = $val['interes'];
                    $numCuotaPagar  = $val['numero'];
                    $observacionNDB = "NDB por Interes de cuota Nº ".$numCuotaPagar.",Sec.Deuda:".$secDeuda;
                    
                    if($montoInteres > 0){
                        $tarea = $this->GenerarNDB($montoInteres,$bodegaDeuda,$fcPag,$operador1,$observacionNDB,$vendedor->VENDEDOR,$date,$numeroPago,$clienteData->CODIGO);
                    
                        if($tarea){
                            Log::info("NDB generada por monto: " .$montoInteres." de la cuota Nº ".$numCuotaPagar." de Sec.Deuda ".$secDeuda);
                        }else{
                            DB::rollback();
                            return response()->json(['error'=>'Error en proceso de Creación de Nota de Debito.']);
                        }
                    }
                } 
            }

            //Proceso de las deudas a pagar
            foreach ($deudas as $pos => $val) {
                $numDeuda = $val['numero'];
                $montoPagar = round($val['monto'],2);
                $montoSinInteres = $montoPagar - $interes;
                
                $deuda = \App\ADMDEUDA::where('SECUENCIAL','=',$numDeuda)
                ->whereIn('TIPO',['NVT','FAC','NDB'])
                ->first();
                //return response()->json($deuda);
                
                if ($deuda != null){
                        
                    //Reducción de Saldo.
                    $saldo = $deuda->SALDO;
                    $deuda->CREDITO = $deuda->CREDITO +  $montoSinInteres ;
                    $deuda->SALDO = round(($deuda->SALDO - $montoSinInteres),2);
                    
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
                    $creditoLinea->FECHA = Carbon::createFromFormat('d-m-Y',$fcPag)->Format('Y-d-m');
                    $creditoLinea->IVA = 0;
                    $creditoLinea->MONTO =  $montoSinInteres;
                    $creditoLinea->SALDO = round(($saldo -  $montoSinInteres),2);
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

                    
                    if($this->pagarCuotasInteres($coutasDetPag,$numDeuda,$pago->numero,$operador1,$observacionCre,$fechaPago) && $this->CambioEstado($numeroGuia,$numDeuda, $montoSinInteres,$tipoPago)){
                        Log::info("Proceso de Pago y Cambio de estado Exitoso.");
                    }else{
                        DB::rollback();
                        return response()->json(['error'=>'Error en proceso de pago y cambio de estado, Secuencial: '.$numDeuda.' de la guia: '. $numeroGuia]);
                    }
                    
                }else{
                    DB::rollback();
                    return response()->json(['error'=>'Deuda Credito no encontrada: Secuencial '.$numDeuda]);
                }  
            }
            
            DB::commit();
            Log::info("Pago completo estado OK, numPago=>".$pago->numero);
            return response()->json(['estado'=>'ok','numPago'=> $pago->numero,'dirCaja'=>$cajaAbierta[0]->DIRECCION]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error"=>["ADMPAGO"=>$e->getMessage()]]);
        }
    }

    public function pagarCuotas(int $secuencial, float $mon, int $pago,string $ope,string $observa, string $fechaPago)
    {
        $monto = $mon;
        $cuotas = \App\ADMDEUDACUOTA::where('SECDEUDA','=',$secuencial)
        ->where('SALDO','>',0.001)
        ->orderBy('NUMCUOTA','ASC')
        ->get();

        $fecha = Carbon::createFromFormat('Y-m-d',$fechaPago)->Format('d-m-Y');

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
                        'FECHACANCELACUOTA'=>Carbon::createFromFormat('d-m-Y',$fecha)->Format('Y-d-m'),
                        'NUMPAGO'=>$pago
                    ]);

                    $admdeuda = \App\ADMDEUDA::where('SECUENCIAL',$secuencial)
                    ->first();

                    $deCuoDet = new \App\ADMDEUDACUOTADET();
                    $deCuoDet->SECDEUDA = $secuencial;
                    $deCuoDet->SECINV = $admdeuda->SECINV;
                    $deCuoDet->NUMCUOTA = $numCuota;
                    $deCuoDet->VALORCUOTA = round($deCuo->MONTO,2);
                    $deCuoDet->MONTO = round($deCuo->SALDO,2);
                    $deCuoDet->SALDO = 0;
                    $deCuoDet->NUMPAGO = $pago;
                    $deCuoDet->FECHACANCELA = Carbon::createFromFormat('d-m-Y',$fecha)->Format('Y-d-m');
                    $deCuoDet->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s',$deCuo->FECHAVEN)->Format('Y-d-m');
                    $deCuoDet->OBSERVACION = $observa;
                    $deCuoDet->OPERADOR = $ope;
                    $deCuoDet->MAQUINA = "SERVER LARAVEL";
                    $deCuoDet->HORA = Carbon::now()->format('H:i:s');
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
                                'FECHACANCELACUOTA'=> Carbon::createFromFormat('d-m-Y',$fecha)->Format('Y-d-m'),
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
                    $deCuoDet->FECHACANCELA = Carbon::createFromFormat('d-m-Y',$fecha)->Format('Y-d-m');
                    $deCuoDet->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s',$deCuo->FECHAVEN)->Format('Y-d-m');
                    $deCuoDet->OBSERVACION = $observa;
                    $deCuoDet->OPERADOR = $ope;
                    $deCuoDet->MAQUINA = "SERVER LARAVEL";
                    $deCuoDet->HORA = Carbon::now()->format('H:i:s');
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

    public function CambioEstado(int $Numguia,int $secuencial,float $monto,string $tipopago){
        //Log::info("CambioEstado",["Guia"=>$Numguia,"sec"=>$secuencial]);
        DB::beginTransaction();
        try{
            $DetGuiaCobro = DB::table('ADMDETGUIACOB')
            ->where('SECUENCIAL',$secuencial)
            ->where('NUMGUIA',$Numguia)
            ->first();

            if($DetGuiaCobro != NULL or COUNT($DetGuiaCobro) == 0){
                //Log::info("entra en el DETGUIACOB valido");
                $chq = 0;
                $efect = 0;
                $otro = 0;

                if ($tipopago == 'EFE') {
                    $efect = $monto;
                }elseif($tipopago == 'CHQ') {
                    $chq = $monto;
                }else{
                    $otro = $monto;
                }

                if (round($DetGuiaCobro->SALDO - $monto,2) <= 0) {
                    $result = DB::table('ADMDETGUIACOB')
                    ->where('SECUENCIAL',$secuencial)
                    ->where ('NUMGUIA',$Numguia)
                    ->update([
                        'ESTADO' => 'L',
                        'SALDO' => 0,
                        'VALORULTPAG' => $monto,
                        'EFECTIVO' => round($DetGuiaCobro->EFECTIVO + $efect,2),
                        'CHEQUE' => round($DetGuiaCobro->CHEQUE + $chq,2),
                        'OTRO' => round($DetGuiaCobro->OTRO + $otro,2),
                        'FECULTPAG'=> Carbon::now()->format('Y-d-m')
                    ]);
                    //Log::info("valor del result en el update 1 CAMBIOESTADO",['result'=>$result]);
                    DB::commit();
                    return true;
                } else {
                    $result = DB::table('ADMDETGUIACOB')
                    ->where('SECUENCIAL',$secuencial)
                    ->where ('NUMGUIA',$Numguia)
                    ->update([
                        'SALDO' => round($DetGuiaCobro->SALDO - $monto,2),
                        'VALORULTPAG' => $monto,
                        'EFECTIVO' => round($DetGuiaCobro->EFECTIVO + $efect,2),
                        'CHEQUE' => round($DetGuiaCobro->CHEQUE + $chq,2),
                        'OTRO' => round($DetGuiaCobro->OTRO + $otro,2),
                        'FECULTPAG'=> Carbon::now()->format('Y-d-m')
                    ]);
                    //Log::info("valor del result en el update 2 CAMBIOESTADO",['result'=>$result]);
                    DB::commit();
                    return true;
                }

            }else{
                DB::rollback();
                Log::error("Error DETGUIACOB no encontrada");
                return false;
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error Cambio de Estado DETGUIACOB:",['Mensaje'=> $e->getMessage()]);
            return false;
        }          
    }

    public function GenerarNDB($interes,$bodegaDeuda,$fcPag,$operador1,$observa,$vende,$date,$numpago,$cliente)
    {
        DB::beginTransaction();
        try {

            $parametrov = ADMPARAMETROV::first();                        
            $numeroNDB =  \App\ADMTIPODOC::where('TIPO','=','NDB')->first();
        
            $deudaNDB = new \App\ADMDEUDA();
            $deudaNDB->SECUENCIAL = $parametrov->SECUENCIAL + 1;
            $deudaNDB->BODEGA = $bodegaDeuda;
            $deudaNDB->CLIENTE = $cliente;
            $deudaNDB->TIPO = "NDB";
            $deudaNDB->NUMERO = $numeroNDB->CONTADOR + 1;
            $deudaNDB->IVA = 0;
            $deudaNDB->MONTO = $interes;
            $deudaNDB->CREDITO = $interes;
            $deudaNDB->SALDO = 0;
            $deudaNDB->FECHAEMI = $fcPag;
            $deudaNDB->FECHAVEN = $fcPag;
            $deudaNDB->FECHADES = $fcPag;
            $deudaNDB->BANCO = '';
            $deudaNDB->CUENTA = '';
            $deudaNDB->NUMCHQ = '';
            $deudaNDB->ESTCHQ = '';
            $deudaNDB->OPERADOR = $operador1;
            $deudaNDB->VENDEDOR = $vende;
            $deudaNDB->OBSERVACION = $observa;
            $deudaNDB->NUMAUTO = "";
            $deudaNDB->BODEGAFAC = 0;
            $deudaNDB->SERIEFAC = "";
            $deudaNDB->NUMEROFAC = 0;
            $deudaNDB->ACT_SCT = "N";
            $deudaNDB->montodocumento = 0;
            $deudaNDB->tipoventa = "";
            $deudaNDB->mesescredito = 0;
            $deudaNDB->tipopago = "";
            $deudaNDB->usuarioeli = "";
            $deudaNDB->EWEB = "N";
            $deudaNDB->save();
            Log::info("Graba Nota de Debito - DEUDA por $".$interes);

            $creditoLinea2 = new \App\ADMCREDITO();
            $creditoLinea2->SECUENCIAL = $deudaNDB->SECUENCIAL;
            $creditoLinea2->SECINV = $deudaNDB->SECUENCIAL;
            $creditoLinea2->BODEGA = $deudaNDB->BODEGA;
            $creditoLinea2->CLIENTE = $deudaNDB->CLIENTE;
            $creditoLinea2->TIPO = $deudaNDB->TIPO;
            $creditoLinea2->NUMERO = $deudaNDB->NUMERO;
            $creditoLinea2->NUMCRE = null;
            $creditoLinea2->FECHA = $fcPag;
            $creditoLinea2->MONTO = $interes;
            $creditoLinea2->SALDO = $interes;
            $creditoLinea2->OPERADOR = $operador1;
            $creditoLinea2->OBSERVACION = $observa;
            $creditoLinea2->VENDEDOR = $deudaNDB->VENDEDOR;
            $creditoLinea2->HORA = $date->Format('H:i:s');
            $creditoLinea2->NOMBREPC = 'Servidor Laravel';
            $creditoLinea2->estafirmado = 'N';
            $creditoLinea2->ACT_SCT = 'N';
            $creditoLinea2->seccreditogen = 0;
            $creditoLinea2->save();

            $creditoLinea3 = new \App\ADMCREDITO();
            $creditoLinea3->SECUENCIAL = $deudaNDB->SECUENCIAL + 1;
            $creditoLinea3->SECINV = $deudaNDB->SECUENCIAL;
            $creditoLinea3->BODEGA = $deudaNDB->BODEGA;
            $creditoLinea3->CLIENTE = $deudaNDB->CLIENTE;
            $creditoLinea3->TIPO = "NDB";
            $creditoLinea3->NUMERO = $deudaNDB->NUMERO;
            $creditoLinea3->TIPOCR = "PAG";
            $creditoLinea3->NUMCRE = $numpago;
            $creditoLinea3->FECHA = $fcPag;
            $creditoLinea3->MONTO = $interes;
            $creditoLinea3->SALDO = 0;
            $creditoLinea3->OPERADOR = $operador1;
            $creditoLinea3->OBSERVACION = $observa;
            $creditoLinea3->VENDEDOR = $deudaNDB->VENDEDOR;
            $creditoLinea3->HORA = $date->Format('H:i:s');
            $creditoLinea3->NOMBREPC = 'Servidor Laravel';
            $creditoLinea3->estafirmado = 'N';
            $creditoLinea3->ACT_SCT = 'N';
            $creditoLinea3->seccreditogen = 0;
            $creditoLinea3->save();

            $numeroNDB->CONTADOR = $numeroNDB->CONTADOR + 1;
            $numeroNDB->save();

            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 2;
            $parametrov->save();

            DB::commit();
            Log::info("Graba Nota debito - CREDITO por $".$interes);
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error Generando Nota de Debito por $".$interes,['Mensaje'=> $e->getMessage()]);
            return false;
        }
    }

    public function pagarCuotasInteres(array $deudaCuotasNew ,int $secuencial, int $pago,string $ope,string $observa, string $fechaPago){
        Log::info("Entra a pagar Cuotas con interes.");
        DB::beginTransaction();
        try {
            foreach($deudaCuotasNew as $key => $value){
                Log::info("cuotas a pagar.",["json"=>$deudaCuotasNew]);
                $numCuotaPagar  = $value['numero'];
                $monto = $value['monto'];
                $fecha = Carbon::createFromFormat('Y-m-d',$fechaPago)->Format('d-m-Y');

                $cuota = \App\ADMDEUDACUOTA::where('SECDEUDA','=',$secuencial)
                ->where('SALDO','>',0.001)
                ->where('NUMCUOTA','=',$numCuotaPagar)
                ->first();
    
                if($cuota == null){
                    DB::rollback();
                    Log::error("Error PagoCuotaInteres: intentando pagar una cuota no valida. Cuota:".$numCuotaPagar." sec Deuda:".$secuencial);
                    return false;
                }
                    
                $saldoCuota = $cuota->SALDO;
                $numCuota = $numCuotaPagar;
                
                if ($saldoCuota <= $monto) {
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
                        'FECHACANCELACUOTA'=>Carbon::createFromFormat('d-m-Y',$fecha)->Format('Y-d-m'),
                        'NUMPAGO'=>$pago
                    ]);
    
                    $admdeuda = \App\ADMDEUDA::where('SECUENCIAL',$secuencial)
                    ->first();
    
                    $deCuoDet = new \App\ADMDEUDACUOTADET();
                    $deCuoDet->SECDEUDA = $secuencial;
                    $deCuoDet->SECINV = $admdeuda->SECINV;
                    $deCuoDet->NUMCUOTA = $numCuota;
                    $deCuoDet->VALORCUOTA = round($deCuo->MONTO,2);
                    $deCuoDet->MONTO = round($deCuo->SALDO,2);
                    $deCuoDet->SALDO = 0;
                    $deCuoDet->NUMPAGO = $pago;
                    $deCuoDet->FECHACANCELA = Carbon::createFromFormat('d-m-Y',$fecha)->Format('Y-d-m');
                    $deCuoDet->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s',$deCuo->FECHAVEN)->Format('Y-d-m');
                    $deCuoDet->OBSERVACION = $observa;
                    $deCuoDet->OPERADOR = $ope;
                    $deCuoDet->MAQUINA = "SERVER LARAVEL";
                    $deCuoDet->HORA = Carbon::now()->format('H:i:s');
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
                                'FECHACANCELACUOTA'=> Carbon::createFromFormat('d-m-Y',$fecha)->Format('Y-d-m'),
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
                    $deCuoDet->FECHACANCELA = Carbon::createFromFormat('d-m-Y',$fecha)->Format('Y-d-m');
                    $deCuoDet->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s',$deCuo->FECHAVEN)->Format('Y-d-m');
                    $deCuoDet->OBSERVACION = $observa;
                    $deCuoDet->OPERADOR = $ope;
                    $deCuoDet->MAQUINA = "SERVER LARAVEL";
                    $deCuoDet->HORA = Carbon::now()->format('H:i:s');
                    $deCuoDet->save();
                    
                } // end If         
            } //end Foreach.
            
            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error pagoCuota:",['Mensaje'=> $e->getMessage()]);
            return false;
        } //end Try
    }//end Function
}

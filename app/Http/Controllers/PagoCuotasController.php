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
        Log::info("---------------------------------------------");
        Log::info("Peticion de PagoCuota", ["Request" => $r]);
        $operador1 = $r->operador;
        $deudas = $r->facturas;
        $tipoPago = $r->medioPago;
        $cliente = $r->cliente;
        $fechaChq = $r->fechaVen;
        $fechaPago = $r->fechaPago;
        $montoPagar = $r->total;
        $interes = round($r->interes, 2);
        $montoPagarT = $r->total;
        $interesT = round($r->interes, 2);
        $parametrov = ADMPARAMETROV::first();
        $NumCre = \App\ADMTIPODOC::where('TIPO', '=', 'PAG')->first();
        $date = Carbon::now()->subHours(5);
        $numeroGuia = $r->numguia;
        $fcChq = Carbon::createFromFormat('Y-m-d', $fechaChq)->Format('d-m-Y');
        $fcPag = Carbon::createFromFormat('Y-m-d', $fechaPago)->Format('d-m-Y');
        $obserRequest = $r->observacion;

        $observacionCre = "";
        $bodegaDeuda = 10;
        $secDelPago = 0;

        $secDeuda = $deudas[0]['numero'];

        $coutasDetPag = $deudas[0]['cuotas'];

        // if(COUNT($coutasDetPag) == 0){
        //     Log::info("La petición no tiene Detalles de las Cuotas a pagar.");
        //     return response()->json("La petición no tiene Detalles de las Cuotas a pagar.");
        // }

        //return response()->json($coutasDetPag);
        // $operador1 = 'CLA';
        $serieDeuda = DB::table('ADMDEUDA')->where('SECUENCIAL', $secDeuda)
            ->first();

        //return response()->json($deudas[0]['numero']);

        $codigoPunto = substr($serieDeuda->SERIE, 0, 3);

        $cajaDeuda = $serieDeuda->CAJAC;

        $vendedor = DB::table('ADMDEUDA')->where('SECUENCIAL', $secDeuda)
            ->select('VENDEDOR')->first();

        //Log::info("Deuda a Procesar:",['deudas'=>$deudas]);
        //Log::info("Vendedor de la deuda:",['vendedor'=>$vendedor]);

        $clienteData = \App\Cliente::where('CODIGO', $cliente)->first();

        //para actualizar al final el seccon
        $numComproContable = 0;
        $numeroPago = 0;
        $numeroMoviBancoCIa = 0;

        DB::beginTransaction();
        try {

            //ADMPAGO
            $cajaAbierta = array(['codigo' => null, 'DIRECCION' => 'MatrizDefault']);

            if (strlen($operador1 == 3)) {
                Log::info('Es un código de operador.' . $operador1);
            }

            //Datos del Cobrador segun operador
            $operadorData = \App\ADMOPERADOR::where('CODIGO', '=', $operador1)->first();
            $cobrador = '';

            Log::info('operadorData', ['data' => $operadorData]);

            if ($operadorData == null || $operadorData->COBRADOR == null || trim($operadorData->COBRADOR) == '') {
                $cobrador = 'ADM';

                $cajaAbierta = DB::table('ADMCAJACOB')->where([
                    ['estadocaja', '=', 'A'],
                    ['estado', '=', 'A'],
                    ['fechaini', '<=', $fcPag],
                    ['fechafin', '>=', $fcPag],
                    ['codigo', '=', $operadorData->caja]
                ])
                    ->select('codigo', 'DIRECCION')
                    ->get();

                Log::info('objeto de caja', ['caja' => $cajaAbierta]);

                if ($cajaAbierta == null or COUNT($cajaAbierta) == 0) {
                    return response()->json(['estado' => 'error', 'info' => 'Caja Cerrada, la caja ' . $operadorData->caja . ' no puede recibir pagos con fecha ' . $fcPag]);
                }

                if ($cajaAbierta[0]->codigo != $operadorData->caja) {
                    return response()->json(['estado' => 'error', 'info' => 'Caja' . $operadorData->caja . ' del operador cerrada']);
                }
            } else {

                $cobrador = trim($operadorData->COBRADOR);

                if (trim($operadorData->COBRADOR) == "") {
                    return response()->json(['estado' => 'error', 'info' => 'Operador no tiene asignado Cobrador.']);
                }
                Log::info('Cobrador', ['cobrador' => $cobrador]);

                $cajaAbierta = DB::table('ADMCAJACOB')->where([
                    ['estadocaja', '=', 'A'],
                    ['estado', '=', 'A'],
                    ['fechaini', '<=', $fcPag],
                    ['fechafin', '>=', $fcPag],
                    ['codigo', '=', $operadorData->caja]
                ])
                    ->select('codigo', 'DIRECCION')
                    ->get();

                Log::info('objeto de caja', ['caja' => $cajaAbierta]);

                if ($cajaAbierta == null or COUNT($cajaAbierta) == 0) {
                    return response()->json(['estado' => 'error', 'info' => 'Caja Cerrada, la caja ' . $operadorData->caja . ' no puede recibir pagos con fecha ' . $fcPag]);
                }

                if ($cajaAbierta[0]->codigo != $operadorData->caja) {
                    return response()->json(['estado' => 'error', 'info' => 'Caja' . $operadorData->caja . ' de operador cerrada']);
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

            //17/03/2022
            // if($interes > 0){
            //     $pago->monto = $montoPagar - $interes;
            // }

            $pago->operador = $operador1;

            if (trim($obserRequest) == "NA" ||  $obserRequest == null) {
                $pago->observacion = "Pago " . $tipoPago . " por ADMGO Nro:" . $pago->numero;
            } else {
                $pago->observacion = trim($obserRequest);
            }


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
            $pago->save();


            $observacionCre = $pago->observacion;
            Log::info('Pasa el Pago en la caja: ' . $cajaAbierta[0]->codigo);
            //ADMDETPAGO
            $detPago = new \App\ADMDETPAGO();
            $detPago->secuencial = $pago->secuencial;
            $detPago->tipo = $pago->tipo;
            $detPago->numero = $pago->numero;
            $detPago->tipopag = $tipoPago;
            $detPago->monto = $montoPagar;

            // 17/03/2022
            // if($interes > 0){
            //     $detPago->monto = $montoPagar - $interes;
            // }

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

                if ($tipoPago == 'TRA') {
                    $tipoOperacionN = 'Transferencia';
                } elseif ($tipoPago == 'DEP') {
                    $tipoOperacionN = 'Deposito';
                }

                $observacionCre = $tipoOperacionN . " " . $r->banco . " - Cuenta No " . $r->cuentaChq . "- No " . $tipoPago . " " . $r->numCheque;
            }

            $detPago->vendedor = $vendedor->VENDEDOR;
            $detPago->intregrado = "N";
            $detPago->save();

            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;

            $NumCre->CONTADOR = $NumCre->CONTADOR + 1;
            $NumCre->save();

            //DEUDA y CREDITO si es Cheque.
            if ($r->medioPago == 'CHQ') {

                $numeroCHP =  \App\ADMTIPODOC::where('TIPO', '=', 'CHP')->first();
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

                $detPago = \App\ADMDETPAGO::where('secuencial', '=', $detPago->secuencial)
                    ->where('numero', '=', $detPago->numero)->first();

                $detPago->docrel = 'CHP';
                $detPago->numerorel = $deudaChq->NUMERO;
                $detPago->save();
            }

            //Si el Pago es por Transferecia o Deposito
            if ($r->medioPago == 'DEP' || $r->medioPago == 'TRA') {
                $tipoDocccb = \App\ADMTIPODOCPROV::where('TIPO', '=', 'CCB')->first();
                $paramC = \App\ADMPARAMETROC::first();

                $numeroCCB = $tipoDocccb->NUMERO + 1;
                $beneficiario =  $paramC->BENEDEFAULT;

                Log::info("Número de pago con DEP o TRA " . $numeroCCB);

                $numeroMoviBancoCIa = $numeroCCB;

                $movibanco = new \App\ADMMOVIBANCOCIA();
                $movibanco->secuencial =  $secDelPago;
                $movibanco->tipomovimiento = "CRE";
                $movibanco->numeromovimiento = $numeroCCB;
                $movibanco->tipodocumento = $r->medioPago;
                $movibanco->motivo = $r->medioPago . "CLI";
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
                Log::info("Numero de Movibancocia =>", ['Objeto' => $movibanco]);
                $tipoDocccb->NUMERO = $numeroCCB;
                $tipoDocccb->save();
            }

            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;
            $parametrov->save();

            //Cabecera Comprobante Contable
            $cabCompro = new \App\ADMCABCOMPROBANTE();
            $parametroBO = \App\ADMPARAMETROBO::first();

            $cabCompro->secuencial = $parametroBO->secuencial + 1;
            // Log::info("Se actualiza secuencial de PARAMBO");

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
            $cabCompro->nocuenta = "";
            $cabCompro->banco = "";
            $cabCompro->cheque = "0";
            $cabCompro->save();

            //Log::info(["cabcomprobanteContable"=>$cabCompro]);

            $parametroBO->secuencial = $parametroBO->secuencial + 1;
            $parametroBO->save();

            //Detalles comprobante contable
            $anioActual  = intval($date->Format('Y'));
            $cuentaCli1 = DB::table('ADMPUNTOASIENTOS')
                ->where([['ASIENTO', '=', 'PAG'], ['ANIO', '=', $anioActual], ['TIPO', '=', 'CLI'], ['PUNTO', '=', $codigoPunto]])
                ->first();
            $cuentaEfe1 = DB::table('ADMPUNTOASIENTOS')
                ->where([['ASIENTO', '=', 'PAG'], ['ANIO', '=', $anioActual], ['TIPO', '=', 'EFE'], ['PUNTO', '=', $codigoPunto]])
                ->first();
            $cuentaChq1 = DB::table('ADMPUNTOASIENTOS')
                ->where([['ASIENTO', '=', 'PAG'], ['ANIO', '=', $anioActual], ['TIPO', '=', 'CHQ'], ['PUNTO', '=', $codigoPunto]])
                ->first();

            $cuentaDEP = DB::table('ADMBANCOCIA')
                ->where('CODIGO', '=', trim($r->banco))
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

            //Log::info(["detcomprobanteContable Linea 1"=> $detCompro]);

            $detCompro2 = new \App\ADMDETCOMPROBANTE();
            $detCompro2->SECUENCIAL = $cabCompro->secuencial;
            $detCompro2->LINEA = 2;

            if (trim($r->medioPago) == 'CHQ') {
                $detCompro2->CUENTA = trim($cuentaChq1->CUENTA);
            } elseif (trim($r->medioPago) == 'EFE') {
                $detCompro2->CUENTA = trim($cuentaEfe1->CUENTA);
            } elseif (trim($r->medioPago) == 'DEP' || trim($r->medioPago) == 'TRA') {
                $detCompro2->CUENTA = trim($cuentaDEP->cuentachq);
            }


            $detCompro2->DETALLE = $observacionCre;
            $detCompro2->DBCR = "DB";
            $detCompro2->MONTO = $montoPagar;
            $detCompro2->ESTADO = "C";
            $detCompro2->save();

            //Log::info(["detcomprobanteContable Linea 2"=> $detCompro2]);

            //actualizar SECCON en el ADMPAGO y ADMMoviBancoCia
            $pagoActualizar = \App\ADMPAGO::where('NUMERO', '=', $numeroPago)->first();
            //Log::info(["Pago a actualizar"=> $pagoActualizar]);
            $pagoActualizar->seccon = $numComproContable;
            $pagoActualizar->save();

            if ($r->medioPago == 'DEP' || $r->medioPago == 'TRA') {

                $result = DB::table('ADMMOVIBANCOCIA')
                    ->where('secuencial', $secDelPago)
                    ->update([
                        'seccon' => $numComproContable,
                    ]);
            }
            Log::info('Interes Valor ' . $interes);
            $observacionNDB = "NDB por Interes de cuota  ADMGO";
            //Generar Nota de Debito cuando existe interes de mora.


            //Proceso de las deudas a pagar
            foreach ($deudas as $pos => $val) {

                $numDeuda = $val['numero'];
                $montoPagar = round($val['monto'], 2);
                $montoSinInteres = round($montoPagar - $interes, 2);
                $sumamontoint = $montoPagarT; // - $interesT;
                $procesoPago = false;
                $deuda = \App\ADMDEUDA::where('SECUENCIAL', '=', $numDeuda)
                    ->whereIn('TIPO', ['NVT', 'FAC', 'NDB'])
                    ->where('CLIENTE', trim($cliente))
                    ->first();
                
                

                if ($deuda != null) {
                    // Consultar Credito de la Deuda
                    $credito = \App\ADMCREDITO::where('SECINV', '=', $deuda->SECINV)
                        ->whereIn('TIPO', ['NVT', 'FAC', 'NDB'])
                        ->where('CLIENTE', trim($cliente))
                        ->first();

                    // Log::info($deuda);
                    Log::info('Saldo de la Deuda entes de entrar a pagar Cuotas: '.round($deuda->SALDO,2));


                    // Log::info("Número de cuotas a pagar: " . COUNT($coutasDetPag));
                    // SE CAMBIO EL 18/03/2022   
                    if ($interesT > 0) {
                        Log::info("Interes : " . $interesT);
                        Log::info("Se procedió a pagar con interes Monto: " . $sumamontoint . " en seccu: " . $numDeuda);
                        $procesoPago = $this->PagarCuotasConInteres2022(
                            $numDeuda,
                            $sumamontoint,
                            $pago->numero,
                            $operador1,
                            $observacionCre,
                            $fechaPago,
                            $bodegaDeuda,
                            $fcPag,
                            $observacionNDB,
                            $vendedor->VENDEDOR,
                            $date,
                            $numeroPago,
                            $cliente,
                            $credito->SERIE,
                            $cajaAbierta[0]->codigo,
                            $credito->SERIE,
                            $credito->NUMERO,
                            $credito->BODEGA,
                            $credito->FECHA,
                            $interesT,
                            $credito,
                            $credito->SECINV
                        );
                        Log::info('Resultado del Proceso de Pago con Interes');
                        //Log::info('Pago a cuotas totales' . $procesoPago['pagosDeudas']);
                    } else {
                        $procesoPago = $this->pagarCuotas(
                            $numDeuda,
                            $sumamontoint,
                            $pago->numero,
                            $operador1,
                            $observacionCre,
                            $fechaPago
                        );
                    }

                    //Reducción de Saldo en Deuda.
                    $deudaActual = \App\ADMDEUDA::where('SECUENCIAL', '=', $numDeuda)
                        ->whereIn('TIPO', ['NVT', 'FAC', 'NDB'])
                        ->where('CLIENTE', trim($cliente))
                        ->first();

                    $saldo = round($deudaActual->SALDO, 2);
                    $bodegaDeuda =   $deudaActual->BODEGA;
    
                    Log::info('Saldo de la Deuda Despues de entrar a pagar Cuotas: '.round($deudaActual->SALDO,2));
                 
                    $montoNDBTotal = 0;
                    $pagoNegativos = 0;
                    if ($interes > 0) {
                        $montoPagar = round($procesoPago['pagosDeudas'], 2);
                        $montoNDBTotal = round($procesoPago['notasDebito'], 2);
                        $pagoNegativos = round($procesoPago['pagoNegativo'], 2);
                    }

                    Log::info("Pagando Deuda SECUENCIAL: " . $numDeuda . " con monto que afecta cuotas => " . $montoPagar);
                    Log::info("Monto que afecta Cuotas: " . $montoPagar);
                    Log::info("Monto que afecta Notas de Debito: " . $montoNDBTotal);
                    Log::info("Monto de Pagos Negativos: " . $pagoNegativos);

                    $deudaActual->CREDITO = round($deudaActual->CREDITO + $montoPagar, 2);
                    $deudaActual->SALDO = round($deudaActual->SALDO - $montoPagar, 2);
                    $deudaActual->save();

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
                    $creditoLinea->FECHA = Carbon::createFromFormat('d-m-Y', $fcPag)->Format('Y-d-m');
                    $creditoLinea->IVA = 0;
                    $creditoLinea->MONTO =  $montoPagar;
                    $creditoLinea->SALDO = round(($saldo -  $montoPagar), 2);
                    if ($creditoLinea->SALDO < 0) {
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

                    $procesoCambio = $this->CambioEstado($numeroGuia, $numDeuda, $montoSinInteres, $tipoPago);
                    Log::info("Resultados de los procesos, DeudaCuota => " . $procesoPago['proceso'] . ",Cambio => " . $procesoCambio);

                    if ($procesoPago['proceso'] &&  $procesoCambio) {
                        Log::info("Proceso de Pago y Cambio de estado Exitoso.");
                    } else {
                        DB::rollback();
                        return response()->json(['error' => 'Error en proceso de pago y cambio de estado, Secuencial: ' . $numDeuda . ' de la guia: ' . $numeroGuia]);
                    }
                } else {
                    DB::rollback();
                    Log::error('Deuda no encontrada: Secuencial: ' . $numDeuda . ', cliente:' . $cliente);
                    return response()->json(['error' => 'Deuda no encontrada: Secuencial: ' . $numDeuda . ', cliente:' . $cliente]);
                }
            }

            DB::commit();
            //Log::info("Hace commit final");
            Log::info("Pago completo estado OK, numPago => " . $pago->numero);
            return response()->json(['estado' => 'ok', 'numPago' => $pago->numero, 'dirCaja' => $cajaAbierta[0]->DIRECCION]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error" => ["ADMPAGO" => $e->getMessage()]]);
        }
    }

    public function pagarCuotas(int $secuencial, float $mon, int $pago, string $ope, string $observa, string $fechaPago)
    {
        Log::info("Entra a pagar Cuotas Metodo viejo: pagaCuota sin interes.");
        $monto = $mon;

        $cuotas = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
            ->where('SALDO', '>', 0.001)
            ->orderBy('NUMCUOTA', 'ASC')
            ->get();


        $fecha = Carbon::createFromFormat('Y-m-d', $fechaPago)->Format('d-m-Y');

        DB::beginTransaction();
        try {

            foreach ($cuotas as $index => $val) {

                $saldoCuota = $val['SALDO'];
                $numCuota = $val['NUMCUOTA'];

                if ($saldoCuota < $monto) {

                    if ($monto < 0.001) {
                        break;
                    }

                    Log::info("Pago del saldo en la cuota.");
                    # Pago de cuota completa.
                    $deCuo = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
                        ->where('NUMCUOTA', '=', $numCuota)
                        ->first();

                    $monto = round($monto - $deCuo->SALDO, 2);

                    //Guardado alternativo por problemas con PK compuestos;
                    $result = DB::table('ADMDEUDACUOTA')
                        ->where('SECDEUDA', $secuencial)
                        ->where('NUMCUOTA', $numCuota)
                        ->update([
                            'SALDO' => 0,
                            'CREDITO' => $deCuo->MONTO,
                            'FECHACANCELACUOTA' => Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m'),
                            'NUMPAGO' => $pago
                        ]);

                    $admdeuda = \App\ADMDEUDA::where('SECUENCIAL', $secuencial)
                        ->first();

                    $deCuoDet1 = new \App\ADMDEUDACUOTADET();
                    $deCuoDet1->SECDEUDA = $secuencial;
                    $deCuoDet1->SECINV = $admdeuda->SECINV;
                    $deCuoDet1->NUMCUOTA = $numCuota;
                    $deCuoDet1->VALORCUOTA = round($deCuo->MONTO, 2);
                    $deCuoDet1->MONTO = round($deCuo->SALDO, 2);
                    $deCuoDet1->SALDO = 0;
                    $deCuoDet1->NUMPAGO = $pago;
                    $deCuoDet1->FECHACANCELA = Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m');
                    $deCuoDet1->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s', $deCuo->FECHAVEN)->Format('Y-d-m');
                    $deCuoDet1->OBSERVACION = $observa;
                    $deCuoDet1->OPERADOR = $ope;
                    $deCuoDet1->MAQUINA = "SERVER LARAVEL";
                    $deCuoDet1->HORA = Carbon::now()->format('H:i:s');
                    $deCuoDet1->save();
                } else {
                    Log::info("Abono de cuota.");
                    # Abono a Cuota.
                    $deCuo = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
                        ->where('NUMCUOTA', '=', $numCuota)
                        ->first();

                    //Guardado alternativo por problemas con PK compuestos;
                    $saldoFinal =  $deCuo->SALDO - $monto;
                    $creditoFinal = $deCuo->CREDITO + $monto;
                    $result = DB::table('ADMDEUDACUOTA')
                        ->where('SECDEUDA', $secuencial)
                        ->where('NUMCUOTA', $numCuota)
                        ->update([
                            'SALDO' => round($saldoFinal, 2),
                            'CREDITO' => round($creditoFinal, 2),
                            'FECHACANCELACUOTA' => Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m'),
                            'NUMPAGO' => $pago
                        ]);

                    $admdeuda = \App\ADMDEUDA::where('SECUENCIAL', $secuencial)
                        ->first();

                    $deCuoDet2 = new \App\ADMDEUDACUOTADET();
                    $deCuoDet2->SECDEUDA = $secuencial;
                    $deCuoDet2->SECINV = $admdeuda->SECINV;
                    $deCuoDet2->NUMCUOTA = $numCuota;
                    $deCuoDet2->VALORCUOTA = $deCuo->MONTO;
                    $deCuoDet2->MONTO = $monto;
                    $deCuoDet2->SALDO = round($deCuo->SALDO - $monto, 2);
                    $deCuoDet2->NUMPAGO = $pago;
                    $deCuoDet2->FECHACANCELA = Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m');
                    $deCuoDet2->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s', $deCuo->FECHAVEN)->Format('Y-d-m');
                    $deCuoDet2->OBSERVACION = $observa;
                    $deCuoDet2->OPERADOR = $ope;
                    $deCuoDet2->MAQUINA = "SERVER LARAVEL";
                    $deCuoDet2->HORA = Carbon::now()->format('H:i:s');
                    $deCuoDet2->save();
                    //Log::info("DeudaCuotaDET=>",['obje'=>$deCuoDet2]);
                    break;
                }
            }

            DB::commit();
            $dataResultante = ['proceso' => true, 'notasDebito' => 0, 'pagosDeudas' => $monto, 'pagoNegativo' => 0];
            return $dataResultante;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error pagoCuota:", ['Mensaje' => $e->getMessage()]);
            $dataResultante = ['proceso' => false, 'notasDebito' => 0, 'pagosDeudas' => 0, 'pagoNegativo' => 0];
            return $dataResultante;
        }
    }
    public function CambioEstado(int $Numguia, int $secuencial, float $monto, string $tipopago)
    {
        Log::info("CambioEstado", ["Guia" => $Numguia, "sec" => $secuencial]);
        DB::beginTransaction();
        try {

            $DetGuiaCobro = DB::table('ADMDETGUIACOB')
                ->where('SECUENCIAL', $secuencial)
                ->where('NUMGUIA', $Numguia)
                ->first();

            if ($DetGuiaCobro != NULL  /* or $DetGuiaCobro->count() == 0*/) {
                //Log::info("entra en el DETGUIACOB valido");
                $chq = 0;
                $efect = 0;
                $otro = 0;

                if ($tipopago == 'EFE') {
                    $efect = $monto;
                } elseif ($tipopago == 'CHQ') {
                    $chq = $monto;
                } else {
                    $otro = $monto;
                }

                if (round($DetGuiaCobro->SALDO - $monto, 2) <= 0) {
                    $result = DB::table('ADMDETGUIACOB')
                        ->where('SECUENCIAL', $secuencial)
                        ->where('NUMGUIA', $Numguia)
                        ->update([
                            'ESTADO' => 'L',
                            'SALDO' => 0,
                            'VALORULTPAG' => $monto,
                            'EFECTIVO' => round($DetGuiaCobro->EFECTIVO + $efect, 2),
                            'CHEQUE' => round($DetGuiaCobro->CHEQUE + $chq, 2),
                            'OTRO' => round($DetGuiaCobro->OTRO + $otro, 2),
                            'FECULTPAG' => Carbon::now()->format('Y-d-m')
                        ]);
                    //Log::info("valor del result en el update 1 CAMBIOESTADO",['result'=>$result]);
                    DB::commit();
                    return true;
                } else {
                    $result = DB::table('ADMDETGUIACOB')
                        ->where('SECUENCIAL', $secuencial)
                        ->where('NUMGUIA', $Numguia)
                        ->update([
                            'SALDO' => round($DetGuiaCobro->SALDO - $monto, 2),
                            'VALORULTPAG' => $monto,
                            'EFECTIVO' => round($DetGuiaCobro->EFECTIVO + $efect, 2),
                            'CHEQUE' => round($DetGuiaCobro->CHEQUE + $chq, 2),
                            'OTRO' => round($DetGuiaCobro->OTRO + $otro, 2),
                            'FECULTPAG' => Carbon::now()->format('Y-d-m')
                        ]);
                    //Log::info("valor del result en el update 2 CAMBIOESTADO",['result'=>$result]);
                    DB::commit();
                    return true;
                }
            } else {
                DB::rollback();
                Log::error("Error DETGUIACOB no encontrada");
                return false;
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error Cambio de Estado DETGUIACOB:", ['Mensaje' => $e->getMessage()]);
            return false;
        }
    }

    public function GenerarNDB($interes, $bodegaDeuda, $fcPag, $operador1, $observa, $vende, $date, $numpago, $cliente, $serie, $cajac, $seriefac, $numerofac, $bodegafac, $fechafac, $numcuotadet, $seccInvFac)
    {
        DB::beginTransaction();
        try {
            Log::info('Entra el Metodo GerneraNDB, SECINV de la Factura: ' . $seccInvFac);
            $parametrov = ADMPARAMETROV::first();
            $numeroNDB =  \App\ADMTIPODOC::where('TIPO', '=', 'NDB')->first();

            $deudaNDB = new \App\ADMDEUDA();
            $deudaNDB->SECUENCIAL = $parametrov->SECUENCIAL + 1;
            $deudaNDB->BODEGA = $bodegaDeuda;
            $deudaNDB->CLIENTE = $cliente;
            $deudaNDB->TIPO = "NDB";
            $deudaNDB->MOTIVO = "IPD";
            $deudaNDB->NUMERO = $numeroNDB->CONTADOR + 1;
            $deudaNDB->SERIE = $serie;
            $deudaNDB->SECINV = $seccInvFac;
            $deudaNDB->IVA = 0;
            $deudaNDB->MONTO = $interes;
            $deudaNDB->CREDITO = $interes;
            $deudaNDB->SALDO = 0;
            $deudaNDB->FECHAEMI = $fcPag;
            $deudaNDB->FECHAVEN = $fcPag;
            $deudaNDB->FECHADES = $fcPag;
            $deudaNDB->HORA = $date->Format('H:i:s');
            $deudaNDB->CAJAC = $cajac;
            $deudaNDB->BANCO = '';
            $deudaNDB->CUENTA = '';
            $deudaNDB->NUMCHQ = '';
            $deudaNDB->ESTCHQ = '';
            $deudaNDB->OPERADOR = $operador1;
            $deudaNDB->VENDEDOR = $vende;
            $deudaNDB->OBSERVACION = $observa . ',Cuota ' . $numcuotadet;
            $deudaNDB->NUMAUTO = "";
            $deudaNDB->BODEGAFAC = $bodegafac;
            $deudaNDB->SERIEFAC = $seriefac;
            $deudaNDB->NUMEROFAC = $numerofac;
            //  $deudaNDB->FECHAFAC = $fechafac;
            $deudaNDB->NOMBREPC = 'Servidor Laravel';
            $deudaNDB->ACT_SCT = "N";
            $deudaNDB->montodocumento = 0;
            $deudaNDB->tipoventa = "";
            $deudaNDB->mesescredito = 0;
            $deudaNDB->tipopago = "";
            $deudaNDB->usuarioeli = "";
            $deudaNDB->EWEB = "N";
            $deudaNDB->save();
            Log::info("Graba Nota de Debito => DEUDA por $" . $interes);

            $creditoLinea2 = new \App\ADMCREDITO();
            $creditoLinea2->SECUENCIAL = $deudaNDB->SECUENCIAL;
            $creditoLinea2->SECINV = $seccInvFac;
            $creditoLinea2->BODEGA = $deudaNDB->BODEGA;
            $creditoLinea2->CLIENTE = $deudaNDB->CLIENTE;
            $creditoLinea2->TIPO = $deudaNDB->TIPO;
            $creditoLinea2->NUMERO = $deudaNDB->NUMERO;
            $creditoLinea2->SERIE = $serie;
            $creditoLinea2->NUMCRE = null;
            $creditoLinea2->FECHA = $fcPag;
            $creditoLinea2->MONTO = $interes;
            $creditoLinea2->SALDO = $interes;
            $creditoLinea2->OPERADOR = $operador1;
            $creditoLinea2->OBSERVACION = $observa . ',Cuota ' . $numcuotadet;
            $creditoLinea2->VENDEDOR = $deudaNDB->VENDEDOR;
            $creditoLinea2->HORA = $date->Format('H:i:s');
            $creditoLinea2->NOMBREPC = 'Servidor Laravel';
            $creditoLinea2->estafirmado = 'N';
            $creditoLinea2->ACT_SCT = 'N';
            $creditoLinea2->seccreditogen = 0;
            $creditoLinea2->save();

            $creditoLinea3 = new \App\ADMCREDITO();
            $creditoLinea3->SECUENCIAL = $deudaNDB->SECUENCIAL + 1;
            $creditoLinea3->SECINV = $seccInvFac;
            $creditoLinea3->BODEGA = $deudaNDB->BODEGA;
            $creditoLinea3->CLIENTE = $deudaNDB->CLIENTE;
            $creditoLinea3->TIPO = "NDB";
            $creditoLinea3->NUMERO = $deudaNDB->NUMERO;
            $creditoLinea3->SERIE = $serie;
            $creditoLinea3->TIPOCR = "PAG";
            $creditoLinea3->NUMCRE = $numpago;
            $creditoLinea3->FECHA = $fcPag;
            $creditoLinea3->MONTO = $interes;
            $creditoLinea3->SALDO = 0;
            $creditoLinea3->OPERADOR = $operador1;
            $creditoLinea3->OBSERVACION = $observa . ',Cuota ' . $numcuotadet;
            $creditoLinea3->VENDEDOR = $deudaNDB->VENDEDOR;
            $creditoLinea3->HORA = $date->Format('H:i:s');
            $creditoLinea3->NOMBREPC = 'Servidor Laravel';
            $creditoLinea3->estafirmado = 'N';
            $creditoLinea3->ACT_SCT = 'N';
            $creditoLinea3->IVA = 0;
            $creditoLinea3->SERIECRE = "";
            $creditoLinea3->NOAUTOR = "";
            $creditoLinea3->seccreditogen = 0;
            $creditoLinea3->NUMCUOTA = $numcuotadet;
            $creditoLinea3->save();

            $numeroNDB->CONTADOR = $numeroNDB->CONTADOR + 1;
            $numeroNDB->save();

            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 2;
            $parametrov->save();

            DB::commit();
            Log::info("Graba Nota debito => CREDITO por $" . $interes);
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error Generando Nota de Debito por $" . $interes, ['Mensaje' => $e->getMessage()]);
            return false;
        }
    }

    public function pagarCuotasInteres(array $deudaCuotasNew, int $secuencial, int $pago, string $ope, string $observa, string $fechaPago)
    {

        Log::info("Entra a pagar Cuotas Metodo pagarCuotasInteres.");
        Log::info("cuotas a pagar.", ["json" => $deudaCuotasNew]);
        DB::beginTransaction();

        try {
            foreach ($deudaCuotasNew as $key => $value) {
                $numCuotaPagar  = $value['numero'];
                $monto = $value['monto'];
                $fecha = Carbon::createFromFormat('Y-m-d', $fechaPago)->Format('d-m-Y');

                $cuota = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
                    ->where('SALDO', '>', 0.001)
                    ->where('NUMCUOTA', '=', $numCuotaPagar)
                    ->first();

                if ($cuota == null) {
                    DB::rollback();
                    Log::error("Error PagoCuotaInteres: intentando pagar una cuota no valida. Cuota:" . $numCuotaPagar . " sec Deuda:" . $secuencial);
                    return false;
                }

                $saldoCuota = $cuota->SALDO;
                $numCuota = $numCuotaPagar;

                if ($saldoCuota <= $monto) {
                    # Pago de cuota completa.
                    $deCuo = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
                        ->where('NUMCUOTA', '=', $numCuota)
                        ->first();

                    $monto = round($monto - $deCuo->SALDO, 2);

                    //Guardado alternativo por problemas con PK compuestos;
                    $result = DB::table('ADMDEUDACUOTA')
                        ->where('SECDEUDA', $secuencial)
                        ->where('NUMCUOTA', $numCuota)
                        ->update([
                            'SALDO' => 0,
                            'CREDITO' => $deCuo->MONTO,
                            'FECHACANCELACUOTA' => Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m'),
                            'NUMPAGO' => $pago
                        ]);

                    $admdeuda = \App\ADMDEUDA::where('SECUENCIAL', $secuencial)
                        ->first();

                    $deCuoDet = new \App\ADMDEUDACUOTADET();
                    $deCuoDet->SECDEUDA = $secuencial;
                    $deCuoDet->SECINV = $admdeuda->SECINV;
                    $deCuoDet->NUMCUOTA = $numCuota;
                    $deCuoDet->VALORCUOTA = round($deCuo->MONTO, 2);
                    $deCuoDet->MONTO = round($deCuo->SALDO, 2);
                    $deCuoDet->SALDO = 0;
                    $deCuoDet->NUMPAGO = $pago;
                    $deCuoDet->FECHACANCELA = Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m');
                    $deCuoDet->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s', $deCuo->FECHAVEN)->Format('Y-d-m');
                    $deCuoDet->OBSERVACION = $observa;
                    $deCuoDet->OPERADOR = $ope;
                    $deCuoDet->MAQUINA = "SERVER LARAVEL";
                    $deCuoDet->HORA = Carbon::now()->format('H:i:s');

                    try {
                        $deCuoDet->save();
                    } catch (\Throwable $th) {
                        Log::error("Error Guardando ADMDEUDACUOTADET pago completo");
                        Log::error($th->getMessage());
                        DB::rollback();
                        return false;
                    }
                } else {
                    # Abono a Cuota.
                    $deCuo = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
                        ->where('NUMCUOTA', '=', $numCuota)
                        ->first();

                    //Guardado alternativo por problemas con PK compuestos;
                    $saldoFinal =  $deCuo->SALDO - $monto;
                    $creditoFinal = $deCuo->CREDITO + $monto;
                    $result = DB::table('ADMDEUDACUOTA')
                        ->where('SECDEUDA', $secuencial)
                        ->where('NUMCUOTA', $numCuota)
                        ->update([
                            'SALDO' => round($saldoFinal, 2),
                            'CREDITO' => round($creditoFinal, 2),
                            'FECHACANCELACUOTA' => Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m'),
                            'NUMPAGO' => $pago
                        ]);

                    $admdeuda = \App\ADMDEUDA::where('SECUENCIAL', $secuencial)
                        ->first();

                    $deCuoDet = new \App\ADMDEUDACUOTADET();
                    $deCuoDet->SECDEUDA = $secuencial;
                    $deCuoDet->SECINV = $admdeuda->SECINV;
                    $deCuoDet->NUMCUOTA = $numCuota;
                    $deCuoDet->VALORCUOTA = $deCuo->MONTO;
                    $deCuoDet->MONTO = $monto;
                    $deCuoDet->SALDO = round($deCuo->SALDO - $monto, 2);
                    $deCuoDet->NUMPAGO = $pago;
                    $deCuoDet->FECHACANCELA = Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m');
                    $deCuoDet->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s', $deCuo->FECHAVEN)->Format('Y-d-m');
                    $deCuoDet->OBSERVACION = $observa;
                    $deCuoDet->OPERADOR = $ope;
                    $deCuoDet->MAQUINA = "SERVER LARAVEL";
                    $deCuoDet->HORA = Carbon::now()->format('H:i:s');

                    try {
                        $deCuoDet->save();
                    } catch (\Throwable $th) {
                        Log::error("Error Guardando ADMDEUDACUOTADET pago parcial");
                        Log::error($th->getMessage());
                        DB::rollback();
                        return false;
                    }
                } // end If         
            } //end Foreach.

            DB::commit();
            Log::info("Pasa el ADMDEUDACUOTADET registro.");
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error pagoCuota:", ['Mensaje' => $e->getMessage()]);
            return false;
        } //end Try
    } //end Function

    public function pagarCuotasGenenrandointeres(int $secuencial, float $mon, int $pago, string $ope, string $observa, string $fechaPago, int $bodegaDeuda, string $fcPag, string $observacionNDB, string $vendedor,  $date, string $numeroPago, string $clienteData, String $serie, int $cajac, String $seriefac, $numerofac, $bodegafac, $fechafac, float $interesCuota, $credito)
    {
        // Seccion de Detección de Cuotas
        Log::info("Entra a pagar Cuotas Metodo Interes Nuevo ACTUAL");

        $monto = $mon;
        $cuotas = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
            ->where('SALDO', '>', 0.001)
            ->orderBy('NUMCUOTA', 'ASC')
            ->get();
        Log::info('Cuotas disponibles a pagar');

        foreach ($cuotas as $index => $valcuo) {
            Log::info('Número ' . $valcuo['NUMCUOTA'] . ', saldo: ' . round($valcuo['SALDO'], 2));
        }
        $seccinvfac = $cuotas[0]->SECINV;
        $parametroc = \App\ADMPARAMETROC::first();
        $porMoraDia = $parametroc->porintmoradia;
        Log::info("Porcentaje de Interes Mensual %" . round($porMoraDia, 3));


        $fecha = Carbon::createFromFormat('Y-m-d', $fechaPago)->Format('d-m-Y');
        $fechadepago = Carbon::createFromFormat('Y-m-d', $fechaPago);

        DB::beginTransaction();
        try {

            foreach ($cuotas as $index => $val) {
                $fechavenscic = $val['FECHAVEN'];
                $saldoCuota = round($val['SALDO'], 2);
                $numCuota = $val['NUMCUOTA'];
                $fechadevenciminetocuota = Carbon::parse($fechavenscic)->format('Y-m-d');
                $fechadevencuota = Carbon::createFromFormat('Y-m-d', $fechadevenciminetocuota); //->format('d-m-Y');
                $dias = $fechadepago->diffInDays($fechadevencuota, false);



                $calculointeres = 0;
                $valorinteres = 0;
                Log::info("Dias (-)atrasado / (+)adelantado = " . $dias . " Cuota: " . $val['NUMCUOTA']);
                if ($dias >= 0) {
                    Log::info("Cuota NO Generará Interes porque está en fechas, NumCuot -> " . $val['NUMCUOTA']);
                    $dias = 0;
                } else {
                    $dias = $dias * -1;
                    Log::info("Cuota SI Generará Interes por Atraso NumCuota -> " . $val['NUMCUOTA']);
                    $calculointeres = round($porMoraDia * $saldoCuota, 2) / 100;
                    $valorinteres = round($dias * $calculointeres, 2);
                }

                // Se Actualiza el valor del monto, menos el interes de la cuota actual.
                $monto = $monto - round($valorinteres, 2);

                if ($saldoCuota < $monto) {
                    //Pagará el interes(NDB) y el saldo de la cuota y sobrará algo para la siguiente cuota.
                    Log::info("Entra a grabar la NDB valor por Interes " . $valorinteres . ", y el saldo: " . $saldoCuota);
                    if ($valorinteres > 0 && $monto > 0) {
                        Log::info("Entra valorInteres > 0 monto > 0 (primera seccion IF)");
                        // Paga el interes completa y parte del saldo de la cuota
                        Log::info("Entra IF => valorinteres > 0 && monto > 0, pagando Interes y parte de Cuota");
                        $tarea = $this->GenerarNDB($valorinteres, $bodegaDeuda, $fcPag, $ope, $observacionNDB, $vendedor, $date, $numeroPago, $clienteData, $serie, $cajac, $seriefac, $numerofac, $bodegafac, $fechafac, $numCuota, $seccinvfac);

                        if ($tarea) {
                            Log::info("NDB generada por monto: " . $valorinteres . " de la cuota Nº " . $numCuota . " de SecuDeuda " . $secuencial);
                        } else {
                            DB::rollback();
                            return response()->json(['error' => 'Error en proceso de Creación de Nota de Debito.']);
                        }
                    } else if ($valorinteres > 0 && $monto < 0) {
                        // Solo alcanza pagar el interes
                        Log::info("Entra valorInteres > 0 monto > 0 (primera seccion IF)");
                        $valinteresenew = $monto * -1;
                        Log::info("Entra IF valorinteres > 0 && monto < 0 " . $valinteresenew);
                        $tarea = $this->GenerarNDB($valinteresenew, $bodegaDeuda, $fcPag, $ope, $observacionNDB, $vendedor, $date, $numeroPago, $clienteData, $serie, $cajac, $seriefac, $numerofac, $bodegafac, $fechafac, $numCuota, $seccinvfac);
                        if ($tarea) {
                            Log::info("NDB generada por monto: " .   $valinteresenew . " de la cuota Nº " . $numCuota . " de Sec.Deuda " . $secuencial);
                        } else {

                            DB::rollback();
                            return response()->json(['error' => 'Error en proceso de Creación de Nota de Debito.']);
                        }
                    }

                    if ($monto < 0.001) {
                        Log::inf('Entra a terminar el ciclo(break)');
                        break;
                    }

                    Log::info("Pago del saldo ADMDEUDACUOTA en la cuota con el monto restante del pago: " . $monto);
                    # Pago de cuota completa.
                    $deCuo = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
                        ->where('NUMCUOTA', '=', $numCuota)
                        ->first();


                    $monto = $monto - round($deCuo->SALDO, 2);

                    Log::info('Valor del Saldo de la Cuota = ' . round($deCuo->SALDO, 2));
                    Log::info('Valor del (Monto - Saldo) de la cuota ' . $numCuota . ' es = ' . $monto);

                    //Guardado alternativo por problemas con PK compuestos;
                    $result = DB::table('ADMDEUDACUOTA')
                        ->where('SECDEUDA', $secuencial)
                        ->where('NUMCUOTA', $numCuota)
                        ->update([
                            'SALDO' => 0,
                            'CREDITO' => $deCuo->MONTO,
                            'FECHACANCELACUOTA' => Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m'),
                            'NUMPAGO' => $pago
                        ]);

                    $admdeuda = \App\ADMDEUDA::where('SECUENCIAL', $secuencial)
                        ->first();

                    $deCuoDet1 = new \App\ADMDEUDACUOTADET();
                    $deCuoDet1->SECDEUDA = $secuencial;
                    $deCuoDet1->SECINV = $admdeuda->SECINV;
                    $deCuoDet1->NUMCUOTA = $numCuota;
                    $deCuoDet1->VALORCUOTA = round($deCuo->MONTO, 2);
                    $deCuoDet1->MONTO = round($deCuo->SALDO, 2);
                    $deCuoDet1->SALDO = 0;
                    $deCuoDet1->NUMPAGO = $pago;
                    $deCuoDet1->FECHACANCELA = Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m');
                    $deCuoDet1->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s', $deCuo->FECHAVEN)->Format('Y-d-m');
                    $deCuoDet1->OBSERVACION = $observa;
                    $deCuoDet1->OPERADOR = $ope;
                    $deCuoDet1->MAQUINA = "SERVER LARAVEL";
                    $deCuoDet1->HORA = Carbon::now()->format('H:i:s');
                    $deCuoDet1->save();
                } else {
                    // Pagará parte de la cuota solamente.
                    Log::info("Entra en IF cuando el pago es igual o menor al saldo de la cuota " . $saldoCuota . " > " . $monto);

                    if ($valorinteres > 0 && $monto > 0) {
                        Log::info("Entra valorInteres > 0 monto > 0 (segunda seccion ELSE)");
                        // El pago cubre el Interes y tambien parte de la Cuota
                        Log::info("Datos del cliente " . $clienteData);
                        $tarea = $this->GenerarNDB($valorinteres, $bodegaDeuda, $fcPag, $ope, $observacionNDB, $vendedor, $date, $numeroPago, $clienteData, $serie, $cajac, $seriefac, $numerofac, $bodegafac, $fechafac, $numCuota, $seccinvfac);

                        if ($tarea) {
                            Log::info("NDB generada por monto: " . $valorinteres . " de la cuota Nº " . $numCuota . " de SecuDeuda " . $secuencial);
                        } else {

                            DB::rollback();
                            return response()->json(['error' => 'Error en proceso de Creación de Nota de Debito.']);
                        }
                    } else if ($valorinteres > 0 && $monto < 0) {
                        Log::info("Entra valorInteres > 0 monto < 0 (segunda seccion ELSE)");
                        // El pago solo cubre el interes
                        $valinteresenew = $monto * -1;
                        Log::info("Valor del Interes = monto * -1 => " .   $valinteresenew);
                        $tarea = $this->GenerarNDB($valinteresenew, $bodegaDeuda, $fcPag, $ope, $observacionNDB, $vendedor, $date, $numeroPago, $clienteData, $serie, $cajac, $seriefac, $numerofac, $bodegafac, $fechafac, $numCuota, $seccinvfac);

                        if ($tarea) {
                            Log::info("NDB generada por monto: " .   $valinteresenew . " de la cuota Nº " . $numCuota . " de Sec.Deuda " . $secuencial);
                        } else {

                            DB::rollback();
                            return response()->json(['error' => 'Error en proceso de Creación de Nota de Debito.']);
                        }
                    }
                    # Abono a Cuota.
                    $deCuo = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
                        ->where('NUMCUOTA', '=', $numCuota)
                        ->first();

                    //Guardado alternativo por problemas con PK compuestos;
                    $saldoFinal =  round($deCuo->SALDO - $monto, 2);
                    $creditoFinal = $deCuo->CREDITO + $monto;
                    $result = DB::table('ADMDEUDACUOTA')
                        ->where('SECDEUDA', $secuencial)
                        ->where('NUMCUOTA', $numCuota)
                        ->update([
                            'SALDO' => round($saldoFinal, 2),
                            'CREDITO' => round($creditoFinal, 2),
                            'FECHACANCELACUOTA' => Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m'),
                            'NUMPAGO' => $pago
                        ]);

                    $admdeuda = \App\ADMDEUDA::where('SECUENCIAL', $secuencial)
                        ->first();

                    $deCuoDet2 = new \App\ADMDEUDACUOTADET();
                    $deCuoDet2->SECDEUDA = $secuencial;
                    $deCuoDet2->SECINV = $admdeuda->SECINV;
                    $deCuoDet2->NUMCUOTA = $numCuota;
                    $deCuoDet2->VALORCUOTA = $deCuo->MONTO;
                    $deCuoDet2->MONTO = $monto;
                    $deCuoDet2->SALDO = round($deCuo->SALDO - $monto, 2);
                    $deCuoDet2->NUMPAGO = $pago;
                    $deCuoDet2->FECHACANCELA = Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m');
                    $deCuoDet2->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s', $deCuo->FECHAVEN)->Format('Y-d-m');
                    $deCuoDet2->OBSERVACION = $observa;
                    $deCuoDet2->OPERADOR = $ope;
                    $deCuoDet2->MAQUINA = "SERVER LARAVEL";
                    $deCuoDet2->HORA = Carbon::now()->format('H:i:s');
                    $deCuoDet2->save();
                    //Log::info("DeudaCuotaDET=>",['obje'=>$deCuoDet2]);
                    break;
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error pagoCuota:", ['Mensaje' => $e->getMessage()]);
            return false;
        }
    }


    public function PagarCuotasConInteres2022(int $secuencial, float $mon, int $pago, string $ope, string $observa, string $fechaPago, int $bodegaDeuda, string $fcPag, string $observacionNDB, string $vendedor,  $date, string $numeroPago, string $clienteData, String $serie, int $cajac, String $seriefac, $numerofac, $bodegafac, $fechafac, float $interesCuota, $credito, $secinvfac)
    {
        // Seccion de Detección de Cuotas
        Log::info("Entra a pagar Cuotas Metodo Interes Nuevo Agosto 2022");
        $monto = $mon;
        $motoANotasDebitos = 0;
        $montoADeudas = 0;
        $montoNegativoGlobal = 0;
        $cuotas = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
            ->where('SALDO', '>', 0.001)
            ->orderBy('NUMCUOTA', 'ASC')
            ->get();

        Log::info('Cuotas disponibles a pagar');
        foreach ($cuotas as $index => $valcuo) {
            Log::info('Número ' . $valcuo['NUMCUOTA'] . ', saldo: ' . round($valcuo['SALDO'], 2));
        }

        $parametroc = \App\ADMPARAMETROC::first();
        $porMoraDia = $parametroc->porintmoradia;
        Log::info("Porcentaje de Interes Mensual %" . round($porMoraDia, 3));
        $fecha = Carbon::createFromFormat('Y-m-d', $fechaPago)->Format('d-m-Y');
        $fechadepago = Carbon::createFromFormat('Y-m-d', $fechaPago);

        DB::beginTransaction();
        try {

            foreach ($cuotas as $index => $val) {
                $fechavenscic = $val['FECHAVEN'];
                $saldoCuota = round($val['SALDO'], 2);
                $numCuota = $val['NUMCUOTA'];
                $fechadevenciminetocuota = Carbon::parse($fechavenscic)->format('Y-m-d');
                $fechadevencuota = Carbon::createFromFormat('Y-m-d', $fechadevenciminetocuota); //->format('d-m-Y');
                $dias = $fechadepago->diffInDays($fechadevencuota, false);

                if ($monto <= 0) {
                    Log::info('Ya no hay dinero para seguir pagando la Cuota.' .$numCuota);
                    break;
                }

                $calculointeres = 0;
                $valorinteres = 0;
                Log::info("Dias (-)atrasado / (+)adelantado = " . $dias . " Cuota: " . $val['NUMCUOTA']);

                if ($dias >= 0) {
                    Log::info("Cuota NO Generará Interes porque está en fechas, NumCuot -> " . $val['NUMCUOTA']);
                    $dias = 0;
                } else {
                    $dias = $dias * -1;
                    Log::info("Cuota SI Generará Interes por Atraso NumCuota -> " . $val['NUMCUOTA']);
                    $calculointeres = round($porMoraDia * $saldoCuota, 2) / 100;
                    $valorinteres = round($dias * $calculointeres, 2);
                }

                if ($monto >= $valorinteres) {
                    // Se paga el interes completo                    
                    $creaNDB = $this->GenerarNDB(
                        $valorinteres,
                        $bodegaDeuda,
                        $fcPag,
                        $ope,
                        $observacionNDB,
                        $vendedor,
                        $date,
                        $numeroPago,
                        $clienteData,
                        $serie,
                        $cajac,
                        $seriefac,
                        $numerofac,
                        $bodegafac,
                        $fechafac,
                        $numCuota,
                        $secinvfac
                    );
                    if ($creaNDB) {
                        $motoANotasDebitos += $valorinteres;
                        Log::info("NDB generada por monto: " . $valorinteres . " de la cuota Nº " . $numCuota . " de SecuDeuda " . $secuencial);
                    } else {
                        DB::rollback();
                        return response()->json(['error' => 'Error en proceso de Creación de Nota de Debito.']);
                    }
                    // Procede a pagar con el restante la couta.
                    $monto = $monto - round($valorinteres, 2);

                    if ($monto <= 0) {
                        Log::info('Solo alcanzó pagar el interes.');
                        break;
                    }
                    // Consulta de las Cuotas a pagar
                    $deCuo = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
                        ->where('NUMCUOTA', '=', $numCuota)
                        ->first();

                    Log::info('Quedan ' . $monto . ' para pagar el Saldo de la Couta => ' . round($deCuo->SALDO, 2));
                    if ($monto >= round($deCuo->SALDO, 2)) {
                        // Paga el saldo de la Cuota Completo
                        Log::info('Se Procede a pagar el saldo de la cuota COMPLETO.');
                        $pagoCompleto = $this->PagarCuotaCompleta($secuencial, $numCuota, $fecha, $pago, $observa, $ope);
                        if ($pagoCompleto === false) {
                            DB::rollback();
                            Log::info('Error pagando Cuota Completa.');
                            return response()->json(['error' => 'Error en proceso de Pago de Cuota.']);
                        } else {
                            $montoADeudas += round($deCuo->SALDO, 2);
                            $monto = round($monto - $deCuo->SALDO, 2);
                            Log::info('Se pago parte de la cuota y quedó para pagar la siguiente => ' . $monto.'$');
                        }
                    } else {
                        // Paga parte de la Cuota
                        Log::info('Se Procede a pagar el saldo de la cuota PARCIAL.');
                        $pagoParcial = $this->PagarCuotaParte($secuencial, $monto, $numCuota, $fecha, $pago, $observa, $ope);
                        if ($pagoParcial === false) {
                            DB::rollback();
                            Log::info('Error pagando Cuota Parcial.');
                            return response()->json(['error' => 'Error en proceso de Pago de Cuota.']);
                        } else {
                            $montoADeudas += $monto;
                            Log::info('Se pago parte de la cuota y NO quedó dinero para pagar la siguiente.');
                            $monto = 0;
                        }
                    }
                } else {

                    // No se puede pagar el interes completo. 

                    $motoANotasDebitos +=  $monto;

                    // Se hace NDB por el Interes Completo de la deuda
                    $creaNDBParcial = $this->GenerarNDB(
                        $valorinteres,
                        $bodegaDeuda,
                        $fcPag,
                        $ope,
                        $observacionNDB,
                        $vendedor,
                        $date,
                        $numeroPago,
                        $clienteData,
                        $serie,
                        $cajac,
                        $seriefac,
                        $numerofac,
                        $bodegafac,
                        $fechafac,
                        $numCuota,
                        $secinvfac
                    );

                    $valorFaltanteNDB = $valorinteres - $monto;

                    $montoNegativo = $valorFaltanteNDB * -1;
                    $montoNegativoGlobal = $montoNegativo;

                    Log::info('Se procede a Realizar Lineas Negativas por ' . $montoNegativo);
                    $pagoParcial = $this->PagarCuotaParte($secuencial, $montoNegativo, $numCuota, $fecha, $pago, $observa, $ope);

                    $parametrov = ADMPARAMETROV::first();
                    $seccuencialNuevo = $parametrov->SECUENCIAL + 1;

                    $deuda = \App\ADMDEUDA::where('SECUENCIAL', '=', $secuencial)
                        ->whereIn('TIPO', ['NVT', 'FAC', 'NDB'])
                        ->where('CLIENTE', trim($clienteData))
                        ->first();

                    $saldoDeuda = round($deuda->SALDO, 2);

                    // Crear Linea ADMCREDITO Negativa 
                    $creditoLinea = new \App\ADMCREDITO();
                    $creditoLinea->SECUENCIAL = $seccuencialNuevo;
                    $creditoLinea->BODEGA = $credito->BODEGA;
                    $creditoLinea->CLIENTE = $credito->CLIENTE;
                    $creditoLinea->TIPO = $credito->TIPO;
                    $creditoLinea->NUMERO = $credito->NUMERO;
                    $creditoLinea->SERIE = $credito->SERIE;
                    $creditoLinea->SECINV = $credito->SECINV;
                    $creditoLinea->TIPOCR = 'PAG';
                    $creditoLinea->NUMCRE = $numeroPago;
                    $creditoLinea->SERIECRE = '';
                    $creditoLinea->NOAUTOR = '';
                    $creditoLinea->FECHA = Carbon::createFromFormat('d-m-Y', $fcPag)->Format('Y-d-m');
                    $creditoLinea->IVA = 0;
                    $creditoLinea->MONTO =  $montoNegativo;
                    $creditoLinea->SALDO = round(($saldoDeuda - $montoNegativo), 2);
                    $creditoLinea->OPERADOR = $ope;
                    $creditoLinea->OBSERVACION = $observa;
                    $creditoLinea->VENDEDOR = $vendedor;
                    $creditoLinea->HORA = $date->Format('H:i:s');
                    $creditoLinea->NOMBREPC = 'Servidor Laravel';
                    $creditoLinea->estafirmado = 'N';
                    $creditoLinea->ACT_SCT = 'N';
                    $creditoLinea->seccreditogen = 0;
                    $creditoLinea->save();

                    // Adiciona el valor restante de la NDB a la ADMDEUDA
                    $deuda->SALDO =  $saldoDeuda + $valorFaltanteNDB;
                    $deuda->save();

                    $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;
                    $parametrov->save();
                    $monto = 0;
                    Log::info('Proceso de Creación de registros Negativos Culminado( Monto = 0).');
                    break;
                }
            }
            DB::commit();
            $dataResultante = ['proceso' => true, 'notasDebito' => $motoANotasDebitos, 'pagosDeudas' => $montoADeudas, 'pagoNegativo' => $montoNegativoGlobal];
            return $dataResultante;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error pagoCuota:", ['Mensaje' => $e->getMessage()]);
            return ['proceso' => false, 'notasDebito' => 0, 'pagosDeudas' => 0];
        }
    }

    public function PagarCuotaCompleta($secuencial, $numCuota, $fecha, $pago, $observa, $ope)
    {

        try {
            $deCuo = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
                ->where('NUMCUOTA', '=', $numCuota)
                ->first();
            //Guardado alternativo por problemas con PK compuestos;
            $result = DB::table('ADMDEUDACUOTA')
                ->where('SECDEUDA', $secuencial)
                ->where('NUMCUOTA', $numCuota)
                ->update([
                    'SALDO' => 0,
                    'CREDITO' => $deCuo->SALDO,
                    'FECHACANCELACUOTA' => Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m'),
                    'NUMPAGO' => $pago
                ]);

            $admdeuda = \App\ADMDEUDA::where('SECUENCIAL', $secuencial)->first();

            $deCuoDet1 = new \App\ADMDEUDACUOTADET();
            $deCuoDet1->SECDEUDA = $secuencial;
            $deCuoDet1->SECINV = $admdeuda->SECINV;
            $deCuoDet1->NUMCUOTA = $numCuota;
            $deCuoDet1->VALORCUOTA = round($deCuo->MONTO, 2);
            $deCuoDet1->MONTO = round($deCuo->SALDO, 2);
            $deCuoDet1->SALDO = 0;
            $deCuoDet1->NUMPAGO = $pago;
            $deCuoDet1->FECHACANCELA = Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m');
            $deCuoDet1->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s', $deCuo->FECHAVEN)->Format('Y-d-m');
            $deCuoDet1->OBSERVACION = $observa;
            $deCuoDet1->OPERADOR = $ope;
            $deCuoDet1->MAQUINA = "SERVER LARAVEL";
            $deCuoDet1->HORA = Carbon::now()->format('H:i:s');
            $deCuoDet1->save();
            return true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function PagarCuotaParte($secuencial, $monto, $numCuota, $fecha, $pago, $observa, $ope)
    {
        # Abono a Cuota.
        try {
            $deCuo = \App\ADMDEUDACUOTA::where('SECDEUDA', '=', $secuencial)
                ->where('NUMCUOTA', '=', $numCuota)
                ->first();

            //Guardado alternativo por problemas con PK compuestos;
            $saldoFinal =  round($deCuo->SALDO - $monto, 2);
            $creditoFinal = $deCuo->CREDITO + $monto;
            $result = DB::table('ADMDEUDACUOTA')
                ->where('SECDEUDA', $secuencial)
                ->where('NUMCUOTA', $numCuota)
                ->update([
                    'SALDO' => round($saldoFinal, 2),
                    'CREDITO' => round($creditoFinal, 2),
                    'FECHACANCELACUOTA' => Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m'),
                    'NUMPAGO' => $pago
                ]);

            $admdeuda = \App\ADMDEUDA::where('SECUENCIAL', $secuencial)->first();

            $deCuoDet2 = new \App\ADMDEUDACUOTADET();
            $deCuoDet2->SECDEUDA = $secuencial;
            $deCuoDet2->SECINV = $admdeuda->SECINV;
            $deCuoDet2->NUMCUOTA = $numCuota;
            $deCuoDet2->VALORCUOTA = $deCuo->MONTO;
            $deCuoDet2->MONTO = $monto;
            $deCuoDet2->SALDO = round($deCuo->SALDO - $monto, 2);
            $deCuoDet2->NUMPAGO = $pago;
            $deCuoDet2->FECHACANCELA = Carbon::createFromFormat('d-m-Y', $fecha)->Format('Y-d-m');
            $deCuoDet2->FECHAVENCE = Carbon::createFromFormat('Y-m-d H:i:s', $deCuo->FECHAVEN)->Format('Y-d-m');
            $deCuoDet2->OBSERVACION = $observa;
            $deCuoDet2->OPERADOR = $ope;
            $deCuoDet2->MAQUINA = "SERVER LARAVEL";
            $deCuoDet2->HORA = Carbon::now()->format('H:i:s');
            $deCuoDet2->save();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    public function GenerarNDBSolo()
    {
        $secDeuda = 113000;
        $operador1 = 'CLA';
        $interes = 0.43;
        $fcPag = '04/09/2022';
        $numpago = 49469;
        $numcuotadet = 3;

        $deudaOriginal = \App\ADMDEUDA::where('SECUENCIAL', $secDeuda)->first();

        if ($deudaOriginal == null) {
            return response()->json('Error -> No se consigue la deuda.');
        }

        $operadorData = \App\ADMOPERADOR::where('CODIGO', '=', $operador1)->first();
        $cajac = $operadorData->caja;
        Log::info('operadorData caja' . $cajac);


        $vende = $deudaOriginal->VENDEDOR;
        $date  = Carbon::now()->subHours(5);
        $cliente = $deudaOriginal->CLIENTE;
        $serie = $deudaOriginal->SERIE;
        $seriefac = $deudaOriginal->SERIE;
        $numerofac = $deudaOriginal->NUMERO;
        $bodegafac = $deudaOriginal->BODEGA;
        $bodegaDeuda = $deudaOriginal->BODEGA;
        $observa = 'NDB por Interes de cuota  ADMGO';


        DB::beginTransaction();
        try {
            Log::info('Entra el Metodo GerneraNDB');
            $parametrov = ADMPARAMETROV::first();
            $numeroNDB =  \App\ADMTIPODOC::where('TIPO', '=', 'NDB')->first();

            $deudaNDB = new \App\ADMDEUDA();
            $deudaNDB->SECUENCIAL = $parametrov->SECUENCIAL + 1;
            $deudaNDB->BODEGA = $bodegaDeuda;
            $deudaNDB->CLIENTE = $cliente;
            $deudaNDB->TIPO = "NDB";
            $deudaNDB->MOTIVO = "IPD";
            $deudaNDB->NUMERO = $numeroNDB->CONTADOR + 1;
            $deudaNDB->SERIE = $serie;
            $deudaNDB->SECINV = $parametrov->SECUENCIAL + 1;
            $deudaNDB->IVA = 0;
            $deudaNDB->MONTO = $interes;
            $deudaNDB->CREDITO = $interes;
            $deudaNDB->SALDO = 0;
            $deudaNDB->FECHAEMI = $fcPag;
            $deudaNDB->FECHAVEN = $fcPag;
            $deudaNDB->FECHADES = $fcPag;
            $deudaNDB->HORA = $date->Format('H:i:s');
            $deudaNDB->CAJAC = $cajac;
            $deudaNDB->BANCO = '';
            $deudaNDB->CUENTA = '';
            $deudaNDB->NUMCHQ = '';
            $deudaNDB->ESTCHQ = '';
            $deudaNDB->OPERADOR = $operador1;
            $deudaNDB->VENDEDOR = $vende;
            $deudaNDB->OBSERVACION = $observa;
            $deudaNDB->NUMAUTO = "";
            $deudaNDB->BODEGAFAC = $bodegafac;
            $deudaNDB->SERIEFAC = $seriefac;
            $deudaNDB->NUMEROFAC = $numerofac;
            //  $deudaNDB->FECHAFAC = $fechafac;
            $deudaNDB->NOMBREPC = 'Servidor Laravel';
            $deudaNDB->ACT_SCT = "N";
            $deudaNDB->montodocumento = 0;
            $deudaNDB->tipoventa = "";
            $deudaNDB->mesescredito = 0;
            $deudaNDB->tipopago = "";
            $deudaNDB->usuarioeli = "";
            $deudaNDB->EWEB = "N";
            $deudaNDB->save();
            Log::info("Graba Nota de Debito => DEUDA por $" . $interes);

            $creditoLinea2 = new \App\ADMCREDITO();
            $creditoLinea2->SECUENCIAL = $deudaNDB->SECUENCIAL;
            $creditoLinea2->SECINV = $deudaNDB->SECUENCIAL;
            $creditoLinea2->BODEGA = $deudaNDB->BODEGA;
            $creditoLinea2->CLIENTE = $deudaNDB->CLIENTE;
            $creditoLinea2->TIPO = $deudaNDB->TIPO;
            $creditoLinea2->NUMERO = $deudaNDB->NUMERO;
            $creditoLinea2->SERIE = $serie;
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
            $creditoLinea3->SERIE = $serie;
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
            $creditoLinea3->IVA = 0;
            $creditoLinea3->SERIECRE = "";
            $creditoLinea3->NOAUTOR = "";
            $creditoLinea3->seccreditogen = 0;
            $creditoLinea3->NUMCUOTA = $numcuotadet;
            $creditoLinea3->save();

            $numeroNDB->CONTADOR = $numeroNDB->CONTADOR + 1;
            $numeroNDB->save();

            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 2;
            $parametrov->save();

            DB::commit();
            Log::info("Graba Nota debito => CREDITO por $" . $interes);
            return response()->json('Nota de Debito numero ' . $deudaNDB->NUMERO . ' secuencial ' . $deudaNDB->SECUENCIAL);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error Generando Nota de Debito por $" . $interes, ['Mensaje' => $e->getMessage()]);
            return response()->json($e->getMessage());
        }
    }
}

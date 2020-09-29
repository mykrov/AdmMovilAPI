<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use \App\ADMPARAMETROV;
use Illuminate\Support\Facades\DB;


class PagosPosController extends Controller
{
    public function Pagopos(Request $r)
    {
        //return response()->json("si llega");
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

        $secDelPago = 0;


        //para actualizar al final el seccon
        $numComproContable = 0;
        $numeroPago = 0;
        $numeroMoviBancoCIa = 0;

        DB::beginTransaction();  
        try {
            //ADMPAGOPOS
            $vendedorData = \App\ADMVENDEDOR::where('CODIGO','=',$vendedor)->first();
            $operador1 = '';
            $cajaAbiertaPOS = DB::table('ADMAPERTURACAJAPOS')
            ->where([['CODIGOCAJA','=',$vendedorData->caja],['ESTADO','=','A']])
            ->where($date->format('d-m-Y'),'>=','FECHADESDE')
            ->where($date->format('d-m-Y'),'<=','FECHAHASTA')
            ->get();

            return response()->json($cajaAbiertaPOS);

            if($cajaAbiertaPOS == null){
                return response()->json(['estado'=>'error','info'=>'NO HAY CAJA']);
            }

            //return response()->json($cajaAbiertaPOS);   
            //Datos del Operador segun vendedor
            if($vendedorData == null || $vendedorData->operadormovil == null || trim($vendedorData->operadormovil) == ''){
                $operador1 = 'ADM';
            }else{
                $operador1 = trim($vendedorData->operadormovil);
            }

            $pago = new \App\ADMPAGOPOS();
            $pago->CODIGOCAJA = $cajaAbiertaPOS[0]->CODIGOCAJA;
            $pago->NUMEROAPERTURA =   $cajaAbiertaPOS[0]->NUMEROAPERTURA;

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
            $pago->vendedor = $vendedor;
            $pago->oripago = "C";
            $pago->hora = $date->Format('H:i:s');
            $pago->nombrepc = 'Servidor Laravel';
            $pago->cajac = $cajaAbiertaPOS[0]->CODIGOCAJA;
            $pago->fechaeli = "";
            $pago->horaeli = "";
            $pago->maquinaeli = "";
            $pago->operadoreli = "";
            $pago->save();

            $observacionCre = $pago->observacion;

            //ADMDETPAGOPOS
            $detPago = new \App\ADMDETPAGOPOS();
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
            //$detPago->vendedor = $vendedor;
            //$detPago->intregrado = "N";
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
                $movibanco->cajac = $cajaAbiertaPOS[0]->CODIGOCAJA;
                $movibanco->conciliado = "N";
                $movibanco->save();

                $tipoDocccb->NUMERO = $numeroCCB;
                $tipoDocccb->save();

            }

            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;
            $parametrov->save();

            //Proceso de las Facturas a pagar
            foreach ($deudas as $pos => $val) {
                $numDeuda = $val['numero'];
                $montoPagar = round($val['monto'],2);
                
                $deuda = \App\ADMDEUDAPOS::where('SECUENCIAL','=',$numDeuda)
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
                    
                    $credito = \App\ADMCREDITOPOS::where('SECUENCIAL','=',$numDeuda)
                    ->whereIn('TIPO',['NVT','FAC','NDB'])
                    ->first();
                    
                    //Nueva Linea ADMCREDITOPOS
                    $creditoLinea = new \App\ADMCREDITOPOS();
                    $creditoLinea->CODIGOCAJA = $cajaAbiertaPOS[0]->CODIGOCAJA;
                    $creditoLinea->NUMEROAPERTURA = $cajaAbiertaPOS[0]->NUMEROAPERTURA;
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
                    $creditoLinea->VENDEDOR = $vendedor;
                    $creditoLinea->HORA = $date->Format('H:i:s');
                    $creditoLinea->NOMBREPC = 'Servidor Laravel';
                    $creditoLinea->save();
                    
                }else{
                    DB::rollback();
                    return response()->json(['error'=>'DeudaPOS no encontrada: Secuencial '.$numDeuda]);
                }  
            }
            
            DB::commit();
            return response()->json(['estado'=>'ok','numPago'=> $pago->numero]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error"=>["ADMPAGOPOS"=>$e->getMessage()]]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use \App\ADMBODEGA;
use \App\ADMPARAMETROV;
use \App\ADMPARAMETROBO;
use \App\Cliente;
use \App\ADMVENDEDOR;
use \App\AMDCAJAPOS;

class VentaPos extends Controller
{
    public function Pedido(Request $r)
    {

        //obtencion de cabecera y detalles del request
        $cabecera = $r->cabecera[0];
        $detalles = $r->detalles;

        DB::beginTransaction();

        try {

              //En caso de Observacion.
            $observacion = "Gracias por su Compra.";
            if(trim($cabecera['observacion']) != ""){
                $observacion = $cabecera['observacion'];
            }

            $campo_adi = "Gracias por su Compra.";
            if(trim($cabecera['datos_adi']) != ""){
                $campo_adi = $cabecera['datos_adi'];
            }

            // consulta de parametros necesarios
            //$bodega = ADMBODEGA::where('CODIGO','=',$cabecera['bodega'])->first();
            $parametrov = ADMPARAMETROV::first();
            $cliente = Cliente::where('CODIGO','=',$cabecera['cliente'])->first();
            $parametrobo = ADMPARAMETROBO::first();
            //Campos de Venta-Vendedor
            $vendedor = ADMVENDEDOR::where('CODIGO','=',$cabecera['usuario'])->first();
            $usaDecimal = $parametrov->usardecimales;
            
            $cajaPos = AMDCAJAPOS::where('codigo','=',$vendedor->caja)->first();

            $aperturaCP = \App\ADMAPERTURACAJAPOS::where('ESTADO','=','A')
            ->where('CODIGOCAJA','=',$vendedor->caja)
            ->first();

            if($aperturaCP == null){
                return response()->json(['estado'=>'error','info'=>'NO HAY CAJA']);
            }
            //return response()->json($cajaPos);

            $grabaIva = "N";
            if(floatval($cabecera['iva']) > 0){
                $grabaIva = "S";
            }
            
            $date = Carbon::now()->subHours(5);
            
            // creacion de entidad y guardado.
            $cab = new \App\ADMCABEGRESOPOS();

            $cab->CODIGOCAJA = $vendedor->caja;
            $cab->NUMEROAPERTURA = $aperturaCP->NUMEROAPERTURA;
            $cab->SECUENCIAL = $parametrov->SECUENCIAL + 1; 
            $cab->TIPO = $cabecera['tipo']; 
            $cab->BODEGA = intval($cabecera['bodega']); 
            $cab->NUMERO = $cajaPos->numeroventa + 1; //actualizar al final.
            $cab->SERIE = trim($cajaPos->seriedocumento);
            $cab->NUMPEDIDO = 0; 
            $cab->NUMGUIA = null; 
            $cab->CAMION = null; 
            $cab->CHOFER = null; 
            $cab->DOCREL = null; 
            $cab->NUMEROREL = null;
            $cab->FECHA = $date->Format('Y-d-m'); 
            $cab->FECHAVEN = $date->addDays(intval($cliente->DIASCREDIT))->format('Y-d-m'); 
            $cab->FECHADES = $cabecera['fecha_ingreso'];
            $cab->OPERADOR = $aperturaCP->OPERADOR;
            $cab->CLIENTE = $cabecera['cliente']; 
            $cab->VENDEDOR = $cabecera['usuario']; 
            $cab->PROVEEDOR = null; 
            $cab->SUBTOTAL = round($cabecera['subtotal'],2); 
            $cab->DESCUENTO = $cabecera['descuento']; 
            $cab->IVA = round($cabecera['iva'],2); 
            $cab->NETO = "";
            $cab->NETO = round($cabecera['neto'],2); 
            $cab->TRANSPORTE = 0; 
            $cab->RECARGO = 0; 
            $cab->BODEGADES = 0; 
            $cab->PESO = 0; 
            $cab->VOLUMEN = 0; 
            $cab->MOTIVO = null;
            $cab->ESTADO = "X"; 
            $cab->ESTADODOC = null; 
            $cab->TIPOVTA = "BIE"; 
            $cab->INTECXC = null; 
            $cab->OBSERVA = $observacion;
            $cab->COMENTA = $campo_adi;
            $cab->INTEGRADO = null;
            $cab->SECCON = 0; 
            $cab->NUMSERIE = null; 
            $cab->NOCARGA = null; 
            $cab->APLSRI = null;
            $cab->NUMAUTO = "";
            $cab->NUMFISICO = null; 
            $cab->HORA = $cabecera['hora_ingreso']; 
            $cab->NOMBREPC = "Servidor Laravel";
            $cab->claseAjuEgreso = null; 
            $cab->SUBTOTAL0 = 0; 
            $cab->NUMGUIATRANS = null; 
            $cab->GRAVAIVA = $grabaIva; 
            $cab->horaventa = "";
            $cab->admventa = "S";
            $cab->numeroventa = 0;
            $cab->fechaventa = null;

            $cajaPos->numeroventa  = $cajaPos->numeroventa + 1;
            $cajaPos->save();
            $cab->save();

            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;

            //Procesado de los Detalles.
            $lineaDet = 1;
            foreach ($detalles as $det) {
                
                // creacion y llenado del detalle
                $d = new \App\ADMDETEGRESOPOS;
                
                $grabaIvadet = "N";
                if(floatval($det['iva']) > 0){
                    $grabaIvadet = "S";
                }

                // Datos del item
                $itemData = \App\ADMITEM::where('ITEM','=',trim($det['item']))->first();

                
                if($usaDecimal == 'N'){
                    $d->CANTIU = intval($det['total_unidades']) % $itemData->FACTOR;
                    $d->CANTIC = intval($det['total_unidades']  / $itemData->FACTOR);
                    $d->CANTFUN = intval($det['total_unidades']);
                }else{
                    $d->CANTIU = round(floatval($det['total_unidades']) % $itemData->FACTOR,2);
                    $d->CANTIC =  round(floatval($det['total_unidades']  / $itemData->FACTOR),2);
                    $d->CANTFUN =  round(floatval($det['total_unidades']),2);
                }

                $d->SECUENCIAL = intval($cab->SECUENCIAL);
                $d->LINEA = $lineaDet;
                $d->ITEM = $det['item'];
                $d->TIPOITEM = $det['tipo_item'];
                $d->PRECIO = floatval($det['precio']);
                $d->COSTOP = $itemData->COSTOP;
                $d->COSTOU = $itemData->COSTOU;               
                $d->CANTDEV = null;
                $d->SUBTOTAL = round(floatval($det['subtotal']),2);
                $d->DESCUENTO = round(floatval($det['descuento']),2);
                $d->IVA = round(floatval($det['iva']),2);
                $d->NETO = round(floatval($det['neto']),2);
                $d->PORDES = floatval($det['pordes']);
                $d->LINEAREL = null;
                $d->TIPOVTA = "V";
                $d->FORMAVTA = $det['forma_venta'];
                $d->GRAVAIVA = $grabaIvadet;
                $d->escambio = '';
                $d->fechaven = null;
                $d->lote = '';
                $d->estado = '';
                $d->hora = '';
                
                $d->save();
                $lineaDet++;
                
            }

            $parametrov->save();
            DB::commit();
            return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error"=>["info"=>$e->getMessage()]]);
        }
    }
}

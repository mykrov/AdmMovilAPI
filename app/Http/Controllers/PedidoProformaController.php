<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use \App\ADMCABPEDIDO;
use \App\ADMDETPEDIDO;
use Illuminate\Support\Facades\Log;

class PedidoProformaController extends Controller
{
    public function PostPedidoProforma(Request $r)
    {
        ///return $r;
        $cabecera = $r->cabecera[0];
        $detalles = $r->detalles;

        if (count($detalles) == 0) {
            Log::error("Cabecera Sin Detalles",['cabecera'=>$cabecera]);
            return response()->json(['error'=>'Cabecera Sin Detalles']);
        }

        $bodega = \App\ADMBODEGA::where('CODIGO','=',$cabecera['bodega'])->first();
        $parametrov = \App\ADMPARAMETROV::first();
        $cliente = \App\Cliente::where('CODIGO','=',$cabecera['cliente'])->first();
        $parametrobo = \App\ADMPARAMETROBO::first();
        $date = Carbon::now()->subHours(5);

        $secuen = $parametrov->SECUENCIAL + 1; //actualizar
        $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;
        $parametrov->save();

        $numPed = $bodega->NOPEDIDO + 1; //actualizar
        $bodega->NOPEDIDO = $bodega->NOPEDIDO +1;
        $bodega->save();


        $grabaIva = "N";
        if(floatval($cabecera['iva']) > 0){
            $grabaIva = "S";
        }

         
        //En caso de Observacion.
        $observacion = "Gracias por su Compra.";
        if(trim($cabecera['observacion']) != "" && $cabecera['observacion'] != null){
            $observacion = $cabecera['observacion'];
            //Log::info("Observacion de PedidoProforma: ",['Contenido'=>$cabecera['observacion']]);
        }

        $campo_adi = "Gracias por su Compra.";
        if(trim($cabecera['datos_adi']) != ""  && $cabecera['datos_adi'] != null){
            $campo_adi = $cabecera['datos_adi'];
            //Log::info("Datos Adi de PedidoProforma: ",['Contenido'=>$cabecera['datos_adi']]);
        }

        DB::beginTransaction();
        try {

            $cabe = new ADMCABPEDIDO();
            $cabe->TIPO = "PED";
            $cabe->BODEGA = intval($cabecera['bodega']);
            $cabe->NUMERO = $numPed;
            $cabe->SECUENCIAL = $secuen; 
            $cabe->CLIENTE = $cabecera['cliente'];
            $cabe->VENDEDOR = $cabecera['usuario'];
            $cabe->FECHA = $date->Format('Y-d-m');
            $cabe->ESTADO = "WEB";
            $cabe->SUBTOTAL = round($cabecera['subtotal'],2);
            $cabe->DESCUENTO = round($cabecera['descuento'],2);
            $cabe->IVA = round($cabecera['iva'],2);
            $cabe->NETO = round($cabecera['neto'],2);
            $cabe->PESO = 0;
            $cabe->VOLUMEN = 0;
            $cabe->OPERADOR = "ADM";
            $cabe->COMENTARIO = $campo_adi;
            $cabe->OBSERVACION = $observacion;
            $cabe->DOCFAC = trim($cabecera['tipo']);
            $cabe->DIASCRED = $cliente->TDCREDITO ;
            $cabe->GRAVAIVA = $grabaIva;
            $cabe->CREDITO = "N";
            $cabe->TIPOCLIENTE = trim($cliente->TIPO);
            $cabe->SUCURSAL = "";
            $cabe->ESMOVIL = "S";
            $cabe->numeroplantilla = 0;
            $cabe->save();

            $linea = 1;

            if (count($detalles) > 0) { //Cuando no trae detalles la cabecera.
                
                foreach ($detalles as $det){

                    $d = new \App\ADMDETPEDIDO;
                    
                    $grabaIvadet = "N";
                    if(floatval($det['iva']) > 0){
                        $grabaIvadet = "S";
                    }
    
                    $itemData = \App\ADMITEM::where('ITEM','=',trim($det['item']))->first();
                    
                    $d->LINEA = $linea;
                    $d->SECUENCIAL = $cabe->SECUENCIAL;
                    $d->ITEM = $det['item'];
                    $d->CANTIC = intval($det['total_unidades']  / $itemData->FACTOR);
                    $d->CANTIU = intval($det['total_unidades']) % $itemData->FACTOR;
                    $d->CANTFUN = intval($det['total_unidades']);
                    $d->PRECIO = floatval($det['precio']);
                    $d->SUBTOTAL = round(floatval($det['subtotal']),2);
                    $d->PORDES = floatval($det['pordes']);
                    $d->DESCUENTO = floatval($det['descuento']);
                    $d->IVA = round(floatval($det['iva']),2);
                    $d->NETO = round(floatval($det['neto']),2);
                    $d->COSTOP = $itemData->COSTOP;
                    $d->COSTOU = $itemData->COSTOU;
                    $d->TIPOITEM = $det['tipo_item'];
                    $d->FORMAVTA = $det['forma_venta'];
                    $d->GRAVAIVA = $grabaIvadet;
                    $d->LINGENCONDICION = 0;
                    $d->PORDES2 = 0;
                    $d->save();
                    $linea++;
                } 
                DB::commit();
                Log::info("Registro de PedidoProformaController ", ["cabecera" => $cabe,"detalles"=> $d,"TiempoPreciso"=>Carbon::now()->subHours(5)->format('H:m:s.u')]);
                return response()->json(["estado"=>"guardado", "Npedido"=>$cabe->NUMERO, "secuencial"=>$cabe->SECUENCIAL]);
    
            } else {
                DB::rollback();
                Log::error("Cabecera Sin Detalles",['cabecera'=>$cabe]);
                return response()->json(['error'=>["info"=>'Cabecera Sin Detalles']]);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error PedidoProformaController ", ["cabecera" => $cabe,"TiempoPreciso"=>Carbon::now()->subHours(5)->format('H:m:s.u'),"Datos"=>$e->getMessage()]);
            return response()->json(["error"=>["info"=>"Error Insertando Pedido"]]);
        }
    }    
}

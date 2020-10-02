<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use \App\ADMCABPEDIDO;
use \App\ADMDETPEDIDO;

class PedidoProformaController extends Controller
{
    public function PostPedidoProforma(Request $r)
    {
        $cabecera = $r->cabecera[0];
        $detalles = $r->detalles;

        $bodega = \App\ADMBODEGA::where('CODIGO','=',$cabecera['bodega'])->first();
        $parametrov = \App\ADMPARAMETROV::first();
        $cliente = \App\Cliente::where('CODIGO','=',$cabecera['cliente'])->first();
        $parametrobo = \App\ADMPARAMETROBO::first();
        $date = Carbon::now()->subHours(5);

        $grabaIva = "N";
        if(floatval($cabecera['iva']) > 0){
            $grabaIva = "S";
        }

         //En caso de Observacion.
        $observacion = "Gracias por su Compra.";
        if(trim($cabecera['observacion']) != ""){
            $observacion = $cabecera['observacion'];
        }

        $campo_adi = "Gracias por su Compra.";
        if(trim($cabecera['observacion']) != ""){
            $campo_adi = $cabecera['datos_adi'];
        }

        DB::beginTransaction();
        try {

            $cabe = new ADMCABPEDIDO();
            
            $cabe->TIPO = "PED";
            $cabe->BODEGA = intval($cabecera['bodega']);
            $cabe->NUMERO = $bodega->NOPEDIDO + 1; //actualizar
            $cabe->SECUENCIAL = $parametrov->SECUENCIAL + 1; //actualizar
            $cabe->CLIENTE = $cabecera['cliente'];
            $cabe->VENDEDOR = $cabecera['usuario'];
            $cabe->FECHA = $date->Format('Y-d-m');;
            $cabe->ESTADO = "WEB";
            $cabe->SUBTOTAL = round($cabecera['subtotal'],2);;
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

            $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;
            $parametrov->save();

            $bodega->NOPEDIDO = $bodega->NOPEDIDO +1;
            $bodega->save();

            $linea = 1;

            foreach ($detalles as $det){

                $d = new \App\ADMDETPEDIDO;
                
                $grabaIvadet = "N";
                if(floatval($det['iva']) > 0){
                    $grabaIvadet = "S";
                }

                $itemData = \App\ADMITEM::where('ITEM','=',trim($det['item']))->first();
                
                $d->LINEA = $linea;
                $d->SECUENCIAL = $cabe->SECUENCIAL ;
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
            return response()->json(["estado"=>"guardado", "Npedido"=>$cabe->NUMERO, "secuencial"=>$cabe->SECUENCIAL]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error"=>["info"=>$e->getMessage()]]);
        }
    }    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use \App\ADMCABPEDIDOX;
use \App\ADMDETPEDIDOX;
use Illuminate\Support\Facades\Log;

class PedidoXController extends Controller
{
    public function PostPedidoProformaOff(Request $r)
    {
        ///return $r;
        $cabecera = $r->cabecera[0];
        $detalles = $r->detalles;

        if (count($detalles) == 0) {
            Log::error("Cabecera Sin Detalles",['cabecera'=>$cabecera]);
            return response()->json(['error'=>'Cabecera Sin Detalles']);
        }

        $cliente = \App\Cliente::where('CODIGO','=',$cabecera['cliente'])->first();
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
        if(trim($cabecera['datos_adi']) != ""){
            $campo_adi = $cabecera['datos_adi'];
        }

        $secAutoNew = 0;

        DB::beginTransaction();
        try {

            $cabe = new ADMCABPEDIDOX();
            $cabe->TIPO = "PED";
            $cabe->BODEGA = intval($cabecera['bodega']);
            $cabe->NUMERO = $cabe->SECAUTO;
            $cabe->SECUENCIAL = $cabe->SECAUTO;
            $cabe->CLIENTE = $cabecera['cliente'];
            $cabe->VENDEDOR = $cabecera['usuario'];
            $cabe->FECHA = $date->Format('Y-d-m');
            $cabe->HORA = $date->Format('H:i:s');
            $cabe->ESTADO = "A";
            $cabe->SUBTOTAL = round($cabecera['subtotal'],2);
            $cabe->DESCUENTO = round($cabecera['descuento'],2);
            $cabe->IVA = round($cabecera['iva'],2);
            $cabe->NETO = round($cabecera['neto'],2);
            $cabe->PESO = 0;
            $cabe->VOLUMEN = 0;
            $cabe->OPERADOR = "ADM";
            $cabe->COMENTARIO = $campo_adi;
            $cabe->OBSERVACION =  $observacion;
            $cabe->GRAVAIVA = $grabaIva;
            $cabe->DOCFAC = trim($cabecera['tipo']);
            $cabe->DIASCRED = $cliente->TDCREDITO ;
            $cabe->REGISTRADO = 'N';
            $cabe->CREDITO = "N";
            $cabe->FECHACREA = $date->Format('Y-d-m');
            $cabe->HORACREA = $date->Format('H:i:s');
            $cabe->CLTENUEVO = 'N';
            $cabe->CODIGOMOVIL = $r->ip();
            $cabe->save();
            
            $secAutoNew = $cabe->SECAUTO;
            $linea = 1;

            if (count($detalles) > 0) { //Cuando no trae detalles la cabecera.
                
                foreach ($detalles as $det){

                    $d = new \App\ADMDETPEDIDOX;
                    
                    $grabaIvadet = "N";
                    if(floatval($det['iva']) > 0){
                        $grabaIvadet = "S";
                    }
    
                    $itemData = \App\ADMITEM::where('ITEM','=',trim($det['item']))->first();
                    
                    $d->LINEA = $linea;
                    $d->SECUENCIAL = $cabe->SECAUTO;
                    $d->VENDEDOR = $cabecera['usuario'];
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
                    $d->ICE = 0;
                    $d->FECHACREA = $cabe->FECHACREA;
                    $d->TIPOITEM = $det['tipo_item'];
                    $d->ESTADO = 'A';
                    $d->FORMAVTA = $det['forma_venta'];
                    $d->GRAVAIVA = $grabaIvadet;
                    $d->PORDESADIC = 0;
                    $d->save();
                    $linea++;
                } 
                DB::commit();
                DB::statement("UPDATE ADMCABPEDIDOX SET SECUENCIAL = SECAUTO,NUMERO = SECAUTO where SECAUTO = " .$secAutoNew);
                Log::info("Registro de PedidoProformaController ", ["cabecera" => $cabe,"detalles"=> $d,"TiempoPreciso"=>Carbon::now()->subHours(5)->format('H:m:s.u')]);
                return response()->json(["estado"=>"guardado", "Npedido"=>$secAutoNew, "secuencial"=>$secAutoNew]);
                
            } else {
                DB::rollback();
                Log::error("Cabecera Sin Detalles",['cabecera'=>$cabe]);
                return response()->json(['error'=>'Cabecera Sin Detalles']);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error PedidoProformaController Last", ["cabecera" => $cabe,"TiempoPreciso"=>Carbon::now()->subHours(5)->format('H:m:s.u'),"Datos"=>$e->getMessage()]);
            return response()->json(["error"=>["info"=>"Error Insertando Pedido"]]);
        }
    }    
}

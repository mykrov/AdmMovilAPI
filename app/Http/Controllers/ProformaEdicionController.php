<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use \App\ADMCABPEDIDO;
use \App\ADMDETPEDIDO;
use Illuminate\Support\Facades\Log;

class ProformaEdicionController extends Controller
{
    public function EditarProforma(Request $r)
    {
        $cabecera = $r->cabecera[0];
        $detalles = $r->detalles;

        $numDetalles = count($detalles);
        Log::info("Nueva Edicion de Proforma con detalles",['CountDet'=>$numDetalles]);
        if ($numDetalles == 0) {
            Log::error("Cabecera Sin Detalles",['cabecera'=>$cabecera]);
            return response()->json(['error'=>'Cabecera Sin Detalles']);
        }

        $grabaIva = "N";
        if(floatval($cabecera['iva']) > 0){
            $grabaIva = "S";
        }
        
        $observacion = "Gracias por su Compra.";
        if(trim($cabecera['observacion']) != ""){
            $observacion = $cabecera['observacion'];
        }

        $campo_adi = "Gracias por su Compra.";
        if(trim($cabecera['datos_adi']) != ""){
            $campo_adi = $cabecera['datos_adi'];
        }

        DB::beginTransaction();
        try {

            $cabe = \App\ADMCABPEDIDO::where('SECUENCIAL','=',$cabecera['secuencial'])->first();

            if($cabe != null && $cabe->ESTADO != 'FAC'){
                
                $cabe->SUBTOTAL = round($cabecera['subtotal'],2);
                $cabe->DESCUENTO = round($cabecera['descuento'],2);
                $cabe->IVA = round($cabecera['iva'],2);
                $cabe->NETO = round($cabecera['neto'],2); 
                $cabe->COMENTARIO = $campo_adi;
                $cabe->OBSERVACION = $observacion;
                $cabe->GRAVAIVA = $grabaIva;
                $cabe->save();
            }

            $linea = 1;

            if (count($detalles) > 0) { //Cuando no trae detalles la cabecera.
                
                //Eliminacion de Detalles existentes.
                $resultDB = DB::statement("DELETE ADMDETPEDIDO WHERE SECUENCIAL = " .$cabecera['secuencial']);
      
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
                    $d->COSTOP = round($itemData->COSTOP,2);
                    $d->COSTOU = round($itemData->COSTOU,2);
                    $d->TIPOITEM = $det['tipo_item'];
                    $d->FORMAVTA = $det['forma_venta'];
                    $d->GRAVAIVA = $grabaIvadet;
                    $d->LINGENCONDICION = 0;
                    $d->PORDES2 = 0;
                    $d->save();
                    $linea++;
                } 
                DB::commit();
                Log::info("Actualizacion de PedidoProformaController ", ["cabecera" => $cabe,"detalles"=> $detalles,"TiempoPreciso"=>Carbon::now()->subHours(5)->format('H:m:s.u')]);
                return response()->json(["estado"=>"actualizado", "Npedido"=>$cabe->NUMERO, "secuencial"=>$cabe->SECUENCIAL]);
    
            } else {
                DB::rollback();
                Log::error("Cabecera Sin Detalles",['cabecera'=>$cabe]);
                return response()->json(['error'=>'Cabecera Sin Detalles']);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error Actualizando PedidoProformaController ", ["cabecera" => $cabe,"TiempoPreciso"=>Carbon::now()->subHours(5)->format('H:m:s.u'),"Datos"=>$e->getMessage()]);
            return response()->json(["error"=>["info"=>"Error Actualizando Pedido","mensaje"=>$e->getMessage()]]);
        }
    }

    public function EliminarPedido(Request $r)
    {       
        $secuencial = $r->secuencial;
        $cabe = \App\ADMCABPEDIDO::where('SECUENCIAL','=',$secuencial)->first();
        
        if($cabe != null && $cabe->ESTADO != 'FAC')
        {
            DB::beginTransaction();
            try {
                $cabe->ESTADO = 'XXX';
                $cabe->OBSERVACION = 'Pedido eliminado por ' .$cabe->VENDEDOR;
                $cabe->save();
                DB::commit();
                Log::error("Eliminando PedidoProformaController ", ["cabecera" => $cabe,"TiempoPreciso"=>Carbon::now()->subHours(5)->format('H:m:s.u')]);
                return response()->json(["estado"=>"eliminado", "Npedido"=>$cabe->NUMERO, "secuencial"=>$cabe->SECUENCIAL]);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error("Error Eliminando PedidoProformaController ", ["cabecera" => $cabe,"TiempoPreciso"=>Carbon::now()->subHours(5)->format('H:m:s.u')]);
                return response()->json(["error"=>["info"=>"Error Eliminando Pedido"]]);
            }
        }else{
            Log::error("Error Eliminando PedidoProformaController ", ["cabecera" => $cabe,"TiempoPreciso"=>Carbon::now()->subHours(5)->format('H:m:s.u')]);
            return response()->json(["error"=>["info"=>"Error Pedido no encontrado."]]);
        }
    }
}

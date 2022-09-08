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
        Log::info("Nuevo Pedido X ----------------");
        try {
            $cabecera = $r->cabecera[0];
            $detalles = $r->detalles;
        } catch (\Throwable $th) {
            return response()->json(['error'=>'Estructura de Json no compatible']);
        }

        Log::info(["request Cabecera" => $cabecera]);

        $secAndroid = $cabecera["secuencial"];
        $vende = $cabecera["usuario"];
        $date = Carbon::now()->subHours(5);

        $registro = DB::table('ADMCABPEDIDOX')
        ->where('SECUENCIAL',$secAndroid)
        ->where('VENDEDOR', $vende)
        ->where('FECHA',$date->Format('Y-d-m'))
        ->get();

        if($registro->count() > 0){
            return response()->json(["estado"=>"guardado", "Npedido"=>$registro[0]->SECAUTO, "secuencial"=>$registro[0]->SECAUTO,"YaRegistrado"=>"si"]);
        }

        if (count($detalles) == 0) {
            Log::error("Cabecera Sin Detalles",['cabecera'=>$cabecera]);
            return response()->json(['error'=>'Cabecera Sin Detalles']);
        }

        $cliente = \App\Cliente::where('CODIGO','=',$cabecera['cliente'])->first();
       

        $grabaIva = "N";
        if(floatval($cabecera['iva']) > 0){
            $grabaIva = "S";
        }

         //En caso de Observacion.
        // $observacion = "Gracias por su Compra.";
        // if(trim($cabecera['observacion']) != ""){
        //     $observacion = $cabecera['observacion'];
        // }

        // $campo_adi = "Gracias por su Compra.";
        // if(trim($cabecera['datos_adi']) != ""){
        //     $campo_adi = $cabecera['datos_adi'];
        // }

        
        $observacion = "Gracias por su Compra.";
        $campo_adi = "Gracias por su Compra.";

        //Observacion y Datos del cliente.
        $clienteObservacion  = $cliente->OBSERVACION;
        $clienteReferencia = $cliente->REFERENCIA;

        if($clienteObservacion != null && trim($clienteObservacion) != "" ){
            $observacion = $clienteObservacion;
        }

        if($clienteReferencia != null && trim($clienteReferencia) != "" ){
            $campo_adi = $clienteReferencia;
        }

        //En caso de Observacion y Datos del Request.
        if(trim($cabecera['observacion']) != "NA" && $cabecera['observacion'] != null){
            $observacion = $cabecera['observacion'];
            //Log::info("Observacion de PedidoProforma: ",['Contenido'=>$cabecera['observacion']]);
        }

        if(trim($cabecera['datos_adi']) != "NA"  && $cabecera['datos_adi'] != null){
            $campo_adi = $cabecera['datos_adi'];
            //Log::info("Datos Adi de PedidoProforma: ",['Contenido'=>$cabecera['datos_adi']]);
        }

        $secAutoNew = 0;

        DB::beginTransaction();
        try {

            $cabe = new ADMCABPEDIDOX();
            $cabe->TIPO = "PED";
            $cabe->BODEGA = intval($cabecera['bodega']);
            $cabe->NUMERO = $cabe->SECAUTO;
            $cabe->SECUENCIAL = $cabecera['secuencial'];
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
            $cabe->COMENTARIO = trim($campo_adi);
            $cabe->OBSERVACION =  trim($observacion);
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
                    usleep(1000000);
                } 
                
                
                $visita = new \App\ADMVISITACLI();
                $visita->CLIENTE = $cabe->CLIENTE;
                $visita->VENDEDOR = $cabe->VENDEDOR;
                $visita->NUMPEDIDO = 0;
                $visita->LATITUD = "marca_pedido";
                $visita->LONGITUD = "marca_pedido";
                $visita->VISITADO  = "S";
                $visita->FECHAVISITA = Carbon::now()->format('Y-m-d');
                $visita->save();
                DB::commit();
                Log::info('Registro de visita por PedidoXController '.$visita->CLIENTE);

               
                // if($this->UpdateCabX($secAutoNew)){
                //     Log::info("Se hizo update de CabX exitoso.");
                // }else{
                //     Log::error("Error en el Update CabX");
                // }     
                
                //Se debe crear el tigger en la BASE
                // CREATE TRIGGER UpdateSecAuto 
                // ON  ADMCABPEDIDOX 
                // AFTER INSERT
                // AS 
                // BEGIN 	
                //     UPDATE ADMCABPEDIDOX SET SECUENCIAL = SECAUTO,NUMERO = SECAUTO WHERE SECAUTO = (SELECT i.SECAUTO FROM inserted i);
                // END
                // GO


                Log::info("Registro de PedidoXController ", ["cabecera" => $cabe,"detalles"=> $d,"TiempoPreciso"=>Carbon::now()->subHours(5)->format('H:m:s.u')]);
                return response()->json(["estado"=>"guardado", "Npedido"=>$secAutoNew, "secuencial"=>$secAutoNew]);
                
            } else {
                DB::rollback();
                Log::error("Cabecera Sin Detalles",['cabecera'=>$cabe]);
                return response()->json(['error'=>'Cabecera Sin Detalles']);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error PedidoXController Last", ["cabecera" => $cabe,"TiempoPreciso"=>Carbon::now()->subHours(5)->format('H:m:s.u')]);
            Log::error($e->getMessage());
            DB::statement("Delete ADMCABPEDIDOX where SECAUTO = " .$secAutoNew);
            Log::info("Se procedió a eliminar el secauto ".$secAutoNew);
            DB::statement("Delete ADMDETPEDIDOX where SECUENCIAL = " .$secAutoNew);
            return response()->json(["error"=>["info"=>"Error Insertando Pedido"]]);
        }
    }    

    public function UpdateCabX($secAuto){
        try {
            DB::statement("UPDATE ADMCABPEDIDOX SET NUMERO = SECAUTO where SECAUTO = " .$secAuto);
            return true;
        } catch (\Throwable $th) {
            Log::error("Error en el Update de ADMCABPEDIDOX",['Error'=>$th->getMessage()]);
            return false;
        }
    }

}

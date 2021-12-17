<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use \App\ADMDETEGRESO;
use \App\ADMPARAMETROV;
use \App\ADMBODEGA;
use \App\Cliente;
use \App\ADMPARAMETROBO;

class ProformaElectroController extends Controller
{
    public function PostProformaCredito(Request $r){
       
        $cabecera = $r->cabecera[0];
        $detalles = $r->detalles;
        //$tablaJson = $r->tablaAmortizacion;
        
        Log::info(['cab'=>$cabecera,'detalle'=>$detalles]);       

        $detallesContador = COUNT($detalles);
        
        if($detallesContador == 0){
            return response()->json(['error'=>'Cabecera Sin Detalles']);
        }

        $operador1 = '';
        //Datos del Operador segun vendedor
        $vendedorData = \App\ADMVENDEDOR::where('CODIGO','=',$cabecera['usuario'])->first();
       
        if($vendedorData == null || $vendedorData->operadormovil == null){
            $operador1 = 'ADM';
        }else{
            $operador1 = $vendedorData->operadormovil ;
        }     
       
        $parametrov = ADMPARAMETROV::first();
        $secuencialNew = $parametrov->SECUENCIAL;
        $parametrov->SECUENCIAL = $parametrov->SECUENCIAL + 1;
        $parametrov->save();

        DB::beginTransaction();

        //En caso de Observacion.
        $observacion = "Gracias por su Compra.";
        if(trim($cabecera['observacion']) != ""){
            $observacion = $cabecera['observacion'];
        }

        $campo_adi = "Gracias por su Compra.";
        if(trim($cabecera['datos_adi']) != ""){
            $campo_adi = $cabecera['datos_adi'];
        }

        try {
        
            $bodega = ADMBODEGA::where('CODIGO','=',$cabecera['bodega'])->first();
            $cliente = Cliente::where('CODIGO','=',$cabecera['cliente'])->first();
            $parametrobo = ADMPARAMETROBO::first();
            
            $grabaIva = "N";
            if(floatval($cabecera['iva']) > 0){
                $grabaIva = "S";
            }
            
            $date = Carbon::now()->subHours(5);
            $inipago = Carbon::createFromFormat('Y-m-d',$cabecera['fechaIniPago']);
            $fechaDes = Carbon::createFromFormat('Y-m-d',$cabecera['fecha_ingreso']);
            
            $cab = new \App\ADMCABEGRESO();
            
            $cab->TIPO = $cabecera['tipo']; 
            $cab->BODEGA = intval($cabecera['bodega']);
            $cab->NUMERO = -1;
            $cab->SERIE = trim($bodega->SERIE); 
            $cab->SECUENCIAL = $secuencialNew + 1; 
            $cab->NUMPROCESO = null; 
            $cab->NUMPEDIDO = 0; 
            $cab->NUMGUIA = null; 
            $cab->CAMION = null; 
            $cab->CHOFER = null; 
            $cab->DOCREL = null; 
            $cab->NUMEROREL = null; 
            $cab->FECHA = $date->Format('Y-d-m'); 
            $cab->FECHAVEN = $date->addDays(intval($cliente->DIASCREDIT))->format('Y-d-m'); 
            $cab->FECHADES = $fechaDes->format('Y-d-m'); 
            $cab->OPERADOR = $operador1; 
            $cab->CLIENTE = $cabecera['cliente']; 
            $cab->VENDEDOR = $cabecera['usuario']; 
            $cab->PROVEEDOR = null; 

            $cab->SUBTOTAL = round($cabecera['subtotal'],2); 
            $cab->DESCUENTO = round($cabecera['descuento'],2); 
            $cab->IVA = round($cabecera['iva'],2); 
            $cab->NETO = round($cabecera['neto'],2); 

            $cab->TRANSPORTE = 0; 
            $cab->RECARGO = 0; 
            $cab->BODEGADES = 0; 
            $cab->PESO = 0; 
            $cab->VOLUMEN = 0; 
            $cab->MOTIVO = null; 
            $cab->ESTADO = "P"; 
            $cab->ESTADODOC = null; 
            $cab->TIPOVTA = "BIE"; 
            $cab->INTECXC = null; 
            $cab->OBSERVA =  $observacion; 
            $cab->COMENTA = $campo_adi;
            $cab->INTEGRADO = null; 
            $cab->SECCON = 0; //Pendiente, se Actualiza al guardar todo.
            $cab->NUMSERIE = null; 
            $cab->NOCARGA = null; 
            $cab->APLSRI = null; 
            $cab->NUMFISICO = null; 
            $cab->HORA = $cabecera['hora_ingreso']; 
            $cab->NOMBREPC = "Servidor - Laravel 2021"; 
            $cab->claseAjuEgreso = null; 
            $cab->SUBTOTAL0 = 0; 
            $cab->NUMGUIATRANS = null; 
            $cab->GRAVAIVA = $grabaIva; 
            $cab->CREDITO = $cabecera["credito"]; 
            $cab->ESTADODESPACHO = "N"; 
            $cab->SECAUTOVENTA = null; 
            $cab->NUMCUOTAS = $cabecera["numCuotas"]; 
            $cab->NUMGUIAREMISION = null; 
            $cab->SBTBIENES = round($cabecera['subtotal'],2); 
            $cab->SBTSERVICIOS = 0; 
            $cab->TIPOCLIENTE = trim($cliente->TIPO); 
            $cab->SUCURSAL = ""; 
            $cab->ACT_SCT = "N"; 
            $cab->NUMPRODUCCION = null; 
            $cab->porentregar = "N"; 
            $cab->ENTREGADA = "N"; 
            $cab->REFERENCIA = null; 
            $cab->FECHA_EMBARQUE = null; 
            $cab->BUQUE = null; 
            $cab->NAVIERA = null; 
            $cab->ALMACENARA = null; 
            $cab->PRODUCTO =null; 
            $cab->CODIGORETAILPRO = null; 
            $cab->GRMFACELEC = "N"; 
            $cab->NOAUTOGRM = null; 
            $cab->mescredito = 0; 
            $cab->tipopago = $cabecera["tipoPago"]; 
            $cab->numeropagos = $cabecera["numeroPagos"]; 
            $cab->mesescredito = $cabecera["mesesCredito"];
            $cab->entrada = $cabecera["entrada"]; 
            $cab->valorfinanciado = $cabecera["valorFinanciado"]; 
            $cab->porinteres = $cabecera["porInteres"]; 
            $cab->montointeres = $cabecera["montoInteres"]; 
            $cab->totaldeuda = $cabecera["totalDeuda"]; 
            $cab->xsubtotal = $cabecera["xSubtotal"]; 
            $cab->xsubtotal0 = $cabecera["xSubtotal0"]; 
            $cab->fechainipago = $inipago->format('Y-d-m');
            $cab->xdescuento = 0; 
            $cab->xdescuento0 = 0; 
            $cab->xiva = 0; 
            $cab->numerosolicitud = 0;              
            $cab->ENVIADONESTLE = "N"; 
            $cab->tipotienda = null; 
            $cab->CodShip = null; 
            $cab->NUMPESAJE = 0; 
            $cab->ESFOMENTO = "N"; 
            $cab->save();

            //Procesado de los Detalles.
            $lineaDet = 1;            
            foreach ($detalles as $det) {                
               
                $itemData = \App\ADMITEM::where('ITEM','=',trim($det['item']))->first();
                $itemElectData = \App\ADMITEMPRECIOELE::where('ITEM','=',trim($det['item']))->first();
                $grabaIvadet = "N";
                
                if(floatval($det['iva']) > 0){
                    $grabaIvadet = "S";
                }

                $d = new \App\ADMDETEGRESO;
                $d->SECUENCIAL = intval($cab->SECUENCIAL);
                $d->LINEA = intval($det['linea']);
                $d->ITEM = $det['item'];
                $d->TIPOITEM = $det['tipo_item'];
                $d->PRECIO = floatval($det['precio']);
                $d->COSTOP = $itemElectData->costo;
                $d->COSTOU = $itemElectData->costo;
                $d->CANTIU = intval($det['total_unidades']) % $itemData->FACTOR;
                
                if(intval($det['total_unidades']) > 1){
                    $d->CANTIC = intval($det['total_unidades']  / $itemData->FACTOR);
                }else{
                    $d->CANTIC = 0;
                }

                $d->CANTFUN = intval($det['total_unidades']);
                $d->CANTDEV = null;
                $d->SUBTOTAL = floatval($det['subtotal']);
                $d->DESCUENTO = floatval($det['descuento']);
                $d->IVA = floatval($det['iva']);
                $d->NETO = floatval($det['neto']);
                $d->PORDES = floatval($det['pordes']);
                $d->LINEAREL = null;
                $d->TIPOVTA = "V";
                $d->FORMAVTA = $det['forma_venta'];
                $d->GRAVAIVA = $grabaIvadet;
                $d->PORDES2 = 0;
                $d->CANTENTREGADA = 0;
                $d->CANTPORENTREGAR = null;
                $d->DETALLE = $campo_adi;
                $d->FECHAELA = null;
                $d->FECHAVEN = null;
                $d->LOTE = null;
                $d->preciox = 0;

                if($det['serialItem'] == null and $det['forma_venta'] == 'R' ){
                    $d->serialitem = 'XXXXXX';
                }elseif($det['serialItem'] == null and $det['forma_venta'] == 'V' ){
                    $d->serialitem = '';
                }else{
                    $d->serialitem = $det['serialItem'];
                }

                $d->save();
                $lineaDet++;
            }
            DB::commit();
            Log::info(['Proforma de Crédito Guardada, Secuencial '=>$cab->SECUENCIAL]);
            return response()->json(["estado"=>"guardado", "Nfactura"=>$cab->NUMERO, "secuencial"=>$cab->SECUENCIAL,"email"=>"-"]);

        }catch (\Exception $e){
            Log::error($e->getMessage());
            return response()->json(["error"=>["info"=>$e->getMessage()]]);
        }
    }
}
